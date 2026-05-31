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

        $dir = rtrim($this->root(), DIRECTORY_SEPARATOR).DIRECTORY_SEPARATOR.trim($subdir, '/');
        if (! is_dir($dir)) {
            mkdir($dir, 0755, true);
        }

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

        $safeName = basename($filename);
        if ($safeName !== $filename || $safeName === '' || str_contains($safeName, '..')) {
            return;
        }

        $path = rtrim($this->root(), DIRECTORY_SEPARATOR).DIRECTORY_SEPARATOR.trim($subdir, '/').DIRECTORY_SEPARATOR.$safeName;
        if (is_file($path)) {
            @unlink($path);
        }
    }
}
