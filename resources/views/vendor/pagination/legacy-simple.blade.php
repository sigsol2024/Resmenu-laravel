@if ($paginator->hasPages())
    <div class="pagination" role="navigation" aria-label="Pagination">
        @if ($paginator->onFirstPage())
            <span class="disabled">&larr; Prev</span>
        @else
            <a href="{{ $paginator->previousPageUrl() }}" rel="prev">&larr; Prev</a>
        @endif

        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" rel="next">Next &rarr;</a>
        @else
            <span class="disabled">Next &rarr;</span>
        @endif
    </div>
@endif
