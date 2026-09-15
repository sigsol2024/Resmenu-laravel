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

    /**
     * True when a logo filename is non-empty and the file exists on an upload disk.
     */
    public function logoFileExists(?string $filename): bool
    {
        if ($filename === null || trim($filename) === '') {
            return false;
        }

        return $this->resolveExistingPath('logos', $filename) !== null;
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
        $targetMax = (int) config('resmenu.image_target_max_bytes', 512000);
        $originalSize = (int) $file->getSize();

        // GIF: preserve animation — never re-encode.
        // Already ≤ soft max: keep original bytes/format (do not inflate or recompress).
        $passthrough = $ext === 'gif'
            || $originalSize <= $targetMax
            || ! function_exists('imagecreatefromstring');

        if ($passthrough) {
            $filename = Str::random(12).'.'.$ext;
            $target = $dir.DIRECTORY_SEPARATOR.$filename;
            $file->move($dir, $filename);

            return $this->validateStoredFile($target, $filename, $allowed);
        }

        $binary = @file_get_contents($file->getRealPath() ?: $file->getPathname());
        if ($binary === false || $binary === '') {
            return ['success' => false, 'message' => 'Unable to read uploaded image.'];
        }

        $optimized = $this->optimizeImageBinary($binary, $ext);
        // Fall back to original when GD fails or optimization did not shrink the file.
        if ($optimized === null || strlen($optimized['bytes']) >= $originalSize) {
            $filename = Str::random(12).'.'.$ext;
            $target = $dir.DIRECTORY_SEPARATOR.$filename;
            $file->move($dir, $filename);

            return $this->validateStoredFile($target, $filename, $allowed);
        }

        $filename = Str::random(12).'.'.$optimized['ext'];
        $target = $dir.DIRECTORY_SEPARATOR.$filename;
        if (@file_put_contents($target, $optimized['bytes']) === false) {
            return ['success' => false, 'message' => 'Unable to store optimized image.'];
        }

        return $this->validateStoredFile($target, $filename, $allowed);
    }

    /**
     * @param  list<string>  $allowed
     * @return array{success: bool, message?: string, filename?: string, path?: string}
     */
    private function validateStoredFile(string $target, string $filename, array $allowed): array
    {
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

    /**
     * Re-encode / downscale toward ≤ image_target_max_bytes without squashing.
     *
     * @return array{bytes: string, ext: string}|null
     */
    private function optimizeImageBinary(string $binary, string $sourceExt): ?array
    {
        $img = @imagecreatefromstring($binary);
        if ($img === false) {
            return null;
        }

        $hasAlpha = $this->imageHasAlpha($img, $sourceExt);
        $width = imagesx($img);
        $height = imagesy($img);
        if ($width < 1 || $height < 1) {
            imagedestroy($img);

            return null;
        }

        $maxEdge = max(1, (int) config('resmenu.image_max_long_edge', 1920));
        $minEdge = max(1, (int) config('resmenu.image_min_long_edge', 800));
        $targetMax = (int) config('resmenu.image_target_max_bytes', 512000);
        $qualityStart = (int) config('resmenu.image_jpeg_quality_start', 85);
        $qualityFloor = (int) config('resmenu.image_jpeg_quality_floor', 72);

        $longEdge = max($width, $height);
        if ($longEdge > $maxEdge) {
            $scale = $maxEdge / $longEdge;
            $resized = $this->resample($img, (int) round($width * $scale), (int) round($height * $scale));
            if ($resized === null) {
                imagedestroy($img);

                return null;
            }
            $img = $resized;
            $width = imagesx($img);
            $height = imagesy($img);
        }

        $format = $hasAlpha
            ? (function_exists('imagewebp') ? 'webp' : 'png')
            : 'jpeg';

        $quality = $qualityStart;
        $best = null;

        for ($attempt = 0; $attempt < 24; $attempt++) {
            $bytes = $this->encodeImage($img, $format, $quality);
            if ($bytes === null) {
                break;
            }

            $best = ['bytes' => $bytes, 'ext' => $format === 'jpeg' ? 'jpg' : $format];

            if (strlen($bytes) <= $targetMax) {
                break;
            }

            $currentLong = max(imagesx($img), imagesy($img));
            if ($quality > $qualityFloor) {
                $quality = max($qualityFloor, $quality - 3);

                continue;
            }

            if ($currentLong > $minEdge) {
                $scale = 0.85;
                $newW = max(1, (int) round(imagesx($img) * $scale));
                $newH = max(1, (int) round(imagesy($img) * $scale));
                if (max($newW, $newH) < $minEdge) {
                    $fix = $minEdge / max($newW, $newH);
                    $newW = max(1, (int) round($newW * $fix));
                    $newH = max(1, (int) round($newH * $fix));
                }
                $resized = $this->resample($img, $newW, $newH);
                if ($resized === null) {
                    break;
                }
                $img = $resized;
                $quality = $qualityStart;

                continue;
            }

            // Floors hit — keep best-effort encode.
            break;
        }

        imagedestroy($img);

        return $best;
    }

    private function imageHasAlpha(\GdImage $img, string $sourceExt): bool
    {
        if (in_array($sourceExt, ['jpeg', 'jpg'], true)) {
            return false;
        }

        if (function_exists('imageistruecolor') && imageistruecolor($img)) {
            // Sample corners + center for transparency.
            $w = imagesx($img);
            $h = imagesy($img);
            $points = [[0, 0], [$w - 1, 0], [0, $h - 1], [$w - 1, $h - 1], [(int) ($w / 2), (int) ($h / 2)]];
            foreach ($points as [$x, $y]) {
                $rgba = imagecolorat($img, max(0, $x), max(0, $y));
                $alpha = ($rgba & 0x7F000000) >> 24;
                if ($alpha > 0) {
                    return true;
                }
            }

            return false;
        }

        return in_array($sourceExt, ['png', 'webp', 'gif'], true);
    }

    private function resample(\GdImage $src, int $newW, int $newH): ?\GdImage
    {
        $dst = imagecreatetruecolor($newW, $newH);
        if ($dst === false) {
            return null;
        }
        imagealphablending($dst, false);
        imagesavealpha($dst, true);
        $transparent = imagecolorallocatealpha($dst, 0, 0, 0, 127);
        imagefilledrectangle($dst, 0, 0, $newW, $newH, $transparent);
        imagecopyresampled($dst, $src, 0, 0, 0, 0, $newW, $newH, imagesx($src), imagesy($src));
        imagedestroy($src);

        return $dst;
    }

    private function encodeImage(\GdImage $img, string $format, int $quality): ?string
    {
        $encodeTarget = $img;
        $flat = null;

        if ($format === 'jpeg') {
            // Flatten onto white so any residual alpha does not become a black matte.
            $flat = imagecreatetruecolor(imagesx($img), imagesy($img));
            if ($flat === false) {
                return null;
            }
            $white = imagecolorallocate($flat, 255, 255, 255);
            imagefilledrectangle($flat, 0, 0, imagesx($img), imagesy($img), $white);
            imagealphablending($flat, true);
            imagecopy($flat, $img, 0, 0, 0, 0, imagesx($img), imagesy($img));
            $encodeTarget = $flat;
        }

        ob_start();
        $ok = match ($format) {
            'jpeg' => imagejpeg($encodeTarget, null, max(0, min(100, $quality))),
            'png' => imagepng($encodeTarget, null, (int) round((100 - max(0, min(100, $quality))) / 100 * 9)),
            'webp' => function_exists('imagewebp')
                ? imagewebp($encodeTarget, null, max(0, min(100, $quality)))
                : false,
            default => false,
        };
        $bytes = ob_get_clean();

        if ($flat instanceof \GdImage) {
            imagedestroy($flat);
        }

        if (! $ok || ! is_string($bytes) || $bytes === '') {
            return null;
        }

        return $bytes;
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
