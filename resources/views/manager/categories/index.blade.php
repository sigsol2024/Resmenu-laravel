@extends('layouts.manager')
@section('title', 'Categories')
@push('head')
<link rel="stylesheet" href="{{ asset('legacy/css/pages/manager-categories.css') }}">
@endpush
@section('content')
<div class="page-header">
    <h1 class="page-title">Category Management</h1>
    <p class="page-subtitle">Create and manage menu categories for your restaurant</p>
</div>
<div class="card">
    <div class="card-header" style="display:flex;justify-content:space-between;align-items:center;">
        <h2 class="card-title">All categories</h2>
        <a href="{{ route('manager.categories.create') }}" class="btn btn-primary">Add category</a>
    </div>
    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Section</th>
                    <th>Items</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
            @forelse($categories as $cat)
                <tr>
                    <td>{{ $cat->name }}</td>
                    <td>{{ $cat->section->name ?? '—' }}</td>
                    <td>{{ $cat->menu_items_count }}</td>
                    <td>
                        @include('partials.admin.actions-dropdown', [
                          'items' => [
                            ['label' => 'Edit', 'url' => route('manager.categories.edit', $cat)],
                            [
                              'type' => 'form',
                              'label' => 'Delete',
                              'action' => route('manager.categories.destroy', $cat),
                              'method' => 'DELETE',
                              'class' => 'danger',
                              'confirm' => 'Delete this category?',
                            ],
                          ],
                        ])
                    </td>
                </tr>
            @empty
                <tr><td colspan="4">No categories yet.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
