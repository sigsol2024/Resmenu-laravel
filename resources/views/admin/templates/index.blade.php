@extends('layouts.admin')
@section('title', 'Templates')
@section('content')
@include('partials.admin.page-header', ['title' => 'Template Management', 'subtitle' => 'Manage menu page design templates'])

<div class="admin-list-card">
  <div class="card-header">
    <h2 class="card-title">Template Management</h2>
  </div>
  <p class="template-intro">Manage template display names, marketing copy, images, and which subscription plans or restaurants can use each design. Folder names stay as template1, template2, etc.; names shown to users come from here.</p>

  @foreach($templates as $t)
    @php
      $isExpanded = $expandedId === (int) $t->id;
      $assignedPlanIds = $planIdsByTemplate[$t->id] ?? [];
      $assignedRestaurantIds = $restaurantIdsByTemplate[$t->id] ?? [];
      $isPrivate = $isPrivateByTemplate[$t->id] ?? false;
      $previewUrl = route('public.template.preview', $t->id);
    @endphp
    <div class="template-accordion-card {{ $isExpanded ? 'is-open' : '' }}" id="template-card-{{ $t->id }}">
      <div class="template-accordion-header" onclick="toggleTemplateAccordion({{ $t->id }})">
        <h2 class="template-accordion-title">{{ $t->name ?? 'Template '.$t->id }}</h2>
        <div class="template-accordion-actions">
          <a href="{{ $previewUrl }}" target="_blank" rel="noopener" class="btn-clear" onclick="event.stopPropagation()">Preview</a>
          <span id="toggle-icon-{{ $t->id }}">{{ $isExpanded ? 'â–¼' : 'â–¶' }}</span>
        </div>
      </div>
      <div class="template-accordion-body" id="template-body-{{ $t->id }}" style="{{ $isExpanded ? '' : 'display:none' }}">
        <form method="post" action="{{ route('admin.templates.update', $t->id) }}" enctype="multipart/form-data">
          @csrf @method('PUT')

          <div class="template-section-card">
            <div class="template-section-header"><h3>Display name</h3></div>
            <div class="template-section-body">
              <div class="form-group">
                <label class="form-label">Display name</label>
                <input type="text" name="name" class="form-input" value="{{ old('name', $t->name ?? 'Template '.$t->id) }}" required>
                <p class="form-hint">Shown in admin, manager template lists, and marketing. Code always loads <code>templates/template{{ $t->id }}</code>.</p>
              </div>
            </div>
          </div>

          <div class="template-section-card">
            <div class="template-section-header"><h3>Subscription Plans &amp; Private Assignment</h3></div>
            <div class="template-section-body">
              <p class="form-hint" style="margin-bottom:16px">Assign this template to subscription plans. Only restaurants on a selected plan can see and use it (unless assigned privately below).</p>
              <div class="form-group">
                <span class="form-label">Subscription plans</span>
                <div class="template-plans-grid">
                  @forelse($plans as $plan)
                    <label><input type="checkbox" name="plan_ids[]" value="{{ $plan->id }}" @checked(in_array($plan->id, old('plan_ids', $assignedPlanIds)))> {{ $plan->name }}</label>
                  @empty
                    <span class="form-hint">No plans defined. Add plans in Subscription Plans.</span>
                  @endforelse
                </div>
              </div>
              <div class="form-group">
                <label style="display:flex;align-items:center;gap:8px;cursor:pointer">
                  <input type="checkbox" name="is_private" value="1" @checked(old('is_private', $isPrivate)) onchange="togglePrivateRestaurants({{ $t->id }})" id="is_private_{{ $t->id }}">
                  Mark this template as private
                </label>
                <p class="form-hint">When private, only the restaurants you select below can see this template.</p>
              </div>
              <div id="private-restaurants-{{ $t->id }}" style="display:{{ $isPrivate ? 'block' : 'none' }}">
                <label class="form-label">Assigned restaurants</label>
                <select name="restaurant_ids[]" multiple size="8" class="form-select">
                  @foreach($restaurants as $r)
                    <option value="{{ $r->id }}" @selected(in_array($r->id, old('restaurant_ids', $assignedRestaurantIds)))>{{ $r->name }}</option>
                  @endforeach
                </select>
              </div>
            </div>
          </div>

          <div class="template-section-card">
            <div class="template-section-header"><h3>Marketing (Description &amp; Images)</h3></div>
            <div class="template-section-body">
              <div class="form-group">
                <label class="form-label">Description</label>
                <textarea name="description" class="form-textarea" rows="4" placeholder="e.g. Elegant and sophisticated fine dining style...">{{ old('description', $t->description ?? '') }}</textarea>
              </div>
              <div class="form-group">
                <label class="form-label">Cover image (template preview page)</label>
                @if(!empty($t->preview_image))
                  <p style="margin-bottom:8px"><img src="{{ asset('uploads/template-previews/'.$t->preview_image) }}" alt="Current cover" style="max-width:200px;max-height:120px;object-fit:contain;border:1px solid #e5e7eb;border-radius:6px"></p>
                @endif
                <input type="file" name="preview_image" class="form-input" accept="image/jpeg,image/png,image/gif,image/webp">
              </div>
              <div class="form-group">
                <label class="form-label">Listing image (resmenu.net templates page)</label>
                @if(!empty($t->listing_image))
                  <p style="margin-bottom:8px"><img src="{{ asset('uploads/template-previews/'.$t->listing_image) }}" alt="Current listing" style="max-width:200px;max-height:120px;object-fit:contain;border:1px solid #e5e7eb;border-radius:6px"></p>
                @endif
                <input type="file" name="listing_image" class="form-input" accept="image/jpeg,image/png,image/gif,image/webp">
              </div>
            </div>
          </div>

          <div style="display:flex;gap:8px;flex-wrap:wrap;align-items:center">
            <button type="submit" class="btn-filter">Save template</button>
          </div>
        </form>
        <form method="post" action="{{ route('admin.templates.toggle', $t->id) }}" style="margin-top:12px">@csrf
          <button type="submit" class="btn-clear">{{ ($t->is_active ?? 1) ? 'Deactivate' : 'Activate' }}</button>
        </form>
      </div>
    </div>
  @endforeach
</div>
@endsection
@push('head')
<link rel="stylesheet" href="{{ asset('legacy/css/pages/admin-payments.css') }}">
<link rel="stylesheet" href="{{ asset('legacy/css/pages/admin-templates.css') }}">
@endpush
@push('scripts')
<script>
function toggleTemplateAccordion(id) {
  const card = document.getElementById('template-card-' + id);
  const body = document.getElementById('template-body-' + id);
  const icon = document.getElementById('toggle-icon-' + id);
  const open = !card.classList.contains('is-open');
  card.classList.toggle('is-open', open);
  body.style.display = open ? 'block' : 'none';
  if (icon) icon.textContent = open ? 'â–¼' : 'â–¶';
}
function togglePrivateRestaurants(id) {
  const cb = document.getElementById('is_private_' + id);
  const el = document.getElementById('private-restaurants-' + id);
  if (el && cb) el.style.display = cb.checked ? 'block' : 'none';
}
</script>
@endpush
