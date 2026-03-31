@if ($paginator->hasPages() || $paginator->total() > 0)
    <div class="d-flex align-items-center justify-content-between gap-2 flex-wrap">
        <div class="small text-muted flex-shrink-0">
            <b>{{ $paginator->count() }}</b> de <b>{{ $paginator->total() }}</b>
        </div>
        @if ($paginator->hasPages())
        <div class="d-flex align-items-center gap-1 flex-nowrap">
            @if ($paginator->onFirstPage())
            <span class="bot botGris disabled"><i class="bi bi-chevron-left"></i></span>
            @else
            <button wire:click="previousPage" wire:loading.attr="disabled" class="bot botAzul"><i class="bi bi-chevron-left"></i></button>
            @endif
            @foreach ($elements as $element)
                @if (is_string($element))
                <span class="bot botGris disabled px-1">{{ $element }}</span>
                @endif
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == 1 || $page == $paginator->lastPage() || abs($page - $paginator->currentPage()) <= 1)
                            @if ($page == $paginator->currentPage())
                            <span class="bot botVerde shadow-lg">{{ $page }}</span>
                            @else
                            <button wire:click="gotoPage({{ $page }})" class="bot botGris">{{ $page }}</button>
                            @endif
                        @elseif (($page == 2 && $paginator->currentPage() > 3) || ($page == $paginator->lastPage() - 1 && $paginator->currentPage() < $paginator->lastPage() - 2))
                            @if (!isset($dotsPrinted[$element_key ?? 0][$page]))
                            <span class="bot botGris disabled px-1">...</span>
                            @php $dotsPrinted[$element_key ?? 0][$page] = true; @endphp
                            @endif
                        @endif
                    @endforeach
                @endif
            @endforeach
            @if ($paginator->hasMorePages())
            <button wire:click="nextPage" wire:loading.attr="disabled" class="bot botAzul"><i class="bi bi-chevron-right"></i></button>
            @else
            <button class="bot botGris disabled"><i class="bi bi-chevron-right"></i></button>
            @endif
        </div>
        @endif
    </div>
@endif