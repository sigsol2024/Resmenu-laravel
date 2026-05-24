@extends('layouts.manager')

@section('title', 'Profile')

@section('content')
<h1 class="text-2xl font-bold mb-6">Profile</h1>
@if(session('success'))<p class="text-green-700 mb-4">{{ session('success') }}</p>@endif
<form method="post" action="{{ route('manager.profile.update') }}" class="max-w-md space-y-4 bg-white p-6 rounded-lg shadow">
    @csrf @method('PUT')
    <div><label class="block text-sm font-medium">Username</label><input name="username" value="{{ old('username', $manager->username) }}" class="w-full border rounded px-3 py-2"></div>
    <div><label class="block text-sm font-medium">Email</label><input type="email" name="email" value="{{ old('email', $manager->email) }}" class="w-full border rounded px-3 py-2"></div>
    <div><label class="block text-sm font-medium">New password (optional)</label><input type="password" name="password" class="w-full border rounded px-3 py-2"></div>
    <button type="submit" class="px-4 py-2 bg-orange-600 text-white rounded font-semibold">Save</button>
</form>
@endsection
