@extends('layouts.manager')

@section('title', 'Customization')

@section('content')
<div class="max-w-2xl">
    <h1 class="text-2xl font-bold mb-6">Menu customization</h1>
    @if(session('success'))<p class="text-green-700 mb-4">{{ session('success') }}</p>@endif
    <form method="post" action="{{ route('manager.customization') }}" class="space-y-4 bg-white p-6 rounded-lg shadow">
        @csrf
        <div>
            <label class="block text-sm font-medium mb-1">Template</label>
            <select name="template_id" class="w-full border rounded px-3 py-2">
                @foreach($templates as $t)
                    <option value="{{ $t->id }}" @selected($restaurant->template_id == $t->id)>{{ $t->name ?? 'Template '.$t->id }}</option>
                @endforeach
            </select>
        </div>
        <div><label class="block text-sm font-medium">Primary color</label><input type="color" name="primary_color" value="{{ $customization['primary_color'] ?? '#f20d0d' }}" class="h-10 w-20"></div>
        <div><label class="block text-sm font-medium">Background</label><input type="color" name="background_color" value="{{ $customization['background_color'] ?? '#ffffff' }}" class="h-10 w-20"></div>
        <button type="submit" class="px-4 py-2 bg-orange-600 text-white rounded font-semibold">Save</button>
    </form>
</div>
@endsection
