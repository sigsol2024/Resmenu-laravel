@php
    $isEdit = !empty($template);
    $cfg = $config ?? [];
    $frame = $cfg['frame'] ?? [];
    $colors = $cfg['colors'] ?? [];
    $logo = $cfg['logo'] ?? [];
@endphp
@extends('layouts.admin')

@section('title', $isEdit ? 'Edit QR Template' : 'Create QR Template')

@section('content')
<div class="page-header">
    <h1 class="page-title">{{ $isEdit ? 'Edit QR Template' : 'Create QR Template' }}</h1>
    <p class="page-subtitle">Design QR code appearance with live preview</p>
</div>

<div class="card qr-editor-page">
    <form method="post"
          action="{{ $isEdit ? route('admin.qr-templates.update', $template->id) : route('admin.qr-templates.store') }}"
          id="qrTemplateForm">
        @csrf
        @if($isEdit) @method('PUT') @endif

        <div class="qr-editor-grid">
            <div class="template-editor-controls">
                <div class="form-group">
                    <label class="form-label">Template Name *</label>
                    <input type="text" name="name" class="form-input" value="{{ old('name', $template->name ?? '') }}" required>
                </div>

                <div class="form-group">
                    <label class="form-label">Description</label>
                    <textarea name="description" class="form-textarea" rows="2">{{ old('description', $template->description ?? '') }}</textarea>
                </div>

                <div class="form-group">
                    <label class="form-label">Pattern Style <span class="field-note">(Applied on download)</span></label>
                    <div class="radio-group">
                        @foreach(['square' => 'Square', 'dots' => 'Dots'] as $val => $label)
                            <label class="radio-option">
                                <input type="radio" name="pattern" value="{{ $val }}" @checked(old('pattern', $cfg['pattern'] ?? 'square') === $val) onchange="updateQrPreview()">
                                <span>{{ $label }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Eye Shape <span class="field-note">(Applied on download)</span></label>
                    <div class="radio-group">
                        @foreach(['square' => 'Square', 'rounded' => 'Rounded'] as $val => $label)
                            <label class="radio-option">
                                <input type="radio" name="eyes" value="{{ $val }}" @checked(old('eyes', $cfg['eyes'] ?? 'square') === $val) onchange="updateQrPreview()">
                                <span>{{ $label }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Frame Border</label>
                    <select name="frame_type" class="form-select" onchange="toggleFrameOptions(); updateQrPreview();">
                        @foreach(['none', 'square', 'rounded', 'circle', 'badge'] as $frameType)
                            <option value="{{ $frameType }}" @selected(old('frame_type', $frame['type'] ?? 'none') === $frameType)>{{ ucfirst($frameType) }}</option>
                        @endforeach
                    </select>
                    <div id="frameOptions" class="frame-options" style="display: {{ old('frame_type', $frame['type'] ?? 'none') !== 'none' ? 'block' : 'none' }};">
                        <label class="form-label">Frame Border Color</label>
                        <input type="color" name="frame_color" value="{{ old('frame_color', $frame['color'] ?? '#000000') }}" onchange="updateQrPreview()" class="color-input">
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Text Below QR Code</label>
                    <input type="text" name="frame_text" class="form-input" value="{{ old('frame_text', $frame['text'] ?? '') }}" placeholder="e.g., SCAN ME" oninput="updateQrPreview()">

                    <div class="qr-editor-inline-grid">
                        <div>
                            <label class="form-label form-label-sm">Text Color</label>
                            <input type="color" name="frame_text_color" value="{{ old('frame_text_color', $frame['text_color'] ?? '#FFFFFF') }}" onchange="updateQrPreview()" class="color-input color-input-sm">
                        </div>
                        <div>
                            <label class="form-label form-label-sm">Text Size (px)</label>
                            <input type="number" name="frame_text_size" value="{{ old('frame_text_size', $frame['text_size'] ?? 14) }}" min="10" max="48" class="form-input" onchange="updateQrPreview()">
                        </div>
                    </div>

                    <label class="checkbox-inline">
                        <input type="checkbox" name="frame_bg_enabled" id="frame_bg_enabled" value="1" @checked(old('frame_bg_enabled', $frame['bg_enabled'] ?? true)) onchange="toggleFrameBgOptions(); updateQrPreview();">
                        Text Background
                    </label>
                    <div id="frameBgOptions" class="frame-options" style="display: {{ old('frame_bg_enabled', $frame['bg_enabled'] ?? true) ? 'block' : 'none' }};">
                        <label class="form-label form-label-sm">Background Color</label>
                        <input type="color" name="frame_bg_color" value="{{ old('frame_bg_color', $frame['bg_color'] ?? '#000000') }}" onchange="updateQrPreview()" class="color-input color-input-sm">
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Foreground Color</label>
                    <input type="color" name="foreground_color" value="{{ old('foreground_color', $colors['foreground'] ?? '#000000') }}" onchange="updateQrPreview()" class="color-input">
                </div>

                <div class="form-group">
                    <label class="form-label">Background Color</label>
                    <input type="color" name="background_color" value="{{ old('background_color', $colors['background'] ?? '#FFFFFF') }}" onchange="updateQrPreview()" class="color-input">
                </div>

                <div class="form-group">
                    <label class="checkbox-inline">
                        <input type="checkbox" name="logo_enabled" id="logo_enabled" value="1" @checked(old('logo_enabled', $logo['enabled'] ?? false)) onchange="toggleLogoOptions(); updateQrPreview();">
                        Enable Logo <span class="field-note">(Preview only)</span>
                    </label>
                    <div id="logoOptions" class="frame-options" style="display: {{ old('logo_enabled', $logo['enabled'] ?? false) ? 'block' : 'none' }};">
                        <label class="form-label">Logo Size (10% - 30%)</label>
                        <input type="range" name="logo_size" min="0.1" max="0.3" step="0.05" value="{{ old('logo_size', $logo['size'] ?? 0.2) }}" onchange="updateQrPreview()">
                        <label class="checkbox-inline">
                            <input type="checkbox" name="logo_center_only" value="1" @checked(old('logo_center_only', $logo['center_only'] ?? true))>
                            Center only
                        </label>
                    </div>
                </div>

                @if($isEdit)
                    <div class="form-group">
                        <label class="checkbox-inline">
                            <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $template->is_active ?? 1))>
                            Active
                        </label>
                    </div>
                @endif
            </div>

            <div class="template-editor-preview">
                <h3 class="preview-title">Live Preview</h3>
                <div class="preview-panel">
                    <p class="preview-note"><strong>Note:</strong> Preview shows colors and frame only. Advanced styling (patterns, eyes, logos) is applied in the final QR download.</p>
                    <div id="qrPreviewWrapper" class="qr-preview-wrap">
                        <div id="qrPreview" class="qr-preview-canvas"><p class="preview-placeholder">Loading preview...</p></div>
                        <div id="qrPreviewText" class="qr-preview-text"></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="qr-editor-footer">
            <a href="{{ route('admin.qr-templates.index') }}" class="btn btn-secondary">Cancel</a>
            <button type="submit" class="btn btn-primary">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
                {{ $isEdit ? 'Update Template' : 'Create Template' }}
            </button>
        </div>
    </form>
</div>
@endsection

@push('head')
<link rel="stylesheet" href="{{ asset('legacy/css/pages/admin-qr-templates.css') }}">
@endpush

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
<script src="{{ asset('legacy/js/qr-template-editor.js') }}"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    initQrTemplateEditor('qrTemplateForm', 'qrPreview', 'qrPreviewText');
});
</script>
@endpush
