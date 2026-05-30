@extends('layouts.manager')

@section('title', 'QR Code')

@push('head')
<link rel="stylesheet" href="{{ resmenu_public_asset('css/pages/manager-qr-code.css') }}">
@if($inline = resmenu_inline_page_css('manager-qr-code.css'))
<style>{!! $inline !!}</style>
@endif
@endpush

@section('content')
@if($dashCss = resmenu_inline_page_css('manager-qr-code.css'))
<style>{!! $dashCss !!}</style>
@endif

<div class="qr-code-page">
<div class="page-header">
    <h1 class="page-title">QR Code</h1>
    <p class="page-subtitle">Select a template and download your restaurant's QR code</p>
</div>

<div class="qr-code-grid">
    {{-- QR Code Preview & Download --}}
    <div class="settings-card">
        <div class="section-header">
            <h2 class="section-title">Your QR Code</h2>
        </div>
        <div style="text-align: center; padding: 20px;">
            @if(empty($templates))
                <div style="padding: 40px;">
                    <p style="color: var(--muted); font-size: 1rem; margin-bottom: 8px;">
                        <strong>No QR Code Templates Available</strong>
                    </p>
                    <p style="color: var(--muted); font-size: 0.875rem;">
                        Please contact your administrator to create QR code templates.
                    </p>
                </div>
            @elseif(! $selectedTemplate)
                <div style="padding: 40px;">
                    <p style="color: var(--muted); font-size: 1rem; margin-bottom: 8px;">
                        <strong>No Template Selected</strong>
                    </p>
                    <p style="color: var(--muted); font-size: 0.875rem;">
                        Please select a template from the list to generate your QR code.
                    </p>
                </div>
            @else
                <div style="margin-bottom: 16px;">
                    <span style="display: inline-block; padding: 6px 16px; background: var(--primary); color: white; border-radius: 20px; font-size: 0.875rem; font-weight: 500;">
                        {{ $selectedTemplate->name }}
                    </span>
                </div>

                <div style="background: #f9fafb; border-radius: 12px; padding: 20px; display: inline-block; margin-bottom: 20px;">
                    @if($imageUrl)
                        <img src="{{ $imageUrl }}&t={{ time() }}"
                             alt="QR Code"
                             style="max-width: 250px; height: auto; display: block;"
                             onerror="this.parentElement.innerHTML='<p style=\'color: var(--muted); padding: 40px;\'>Preview loading...</p>'">
                    @endif
                </div>

                <p style="color: var(--muted); margin-bottom: 20px; font-size: 0.875rem;">
                    <strong>URL:</strong> <code style="background: #f3f4f6; padding: 4px 8px; border-radius: 4px;">{{ $qrUrl }}</code>
                </p>

                @if($sections->isNotEmpty())
                    <div style="margin: 0 -4px 20px; padding-top: 8px;">
                        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:12px;">
                            <h3 class="section-title" style="margin:0; font-size:1rem;">Your QR Codes (Sections)</h3>
                        </div>
                        <div style="display:grid; grid-template-columns: repeat(auto-fill, minmax(180px, 1fr)); gap: 16px;">
                            @foreach($sections as $sec)
                                @if(empty($sec->slug))
                                    @continue
                                @endif
                                @php
                                    $secUrl = url('/qr/'.$restaurant->slug.'/'.$sec->slug);
                                    $secImg = route('manager.qr.image', ['format' => 'png', 'size' => 160, 'section_slug' => $sec->slug]);
                                @endphp
                                <div style="background: #f9fafb; border-radius: 12px; padding: 14px; text-align:center; border: 1px solid #e5e7eb;">
                                    <div style="font-weight: 600; font-size: 0.9rem; margin-bottom: 8px; color: var(--text);">
                                        {{ $sec->name }}
                                    </div>
                                    <div style="background:#fff; border-radius: 10px; padding: 10px; display:flex; justify-content:center; align-items:center; margin-bottom: 10px;">
                                        <img src="{{ $secImg }}&t={{ time() }}"
                                             alt="QR Code for {{ $sec->name }}"
                                             style="max-width: 160px; height: auto; display: block;"
                                             onerror="this.parentElement.innerHTML='<p style=\'color: var(--muted); margin: 0;\'>Preview loading...</p>'">
                                    </div>
                                    <div style="color: var(--muted); margin-bottom: 10px; font-size: 0.75rem;">
                                        <strong>URL:</strong>
                                        <div>
                                            <code style="background: #f3f4f6; padding: 3px 6px; border-radius: 4px; display:inline-block; word-break: break-word; max-width: 150px;">
                                                {{ $secUrl }}
                                            </code>
                                        </div>
                                    </div>
                                    <div style="display:flex; gap:8px; justify-content:center; flex-wrap:wrap;">
                                        <a href="{{ route('manager.qr.image', ['format' => 'png', 'download' => 1, 'section_slug' => $sec->slug]) }}"
                                           class="btn btn-primary" style="padding: 8px 12px; font-size: 0.75rem;">
                                            Download PNG
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                <div style="display: flex; gap: 12px; justify-content: center; flex-wrap: wrap;">
                    <a href="{{ route('manager.qr.image', ['format' => 'png', 'download' => 1]) }}" class="btn btn-primary">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                        </svg>
                        Download PNG
                    </a>
                    <a href="{{ route('manager.qr.image', ['format' => 'jpeg', 'download' => 1]) }}" class="btn btn-primary">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                        </svg>
                        Download JPEG
                    </a>
                    <a href="{{ route('manager.qr.image', ['format' => 'pdf', 'download' => 1]) }}" class="btn btn-primary">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                        </svg>
                        Download PDF
                    </a>
                </div>
            @endif
        </div>
    </div>

    {{-- Template Selection --}}
    <div class="settings-card">
        <div class="section-header">
            <h2 class="section-title">Select Template</h2>
        </div>

        @if(empty($templates))
            <div style="padding: 40px; text-align: center;">
                <p style="color: var(--muted); font-size: 1rem; margin-bottom: 8px;">
                    <strong>No Templates Available</strong>
                </p>
                <p style="color: var(--muted); font-size: 0.875rem;">
                    Please contact your administrator to create QR code templates.
                </p>
            </div>
        @else
            <form method="POST" id="template-form" action="{{ route('manager.qr.code') }}">
                @csrf
                <input type="hidden" name="qr_template_id" id="selected-template-id" value="{{ $qrSettings->qr_template_id ?? '' }}">

                <p style="color: var(--muted); font-size: 0.875rem; margin-bottom: 16px;">
                    Choose a QR code design created by your administrator:
                </p>

                <div class="template-grid">
                    @foreach($templates as $template)
                        @php
                            $isSelected = (int) ($qrSettings->qr_template_id ?? 0) === (int) $template->id;
                            $configJson = isset($template->config_json)
                                ? (is_string($template->config_json) ? $template->config_json : json_encode($template->config_json))
                                : '{}';
                            $previewSrc = $templatePreviewUrl.'?template_id='.(int) $template->id.'&size=100';
                        @endphp
                        <div class="template-card {{ $isSelected ? 'selected' : '' }}"
                             data-template-id="{{ $template->id }}"
                             onclick="selectTemplate({{ $template->id }})"
                             style="cursor: pointer; border: 3px solid {{ $isSelected ? 'var(--primary)' : '#e5e7eb' }}; border-radius: 12px; padding: 12px; text-align: center; transition: all 0.2s; background: {{ $isSelected ? '#f0f9ff' : 'white' }};">

                            <div style="font-weight: 600; margin-bottom: 8px; font-size: 0.875rem; color: {{ $isSelected ? 'var(--primary)' : 'var(--text)' }};">
                                {{ $template->name }}
                            </div>

                            <div style="width: 100%; height: 100px; background: #f9fafb; border-radius: 8px; display: flex; align-items: center; justify-content: center; overflow: hidden; margin-bottom: 8px;">
                                <div class="qr-preview-container"
                                     style="position: relative; width: 100%; height: 100%; display: flex; align-items: center; justify-content: center;"
                                     data-config="{{ e($configJson) }}"
                                     data-fallback-src="{{ e($previewSrc) }}"
                                     data-alt="{{ e($template->name) }}">
                                    <span style="color: var(--muted); font-size: 0.75rem;">Preview</span>
                                </div>
                            </div>

                            @if($isSelected)
                                <span style="display: inline-block; padding: 4px 10px; background: var(--primary); color: white; border-radius: 12px; font-size: 0.7rem; font-weight: 600;">
                                    ✓ SELECTED
                                </span>
                            @else
                                <span style="display: inline-block; padding: 4px 10px; background: #e5e7eb; color: var(--muted); border-radius: 12px; font-size: 0.7rem;">
                                    Click to select
                                </span>
                            @endif
                        </div>
                    @endforeach
                </div>

                <button type="submit" class="btn btn-primary" style="width: 100%;">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    Save Template Selection
                </button>
            </form>
        @endif
    </div>
</div>

{{-- Quick Stats --}}
<div class="card">
    <div class="card-header">
        <h2 class="card-title">Quick Stats</h2>
    </div>
    <div class="card-stats-row">
        <div>
            <div style="font-size: 2rem; font-weight: 700; color: var(--text); margin-bottom: 8px;">
                {{ number_format($totalScans) }}
            </div>
            <div style="font-size: 0.875rem; color: var(--muted);">Total Scans</div>
        </div>
        <div></div>
        <div>
            <a href="{{ route('manager.qr.analytics') }}" class="btn btn-secondary">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                </svg>
                View Full Analytics
            </a>
        </div>
    </div>
</div>
</div>

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
<script src="{{ resmenu_public_asset('js/qr-preview.js') }}"></script>
<script>
function selectTemplate(templateId) {
    document.getElementById('selected-template-id').value = templateId;

    document.querySelectorAll('.template-card').forEach(function(card) {
        var cardId = parseInt(card.getAttribute('data-template-id'), 10);
        if (cardId === templateId) {
            card.classList.add('selected');
            card.style.borderColor = 'var(--primary)';
            card.style.background = '#f0f9ff';
        } else {
            card.classList.remove('selected');
            card.style.borderColor = '#e5e7eb';
            card.style.background = 'white';
        }
    });
}

(function initQRPreviews() {
    function fallbackToImage(container) {
        var src = container.getAttribute('data-fallback-src');
        var alt = container.getAttribute('data-alt') || 'Preview';
        if (src) {
            var img = document.createElement('img');
            img.src = src;
            img.alt = alt;
            img.style.cssText = 'max-width:90%;max-height:90%;object-fit:contain;';
            container.innerHTML = '';
            container.appendChild(img);
        }
    }

    function runPreviews() {
        if (typeof generateQRPreview !== 'function') {
            document.querySelectorAll('.qr-preview-container').forEach(fallbackToImage);
            return;
        }

        document.querySelectorAll('.qr-preview-container').forEach(function(container) {
            var configRaw = container.getAttribute('data-config');
            var config = {};
            try {
                config = configRaw ? JSON.parse(configRaw) : {};
            } catch (e) {
                fallbackToImage(container);
                return;
            }
            try {
                generateQRPreview(container, config, null, 100, function() { fallbackToImage(container); });
            } catch (e) {
                fallbackToImage(container);
            }
            setTimeout(function() {
                if (!container.querySelector('svg')) {
                    fallbackToImage(container);
                }
            }, 4000);
        });
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', runPreviews);
    } else {
        runPreviews();
    }
})();
</script>
@endpush
@endsection
