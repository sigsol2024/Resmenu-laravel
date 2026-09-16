<?php

namespace Tests\Unit;

use App\Services\UploadService;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Tests\TestCase;

class UploadServiceImageOptimizeTest extends TestCase
{
    private string $tempRoot;

    protected function setUp(): void
    {
        parent::setUp();

        $this->tempRoot = sys_get_temp_dir().DIRECTORY_SEPARATOR.'resmenu_upload_test_'.uniqid();
        File::ensureDirectoryExists($this->tempRoot);

        config([
            'resmenu.upload_root' => $this->tempRoot,
            'resmenu.image_upload_max_bytes' => 1048576,
            'resmenu.image_target_min_bytes' => 300000,
            'resmenu.image_target_max_bytes' => 512000,
            'resmenu.image_max_long_edge' => 1920,
            'resmenu.image_min_long_edge' => 800,
            'resmenu.image_jpeg_quality_start' => 85,
            'resmenu.image_jpeg_quality_floor' => 72,
        ]);
    }

    protected function tearDown(): void
    {
        if (is_dir($this->tempRoot)) {
            File::deleteDirectory($this->tempRoot);
        }

        parent::tearDown();
    }

    public function test_rejects_files_over_one_megabyte(): void
    {
        $uploads = app(UploadService::class);
        $path = $this->tempRoot.DIRECTORY_SEPARATOR.'huge.jpg';
        file_put_contents($path, str_repeat("\0", 1100000));
        $file = new UploadedFile($path, 'huge.jpg', 'image/jpeg', null, true);

        $result = $uploads->storeImage($file, 'menu-items');

        $this->assertFalse($result['success'] ?? true);
        $this->assertStringContainsString('maximum', strtolower((string) ($result['message'] ?? '')));
    }

    public function test_small_jpeg_passthrough_keeps_reasonable_size_and_aspect(): void
    {
        if (! function_exists('imagecreatetruecolor') || ! function_exists('imagejpeg')) {
            $this->markTestSkipped('GD not available.');
        }

        $uploads = app(UploadService::class);
        $path = $this->makeJpegFixture(400, 300, 70);
        $file = new UploadedFile($path, 'small.jpg', 'image/jpeg', null, true);

        $this->assertLessThanOrEqual(300000, filesize($path));

        $result = $uploads->storeImage($file, 'menu-items');

        $this->assertTrue($result['success'] ?? false, $result['message'] ?? 'store failed');
        $stored = $result['path'] ?? null;
        $this->assertNotEmpty($stored);
        $this->assertFileExists($stored);

        $info = getimagesize($stored);
        $this->assertNotFalse($info);
        $this->assertSame(400, $info[0]);
        $this->assertSame(300, $info[1]);
    }

    public function test_large_jpeg_is_optimized_toward_target_without_squashing(): void
    {
        if (! function_exists('imagecreatetruecolor') || ! function_exists('imagejpeg')) {
            $this->markTestSkipped('GD not available.');
        }

        $uploads = app(UploadService::class);
        // Wide image over soft max so optimize path runs.
        $path = $this->makeJpegFixture(2400, 1600, 92);
        $originalSize = filesize($path);
        if ($originalSize <= 512000) {
            $this->markTestSkipped('Could not generate a JPEG larger than soft max for this environment.');
        }

        $file = new UploadedFile($path, 'large.jpg', 'image/jpeg', null, true);
        $result = $uploads->storeImage($file, 'menu-items');

        $this->assertTrue($result['success'] ?? false, $result['message'] ?? 'store failed');
        $stored = $result['path'] ?? null;
        $this->assertFileExists($stored);

        $info = getimagesize($stored);
        $this->assertNotFalse($info);
        $ratio = $info[0] / max(1, $info[1]);
        $this->assertEqualsWithDelta(2400 / 1600, $ratio, 0.02);

        $storedSize = filesize($stored);
        $this->assertLessThan($originalSize, $storedSize);
        // Soft target: prefer ≤ 512KB; allow best-effort slightly over if floors hit.
        $this->assertLessThanOrEqual(700000, $storedSize);
    }

    public function test_transparent_png_preserves_alpha_channel_family(): void
    {
        if (! function_exists('imagecreatetruecolor') || ! function_exists('imagepng')) {
            $this->markTestSkipped('GD not available.');
        }

        $uploads = app(UploadService::class);
        $path = $this->makeTransparentPngFixture(200, 200);
        $file = new UploadedFile($path, 'logo.png', 'image/png', null, true);

        $result = $uploads->storeImage($file, 'logos');

        $this->assertTrue($result['success'] ?? false, $result['message'] ?? 'store failed');
        $filename = (string) ($result['filename'] ?? '');
        $this->assertMatchesRegularExpression('/\.(png|webp)$/i', $filename);
        $this->assertFileExists($result['path']);
    }

    public function test_whatsapp_style_multi_dot_filename_is_accepted_and_renamed(): void
    {
        $uploads = app(UploadService::class);
        // 1×1 JPEG — no GD required; under soft target so store is passthrough.
        $jpeg = base64_decode(
            '/9j/4AAQSkZJRgABAQEAYABgAAD/2wBDAAgGBgcGBQgHBwcJCQgKDBQNDAsLDBkSEw8UHRofHh0aHBwgJC4nICIsIxwcKDcpLDAxNDQ0Hyc5PTgyPC4zNDL/2wBDAQkJCQwLDBgNDRgyIRwhMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjL/wAARCAABAAEDASIAAhEBAxEB/8QAFQABAQAAAAAAAAAAAAAAAAAAAAv/xAAUEAEAAAAAAAAAAAAAAAAAAAAA/8QAFQEBAQAAAAAAAAAAAAAAAAAAAAX/xAAUEQEAAAAAAAAAAAAAAAAAAAAA/9oADAMBAAIQAxAAAAGcP//Z'
        );
        $this->assertNotFalse($jpeg);
        $path = $this->tempRoot.DIRECTORY_SEPARATOR.'wa_src.jpg';
        file_put_contents($path, $jpeg);

        $originalName = 'WhatsApp Image 2026-09-15 at 12.19.11 PM.jpeg';
        $file = new UploadedFile($path, $originalName, 'image/jpeg', null, true);

        $result = $uploads->storeImage($file, 'sections');

        $this->assertTrue($result['success'] ?? false, $result['message'] ?? 'store failed');
        $filename = (string) ($result['filename'] ?? '');
        $this->assertNotSame($originalName, $filename);
        $this->assertMatchesRegularExpression('/^[a-zA-Z0-9]+\.jpe?g$/i', $filename);
        $this->assertFileExists($result['path']);
    }

    public function test_logo_file_exists_requires_real_file(): void
    {
        $uploads = app(UploadService::class);
        $this->assertFalse($uploads->logoFileExists('missing-logo.png'));
        $this->assertFalse($uploads->logoFileExists(null));

        $dir = $uploads->subdirPath('logos');
        $name = 'exists'.uniqid().'.png';
        file_put_contents($dir.DIRECTORY_SEPARATOR.$name, 'x');
        $this->assertTrue($uploads->logoFileExists($name));
    }

    private function makeJpegFixture(int $width, int $height, int $quality): string
    {
        $img = imagecreatetruecolor($width, $height);
        for ($y = 0; $y < $height; $y += 8) {
            for ($x = 0; $x < $width; $x += 8) {
                $color = imagecolorallocate($img, ($x * 3) % 255, ($y * 5) % 255, ($x + $y) % 255);
                imagefilledrectangle($img, $x, $y, $x + 7, $y + 7, $color);
            }
        }
        $path = $this->tempRoot.DIRECTORY_SEPARATOR.'fixture_'.uniqid().'.jpg';
        imagejpeg($img, $path, $quality);
        imagedestroy($img);

        return $path;
    }

    private function makeTransparentPngFixture(int $width, int $height): string
    {
        $img = imagecreatetruecolor($width, $height);
        imagesavealpha($img, true);
        $transparent = imagecolorallocatealpha($img, 0, 0, 0, 127);
        imagefill($img, 0, 0, $transparent);
        $red = imagecolorallocatealpha($img, 220, 40, 40, 40);
        imagefilledellipse($img, (int) ($width / 2), (int) ($height / 2), (int) ($width / 2), (int) ($height / 2), $red);
        $path = $this->tempRoot.DIRECTORY_SEPARATOR.'fixture_'.uniqid().'.png';
        imagepng($img, $path);
        imagedestroy($img);

        return $path;
    }
}
