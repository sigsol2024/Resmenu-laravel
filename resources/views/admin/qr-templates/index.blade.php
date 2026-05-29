@extends('layouts.admin')
@section('title', 'QR Templates')

@push('head')
<link rel="stylesheet" href="{{ asset('legacy/css/pages/admin-qr-templates.css') }}">
@endpush

@section('content')
@php
    $editConfig = $editConfig ?? [
        'pattern' => 'square',
        'eyes' => 'square',
        'frame' => ['type' => 'none', 'text' => '', 'color' => '#000000', 'text_color' => '#000000', 'text_size' => 14, 'bg_enabled' => false, 'bg_color' => '#FFFFFF'],
        'colors' => ['foreground' => '#000000', 'background' => '#FFFFFF'],
        'logo' => ['enabled' => false, 'size' => 0.2, 'center_only' => false],
    ];
@endphp

<!-- Page Header -->
<div class="page-header">
    <h1 class="page-title">QR Code Templates</h1>
    <p class="page-subtitle">Create and manage QR code designs for restaurants</p>
</div>

@if (session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

@if (session('error'))
    <div class="alert alert-error">
        {{ session('error') }}
    </div>
@endif

@if (isset($errors) && $errors->any())
    <div class="alert alert-error">
        {{ $errors->first() }}
    </div>
@endif

<div class="card">
    <div class="card-header">
        <h2 class="card-title">QR Code Templates</h2>
        <div style="display: flex; gap: 10px; flex-wrap: wrap;">
            <form method="POST" action="{{ route('admin.qr-templates.regenerate-previews') }}" style="display: inline;" onsubmit="return confirm('Generate preview images for all templates? This updates existing previews and creates previews for templates that don\'t have one yet.');">
                @csrf
                <button type="submit" class="btn btn-secondary">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" /></svg>
                    Regenerate all previews
                </button>
            </form>
            <button type="button" class="btn btn-primary" onclick="openCreateTemplateModal()">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Create New Template
            </button>
        </div>
    </div>
    <div style="overflow-x: auto;">
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Description</th>
                    <th>Has Text</th>
                    <th>Status</th>
                    <th>Usage</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @if ($templates->isEmpty())
                    <tr>
                        <td colspan="7" style="text-align: center; color: var(--muted); padding: 20px;">No templates found</td>
                    </tr>
                @else
                    @foreach ($templates as $template)
                        @php
                            $usageCount = (int) ($template->usage_count ?? 0);
                            $deleteConfirm = 'Are you sure you want to delete this template?'.($usageCount > 0 ? ' This will clear the template selection for '.$usageCount.' restaurant(s).' : '');
                        @endphp
                        <tr>
                            <td>{{ $template->id }}</td>
                            <td>{{ $template->name }}</td>
                            <td>{{ $template->description ?? '' }}</td>
                            <td>{{ $template->has_text ? 'Yes' : 'No' }}</td>
                            <td>
                                @if ($template->is_active)
                                    <span style="display: inline-block; padding: 4px 12px; border-radius: 12px; font-size: 0.75rem; font-weight: 500; background: #d1fae5; color: #065f46;">
                                        Active
                                    </span>
                                @else
                                    <span style="display: inline-block; padding: 4px 12px; border-radius: 12px; font-size: 0.75rem; font-weight: 500; background: #e5e7eb; color: #6b7280;">
                                        Inactive
                                    </span>
                                @endif
                            </td>
                            <td>{{ $template->usage_count ?? 0 }} restaurant(s)</td>
                            <td class="actions-cell">
                                <button class="actions-btn" type="button" title="Actions">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" width="20" height="20">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z" />
                                    </svg>
                                </button>
                                <div class="actions-dropdown">
                                    <a href="{{ route('admin.qr-templates.index', ['edit' => $template->id]) }}" class="actions-dropdown-item">Edit</a>
                                    <div class="actions-dropdown-divider"></div>
                                    <form method="POST" action="{{ route('admin.qr-templates.destroy', $template->id) }}" data-confirm="{{ $deleteConfirm }}" onsubmit="return confirm(this.dataset.confirm);">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="actions-dropdown-item danger">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                @endif
            </tbody>
        </table>
    </div>
</div>

<!-- Load QRCode library for preview functionality -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
<script>
// Adapt qrcodejs library to our API (simple preview - advanced styling done in PHP)
(function() {
    // Wait for QRCode to load, then create adapter
    function adaptQRCodeLibrary() {
        if (typeof QRCode === 'undefined') {
            setTimeout(adaptQRCodeLibrary, 100);
            return;
        }

        // qrcodejs uses constructor pattern, create adapter for our callback API
        var originalQRCode = QRCode;
        window.QRCode = {
            toString: function(text, options, callback) {
                try {
                    // Create hidden div for QR code
                    var div = document.createElement('div');
                    div.style.position = 'absolute';
                    div.style.left = '-9999px';
                    div.style.top = '-9999px';
                    document.body.appendChild(div);

                    // Generate QR code using qrcodejs
                    var qr = new originalQRCode(div, {
                        text: text,
                        width: options.width || 300,
                        height: options.width || 300,
                        colorDark: options.color.dark || '#000000',
                        colorLight: options.color.light || '#FFFFFF',
                        correctLevel: originalQRCode.CorrectLevel.M
                    });

                    // Wait for QR code to render, then convert to SVG
                    setTimeout(function() {
                        var canvas = div.querySelector('canvas');
                        if (canvas) {
                            var svg = canvasToSVG(canvas, options.color.dark || '#000000', options.color.light || '#FFFFFF');
                            document.body.removeChild(div);
                            if (callback) callback(null, svg);
                        } else {
                            document.body.removeChild(div);
                            if (callback) callback(new Error('Failed to generate QR code'));
                        }
                    }, 100);
                } catch (error) {
                    console.error('QR generation error:', error);
                    if (callback) callback(error);
                }
            }
        };

        // Signal that adapter is ready
        window.QRCodeAdapterReady = true;
    }

    // Convert canvas to SVG (for preview - advanced styling done server-side)
    function canvasToSVG(canvas, darkColor, lightColor) {
        var width = canvas.width;
        var height = canvas.height;
        var ctx = canvas.getContext('2d');
        var imageData = ctx.getImageData(0, 0, width, height);
        var data = imageData.data;

        var svg = '<svg width="' + width + '" height="' + height + '" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 ' + width + ' ' + height + '">';
        svg += '<rect width="' + width + '" height="' + height + '" fill="' + lightColor + '"/>';

        // Convert pixels to rectangles
        var blockSize = Math.max(1, Math.floor(width / 100));
        for (var y = 0; y < height; y += blockSize) {
            for (var x = 0; x < width; x += blockSize) {
                var idx = (y * width + x) * 4;
                if (idx < data.length && data[idx] < 128) {
                    var w = Math.min(blockSize, width - x);
                    var h = Math.min(blockSize, height - y);
                    svg += '<rect x="' + x + '" y="' + y + '" width="' + w + '" height="' + h + '" fill="' + darkColor + '"/>';
                }
            }
        }

        svg += '</svg>';
        return svg;
    }

    adaptQRCodeLibrary();
})();
</script>

<!-- Create Template Modal - Visual Editor -->
<div class="modal" id="createTemplateModal" style="display: none;">
    <div class="modal-overlay" onclick="closeCreateTemplateModal()"></div>
    <div class="modal-content qr-template-editor-modal" style="max-width: 1200px; width: 95%;">
        <form method="POST" action="{{ route('admin.qr-templates.store') }}" id="createTemplateForm">
            @csrf
            <div class="modal-header">
                <h2 class="modal-title">
                    Create QR Template
                </h2>
                <button type="button" class="modal-close" onclick="closeCreateTemplateModal()" aria-label="Close">&times;</button>
            </div>
            <div class="modal-body qr-template-editor-body">
                <!-- Left Column: Form Controls -->
                <div class="template-editor-controls">
                    <input type="hidden" name="action" value="create_template">

                    <div class="form-group">
                        <label class="form-label">Template Name *</label>
                        <input type="text" name="name" class="form-input" required>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Description</label>
                        <textarea name="description" class="form-textarea" rows="2"></textarea>
                    </div>

                    <!-- Pattern Selector -->
                    <div class="form-group">
                        <label class="form-label">Pattern Style <span style="color: var(--muted); font-size: 11px;">(Applied on download)</span></label>
                        <div class="radio-group">
                            <label class="radio-option">
                                <input type="radio" name="pattern" value="square" checked onchange="updatePreview()">
                                <span>Square</span>
                            </label>
                            <label class="radio-option">
                                <input type="radio" name="pattern" value="dots" onchange="updatePreview()">
                                <span>Dots</span>
                            </label>
                        </div>
                    </div>

                    <!-- Eye Shape Selector -->
                    <div class="form-group">
                        <label class="form-label">Eye Shape <span style="color: var(--muted); font-size: 11px;">(Applied on download)</span></label>
                        <div class="radio-group">
                            <label class="radio-option">
                                <input type="radio" name="eyes" value="square" checked onchange="updatePreview()">
                                <span>Square</span>
                            </label>
                            <label class="radio-option">
                                <input type="radio" name="eyes" value="rounded" onchange="updatePreview()">
                                <span>Rounded</span>
                            </label>
                        </div>
                    </div>

                    <!-- Frame Border Selector -->
                    <div class="form-group">
                        <label class="form-label">Frame Border</label>
                        <select name="frame_type" class="form-select" onchange="toggleFrameOptions(); updatePreview();">
                            <option value="none">None</option>
                            <option value="square">Square</option>
                            <option value="rounded">Rounded</option>
                            <option value="circle">Circle</option>
                            <option value="badge">Badge</option>
                        </select>
                        <div id="frameOptions" style="display: none; margin-top: 12px;">
                            <label class="form-label" style="margin-bottom: 4px;">Frame Border Color</label>
                            <input type="color" name="frame_color" value="#000000" onchange="updatePreview()" style="width: 100%; height: 40px; cursor: pointer;">
                        </div>
                    </div>

                    <!-- Text Below QR Code -->
                    <div class="form-group">
                        <label class="form-label">Text Below QR Code</label>
                        <input type="text" name="frame_text" class="form-input" placeholder="e.g., SCAN ME" style="margin-bottom: 12px;" oninput="updatePreview()">

                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px; margin-bottom: 12px;">
                            <div>
                                <label class="form-label" style="margin-bottom: 4px; font-size: 12px;">Text Color</label>
                                <input type="color" name="frame_text_color" value="#FFFFFF" onchange="updatePreview()" style="width: 100%; height: 36px; cursor: pointer;">
                            </div>
                            <div>
                                <label class="form-label" style="margin-bottom: 4px; font-size: 12px;">Text Size (px)</label>
                                <input type="number" name="frame_text_size" value="14" min="10" max="48" class="form-input" style="height: 36px;" onchange="updatePreview()">
                            </div>
                        </div>

                        <label style="display: flex; align-items: center; gap: 8px; cursor: pointer; margin-bottom: 8px;">
                            <input type="checkbox" name="frame_bg_enabled" id="frame_bg_enabled_create" checked onchange="toggleFrameBgOptions(); updatePreview();" style="width: 18px; height: 18px; cursor: pointer;">
                            <span>Text Background</span>
                        </label>
                        <div id="frameBgOptions" style="margin-top: 8px;">
                            <label class="form-label" style="margin-bottom: 4px; font-size: 12px;">Background Color</label>
                            <input type="color" name="frame_bg_color" value="#000000" onchange="updatePreview()" style="width: 100%; height: 36px; cursor: pointer;">
                        </div>
                    </div>

                    <!-- Colors -->
                    <div class="form-group">
                        <label class="form-label">Foreground Color</label>
                        <input type="color" name="foreground_color" value="#000000" onchange="updatePreview()" style="width: 100%; height: 40px; cursor: pointer;">
                    </div>

                    <div class="form-group">
                        <label class="form-label">Background Color</label>
                        <input type="color" name="background_color" value="#FFFFFF" onchange="updatePreview()" style="width: 100%; height: 40px; cursor: pointer;">
                    </div>

                    <!-- Logo Rules -->
                    <div class="form-group">
                        <label style="display: flex; align-items: center; gap: 8px; cursor: pointer;">
                            <input type="checkbox" name="logo_enabled" id="logo_enabled_create" onchange="toggleLogoOptions(); updatePreview();" style="width: 18px; height: 18px; cursor: pointer;">
                            <span>Enable Logo <span style="color: var(--muted); font-size: 11px;">(Preview only)</span></span>
                        </label>
                        <div id="logoOptions" style="display: none; margin-top: 12px;">
                            <label class="form-label">Logo Size (10% - 30%)</label>
                            <input type="range" name="logo_size" min="0.1" max="0.3" step="0.05" value="0.2" onchange="updatePreview()" style="width: 100%;">
                            <label style="display: flex; align-items: center; gap: 8px; cursor: pointer; margin-top: 8px;">
                                <input type="checkbox" name="logo_center_only" checked style="width: 18px; height: 18px; cursor: pointer;">
                                <span>Center only</span>
                            </label>
                        </div>
                    </div>

                </div>

                <!-- Right Column: Live Preview -->
                <div class="template-editor-preview">
                    <h3 style="margin-top: 0; margin-bottom: 16px;">Live Preview</h3>
                    <div style="background: white; border: 2px solid #e5e7eb; border-radius: 8px; padding: 20px; min-height: 300px;">
                        <p style="color: var(--muted); font-size: 12px; margin-bottom: 12px; padding: 8px; background: #f0f9ff; border-radius: 4px;">
                            <strong>Note:</strong> Preview shows colors and frame only. Advanced styling (patterns, eyes, logos) is applied in the final QR download.
                        </p>
                        <div id="qrPreviewWrapper" style="display: flex; flex-direction: column; align-items: center; gap: 12px;">
                            <div id="qrPreview" style="position: relative; display: flex; align-items: center; justify-content: center; min-height: 250px;">
                                <p style="color: var(--muted);">Preview will appear here</p>
                            </div>
                            <div id="qrPreviewText" style="display: none;"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="closeCreateTemplateModal()">Cancel</button>
                <button type="submit" class="btn btn-primary">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    Create Template
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Edit Template Modal - Visual Editor -->
@if ($editTemplate)
<div class="modal" id="editTemplateModal" style="display: flex;">
    <div class="modal-overlay" onclick="closeEditTemplateModal()"></div>
    <div class="modal-content qr-template-editor-modal" style="max-width: 1200px; width: 95%;">
        <form method="POST" action="{{ route('admin.qr-templates.update', $editTemplate->id) }}" id="editTemplateForm">
            @csrf
            @method('PUT')
            <div class="modal-header">
                <h2 class="modal-title">
                    Edit QR Template
                </h2>
                <a href="{{ route('admin.qr-templates.index') }}" class="modal-close" aria-label="Close">&times;</a>
            </div>
            <div class="modal-body qr-template-editor-body">
                <!-- Left Column: Form Controls -->
                <div class="template-editor-controls">
                    <input type="hidden" name="action" value="update_template">
                    <input type="hidden" name="template_id" value="{{ $editTemplate->id }}">

                    <div class="form-group">
                        <label class="form-label">Template Name *</label>
                        <input type="text" name="name" class="form-input" value="{{ $editTemplate->name }}" required>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Description</label>
                        <textarea name="description" class="form-textarea" rows="2">{{ $editTemplate->description ?? '' }}</textarea>
                    </div>

                    <!-- Pattern Selector -->
                    <div class="form-group">
                        <label class="form-label">Pattern Style <span style="color: var(--muted); font-size: 11px;">(Applied on download)</span></label>
                        <div class="radio-group">
                            <label class="radio-option">
                                <input type="radio" name="pattern" value="square" {{ ($editConfig['pattern'] ?? 'square') === 'square' ? 'checked' : '' }} onchange="updatePreviewEdit()">
                                <span>Square</span>
                            </label>
                            <label class="radio-option">
                                <input type="radio" name="pattern" value="dots" {{ ($editConfig['pattern'] ?? 'square') === 'dots' ? 'checked' : '' }} onchange="updatePreviewEdit()">
                                <span>Dots</span>
                            </label>
                        </div>
                    </div>

                    <!-- Eye Shape Selector -->
                    <div class="form-group">
                        <label class="form-label">Eye Shape <span style="color: var(--muted); font-size: 11px;">(Applied on download)</span></label>
                        <div class="radio-group">
                            <label class="radio-option">
                                <input type="radio" name="eyes" value="square" {{ ($editConfig['eyes'] ?? 'square') === 'square' ? 'checked' : '' }} onchange="updatePreviewEdit()">
                                <span>Square</span>
                            </label>
                            <label class="radio-option">
                                <input type="radio" name="eyes" value="rounded" {{ ($editConfig['eyes'] ?? 'square') === 'rounded' ? 'checked' : '' }} onchange="updatePreviewEdit()">
                                <span>Rounded</span>
                            </label>
                        </div>
                    </div>

                    <!-- Frame Border Selector -->
                    <div class="form-group">
                        <label class="form-label">Frame Border</label>
                        <select name="frame_type" class="form-select" onchange="toggleFrameOptionsEdit(); updatePreviewEdit();">
                            <option value="none" {{ ($editConfig['frame']['type'] ?? 'none') === 'none' ? 'selected' : '' }}>None</option>
                            <option value="square" {{ ($editConfig['frame']['type'] ?? 'none') === 'square' ? 'selected' : '' }}>Square</option>
                            <option value="rounded" {{ ($editConfig['frame']['type'] ?? 'none') === 'rounded' ? 'selected' : '' }}>Rounded</option>
                            <option value="circle" {{ ($editConfig['frame']['type'] ?? 'none') === 'circle' ? 'selected' : '' }}>Circle</option>
                            <option value="badge" {{ ($editConfig['frame']['type'] ?? 'none') === 'badge' ? 'selected' : '' }}>Badge</option>
                        </select>
                        <div id="frameOptionsEdit" style="display: {{ ($editConfig['frame']['type'] ?? 'none') !== 'none' ? 'block' : 'none' }}; margin-top: 12px;">
                            <label class="form-label" style="margin-bottom: 4px;">Frame Border Color</label>
                            <input type="color" name="frame_color" value="{{ $editConfig['frame']['color'] ?? '#000000' }}" onchange="updatePreviewEdit()" style="width: 100%; height: 40px; cursor: pointer;">
                        </div>
                    </div>

                    <!-- Text Below QR Code -->
                    <div class="form-group">
                        <label class="form-label">Text Below QR Code</label>
                        <input type="text" name="frame_text" class="form-input" value="{{ $editConfig['frame']['text'] ?? '' }}" placeholder="e.g., SCAN ME" style="margin-bottom: 12px;" oninput="updatePreviewEdit()">

                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px; margin-bottom: 12px;">
                            <div>
                                <label class="form-label" style="margin-bottom: 4px; font-size: 12px;">Text Color</label>
                                <input type="color" name="frame_text_color" value="{{ $editConfig['frame']['text_color'] ?? '#FFFFFF' }}" onchange="updatePreviewEdit()" style="width: 100%; height: 36px; cursor: pointer;">
                            </div>
                            <div>
                                <label class="form-label" style="margin-bottom: 4px; font-size: 12px;">Text Size (px)</label>
                                <input type="number" name="frame_text_size" value="{{ $editConfig['frame']['text_size'] ?? 14 }}" min="10" max="48" class="form-input" style="height: 36px;" onchange="updatePreviewEdit()">
                            </div>
                        </div>

                        <label style="display: flex; align-items: center; gap: 8px; cursor: pointer; margin-bottom: 8px;">
                            <input type="checkbox" name="frame_bg_enabled" id="frame_bg_enabled_edit" {{ ($editConfig['frame']['bg_enabled'] ?? true) ? 'checked' : '' }} onchange="toggleFrameBgOptionsEdit(); updatePreviewEdit();" style="width: 18px; height: 18px; cursor: pointer;">
                            <span>Text Background</span>
                        </label>
                        <div id="frameBgOptionsEdit" class="{{ ($editConfig['frame']['bg_enabled'] ?? true) ? '' : 'is-hidden' }}" style="margin-top: 8px;">
                            <label class="form-label" style="margin-bottom: 4px; font-size: 12px;">Background Color</label>
                            <input type="color" name="frame_bg_color" value="{{ $editConfig['frame']['bg_color'] ?? '#000000' }}" onchange="updatePreviewEdit()" style="width: 100%; height: 36px; cursor: pointer;">
                        </div>
                    </div>

                    <!-- Colors -->
                    <div class="form-group">
                        <label class="form-label">Foreground Color</label>
                        <input type="color" name="foreground_color" value="{{ $editConfig['colors']['foreground'] ?? '#000000' }}" onchange="updatePreviewEdit()" style="width: 100%; height: 40px; cursor: pointer;">
                    </div>

                    <div class="form-group">
                        <label class="form-label">Background Color</label>
                        <input type="color" name="background_color" value="{{ $editConfig['colors']['background'] ?? '#FFFFFF' }}" onchange="updatePreviewEdit()" style="width: 100%; height: 40px; cursor: pointer;">
                    </div>

                    <!-- Logo Rules -->
                    <div class="form-group">
                        <label style="display: flex; align-items: center; gap: 8px; cursor: pointer;">
                            <input type="checkbox" name="logo_enabled" id="logo_enabled_edit" {{ ($editConfig['logo']['enabled'] ?? false) ? 'checked' : '' }} onchange="toggleLogoOptionsEdit(); updatePreviewEdit();" style="width: 18px; height: 18px; cursor: pointer;">
                            <span>Enable Logo <span style="color: var(--muted); font-size: 11px;">(Preview only)</span></span>
                        </label>
                        <div id="logoOptionsEdit" class="{{ ($editConfig['logo']['enabled'] ?? false) ? '' : 'is-hidden' }}" style="margin-top: 12px;">
                            <label class="form-label">Logo Size (10% - 30%)</label>
                            <input type="range" name="logo_size" min="0.1" max="0.3" step="0.05" value="{{ $editConfig['logo']['size'] ?? 0.2 }}" onchange="updatePreviewEdit()" style="width: 100%;">
                            <label style="display: flex; align-items: center; gap: 8px; cursor: pointer; margin-top: 8px;">
                                <input type="checkbox" name="logo_center_only" {{ ($editConfig['logo']['center_only'] ?? true) ? 'checked' : '' }} style="width: 18px; height: 18px; cursor: pointer;">
                                <span>Center only</span>
                            </label>
                        </div>
                    </div>

                    <div class="form-group">
                        <label style="display: flex; align-items: center; gap: 8px; cursor: pointer;">
                            <input type="checkbox" name="is_active" id="is_active_edit" {{ ($editTemplate->is_active ?? 0) ? 'checked' : '' }} style="width: 18px; height: 18px; cursor: pointer;">
                            <span>Active</span>
                        </label>
                    </div>
                </div>

                <!-- Right Column: Live Preview -->
                <div class="template-editor-preview">
                    <h3 style="margin-top: 0; margin-bottom: 16px;">Live Preview</h3>
                    <div style="background: white; border: 2px solid #e5e7eb; border-radius: 8px; padding: 20px; min-height: 300px;">
                        <p style="color: var(--muted); font-size: 12px; margin-bottom: 12px; padding: 8px; background: #f0f9ff; border-radius: 4px;">
                            <strong>Note:</strong> Preview shows colors and frame only. Advanced styling (patterns, eyes, logos) is applied in the final QR download.
                        </p>
                        <div id="qrPreviewWrapperEdit" style="display: flex; flex-direction: column; align-items: center; gap: 12px;">
                            <div id="qrPreviewEdit" style="position: relative; display: flex; align-items: center; justify-content: center; min-height: 250px;">
                                <p style="color: var(--muted);">Loading preview...</p>
                            </div>
                            <div id="qrPreviewTextEdit" style="display: none;"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <a href="{{ route('admin.qr-templates.index') }}" class="btn btn-secondary">Cancel</a>
                <button type="submit" class="btn btn-primary">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    Update Template
                </button>
            </div>
        </form>
    </div>
</div>
<script>
document.body.style.overflow = 'hidden';
// Flag to indicate edit modal needs preview initialization
window.editModalNeedsInit = true;
</script>
@endif

<script>
// Wait for QRCode library to be available
function waitForQRCode(callback, maxAttempts = 50) {
    if (typeof QRCode !== 'undefined' && typeof QRCode.toString === 'function' && window.QRCodeAdapterReady) {
        callback();
    } else if (maxAttempts > 0) {
        setTimeout(() => waitForQRCode(callback, maxAttempts - 1), 100);
    } else {
        console.error('QRCode library failed to load');
        // Try to call anyway if QRCode exists
        if (typeof QRCode !== 'undefined' && typeof QRCode.toString === 'function') {
            callback();
        }
    }
}

function openCreateTemplateModal() {
    document.getElementById('createTemplateModal').style.display = 'flex';
    document.body.style.overflow = 'hidden';
    waitForQRCode(() => updatePreview());
}

function closeCreateTemplateModal() {
    document.getElementById('createTemplateModal').style.display = 'none';
    document.body.style.overflow = '';
}

function closeEditTemplateModal() {
    window.location.href = "{{ route('admin.qr-templates.index') }}";
}

function toggleFrameOptions() {
    const frameType = document.querySelector('#createTemplateForm select[name="frame_type"]').value;
    const frameOptions = document.getElementById('frameOptions');
    frameOptions.style.display = frameType !== 'none' ? 'block' : 'none';
}

function toggleFrameOptionsEdit() {
    const frameType = document.querySelector('#editTemplateForm select[name="frame_type"]').value;
    const frameOptions = document.getElementById('frameOptionsEdit');
    frameOptions.style.display = frameType !== 'none' ? 'block' : 'none';
}

function toggleFrameBgOptions() {
    const bgCheckbox = document.getElementById('frame_bg_enabled_create');
    const bgOptions = document.getElementById('frameBgOptions');
    if (bgCheckbox && bgOptions) {
        bgOptions.style.display = bgCheckbox.checked ? 'block' : 'none';
    }
}

function toggleFrameBgOptionsEdit() {
    const bgCheckbox = document.getElementById('frame_bg_enabled_edit');
    const bgOptions = document.getElementById('frameBgOptionsEdit');
    if (bgCheckbox && bgOptions) {
        bgOptions.style.display = bgCheckbox.checked ? 'block' : 'none';
    }
}

function toggleLogoOptions() {
    const logoCheckbox = document.getElementById('logo_enabled_create');
    if (!logoCheckbox) return;
    const logoEnabled = logoCheckbox.checked;
    const logoOptions = document.getElementById('logoOptions');
    logoOptions.style.display = logoEnabled ? 'block' : 'none';
}

function toggleLogoOptionsEdit() {
    const logoCheckbox = document.getElementById('logo_enabled_edit');
    if (!logoCheckbox) return;
    const logoEnabled = logoCheckbox.checked;
    const logoOptions = document.getElementById('logoOptionsEdit');
    logoOptions.style.display = logoEnabled ? 'block' : 'none';
}

// Update preview for create modal
function updatePreview() {
    const previewDiv = document.getElementById('qrPreview');
    const previewTextDiv = document.getElementById('qrPreviewText');
    if (!previewDiv) return;

    const form = document.getElementById('createTemplateForm');
    if (!form) return;

    const formData = new FormData(form);
    const config = {
        pattern: formData.get('pattern') || 'square',
        eyes: formData.get('eyes') || 'square',
        frame: {
            type: formData.get('frame_type') || 'none',
            text: formData.get('frame_text') || '',
            color: formData.get('frame_color') || '#000000',
            text_color: formData.get('frame_text_color') || '#000000',
            text_size: parseInt(formData.get('frame_text_size') || 14),
            bg_enabled: formData.get('frame_bg_enabled') === 'on',
            bg_color: formData.get('frame_bg_color') || '#FFFFFF'
        },
        colors: {
            foreground: formData.get('foreground_color') || '#000000',
            background: formData.get('background_color') || '#FFFFFF'
        },
        logo: {
            enabled: formData.get('logo_enabled') === 'on',
            size: parseFloat(formData.get('logo_size') || 0.2)
        }
    };

    // Generate QR code client-side
    generateQRPreview(previewDiv, config, previewTextDiv);
}

// Generate QR preview using client-side library
// Note: Preview shows basic QR - advanced styling (patterns, eyes, frames) done server-side
function generateQRPreview(container, config, textContainer) {
    // Check if library is loaded
    if (typeof QRCode === 'undefined' || typeof QRCode.toString !== 'function') {
        container.innerHTML = '<p style="color: var(--muted);">QR Code library loading...</p>';
        waitForQRCode(() => generateQRPreview(container, config, textContainer));
        return;
    }

    container.innerHTML = '<p style="color: var(--muted);">Generating preview...</p>';

    const testURL = 'https://example.com/test-qr-preview';
    const size = 300;
    const margin = 2;

    try {
        // Generate QR code as SVG (basic preview - advanced styling in PHP)
        QRCode.toString(testURL, {
            type: 'svg',
            width: size,
            margin: margin,
            color: {
                dark: config.colors.foreground,
                light: config.colors.background
            },
            errorCorrectionLevel: 'M'
        }, function(err, svg) {
            if (err) {
                console.error('QR Code generation error:', err);
                container.innerHTML = '<p style="color: red;">Error generating QR code: ' + (err.message || err) + '</p>';
                return;
            }

            if (!svg || typeof svg !== 'string') {
                console.error('Invalid SVG returned:', svg);
                container.innerHTML = '<p style="color: red;">Invalid QR code generated</p>';
                return;
            }

            // Apply frame if specified (simple preview - frame drawn around QR)
            let styledSvg = svg;
            if (config.frame.type !== 'none') {
                styledSvg = applyFrameToSVG(svg, config.frame, size);
            }

            // Display QR code
            container.innerHTML = styledSvg;

            // Get actual SVG width after rendering
            const svgElement = container.querySelector('svg');
            const actualWidth = svgElement ? (svgElement.getAttribute('width') || svgElement.getBoundingClientRect().width || size) : size;
            const qrWidth = parseFloat(actualWidth) || size;

            // Handle text OUTSIDE the QR code (below it)
            if (textContainer) {
                if (config.frame.text && config.frame.text.trim()) {
                    textContainer.textContent = config.frame.text;
                    textContainer.style.display = 'block';
                    textContainer.style.color = config.frame.text_color || '#000000';
                    textContainer.style.fontSize = (config.frame.text_size || 14) + 'px';
                    textContainer.style.fontWeight = '600';
                    textContainer.style.textAlign = 'center';
                    textContainer.style.width = qrWidth + 'px'; // Same width as QR code
                    textContainer.style.padding = '8px 0';
                    textContainer.style.marginTop = '0';

                    // Apply background if enabled
                    if (config.frame.bg_enabled) {
                        textContainer.style.backgroundColor = config.frame.bg_color || '#FFFFFF';
                        textContainer.style.borderRadius = '4px';
                    } else {
                        textContainer.style.backgroundColor = 'transparent';
                    }
                } else {
                    textContainer.style.display = 'none';
                }
            }

            // Handle logo overlay (fake preview)
            if (config.logo && config.logo.enabled) {
                addLogoOverlay(container, config.logo.size || 0.2);
            } else {
                removeLogoOverlay(container);
            }
        });
    } catch (error) {
        console.error('QR Preview error:', error);
        container.innerHTML = '<p style="color: red;">Error: ' + error.message + '</p>';
    }
}

// Add fake logo overlay for preview
function addLogoOverlay(container, sizePercent) {
    // Remove existing logo overlay if any
    removeLogoOverlay(container);

    // Create logo placeholder overlay
    const logoOverlay = document.createElement('div');
    logoOverlay.id = 'qrLogoOverlay';
    logoOverlay.style.cssText = `
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        width: ${sizePercent * 100}%;
        height: ${sizePercent * 100}%;
        background: white;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        border: 2px solid #e5e7eb;
    `;
    logoOverlay.innerHTML = '<span style="color: #9ca3af; font-size: 12px;">LOGO</span>';
    container.appendChild(logoOverlay);
}

// Remove logo overlay
function removeLogoOverlay(container) {
    const existing = container.querySelector('#qrLogoOverlay');
    if (existing) {
        existing.remove();
    }
}

// Apply pattern style to SVG
function applyPatternToSVG(svg, pattern) {
    if (pattern === 'square') {
        return svg; // Default, no change
    }

    if (pattern === 'dots') {
        // Replace rectangles with circles
        svg = svg.replace(/<rect([^>]*?)x="([^"]*?)"\s+y="([^"]*?)"\s+width="([^"]*?)"\s+height="([^"]*?)"([^>]*?)>/gi, function(match, p1, x, y, w, h, p6) {
            const cx = parseFloat(x) + parseFloat(w) / 2;
            const cy = parseFloat(y) + parseFloat(h) / 2;
            const r = Math.min(parseFloat(w), parseFloat(h)) / 2;
            return `<circle cx="${cx}" cy="${cy}" r="${r}"${p1}${p6}>`;
        });
        svg = svg.replace(/<\/rect>/g, '</circle>');
    }

    if (pattern === 'rounded' || pattern === 'extra-rounded') {
        const radius = pattern === 'extra-rounded' ? '8' : '4';
        svg = svg.replace(/<rect([^>]*?)>/gi, function(match) {
            if (match.indexOf('rx=') === -1) {
                return match.replace('>', ` rx="${radius}" ry="${radius}">`);
            }
            return match;
        });
    }

    return svg;
}

// Apply eye shape to SVG
function applyEyeShapeToSVG(svg, eyeShape) {
    if (eyeShape === 'square') {
        return svg; // Default, no change
    }

    if (eyeShape === 'rounded') {
        // Round corners of large squares (eyes)
        svg = svg.replace(/<rect([^>]*?)x="([^"]*?)"\s+y="([^"]*?)"\s+width="([^"]*?)"\s+height="([^"]*?)"([^>]*?)>/gi, function(match, p1, x, y, w, h, p6) {
            const width = parseFloat(w);
            const height = parseFloat(h);
            // Only round if it's a large square (likely an eye)
            if (width > 20 && height > 20) {
                const radius = Math.min(width, height) * 0.2;
                if (match.indexOf('rx=') === -1) {
                    return match.replace('>', ` rx="${radius}" ry="${radius}">`);
                }
            }
            return match;
        });
    }

    return svg;
}

// Apply frame to SVG
function applyFrameToSVG(svg, frameConfig, size) {
    const frameType = frameConfig.type;
    const frameColor = frameConfig.color || '#000000';
    const frameText = frameConfig.text || '';

    // Extract viewBox or use default
    let viewBox = '0 0 ' + size + ' ' + size;
    const viewBoxMatch = svg.match(/viewBox="([^"]*)"/);
    if (viewBoxMatch) {
        viewBox = viewBoxMatch[1];
    }

    let frameSvg = '';

    switch (frameType) {
        case 'square':
            frameSvg = `<rect x="0" y="0" width="${size}" height="${size}" fill="none" stroke="${frameColor}" stroke-width="4"/>`;
            break;
        case 'rounded':
            frameSvg = `<rect x="0" y="0" width="${size}" height="${size}" rx="20" ry="20" fill="none" stroke="${frameColor}" stroke-width="4"/>`;
            break;
        case 'circle':
            const center = size / 2;
            const radius = size / 2 - 2;
            frameSvg = `<circle cx="${center}" cy="${center}" r="${radius}" fill="none" stroke="${frameColor}" stroke-width="4"/>`;
            break;
        case 'badge':
            frameSvg = `<rect x="0" y="0" width="${size}" height="${size}" rx="15" ry="15" fill="none" stroke="${frameColor}" stroke-width="4"/>`;
            const notchWidth = 60;
            const notchHeight = 20;
            const notchX = (size - notchWidth) / 2;
            frameSvg += `<rect x="${notchX}" y="-2" width="${notchWidth}" height="${notchHeight}" rx="10" ry="10" fill="white" stroke="${frameColor}" stroke-width="4"/>`;
            break;
    }

    // Note: Frame text is handled OUTSIDE the QR code via textContainer
    // We don't add text inside the SVG here

    // Insert frame before closing </svg> tag
    svg = svg.replace('</svg>', frameSvg + '</svg>');

    return svg;
}

function escapeHtml(text) {
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

// Update preview for edit modal
function updatePreviewEdit() {
    const previewDiv = document.getElementById('qrPreviewEdit');
    const previewTextDiv = document.getElementById('qrPreviewTextEdit');
    if (!previewDiv) return;

    const form = document.getElementById('editTemplateForm');
    if (!form) return;

    const formData = new FormData(form);
    const config = {
        pattern: formData.get('pattern') || 'square',
        eyes: formData.get('eyes') || 'square',
        frame: {
            type: formData.get('frame_type') || 'none',
            text: formData.get('frame_text') || '',
            color: formData.get('frame_color') || '#000000',
            text_color: formData.get('frame_text_color') || '#000000',
            text_size: parseInt(formData.get('frame_text_size') || 14),
            bg_enabled: formData.get('frame_bg_enabled') === 'on',
            bg_color: formData.get('frame_bg_color') || '#FFFFFF'
        },
        colors: {
            foreground: formData.get('foreground_color') || '#000000',
            background: formData.get('background_color') || '#FFFFFF'
        },
        logo: {
            enabled: formData.get('logo_enabled') === 'on',
            size: parseFloat(formData.get('logo_size') || 0.2)
        }
    };

    // Generate QR code client-side
    generateQRPreview(previewDiv, config, previewTextDiv);
}

// Initialize logo options toggle on create modal
document.addEventListener('DOMContentLoaded', function() {
    const logoCheckbox = document.getElementById('logo_enabled_create');
    if (logoCheckbox) {
        logoCheckbox.addEventListener('change', toggleLogoOptions);
    }

    // Initialize edit modal preview if it's open
    if (window.editModalNeedsInit && document.getElementById('qrPreviewEdit')) {
        waitForQRCode(function() {
            updatePreviewEdit();
        });
    }

    @if(!empty($openCreateModal))
    openCreateTemplateModal();
    @endif
});
</script>
@endsection
