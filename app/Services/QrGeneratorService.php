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
}
