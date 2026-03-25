<div class="container-fluid p-2" style="height: 100vh; overflow: hidden;">
    <div class="cardPrin d-flex flex-column" style="height: 88vh; overflow: hidden;">
        <div class="cardPrin-header flex-shrink-0 d-flex justify-content-between align-items-center py-2 px-3">
            <span class="fw-bold text-white">Tablas de herraje</span>
        </div>
        <div class="cardPrin-body flex-grow-1" style="overflow-y: auto;">
            <div class="row g-0 m-0">
                <div class="col-12 col-md-4 border-end h-md-100">
                    <div class="cardSec-body p-0" style="overflow: hidden;">
                        <div style="overflow-y: auto;">
                            @include('livewire.tablaherrajes.tablas')
                        </div>
                        
                        <div style="overflow-y: auto;">
                            <livewire:arbolclasesmats />
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-8 border-end">
                    <div class="cardSec-body">
                        <div style="max-height: 100%; overflow: hidden;">
                            <div class="tab-container">
                                <div class="tab-headers">
                                    <button wire:click="$set('tabActivo', 'tab1')"
                                        class="tab-button {{ $tabActivo === 'tab1' ? 'active' : '' }}"
                                        data-tab="tab1">Tabla</button>
                                    <button wire:click="$set('tabActivo', 'tab2')"
                                        class="tab-button {{ $tabActivo === 'tab2' ? 'active' : '' }}"
                                        data-tab="tab2">Dependencias</button>
                                </div>
                                <div style="height: 100%; min-height: 25vh;">
                                    <div class="tab-content-wrapper">
                                        <div id="tab1" class="tab-content {{ $tabActivo === 'tab1' ? 'active' : '' }}">
                                            @livewire('tablaherrajesdets', ['IdTablaHerraje' => $selected_id], 
                                                key('tablaherrajesdets-' . $selected_id))
                                        </div>
                                        <div id="tab2" class="tab-content {{ $tabActivo === 'tab2' ? 'active' : '' }}">
                                            @include('livewire.tablaherrajes.modelosdep')
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>                           
                    </div>
                </div>                
        </div>
    </div>
</div>