@php
    $meta = $meta ?? [];
    $subscription = $subscription ?? [];
    $isCategory = $isCategory ?? false;
    $limit = $isCategory
        ? ($subscription['max_categories'] ?? 0)
        : ($subscription['max_menu_items'] ?? 0);
    $limitLabel = $limit === -1 ? 'unlimited' : (string) $limit;
    $reason = $meta['hidden_reason'] ?? null;
@endphp
@if($meta['is_plan_hidden'] ?? false)
    <div class="plan-hidden-hint">
        @if($reason === 'category_limit')
            Hidden on public menu — your plan allows {{ $limitLabel }} categories. Upgrade to display this category.
        @elseif($reason === 'menu_item_limit')
            Hidden on public menu — your plan allows {{ $limitLabel }} menu items. Upgrade to display this item.
        @else
            Hidden on public menu — upgrade your plan to display on the public menu.
        @endif
    </div>
@endif
