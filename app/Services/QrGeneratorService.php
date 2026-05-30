<?php

namespace App\Services;

use App\Models\Restaurant;
use Endroid\QrCode\Color\Color;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\RoundBlockSizeMode;
use Endroid\QrCode\Writer\PngWriter;
use Endroid\QrCode\Writer\SvgWriter;
use Illuminate\Support\Facades\DB;

class QrGeneratorService
{
    public function menuUrl(Restaurant $restaurant, ?string $sectionSlug = null): string
    {
        $base = url('/qr/'.$restaurant->slug);
        $sectionSlug = strtolower(trim((string) $sectionSlug));
        $sectionSlug = preg_replace('/[^a-z0-9-]/', '', $sectionSlug) ?? '';

        return $sectionSlug !== '' ? $base.'/'.$sectionSlug : $base;
    }

    /** @return array{body: string, content_type: string}|null */
    public function generateImage(int $restaurantId, string $format = 'png', ?int $size = null, ?string $sectionSlug = null): ?array
    {
        $restaurant = Restaurant::find($restaurantId);
        if (! $restaurant) {
            return null;
        }

        $settings = DB::table('restaurant_qr_codes')->where('restaurant_id', $restaurantId)->first();
        if (! $settings || empty($settings->qr_template_id)) {
            return null;
        }

        $config = $this->resolveConfig($settings);
        $fg = $config['colors']['foreground'] ?? $settings->qr_color ?? '#000000';
        $bg = $config['colors']['background'] ?? $settings->background_color ?? '#FFFFFF';
        $qrSize = $size ?? (int) ($settings->qr_size ?? 300);
        $margin = (int) ($settings->margin ?? 20);
        $url = $this->menuUrl($restaurant, $sectionSlug);

        $qrCode = QrCode::create($url)
            ->setEncoding(new Encoding('UTF-8'))
            ->setErrorCorrectionLevel(ErrorCorrectionLevel::Medium)
            ->setSize($qrSize)
            ->setMargin($margin)
            ->setForegroundColor($this->hexColor($fg))
            ->setBackgroundColor($this->hexColor($bg))
            ->setRoundBlockSizeMode(RoundBlockSizeMode::Margin);

        $format = strtolower($format);
        if ($format === 'svg') {
            $writer = new SvgWriter();
            $result = $writer->write($qrCode);

            return ['body' => $result->getString(), 'content_type' => 'image/svg+xml'];
        }

        $writer = new PngWriter();
        $result = $writer->write($qrCode);
        $pngBody = $result->getString();

        if (in_array($format, ['jpeg', 'jpg'], true)) {
            $jpeg = $this->pngToJpeg($pngBody);
            if ($jpeg !== null) {
                return ['body' => $jpeg, 'content_type' => 'image/jpeg'];
            }
        }

        if ($format === 'pdf') {
            $pdf = $this->pngToSimplePdf($pngBody, $restaurant->name ?? 'QR Code');
            if ($pdf !== null) {
                return ['body' => $pdf, 'content_type' => 'application/pdf'];
            }
        }

        return ['body' => $pngBody, 'content_type' => 'image/png'];
    }

    /** @return array{body: string, content_type: string}|null */
    public function generateTemplatePreviewImage(int $templateId, int $size = 200): ?array
    {
        $template = DB::table('qr_templates')->where('id', $templateId)->where('is_active', 1)->first();
        if (! $template) {
            return null;
        }

        $previewImage = $template->preview_image ?? '';
        if ($previewImage !== '' && preg_match('/^[a-z0-9_.-]+\.(png|svg)$/i', $previewImage)) {
            foreach ([
                public_path('uploads/qr-templates/'.$previewImage),
                public_path('legacy/uploads/qr-templates/'.$previewImage),
            ] as $path) {
                if (is_file($path) && is_readable($path)) {
                    $ext = strtolower(pathinfo($previewImage, PATHINFO_EXTENSION));

                    return [
                        'body' => (string) file_get_contents($path),
                        'content_type' => $ext === 'svg' ? 'image/svg+xml' : 'image/png',
                    ];
                }
            }
        }

        $config = is_string($template->config_json) ? json_decode($template->config_json, true) : $template->config_json;
        if (! is_array($config)) {
            return null;
        }

        $fg = $config['colors']['foreground'] ?? '#000000';
        $bg = $config['colors']['background'] ?? '#FFFFFF';
        $pixels = max(50, min(400, $size));

        $qrCode = QrCode::create('https://menu.example.com/preview')
            ->setEncoding(new Encoding('UTF-8'))
            ->setErrorCorrectionLevel(ErrorCorrectionLevel::Medium)
            ->setSize($pixels)
            ->setMargin(10)
            ->setForegroundColor($this->hexColor($fg))
            ->setBackgroundColor($this->hexColor($bg))
            ->setRoundBlockSizeMode(RoundBlockSizeMode::Margin);

        $writer = new PngWriter();
        $result = $writer->write($qrCode);

        return ['body' => $result->getString(), 'content_type' => 'image/png'];
    }

    /** Generate and store a preview image for a QR template. */
    public function generateTemplatePreview(int $templateId): ?string
    {
        $template = DB::table('qr_templates')->where('id', $templateId)->first();
        if (! $template || empty($template->config_json)) {
            return null;
        }

        $config = is_string($template->config_json) ? json_decode($template->config_json, true) : $template->config_json;
        if (! is_array($config)) {
            return null;
        }

        $fg = $config['colors']['foreground'] ?? '#000000';
        $bg = $config['colors']['background'] ?? '#FFFFFF';

        $qrCode = QrCode::create('https://menu.example.com/preview')
            ->setEncoding(new Encoding('UTF-8'))
            ->setErrorCorrectionLevel(ErrorCorrectionLevel::Medium)
            ->setSize(200)
            ->setMargin(10)
            ->setForegroundColor($this->hexColor($fg))
            ->setBackgroundColor($this->hexColor($bg))
            ->setRoundBlockSizeMode(RoundBlockSizeMode::Margin);

        $writer = new PngWriter();
        $result = $writer->write($qrCode);
        $dir = public_path('uploads/qr-templates');
        if (! is_dir($dir)) {
            @mkdir($dir, 0755, true);
        }

        foreach (['png', 'svg'] as $ext) {
            $old = $dir.'/'.$templateId.'.'.$ext;
            if (is_file($old)) {
                @unlink($old);
            }
        }

        $filename = $templateId.'.png';
        if (@file_put_contents($dir.'/'.$filename, $result->getString()) === false) {
            return null;
        }

        DB::table('qr_templates')->where('id', $templateId)->update([
            'preview_image' => $filename,
            'updated_at' => now(),
        ]);

        return $filename;
    }

    /** @return array<string, mixed>|null */
    private function resolveConfig(object $settings): ?array
    {
        $raw = $settings->final_config_json ?? null;
        if (! $raw) {
            $template = DB::table('qr_templates')->where('id', $settings->qr_template_id)->first();
            $raw = $template->config_json ?? null;
        }
        if (! $raw) {
            return null;
        }
        $decoded = is_string($raw) ? json_decode($raw, true) : $raw;

        return is_array($decoded) ? $decoded : null;
    }

    private function hexColor(string $hex): Color
    {
        $hex = ltrim($hex, '#');
        if (strlen($hex) !== 6) {
            $hex = '000000';
        }

        return new Color(
            hexdec(substr($hex, 0, 2)),
            hexdec(substr($hex, 2, 2)),
            hexdec(substr($hex, 4, 2)),
        );
    }

    private function pngToJpeg(string $pngData): ?string
    {
        if (! function_exists('imagecreatefromstring') || ! function_exists('imagejpeg')) {
            return null;
        }

        $img = @imagecreatefromstring($pngData);
        if (! $img) {
            return null;
        }

        ob_start();
        imagejpeg($img, null, 90);
        imagedestroy($img);
        $jpeg = ob_get_clean();

        return is_string($jpeg) && $jpeg !== '' ? $jpeg : null;
    }

    private function pngToSimplePdf(string $pngData, string $title): ?string
    {
        if (! function_exists('imagecreatefromstring')) {
            return null;
        }

        $img = @imagecreatefromstring($pngData);
        if (! $img) {
            return null;
        }

        ob_start();
        imagejpeg($img, null, 90);
        imagedestroy($img);
        $jpegData = ob_get_clean();
        if (! is_string($jpegData) || $jpegData === '') {
            return null;
        }

        $pageWidth = 595;
        $pageHeight = 842;
        $qrSize = 200;
        $qrX = ($pageWidth - $qrSize) / 2;
        $qrY = 300;
        $titleX = $pageWidth / 2;
        $titleY = $pageHeight - 100;
        $subtitleY = $qrY - 50;
        $safeTitle = str_replace(['(', ')', '\\'], ['\\(', '\\)', '\\\\'], $title);

        $content = "BT\n/F1 24 Tf\n{$titleX} {$titleY} Td\n({$safeTitle}) Tj\nET\n";
        $content .= "q\n{$qrSize} 0 0 {$qrSize} {$qrX} ".($pageHeight - $qrY - $qrSize)." cm\n/QR Do\nQ\n";
        $content .= "BT\n/F1 14 Tf\n{$titleX} {$subtitleY} Td\n(Scan to view menu) Tj\nET\n";

        $jpegLength = strlen($jpegData);
        $contentLength = strlen($content);

        $pdf = "%PDF-1.4\n";
        $offsets = [];
        $objects = [
            1 => "<< /Type /Catalog /Pages 2 0 R >>",
            2 => "<< /Type /Pages /Kids [3 0 R] /Count 1 >>",
            3 => "<< /Type /Page /Parent 2 0 R /MediaBox [0 0 {$pageWidth} {$pageHeight}] /Contents 4 0 R /Resources << /XObject << /QR 5 0 R >> /Font << /F1 6 0 R >> >> >>",
            4 => "<< /Length {$contentLength} >>\nstream\n{$content}\nendstream",
            5 => "<< /Type /XObject /Subtype /Image /Width 200 /Height 200 /ColorSpace /DeviceRGB /BitsPerComponent 8 /Filter /DCTDecode /Length {$jpegLength} >>\nstream\n{$jpegData}\nendstream",
            6 => "<< /Type /Font /Subtype /Type1 /BaseFont /Helvetica >>",
        ];

        foreach ($objects as $num => $body) {
            $offsets[$num] = strlen($pdf);
            $pdf .= "{$num} 0 obj\n{$body}\nendobj\n";
        }

        $xrefPos = strlen($pdf);
        $pdf .= "xref\n0 ".(count($objects) + 1)."\n";
        $pdf .= "0000000000 65535 f \n";
        foreach (array_keys($objects) as $num) {
            $pdf .= sprintf("%010d 00000 n \n", $offsets[$num]);
        }
        $pdf .= "trailer\n<< /Size ".(count($objects) + 1)." /Root 1 0 R >>\n";
        $pdf .= "startxref\n{$xrefPos}\n%%EOF";

        return $pdf;
    }
}
