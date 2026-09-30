<?php

namespace App\Services\MenuImport;

use Illuminate\Http\UploadedFile;

/**
 * Turns an uploaded CSV into flat draft rows. It never touches the database
 * and never stores the file; it only reads PHP's temporary upload.
 *
 * @phpstan-type DraftRow array{id: string, row_number: int, section: string, category: string, name: string, description: string, price: string, excluded: bool, parse_error: ?string}
 */
final class MenuImportCsvReader
{
    /**
     * A valid row is at most about 67 KB (three 255-character names, a 65,535-byte
     * description and a price). fgetcsv builds the whole cell array for a line
     * before it can be checked, so a 2 MB line of commas would use ~100 MB.
     */
    private const MAX_LINE_BYTES = 200_000;

    public const EXCEL_MESSAGE = 'Excel files (.xlsx or .xls) cannot be imported directly. In Excel choose File, Save As, "CSV UTF-8 (Comma delimited)", then upload that file.';

    /**
     * @return array{ok: bool, errors: list<string>, notices: list<string>, rows: list<array<string, mixed>>, blank_rows: int, filename: string}
     */
    public function read(?UploadedFile $file): array
    {
        if (! $file || ! $file->isValid()) {
            return $this->fail('The file could not be uploaded. Please choose a CSV file and try again.');
        }

        $filename = $this->displayName($file->getClientOriginalName());
        $extension = strtolower((string) $file->getClientOriginalExtension());

        if (in_array($extension, ['xlsx', 'xls', 'xlsm', 'ods'], true)) {
            return $this->fail(self::EXCEL_MESSAGE, $filename);
        }
        if ($extension !== 'csv') {
            return $this->fail('Only .csv files can be imported. Download the sample CSV to see the expected format.', $filename);
        }

        $maxBytes = $this->maxFileKb() * 1024;
        $size = (int) $file->getSize();
        if ($size <= 0) {
            return $this->fail('The file is empty.', $filename);
        }
        if ($size > $maxBytes) {
            return $this->fail('The file is larger than '.self::maxFileLabel().'. Split it into smaller files and import them one at a time.', $filename);
        }

        $contents = @file_get_contents((string) $file->getRealPath());
        if ($contents === false) {
            return $this->fail('The file could not be read. Please try again.', $filename);
        }

        return $this->readString($contents, $filename);
    }

    /**
     * @return array{ok: bool, errors: list<string>, notices: list<string>, rows: list<array<string, mixed>>, blank_rows: int, filename: string}
     */
    public function readString(string $contents, string $filename = 'menu.csv'): array
    {
        if (str_starts_with($contents, "PK\x03\x04") || str_starts_with($contents, "\xD0\xCF\x11\xE0")) {
            return $this->fail(self::EXCEL_MESSAGE, $filename);
        }

        $notices = [];
        $text = $this->toUtf8($contents, $notices);
        if ($text === null) {
            return $this->fail('This file does not look like a text CSV file. Save it as "CSV UTF-8" and try again.', $filename);
        }

        $text = str_replace(["\r\n", "\r"], "\n", $text);
        if (trim($text) === '') {
            return $this->fail('The file is empty.', $filename);
        }

        if (self::hasLineLongerThan($text, self::MAX_LINE_BYTES)) {
            return $this->fail('A line in the file is too long to be a menu row. Check that the file is a menu CSV and that its rows are on separate lines.', $filename, $notices);
        }

        $delimiter = $this->detectDelimiter($text);
        $handle = fopen('php://temp', 'r+');
        fwrite($handle, $text);
        rewind($handle);
        unset($text);

        try {
            return $this->parseRows($handle, $delimiter, $filename, $notices);
        } finally {
            fclose($handle);
        }
    }

    /**
     * Reads one record at a time and stops as soon as a limit is exceeded, so a
     * small file of millions of short lines cannot exhaust memory.
     *
     * @param  resource  $handle
     * @param  list<string>  $notices
     * @return array{ok: bool, errors: list<string>, notices: list<string>, rows: list<array<string, mixed>>, blank_rows: int, filename: string}
     */
    private function parseRows($handle, string $delimiter, string $filename, array $notices): array
    {
        $header = null;
        $map = [];
        $rows = [];
        $blankRows = 0;
        $rowNumber = 0;
        $maxRows = $this->maxRows();
        $maxRecords = self::maxRecords();

        while (($cells = fgetcsv($handle, 0, $delimiter, '"', '')) !== false) {
            if (++$rowNumber > $maxRecords) {
                return $this->fail("The file has too many lines. It can contain at most {$maxRows} menu rows; remove empty rows or split it into smaller files.", $filename, $notices);
            }
            if ($this->isBlank($cells)) {
                if ($header !== null) {
                    $blankRows++;
                }

                continue;
            }
            if ($header === null) {
                $header = $cells;
                $map = $this->mapHeader($header, $errors, $notices);
                if ($errors) {
                    return $this->fail($errors, $filename, $notices);
                }

                continue;
            }
            if (count($rows) >= $maxRows) {
                return $this->fail("The file has more than {$maxRows} menu rows. Split it into smaller files and import them one at a time.", $filename, $notices);
            }

            $row = [
                'id' => 'r'.$rowNumber,
                'row_number' => $rowNumber,
                'excluded' => false,
                'parse_error' => null,
            ];
            foreach ($map as $field => $index) {
                $row[$field] = trim((string) ($cells[$index] ?? ''));
            }
            if (count($cells) !== count($header)) {
                $row['parse_error'] = sprintf(
                    'This row has %d values but the file has %d columns. Check for a missing value or an unquoted comma, then correct the fields below.',
                    count($cells),
                    count($header),
                );
            }
            $rows[] = $row;
        }

        if ($header === null) {
            return $this->fail('The file is empty.', $filename);
        }
        if ($rows === []) {
            return $this->fail('The file has a header row but no menu rows.', $filename, $notices);
        }
        if ($blankRows > 0) {
            $notices[] = $blankRows === 1
                ? '1 empty row was skipped.'
                : "{$blankRows} empty rows were skipped.";
        }

        return [
            'ok' => true,
            'errors' => [],
            'notices' => $notices,
            'rows' => $rows,
            'blank_rows' => $blankRows,
            'filename' => $filename,
        ];
    }

    /** @param  list<string>  $notices */
    private function toUtf8(string $contents, array &$notices): ?string
    {
        if (str_starts_with($contents, "\xEF\xBB\xBF")) {
            $contents = substr($contents, 3);
        } elseif (str_starts_with($contents, "\xFF\xFE")) {
            $contents = mb_convert_encoding(substr($contents, 2), 'UTF-8', 'UTF-16LE');
        } elseif (str_starts_with($contents, "\xFE\xFF")) {
            $contents = mb_convert_encoding(substr($contents, 2), 'UTF-8', 'UTF-16BE');
        } elseif (! mb_check_encoding($contents, 'UTF-8')) {
            if (str_contains($contents, "\0")) {
                return null;
            }
            $contents = mb_convert_encoding($contents, 'UTF-8', 'Windows-1252');
            $notices[] = 'The file was not saved as UTF-8, so it was read as Windows-1252 (Excel\'s default). Check that special characters such as ₦ or accents look right.';
        }

        if (str_contains($contents, "\0")) {
            return null;
        }

        return $contents;
    }

    private static function hasLineLongerThan(string $text, int $limit): bool
    {
        $length = strlen($text);
        $start = 0;
        while ($start < $length) {
            $end = strpos($text, "\n", $start);
            if ($end === false) {
                $end = $length;
            }
            if ($end - $start > $limit) {
                return true;
            }
            $start = $end + 1;
        }

        return false;
    }

    private function detectDelimiter(string $text): string
    {
        $firstLine = strtok($text, "\n") ?: '';
        $unquoted = preg_replace('/"[^"]*"/', '', $firstLine) ?? $firstLine;

        $counts = [
            ',' => substr_count($unquoted, ','),
            ';' => substr_count($unquoted, ';'),
            "\t" => substr_count($unquoted, "\t"),
        ];
        arsort($counts);
        $best = array_key_first($counts);

        return $counts[$best] > 0 ? $best : ',';
    }

    /**
     * @param  list<string|null>  $header
     * @param  list<string>|null  $errors
     * @param  list<string>  $notices
     * @return array<string, int> draft field => column index
     */
    private function mapHeader(array $header, ?array &$errors, array &$notices): array
    {
        $errors = [];
        $known = [];
        foreach (MenuImportFormat::COLUMNS as $label => $field) {
            $known[$this->normalizeHeader($label)] = [$label, $field];
        }

        $map = [];
        $extra = [];
        foreach ($header as $index => $cell) {
            $key = $this->normalizeHeader((string) $cell);
            if ($key === '') {
                continue;
            }
            if (! isset($known[$key])) {
                $extra[] = trim((string) $cell);

                continue;
            }
            [$label, $field] = $known[$key];
            if (isset($map[$field])) {
                $errors[] = "The \"{$label}\" column appears more than once. Keep only one.";

                continue;
            }
            $map[$field] = $index;
        }

        $missing = [];
        foreach (MenuImportFormat::COLUMNS as $label => $field) {
            if (! isset($map[$field])) {
                $missing[] = $label;
            }
        }
        if ($missing) {
            $errors[] = 'Missing required column'.(count($missing) > 1 ? 's' : '').': '.implode(', ', $missing)
                .'. The first row must contain these headers: '.implode(', ', MenuImportFormat::HEADERS).'.';
        }
        if ($extra && ! $errors) {
            $notices[] = 'These extra columns were ignored: '.implode(', ', $extra).'.';
        }

        return $map;
    }

    private function normalizeHeader(string $value): string
    {
        $value = str_replace("\xEF\xBB\xBF", '', $value);

        return mb_strtolower(trim(preg_replace('/\s+/u', ' ', $value) ?? $value));
    }

    /** @param  list<string|null>  $cells */
    private function isBlank(array $cells): bool
    {
        foreach ($cells as $cell) {
            if (trim((string) $cell) !== '') {
                return false;
            }
        }

        return true;
    }

    private function displayName(string $name): string
    {
        $name = basename(str_replace('\\', '/', $name));
        $name = preg_replace('/[\x00-\x1F\x7F]/u', '', $name) ?? '';

        return mb_substr($name !== '' ? $name : 'menu.csv', 0, 120);
    }

    /**
     * @param  string|list<string>  $errors
     * @param  list<string>  $notices
     * @return array{ok: bool, errors: list<string>, notices: list<string>, rows: list<array<string, mixed>>, blank_rows: int, filename: string}
     */
    private function fail(string|array $errors, string $filename = '', array $notices = []): array
    {
        return [
            'ok' => false,
            'errors' => (array) $errors,
            'notices' => $notices,
            'rows' => [],
            'blank_rows' => 0,
            'filename' => $filename,
        ];
    }

    public static function maxFileLabel(): string
    {
        $kb = self::maxFileKb();

        return $kb >= 1024 ? round($kb / 1024, 1).' MB' : $kb.' KB';
    }

    public static function maxFileKb(): int
    {
        return max(1, (int) config('resmenu.menu_import.max_file_kb', 2048));
    }

    public static function maxRows(): int
    {
        return max(1, (int) config('resmenu.menu_import.max_rows', 1000));
    }

    /** Spreadsheet rows (blank ones included) read before the file is rejected. */
    public static function maxRecords(): int
    {
        return self::maxRows() * 5 + 100;
    }
}
