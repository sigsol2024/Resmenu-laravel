@extends('layouts.manager')

@section('title', 'QR code')

@section('content')
<div class="page-header">
    <h1 class="page-title">QR code</h1>
    <p class="page-subtitle">Select a branded template and download QR codes for your menu</p>
</div>

@if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
@if($errors->any())<div class="alert alert-error">{{ $errors->first() }}</div>@endif

<div class="settings-card" style="margin-bottom:24px;">
    <h2 class="section-title">QR template</h2>
    <form method="post" action="{{ route('manager.qr.code') }}">
        @csrf
        <div class="form-group">
            <select name="qr_template_id" class="form-select" required>
                <option value="">— Select template —</option>
                @foreach($templates as $t)
                    <option value="{{ $t->id }}" @selected(($qrSettings->qr_template_id ?? null) == $t->id)>{{ $t->name }}</option>
                @endforeach
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Save template</button>
    </form>
</div>

<div class="settings-card" style="margin-bottom:24px;">
    <h2 class="section-title">Main menu QR</h2>
    <p class="font-mono text-sm break-all mb-4">{{ $qrUrl }}</p>
    @if($imageUrl)
        <img src="{{ $imageUrl }}" alt="QR code" width="280" height="280" style="max-width:100%">
        <p style="margin-top:12px">
            <a href="{{ route('manager.qr.image', ['format' => 'png', 'size' => 512, 'download' => 1]) }}" class="btn btn-primary">Download PNG</a>
            <a href="{{ $menuUrl }}" target="_blank" class="btn btn-secondary" style="margin-left:8px">Open menu</a>
        </p>
    @else
        <p class="text-muted">Select a QR template above to generate your code.</p>
    @endif
</div>

@if($sections->isNotEmpty())
<div class="settings-card">
    <h2 class="section-title">Section QR codes</h2>
    <ul style="list-style:none;padding:0;margin:0">
        @foreach($sections as $section)
            @php $sectionUrl = url('/qr/'.$restaurant->slug.'/'.$section->slug); @endphp
            <li style="padding:12px 0;border-bottom:1px solid #e5e7eb">
                <strong>{{ $section->name }}</strong><br>
                <span class="font-mono text-xs">{{ $sectionUrl }}</span><br>
                <a href="{{ route('manager.qr.image', ['format' => 'png', 'size' => 256, 'section_slug' => $section->slug, 'download' => 1]) }}" class="text-sm" style="color:#ea580c">Download QR</a>
            </li>
        @endforeach
    </ul>
</div>
@endif
@endsection
