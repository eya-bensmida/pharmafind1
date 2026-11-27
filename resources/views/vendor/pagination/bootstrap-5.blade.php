
@if ($paginator->hasPages())
    <nav aria-label="Pagination des pharmacies">
        <ul class="pagination justify-content-center align-items-center gap-1 mb-0">

            {{-- Précédent --}}
            @if ($paginator->onFirstPage())
                <li class="page-item disabled">
                    <span class="page-link pagination-arrow">
                        <i class="bi bi-chevron-left"></i>
                    </span>
                </li>
            @else
                <li class="page-item">
                    <a class="page-link pagination-arrow"
                       href="{{ $paginator->previousPageUrl() }}"
                       aria-label="Page précédente">
                        <i class="bi bi-chevron-left"></i>
                    </a>
                </li>
            @endif

            {{-- Numéros de pages --}}
            @foreach ($elements as $element)
                @if (is_string($element))
                    <li class="page-item disabled">
                        <span class="page-link">{{ $element }}</span>
                    </li>
                @endif

                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <li class="page-item active">
                                <span class="page-link">{{ $page }}</span>
                            </li>
                        @else
                            <li class="page-item">
                                <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                            </li>
                        @endif
                    @endforeach
                @endif
            @endforeach

            {{-- Suivant --}}
            @if ($paginator->hasMorePages())
                <li class="page-item">
                    <a class="page-link pagination-arrow"
                       href="{{ $paginator->nextPageUrl() }}"
                       aria-label="Page suivante">
                        <i class="bi bi-chevron-right"></i>
                    </a>
                </li>
            @else
                <li class="page-item disabled">
                    <span class="page-link pagination-arrow">
                        <i class="bi bi-chevron-right"></i>
                    </span>
                </li>
            @endif

        </ul>
    </nav>

    <style>
        .pagination .page-link {
            min-width: 38px;
            height: 38px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 14px;
            color: #123B4A;
            border: 1px solid #dee5e9;
            border-radius: 7px !important;
            box-shadow: none;
        }

        .pagination .pagination-arrow {
            width: 38px;
            height: 38px;
            padding: 0;
            font-size: 13px !important;
        }

        .pagination .pagination-arrow i {
            font-size: 13px !important;
            line-height: 1;
        }

        .pagination .page-item.active .page-link {
            background: #16A6A0;
            border-color: #16A6A0;
            color: white;
        }

        .pagination .page-link:hover {
            background: #eaf7f6;
            color: #123B4A;
        }

        .pagination .page-item.disabled .page-link {
            background: #f5f8fa;
            color: #aab5bb;
        }
    </style>
@endif
