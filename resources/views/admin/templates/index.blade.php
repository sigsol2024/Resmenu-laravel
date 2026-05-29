@extends('layouts.admin')

@section('title', 'Template Management')

@section('content')
<div class="page-header">
    <h1 class="page-title">Template Management</h1>
    <p class="page-subtitle">Manage menu page design templates</p>
</div>

<div class="card templates-shell">
    <div class="card-header templates-shell-header">
        <h2 class="card-title">Template Management</h2>
    </div>
    <div class="template-intro">
        Manage template display names, marketing copy, images, and which subscription plans or restaurants can use each design. Folder names stay as template1, template2, etc.; names shown to users come from here.
    </div>

    <div class="template-list">
    @foreach($templates as $t)
        @php
            $isExpanded = $expandedId === (int) $t->id;
            $assignedPlanIds = $planIdsByTemplate[$t->id] ?? [];
            $assignedRestaurantIds = $restaurantIdsByTemplate[$t->id] ?? [];
            $isPrivate = $isPrivateByTemplate[$t->id] ?? false;
            $previewUrl = route('public.template.preview', $t->id);
        @endphp
        <div class="card template-item-card">
            <div class="card-header template-item-header" onclick="toggleTemplate({{ $t->id }})">
                <div class="template-item-header-row">
                    <h2 class="card-title">{{ $t->name ?? 'Template '.$t->id }}</h2>
                    <div class="template-item-actions">
                        <a href="{{ $previewUrl }}" target="_blank" rel="noopener" class="btn btn-secondary" onclick="event.stopPropagation()">Preview</a>
                        <span id="toggle-{{ $t->id }}" class="template-toggle-icon">{!! $isExpanded ? '&#9660;' : '&#9654;' !!}</span>
                    </div>
                </div>
            </div>

            <div id="content-{{ $t->id }}" class="template-item-body" style="{{ $isExpanded ? '' : 'display:none' }}">
                <form method="post" action="{{ route('admin.templates.update', $t->id) }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="card inner-section-card">
                        <div class="card-header" style="cursor: default;"><h3 class="card-title">Display name</h3></div>
                        <div class="inner-section-body">
                            <div class="form-group">
                                <label class="form-label">Display name</label>
                                <input type="text" name="name" class="form-input" value="{{ old('name', $t->name ?? 'Template '.$t->id) }}" required>
                                <p class="form-hint">Shown in admin, manager template lists, and marketing. Code always loads <code>templates/template{{ $t->id }}</code>.</p>
                            </div>
                        </div>
                    </div>

                    <div class="card inner-section-card">
                        <div class="card-header" style="cursor: default;"><h3 class="card-title">Subscription Plans &amp; Private Assignment</h3></div>
                        <div class="inner-section-body">
                            <p class="form-hint section-intro">Assign this template to subscription plans. Only restaurants on a selected plan can see and use it (unless assigned privately below).</p>
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
                                <label class="checkbox-inline">
                                    <input type="checkbox" name="is_private" value="1" @checked(old('is_private', $isPrivate)) onchange="togglePrivateRestaurants({{ $t->id }})" id="is_private_{{ $t->id }}">
                                    Mark this template as private
                                </label>
                                <p class="form-hint">When private, only the restaurants you select below can see this template.</p>
                            </div>
                            <div id="private-restaurants-{{ $t->id }}" style="display: {{ $isPrivate ? 'block' : 'none' }}">
                                <label class="form-label">Assigned restaurants</label>
                                <div class="restaurant-search-wrap">
                                    <input type="text" id="search-restaurant-{{ $t->id }}" class="form-input restaurant-search-input" placeholder="Search by restaurant name..." autocomplete="off">
                                    <div id="search-results-{{ $t->id }}" class="search-results-dropdown" style="display:none;"></div>
                                </div>
                                <div id="selected-restaurants-{{ $t->id }}" class="selected-restaurants"></div>
                                <div id="restaurant_ids_container_{{ $t->id }}"></div>
                            </div>
                        </div>
                    </div>

                    <div class="card inner-section-card">
                        <div class="card-header" style="cursor: default;"><h3 class="card-title">Marketing (Description &amp; Images)</h3></div>
                        <div class="inner-section-body">
                            <p class="form-hint section-intro">Description is shown on resmenu.net. Cover image is used on the template preview page. Listing image is used on the resmenu.net templates page.</p>
                            <div class="form-group">
                                <label class="form-label">Description</label>
                                <textarea name="description" class="form-input" rows="4" placeholder="e.g. Elegant and sophisticated fine dining style...">{{ old('description', $t->description ?? '') }}</textarea>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Cover image (template preview page)</label>
                                @if(!empty($t->preview_image))
                                    <p class="image-preview-wrap"><img src="{{ asset('uploads/template-previews/'.$t->preview_image) }}" alt="Current cover" class="template-preview-thumb"></p>
                                @endif
                                <input type="file" name="preview_image" class="form-input" accept="image/jpeg,image/png,image/gif,image/webp">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Listing image (resmenu.net templates page)</label>
                                @if(!empty($t->listing_image))
                                    <p class="image-preview-wrap"><img src="{{ asset('uploads/template-previews/'.$t->listing_image) }}" alt="Current listing" class="template-preview-thumb"></p>
                                @endif
                                <input type="file" name="listing_image" class="form-input" accept="image/jpeg,image/png,image/gif,image/webp">
                            </div>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        Save Template
                    </button>
                </form>

                <form method="post" action="{{ route('admin.templates.toggle', $t->id) }}" class="template-toggle-form">
                    @csrf
                    <button type="submit" class="btn btn-secondary">{{ ($t->is_active ?? 1) ? 'Deactivate' : 'Activate' }}</button>
                </form>
            </div>
        </div>
    @endforeach
    </div>
</div>
@endsection

@push('head')
<link rel="stylesheet" href="{{ asset('legacy/css/pages/admin-templates.css') }}">
@endpush

@push('scripts')
<script>
const initialSelectedRestaurants = @json($restaurantNamesByTemplate ?? []);
const restaurantSearchUrl = @json(route('admin.restaurants.search'));

function toggleTemplate(id) {
    var content = document.getElementById('content-' + id);
    var toggle = document.getElementById('toggle-' + id);
    var open = content.style.display === 'none';
    content.style.display = open ? 'block' : 'none';
    if (toggle) toggle.innerHTML = open ? '&#9660;' : '&#9654;';
}
function togglePrivateRestaurants(id) {
    var cb = document.getElementById('is_private_' + id);
    var el = document.getElementById('private-restaurants-' + id);
    if (el && cb) el.style.display = cb.checked ? 'block' : 'none';
}
function escapeHtml(s) {
    var div = document.createElement('div');
    div.textContent = s;
    return div.innerHTML;
}
function addRestaurant(templateId, id, name) {
    var container = document.getElementById('selected-restaurants-' + templateId);
    var hiddenContainer = document.getElementById('restaurant_ids_container_' + templateId);
    if (container.querySelector('[data-restaurant-id="' + id + '"]')) return;
    var chip = document.createElement('span');
    chip.className = 'restaurant-chip';
    chip.setAttribute('data-restaurant-id', id);
    chip.innerHTML = escapeHtml(name) + ' <button type="button" aria-label="Remove">&times;</button>';
    chip.querySelector('button').addEventListener('click', function () {
        chip.remove();
        hiddenContainer.querySelector('input[value="' + id + '"]')?.remove();
    });
    var hid = document.createElement('input');
    hid.type = 'hidden';
    hid.name = 'restaurant_ids[]';
    hid.value = id;
    container.appendChild(chip);
    hiddenContainer.appendChild(hid);
}
function initTemplateRestaurantSearch(templateId) {
    var input = document.getElementById('search-restaurant-' + templateId);
    var resultsEl = document.getElementById('search-results-' + templateId);
    if (!input || !resultsEl) return;
    var debounceTimer = null;
    var initial = initialSelectedRestaurants[templateId] || {};
    Object.keys(initial).forEach(function (id) { addRestaurant(templateId, id, initial[id]); });
    input.addEventListener('input', function () {
        clearTimeout(debounceTimer);
        var q = (this.value || '').trim();
        resultsEl.style.display = 'none';
        resultsEl.innerHTML = '';
        if (q.length < 1) return;
        debounceTimer = setTimeout(function () {
            fetch(restaurantSearchUrl + '?q=' + encodeURIComponent(q))
                .then(function (r) { return r.json(); })
                .then(function (payload) {
                    var list = payload.results || payload || [];
                    resultsEl.innerHTML = '';
                    if (!list.length) {
                        resultsEl.innerHTML = '<div class="search-result-item">No restaurants found</div>';
                    } else {
                        list.forEach(function (r) {
                            var item = document.createElement('div');
                            item.className = 'search-result-item';
                            item.textContent = r.name;
                            item.addEventListener('click', function () {
                                addRestaurant(templateId, String(r.id), r.name);
                                input.value = '';
                                resultsEl.style.display = 'none';
                            });
                            resultsEl.appendChild(item);
                        });
                    }
                    resultsEl.style.display = 'block';
                });
        }, 250);
    });
    document.addEventListener('click', function (e) {
        if (!input.contains(e.target) && !resultsEl.contains(e.target)) {
            resultsEl.style.display = 'none';
        }
    });
}
document.addEventListener('DOMContentLoaded', function () {
    @foreach($templates as $t)
    initTemplateRestaurantSearch({{ $t->id }});
    @endforeach
});
</script>
@endpush
