@extends('layouts.admin')

@section('title', 'Restaurants')

@section('content')

@include('partials.admin.page-header', ['title' => 'Restaurant Management', 'subtitle' => 'Create and manage restaurants on the platform'])



<div class="admin-toolbar">

  <form method="get" style="display:flex;gap:8px;flex-wrap:wrap;align-items:center">

    <input type="text" name="q" value="{{ $q }}" placeholder="Search name or slug">

    <button type="submit" class="btn-filter">Search</button>

  </form>

</div>



@include('partials.admin.restaurant-modals', [

  'editRestaurant' => $editRestaurant,

  'editManager' => $editManager,

  'plans' => $plans,

  'showCreateModal' => $showCreateModal ?? false,

])



<div class="admin-list-card">

  <div class="card-header">

    <h2 class="card-title">All Restaurants</h2>

    <button type="button" class="btn-primary" onclick="openRestaurantModal()">New Restaurant</button>

  </div>

  <div class="table-card">

    <table class="data-table">

      <thead>

        <tr>

          <th>ID</th>

          <th>Name</th>

          <th>Slug</th>

          <th>Status</th>

          <th>Actions</th>

        </tr>

      </thead>

      <tbody>

      @forelse($restaurants as $r)

        <tr>

          <td>{{ $r->id }}</td>

          <td>{{ $r->name }}</td>

          <td><code class="slug-code">{{ $r->slug }}</code></td>

          <td>

            <span class="status-badge {{ $r->is_active ? 'active' : 'inactive' }}">

              {{ $r->is_active ? 'Active' : 'Inactive' }}

            </span>

          </td>

          <td>

            @include('partials.admin.actions-dropdown', [

              'items' => [

                ['label' => 'Manage', 'url' => route('admin.restaurants.hub', $r)],

                ['type' => 'button', 'label' => 'Edit', 'onclick' => 'window.location.href='.e(json_encode(route('admin.restaurants.index', ['edit' => $r->id]))).''],

                ['label' => 'View Menu', 'url' => route('public.menu', $r->slug), 'target' => '_blank', 'rel' => 'noopener'],

                ['type' => 'divider'],

                ['type' => 'button', 'label' => 'Delete', 'class' => 'danger', 'onclick' => 'openDeleteModal('.e(json_encode(['id' => $r->id, 'name' => $r->name])).')'],

              ],

            ])

          </td>

        </tr>

      @empty

        <tr><td colspan="5" style="text-align:center;padding:40px;color:#6b7280">No restaurants found.</td></tr>

      @endforelse

      </tbody>

    </table>

  </div>

  @if($restaurants->hasPages())

    <div style="padding:16px 24px">{{ $restaurants->links() }}</div>

  @endif

</div>

@endsection

@push('head')

<link rel="stylesheet" href="{{ asset('assets/css/admin-payments.css') }}">

@endpush

@push('scripts')
<script>
function openRestaurantModal() {
  window.location.href = @json(route('admin.restaurants.index', ['new' => 1]));
}
function openDeleteModal(data) {
  document.getElementById('deleteModal').style.display = 'flex';
  document.getElementById('delete-restaurant-name').textContent = data.name;
  document.getElementById('deleteForm').action = @json(url('/admin/restaurants')).replace(/\/$/, '') + '/' + data.id;
}
function closeRestaurantModal() {
  window.location.href = @json(route('admin.restaurants.index'));
}
function closeDeleteModal() { document.getElementById('deleteModal').style.display = 'none'; }
</script>
@endpush

