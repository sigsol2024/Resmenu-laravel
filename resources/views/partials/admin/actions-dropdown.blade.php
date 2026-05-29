{{-- Three-dot actions menu. Items: link|form|divider. Pass onclick stopPropagation via wrapper class. --}}
@php
    $items = $items ?? [];
    $stopPropagation = $stopPropagation ?? true;
@endphp

<div class="actions-cell" @if($stopPropagation) onclick="event.stopPropagation()" @endif>
    <button class="actions-btn" type="button" title="Actions" aria-label="Actions">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" width="20" height="20" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z" />
        </svg>
    </button>
    <div class="actions-dropdown">
        @foreach($items as $item)
            @if(($item['type'] ?? 'link') === 'title')
                <div class="actions-dropdown-title">{{ $item['label'] }}</div>
            @elseif(($item['type'] ?? 'link') === 'divider')
                <div class="actions-dropdown-divider"></div>
            @elseif(($item['type'] ?? 'link') === 'form')
                <form method="post" action="{{ $item['action'] }}" style="display:contents;" @if(!empty($item['confirm'])) onsubmit="return confirm(@js($item['confirm']))" @endif>
                    @csrf
                    @if(!empty($item['method'])) @method($item['method']) @endif
                    @foreach($item['hidden'] ?? [] as $name => $value)
                        <input type="hidden" name="{{ $name }}" value="{{ $value }}">
                    @endforeach
                    <button type="submit" class="actions-dropdown-item {{ $item['class'] ?? '' }}">{{ $item['label'] }}</button>
                </form>
            @elseif(($item['type'] ?? 'link') === 'button')
                <button type="button" class="actions-dropdown-item {{ $item['class'] ?? '' }}" @if(!empty($item['onclick'])) onclick="{{ $item['onclick'] }}" @endif>{{ $item['label'] }}</button>
            @else
                <a href="{{ $item['url'] ?? '#' }}"
                   class="actions-dropdown-item {{ $item['class'] ?? '' }}"
                   @if(!empty($item['target'])) target="{{ $item['target'] }}" @endif
                   @if(!empty($item['rel'])) rel="{{ $item['rel'] }}" @endif
                >{{ $item['label'] }}</a>
            @endif
        @endforeach
    </div>
</div>
