@if ($paginator->hasPages())
    @php
        $current = $paginator->currentPage();
        $last = $paginator->lastPage();
        $start = max(1, $current - 2);
        $end = min($last, $current + 2);
        if ($end - $start < 4 && $last > 4) {
            if ($start === 1) {
                $end = min($last, $start + 4);
            } else {
                $start = max(1, $end - 4);
            }
        }
    @endphp
    <nav class="pagination" aria-label="Pagination">
        @if ($current > 1)
            <a class="page-pill" href="{{ $paginator->previousPageUrl() }}" rel="prev">‹</a>
        @else
            <span class="page-pill muted">‹</span>
        @endif

        @for ($p = $start; $p <= $end; $p++)
            @if ($p === $current)
                <span class="page-pill active">{{ $p }}</span>
            @else
                <a class="page-pill" href="{{ $paginator->url($p) }}">{{ $p }}</a>
            @endif
        @endfor

        @if ($current < $last)
            <a class="page-pill" href="{{ $paginator->nextPageUrl() }}" rel="next">›</a>
        @else
            <span class="page-pill muted">›</span>
        @endif
    </nav>
@endif
