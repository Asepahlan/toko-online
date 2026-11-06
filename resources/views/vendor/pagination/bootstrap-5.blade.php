@if ($paginator->hasPages())
    <nav class="d-flex justify-content-end">
        <ul class="pagination mb-0" style="transform: scale(0.7); transform-origin: right;">
            {{-- Previous --}}
            @if ($paginator->onFirstPage())
                <li class="page-item disabled">
                    <span class="page-link">‹</span>
                </li>
            @else
                <li class="page-item">
                    <a class="page-link" href="{{ $paginator->previousPageUrl() }}">‹</a>
                </li>
            @endif

            {{-- Numbered pages --}}
            @foreach ($elements as $element)
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        <li class="page-item {{ $page == $paginator->currentPage() ? 'active' : '' }}">
                            <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                        </li>
                    @endforeach
                @endif
            @endforeach

            {{-- Next --}}
            @if ($paginator->hasMorePages())
                <li class="page-item">
                    <a class="page-link" href="{{ $paginator->nextPageUrl() }}">›</a>
                </li>
            @else
                <li class="page-item disabled">
                    <span class="page-link">›</span>
                </li>
            @endif
        </ul>
    </nav>

    <style>
    .pagination {
        margin: 0;
    }
    .page-link {
        padding: 3px 8px;
        border: 1px solid #dee2e6;
        color: #666;
        background: #fff;
        font-size: 14px;
    }
    .page-item.active .page-link {
        background: #0d6efd;
        border-color: #0d6efd;
    }
    .page-item.disabled .page-link {
        color: #ccc;
    }
    </style>
@endif
