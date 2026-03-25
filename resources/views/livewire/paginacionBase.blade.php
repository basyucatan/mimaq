@if ($paginator->hasPages() || $paginator->total() > 0)
    <div class="d-flex align-items-center justify-content-between gap-3 flex-wrap">
        <div class="small text-muted">
            <b>{{ $paginator->count() }}</b> de <b>{{ $paginator->total() }}</b>
        </div>
        @if ($paginator->hasPages())
            <div class="d-flex align-items-center gap-1">
                @if ($paginator->onFirstPage())
                    <span class="bot botGris disabled"><i class="bi bi-chevron-left"></i></span>
                @else
                    <button wire:click="previousPage" wire:loading.attr="disabled" class="bot botAzul">
                        <i class="bi bi-chevron-left"></i>
                    </button>
                @endif
                @foreach ($elements as $element)
                    @if (is_string($element))
                        <span class="bot botGris disabled">{{ $element }}</span>
                    @endif
                    @if (is_array($element))
                        @foreach ($element as $page => $url)
                            @if ($page == $paginator->currentPage())
                                <span class="bot botVerde shadow-lg">{{ $page }}</span>
                            @else
                                <button wire:click="gotoPage({{ $page }})" class="bot botGris">{{ $page }}</button>
                            @endif
                        @endforeach
                    @endif
                @endforeach
                @if ($paginator->hasMorePages())
                    <button wire:click="nextPage" wire:loading.attr="disabled" class="bot botAzul">
                        <i class="bi bi-chevron-right"></i>
                    </button>
                @else
                    <button class="bot botGris disabled"><i class="bi bi-chevron-right"></i></button>
                @endif
            </div>
        @endif
    </div>
@endif