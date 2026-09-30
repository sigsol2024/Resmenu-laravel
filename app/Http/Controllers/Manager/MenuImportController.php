<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Services\MenuImport\MenuImportAnalyzer;
use App\Services\MenuImport\MenuImportCsvReader;
use App\Services\MenuImport\MenuImportDraftStore;
use App\Services\MenuImport\MenuImportFormat;
use App\Services\MenuImport\MenuImportWriter;
use App\Support\ManagerImpersonation;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Throwable;

class MenuImportController extends Controller
{
    private const RESULT_SESSION_KEY = 'menu_import_result';

    public function __construct(
        private MenuImportCsvReader $reader,
        private MenuImportAnalyzer $analyzer,
        private MenuImportDraftStore $drafts,
        private MenuImportWriter $writer,
    ) {}

    public function template(): Response
    {
        return $this->csvDownload(MenuImportFormat::templateCsv(), MenuImportFormat::TEMPLATE_FILENAME);
    }

    public function sample(): Response
    {
        return $this->csvDownload(MenuImportFormat::sampleCsv(), MenuImportFormat::SAMPLE_FILENAME);
    }

    public function upload(Request $request): RedirectResponse
    {
        [$restaurantId, $managerId] = $this->owner($request);

        $file = $request->file('file');
        $result = $this->reader->read(is_array($file) ? null : $file);
        if (! $result['ok']) {
            return back()
                ->withErrors($result['errors'], 'menuImport')
                ->with('menu_import_open', true);
        }

        $token = $this->drafts->create($restaurantId, $managerId, [
            'filename' => $result['filename'],
            'notices' => $result['notices'],
            'rows' => $result['rows'],
        ]);

        return redirect()->route('manager.menu-import.preview', $token);
    }

    public function preview(Request $request, string $token)
    {
        [$restaurantId, $managerId] = $this->owner($request);
        $draft = $this->draftOrAbort($restaurantId, $managerId, $token);

        return view('manager.menu-import.preview', [
            'token' => $token,
            'filename' => $draft['filename'] ?? 'menu.csv',
            'notices' => $draft['notices'] ?? [],
            'analysis' => MenuImportAnalyzer::forClient($this->analyzer->analyze($restaurantId, $draft['rows'])),
        ]);
    }

    public function analyze(Request $request, string $token): JsonResponse
    {
        [$restaurantId, $managerId] = $this->owner($request);
        $draft = $this->drafts->get($restaurantId, $managerId, $token);
        if (! $draft) {
            return $this->expired();
        }

        if (strlen((string) $request->getContent()) > self::maxAnalyzeBytes()) {
            return response()->json(['message' => 'Your changes are too large to check. Shorten long descriptions or split the file into smaller imports.'], 413);
        }

        $incoming = $request->input('rows');
        if (! is_array($incoming)) {
            return response()->json(['message' => 'The preview data was not sent correctly. Reload the page and try again.'], 422);
        }

        $draft['rows'] = $this->mergeRows($draft['rows'], $incoming);
        $this->drafts->put($restaurantId, $managerId, $token, $draft);

        return response()->json(MenuImportAnalyzer::forClient($this->analyzer->analyze($restaurantId, $draft['rows'])));
    }

    public function import(Request $request, string $token): JsonResponse
    {
        [$restaurantId, $managerId] = $this->owner($request);
        $revision = $request->input('revision');
        if (! is_string($revision) || $revision === '') {
            return response()->json(['message' => 'Run Verify & Import again before confirming.'], 422);
        }
        if (! MenuImportDraftStore::validToken($token)) {
            return $this->expired();
        }

        $lock = Cache::lock($this->drafts->lockKey($restaurantId, $managerId, $token), 120);
        if (! $lock->get()) {
            return response()->json(['message' => 'This import is already being processed. Please wait a moment.'], 409);
        }

        try {
            $draft = $this->drafts->get($restaurantId, $managerId, $token);
            if (! $draft) {
                return $this->expired();
            }

            $outcome = $this->writer->import($restaurantId, $managerId, $draft['rows'], $revision, [
                'filename' => $draft['filename'] ?? null,
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'impersonator_admin_id' => ManagerImpersonation::active($request)
                    ? (int) $request->session()->get('impersonator_admin_id')
                    : null,
            ]);
        } catch (Throwable $e) {
            report($e);

            return response()->json(['message' => 'The import failed and nothing was saved. Please try again.'], 500);
        } finally {
            $lock->release();
        }

        $analysis = MenuImportAnalyzer::forClient($outcome['analysis']);

        if ($outcome['status'] === 'stale') {
            return response()->json([
                'message' => 'Your menu or this preview changed since you reviewed it. Check the updated summary and confirm again.',
                'analysis' => $analysis,
            ], 409);
        }
        if ($outcome['status'] === 'blocked') {
            return response()->json([
                'message' => $analysis['blocking'] > 0
                    ? 'This import cannot be completed until the highlighted problems are fixed.'
                    : 'There is nothing to import: every item already exists with the same details.',
                'analysis' => $analysis,
            ], 422);
        }

        $this->drafts->forget($restaurantId, $managerId, $token);
        $request->session()->put(self::RESULT_SESSION_KEY, [
            'restaurant_id' => $restaurantId,
            'filename' => $draft['filename'] ?? null,
            'result' => $outcome['result'],
        ]);

        return response()->json(['redirect' => route('manager.menu-import.result')]);
    }

    public function cancel(Request $request, string $token): RedirectResponse
    {
        [$restaurantId, $managerId] = $this->owner($request);
        $this->drafts->forget($restaurantId, $managerId, $token);

        return redirect()->route('manager.sections.index')->with('success', 'Import cancelled. Nothing was saved.');
    }

    public function result(Request $request)
    {
        [$restaurantId] = $this->owner($request);
        $stored = $request->session()->get(self::RESULT_SESSION_KEY);
        if (! is_array($stored) || (int) ($stored['restaurant_id'] ?? 0) !== $restaurantId) {
            return redirect()->route('manager.sections.index');
        }

        return view('manager.menu-import.result', [
            'filename' => $stored['filename'],
            'result' => $stored['result'],
        ]);
    }

    /** @return array{0: int, 1: int} */
    private function owner(Request $request): array
    {
        return [
            (int) $request->attributes->get('restaurant_id'),
            (int) Auth::guard('manager')->id(),
        ];
    }

    private function draftOrAbort(int $restaurantId, int $managerId, string $token): array
    {
        $draft = $this->drafts->get($restaurantId, $managerId, $token);
        if (! $draft) {
            abort(404, 'This import has expired or does not exist. Please upload your file again.');
        }

        return $draft;
    }

    /**
     * Applies the browser's edits to the stored rows. Only rows that were in the
     * uploaded file are accepted, row numbers always come from the file, and a
     * parse error can be cleared by the manager but never introduced.
     *
     * @param  list<array<string, mixed>>  $stored
     * @param  array<mixed>  $incoming
     * @return list<array<string, mixed>>
     */
    private function mergeRows(array $stored, array $incoming): array
    {
        $edits = [];
        foreach (MenuImportAnalyzer::sanitizeRows($incoming) as $row) {
            $edits[$row['id']] = $row;
        }

        $merged = [];
        foreach ($stored as $row) {
            $edit = $edits[$row['id']] ?? null;
            if ($edit) {
                $edit['row_number'] = $row['row_number'];
                $edit['parse_error'] = $edit['parse_error'] !== null ? $row['parse_error'] : null;
                $row = $edit;
            }
            $merged[] = $row;
        }

        return $merged;
    }

    /** Twice the upload limit plus room for the JSON keys of every row. */
    private static function maxAnalyzeBytes(): int
    {
        return MenuImportCsvReader::maxFileKb() * 1024 * 2 + 512 * 1024;
    }

    private function expired(): JsonResponse
    {
        return response()->json([
            'message' => 'This import has expired or was already completed. Please upload your file again.',
            'redirect' => route('manager.sections.index'),
        ], 410);
    }

    private function csvDownload(string $csv, string $filename): Response
    {
        return response($csv, 200, [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="'.$filename.'"',
            'Cache-Control' => 'no-store',
            'X-Content-Type-Options' => 'nosniff',
        ]);
    }
}
