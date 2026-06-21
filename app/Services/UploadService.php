<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;

class UploadService
{
    public function root(): string
    {
        return rtrim((string) config('resmenu.upload_root'), '/\\');
    }

    public function publicBaseUrl(): string
    {
        return rtrim((string) (config('resmenu.canonical_upload_url') ?: config('resmenu.upload_url')), '/');
    }

    public function publicUrl(string $subdir, ?string $filename): ?string
    {
        if ($filename === null || $filename === '') {
            return null;
        }

        return $this->publicBaseUrl().'/'.trim($subdir, '/').'/'.ltrim($filename, '/');
    }

    /** @return list<string> */
    public function diskRoots(): array
    {
        $primary = $this->root();
        $roots = [$primary];

        foreach ([
            public_path('storage/uploads'),
            public_path('uploads'),
            public_path('legacy/uploads'),
        ] as $candidate) {
            $candidate = rtrim(str_replace(['/', '\\'], DIRECTORY_SEPARATOR, $candidate), DIRECTORY_SEPARATOR);
            if ($candidate !== $primary && is_dir($candidate)) {
                $roots[] = $candidate;
            }
        }

        return array_values(array_unique($roots));
    }

    public function subdirPath(string $subdir): string
    {
        $dir = $this->root().DIRECTORY_SEPARATOR.trim($subdir, '/');
        if (! is_dir($dir)) {
            mkdir($dir, 0755, true);
        }

        return $dir;
    }

    public function filePath(string $subdir, string $filename): string
    {
        $safeName = $this->safeFilename($filename);

        return $this->subdirPath($subdir).DIRECTORY_SEPARATOR.$safeName;
    }

    public function resolveExistingPath(string $subdir, string $filename): ?string
    {
        $safeName = $this->safeFilename($filename);
        if ($safeName === null) {
            return null;
        }

        foreach ($this->diskRoots() as $root) {
            $path = rtrim($root, DIRECTORY_SEPARATOR).DIRECTORY_SEPARATOR.trim($subdir, '/').DIRECTORY_SEPARATOR.$safeName;
            if (is_file($path)) {
                return $path;
            }
        }

        return null;
    }

    public function storeImage(UploadedFile $file, string $subdir): array
    {
        $maxUpload = (int) config('resmenu.image_upload_max_bytes', 1048576);
        if ($file->getSize() <= 0 || $file->getSize() > $maxUpload) {
            return ['success' => false, 'message' => 'File exceeds maximum upload size.'];
        }

        $ext = strtolower($file->getClientOriginalExtension() ?: '');
        $allowedExt = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
        if (! in_array($ext, $allowedExt, true)) {
            return ['success' => false, 'message' => 'Invalid image extension.'];
        }

        if (preg_match('/\.[^.]+\./', $file->getClientOriginalName())) {
            return ['success' => false, 'message' => 'Invalid filename.'];
        }

        $allowed = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
        $detected = $file->getMimeType();
        if (! in_array($detected, $allowed, true)) {
            return ['success' => false, 'message' => 'Invalid image type.'];
        }

        $dir = $this->subdirPath($subdir);
        $filename = Str::random(12).'.'.$ext;
        $target = $dir.DIRECTORY_SEPARATOR.$filename;
        $file->move($dir, $filename);

        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $storedMime = $finfo ? finfo_file($finfo, $target) : false;
        if ($finfo) {
            finfo_close($finfo);
        }
        if ($storedMime && ! in_array($storedMime, $allowed, true)) {
            @unlink($target);

            return ['success' => false, 'message' => 'Uploaded file failed content validation.'];
        }

        return ['success' => true, 'filename' => $filename, 'path' => $target];
    }

    public function storeRawContents(string $subdir, string $filename, string $contents): ?string
    {
        $safeName = $this->safeFilename($filename);
        if ($safeName === null) {
            return null;
        }

        $target = $this->filePath($subdir, $safeName);
        if (@file_put_contents($target, $contents) === false) {
            return null;
        }

        return $safeName;
    }

    public function storeSiteAsset(UploadedFile $file, ?string $previousFilename = null): ?string
    {
        $result = $this->storeImage($file, 'site');
        if (! ($result['success'] ?? false)) {
            return $previousFilename;
        }
        if ($previousFilename) {
            $this->delete('site', $previousFilename);
        }

        return $result['filename'];
    }

    public function delete(string $subdir, ?string $filename): void
    {
        $safeName = $this->safeFilename($filename);
        if ($safeName === null) {
            return;
        }

        foreach ($this->diskRoots() as $root) {
            $path = rtrim($root, DIRECTORY_SEPARATOR).DIRECTORY_SEPARATOR.trim($subdir, '/').DIRECTORY_SEPARATOR.$safeName;
            if (is_file($path)) {
                @unlink($path);
            }
        }
    }

    /**
     * Move files saved under public/uploads into the configured upload_root.
     *
     * @return array{moved: int, skipped: int, errors: int}
     */
    public function relocateFromLegacyPublicUploads(bool $dryRun = false): array
    {
        $source = rtrim(public_path('uploads'), DIRECTORY_SEPARATOR);
        $targetRoot = $this->root();

        $stats = ['moved' => 0, 'skipped' => 0, 'errors' => 0];
        if (! is_dir($source) || $source === $targetRoot) {
            return $stats;
        }

        $iterator = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($source, \FilesystemIterator::SKIP_DOTS),
            \RecursiveIteratorIterator::SELF_FIRST,
        );

        foreach ($iterator as $item) {
            if (! $item->isFile()) {
                continue;
            }

            $relative = ltrim(str_replace($source, '', $item->getPathname()), DIRECTORY_SEPARATOR);
            $relative = str_replace(DIRECTORY_SEPARATOR, '/', $relative);
            if ($relative === '' || str_contains($relative, '..')) {
                $stats['errors']++;

                continue;
            }

            $dest = $targetRoot.DIRECTORY_SEPARATOR.str_replace('/', DIRECTORY_SEPARATOR, $relative);
            if (is_file($dest)) {
                $stats['skipped']++;

                continue;
            }

            $destDir = dirname($dest);
            if (! $dryRun && ! is_dir($destDir) && ! mkdir($destDir, 0755, true) && ! is_dir($destDir)) {
                $stats['errors']++;

                continue;
            }

            if ($dryRun) {
                $stats['moved']++;

                continue;
            }

            if (@rename($item->getPathname(), $dest)) {
                $stats['moved']++;
            } else {
                $stats['errors']++;
            }
        }

        return $stats;
    }

    private function safeFilename(?string $filename): ?string
    {
        if ($filename === null || $filename === '') {
            return null;
        }

        $safeName = basename($filename);
        if ($safeName !== $filename || $safeName === '' || str_contains($safeName, '..')) {
            return null;
        }

        return $safeName;
    }
}
