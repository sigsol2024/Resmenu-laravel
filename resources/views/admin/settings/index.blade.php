@extends('layouts.admin')
@section('title', 'Settings')
@section('content')
<div class="card">
    <h1>Site settings</h1>
    @if(session('success'))<p>{{ session('success') }}</p>@endif
    <form method="post" action="{{ route('admin.settings.index') }}" class="mt-4 space-y-3">
        @csrf
        <div><label>Site name</label><input name="site_name" value="{{ $settings['site_name'] ?? 'Resmenu' }}" class="w-full border p-2"></div>
        <div><label>Support email</label><input name="support_email" value="{{ $settings['support_email'] ?? '' }}" class="w-full border p-2"></div>
        <div><label>Test email</label><input name="test_email" class="w-full border p-2"><label><input type="checkbox" name="send_test_email" value="1"> Send test</label></div>
        <button type="submit" class="px-4 py-2 bg-slate-900 text-white rounded">Save</button>
    </form>
</div>
@endsection
