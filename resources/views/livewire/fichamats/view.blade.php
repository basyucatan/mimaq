<div class="container-fluid p-2" style="height: 100vh; overflow: hidden;">
    <div class="cardPrin d-flex flex-column" style="height: 88vh; overflow: hidden;">
        <div class="cardPrin-header flex-shrink-0 d-flex justify-content-between align-items-center py-2 px-3">
            <span class="fw-bold text-white">Ficha de Materiales</span>
        </div>
        <div class="cardPrin-body flex-grow-1" style="overflow-y: auto;">
            <div class="row g-0 m-0">
                <div class="col-12 col-md-3 border-end h-md-100">
                    <div class="cardSec-body p-0" style="overflow: hidden;">
                        <div style="overflow-y: auto;">
                            <livewire:arbolclasesmats />
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-9 border-end">
                    <div class="cardSec-body">
                        <div style="max-height: 100%; overflow: hidden;">
                            <div class="tab-container d-flex flex-column h-100">
                                <div class="tab-headers flex-shrink-0">
                                    <button wire:click="$set('tabActivo', 'tab1')" class="tab-button {{ $tabActivo === 'tab1' ? 'active' : '' }}">Generales</button>
                                    <button wire:click="$set('tabActivo', 'tab2')" class="tab-button {{ $tabActivo === 'tab2' ? 'active' : '' }}">Costos</button>
                                    <button wire:click="$set('tabActivo', 'tab3')" class="tab-button {{ $tabActivo === 'tab3' ? 'active' : '' }}">Dependencias</button>
                                </div>
                                <div class="tab-content-wrapper flex-grow-1 bg-white p-2">
                                    <div class="tab-content {{ $tabActivo === 'tab1' ? 'active' : 'd-none' }}">
                                        <div class="row g-2 m-0">
                                            {{-- <div class="col-auto d-flex flex-md-column gap-1">
                                                <button wire:click="costosPDF('sin')" class="bot botMenu">🖨️❌</button>
                                                <button wire:click="costosPDF('con')" class="bot botVerde">🖨️✅</button>
                                            </div> --}}
                                            <div class="col">
                                                @livewire('materials', ['selected_id' => $IdMaterial], key('m-'.$IdMaterial))
                                            </div>
                                        </div>
                                    </div>
                                    <div class="tab-content {{ $tabActivo === 'tab2' ? 'active' : 'd-none' }}">
                                        @livewire('materialscostos', ['IdMaterial' => $IdMaterial], key('mc-'.$IdMaterial))
                                    </div>
                                    <div class="tab-content {{ $tabActivo === 'tab3' ? 'active' : 'd-none' }}">
                                        @include('livewire.fichamats.dependencias')
                                    </div>
                                </div>
                            </div>
                        </div>                           
                    </div>
                </div>                
        </div>
    </div>
</div>