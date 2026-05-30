@extends('layouts.manager')

@section('title', 'QR Code')

@push('head')
<link rel="stylesheet" href="{{ resmenu_public_asset('css/pages/manager-qr-code.css') }}">
@endpush

@section('content')
<div class="page-header">
    <h1 class="page-title">QR Code</h1>
    <p class="page-subtitle">Select a template and download your restaurant's QR code</p>
</div>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif
@if($errors->any())
    <div class="alert alert-error">{{ $errors->first() }}</div>
@endif

<div style="display: grid; grid-template-columns: 1fr 1fr; gap: 24px; margin-bottom: 24px;" class="qr-code-grid">
    <div class="settings-card">
        <div class="section-header">
            <h2 class="section-title">Your QR Code</h2>
        </div>
        <div style="text-align: center; padding: 20px;">
            @if(empty($templates))
                <div style="padding: 40px;">
                    <p style="color: #6b7280; font-size: 1rem; margin-bottom: 8px;"><strong>No QR Code Templates Available</strong></p>
                    <p style="color: #6b7280; font-size: 0.875rem;">Please contact your administrator to create QR code templates.</p>
                </div>
            @elseif(! $selectedTemplate)
                <div style="padding: 40px;">
                    <p style="color: #6b7280; font-size: 1rem; margin-bottom: 8px;"><strong>No Template Selected</strong></p>
                    <p style="color: #6b7280; font-size: 0.875rem;">Please select a template from the list to generate your QR code.</p>
                </div>
            @else
                <div style="margin-bottom: 16px;">
                    <span style="display: inline-block; padding: 6px 16px; background: #111827; color: white; border-radius: 20px; font-size: 0.875rem; font-weight: 500;">
                        {{ $selectedTemplate->name }}
                    </span>
                </div>
                <div style="background: #f9fafb; border-radius: 12px; padding: 20px; display: inline-block; margin-bottom: 20px;">
                    @if($imageUrl)
                        <img src="{{ $imageUrl }}?t={{ time() }}" alt="QR Code" style="max-width: 250px; height: auto; display: block;">
                    @endif
                </div>
                <p style="color: #6b7280; margin-bottom: 20px; font-size: 0.875rem;">
                    <strong>URL:</strong> <code style="background: #f3f4f6; padding: 4px 8px; border-radius: 4px;">{{ $qrUrl }}</code>
                </p>
                <p style="margin-top: 12px;">
                    <a href="{{ route('manager.qr.image', ['format' => 'png', 'size' => 512, 'download' => 1]) }}" class="btn btn-primary">Download PNG</a>
                    <a href="{{ $menuUrl }}" target="_blank" rel="noopener" class="btn btn-secondary" style="margin-left: 8px;">Open menu</a>
                </p>
            @endif
        </div>
    </div>

    <div class="settings-card">
        <div class="section-header">
            <h2 class="section-title">Select Template</h2>
        </div>
        <div style="padding: 20px;">
            <form method="post" action="{{ route('manager.qr.code') }}">
                @csrf
                <div class="form-group">
                    <label class="form-label" for="qr_template_id">QR template</label>
                    <select id="qr_template_id" name="qr_template_id" class="form-select" required>
                        <option value="">— Select template —</option>
                        @foreach($templates as $t)
                            <option value="{{ $t->id }}" @selected(($qrSettings->qr_template_id ?? null) == $t->id)>{{ $t->name }}</option>
                        @endforeach
                    </select>
                </div>
                <button type="submit" class="btn btn-primary">Save template</button>
            </form>
            <p style="margin-top: 16px; font-size: 0.875rem; color: #6b7280;">
                <a href="{{ route('manager.qr.analytics') }}">View QR scan analytics →</a>
            </p>
        </div>
    </div>
</div>

@if($sections->isNotEmpty())
    <div class="settings-card">
        <h2 class="section-title">Section QR codes</h2>
        <ul style="list-style:none;padding:0;margin:0">
            @foreach($sections as $section)
                @php $sectionUrl = url('/qr/'.$restaurant->slug.'/'.$section->slug); @endphp
                <li style="padding:12px 0;border-bottom:1px solid #e5e7eb">
                    <strong>{{ $section->name }}</strong><br>
                    <span style="font-family:monospace;font-size:0.75rem;word-break:break-all;">{{ $sectionUrl }}</span><br>
                    @if($selectedTemplate)
                        <a href="{{ route('manager.qr.image', ['format' => 'png', 'size' => 256, 'section_slug' => $section->slug, 'download' => 1]) }}" style="font-size:0.875rem;color:#ea580c;">Download QR</a>
                    @endif
                </li>
            @endforeach
        </ul>
    </div>
@endif

<style>
@media (max-width: 900px) {
    .qr-code-grid { grid-template-columns: 1fr !important; }
}
</style>
@endsection
