<div class="d-flex flex-column h-100">
    <div class="tab-headers flex-shrink-0 bg-light border-bottom">
        <button wire:click="$set('subTabActivo', 'sub1')" 
            class="tab-button2 small {{ $subTabActivo === 'sub1' ? 'active' : '' }}">Modelos</button>
        <button wire:click="$set('subTabActivo', 'sub2')" 
            class="tab-button2 small {{ $subTabActivo === 'sub2' ? 'active' : '' }}">Pre-modelos</button>
        <button wire:click="$set('subTabActivo', 'sub3')" 
            class="tab-button2 small {{ $subTabActivo === 'sub3' ? 'active' : '' }}">Reglas</button>
    </div>
    <div class="tab-content-wrapper flex-grow-1 p-0">
        <div class="h-100 {{ $subTabActivo === 'sub1' ? '' : 'd-none' }}">
            @include('livewire.fichamats.modelos')
        </div>
        <div class="h-100 {{ $subTabActivo === 'sub2' ? '' : 'd-none' }}">
            @include('livewire.fichamats.modelospre')
        </div>
        <div class="h-100 {{ $subTabActivo === 'sub3' ? '' : 'd-none' }}">
            @include('livewire.fichamats.reglas')
        </div>
    </div>
</div>