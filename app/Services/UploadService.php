<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;

class UploadService
{
    public function root(): string
    {
        return config('resmenu.upload_root');
    }

    public function publicUrl(string $subdir, ?string $filename): ?string
    {
        if ($filename === null || $filename === '') {
            return null;
        }
        $base = config('resmenu.canonical_upload_url') ?: config('resmenu.upload_url');

        return rtrim($base, '/').'/'.trim($subdir, '/').'/'.ltrim($filename, '/');
    }

    public function storeImage(UploadedFile $file, string $subdir): array
    {
        $maxUpload = (int) config('resmenu.image_upload_max_bytes', 1048576);
        if ($file->getSize() > $maxUpload) {
            return ['success' => false, 'message' => 'File exceeds maximum upload size.'];
        }

        $allowed = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
        if (! in_array($file->getMimeType(), $allowed, true)) {
            return ['success' => false, 'message' => 'Invalid image type.'];
        }

        $dir = rtrim($this->root(), DIRECTORY_SEPARATOR).DIRECTORY_SEPARATOR.trim($subdir, '/');
        if (! is_dir($dir)) {
            mkdir($dir, 0755, true);
        }

        $ext = strtolower($file->getClientOriginalExtension() ?: 'jpg');
        $filename = Str::random(12).'.'.$ext;
        $file->move($dir, $filename);

        return ['success' => true, 'filename' => $filename, 'path' => $dir.DIRECTORY_SEPARATOR.$filename];
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
        if ($filename === null || $filename === '') {
            return;
        }
        $path = rtrim($this->root(), DIRECTORY_SEPARATOR).DIRECTORY_SEPARATOR.trim($subdir, '/').DIRECTORY_SEPARATOR.$filename;
        if (is_file($path)) {
            @unlink($path);
        }
    }
}
