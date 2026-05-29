@extends('layouts.manager')
@section('title', 'Sections')
@section('content')
<div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:20px;">
    <h1 style="margin:0;">Sections</h1>
    <a href="{{ route('manager.sections.create') }}" class="btn btn-primary">Add section</a>
</div>
<div class="card">
<table>
    <thead><tr><th>Name</th><th>Slug</th><th>Categories</th><th>Order</th><th>Actions</th></tr></thead>
    <tbody>
    @forelse($sections as $section)
        <tr>
            <td>{{ $section->name }}</td>
            <td>{{ $section->slug }}</td>
            <td>{{ $section->categories_count }}</td>
            <td>{{ $section->display_order }}</td>
            <td>
                @include('partials.admin.actions-dropdown', [
                  'items' => [
                    ['label' => 'Edit', 'url' => route('manager.sections.edit', $section)],
                    [
                      'type' => 'form',
                      'label' => 'Delete',
                      'action' => route('manager.sections.destroy', $section),
                      'method' => 'DELETE',
                      'class' => 'danger',
                      'confirm' => 'Delete this section?',
                    ],
                  ],
                ])
            </td>
        </tr>
    @empty
        <tr><td colspan="5">No sections yet.</td></tr>
    @endforelse
    </tbody>
</table>
</div>
@endsection
