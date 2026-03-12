@section('title', __('Precios'))
<div class="container-fluid p-2" style="height: 100vh; overflow: hidden;">
    <div class="cardPrin d-flex flex-column" style="height: 85vh; max-height: 88vh; overflow: hidden;">
        
        <div class="cardPrin-header flex-shrink-0">
            <div>Ficha de Material</div>
        </div>

        <div class="alert alert-primary p-1 m-1 flex-shrink-0 small">
            <div class="col-md-10">
                {{ $material->linea->linea ?? '' }}
                {{ $material?->referencia ? ', Cod-'.$material->referencia : '' }}
                {{ $material?->material ? ', '.$material->material : '' }}
            </div>
        </div>

        <div class="cardPrin-body flex-grow-1" style="overflow-y: auto; overflow-x: hidden;">
            <div class="row g-2 m-0 h-md-100"> <div class="col-12 col-md-3 h-md-100">
                    <livewire:arbolclasesmats />
                </div>

                <div class="col-12 col-md-9 d-flex flex-column h-md-100">
                    <div class="tab-container d-flex flex-column h-100">
                        <div class="tab-headers flex-shrink-0">
                            <button wire:click="$set('tabActivo', 'tab1')"
                                class="tab-button {{ $tabActivo === 'tab1' ? 'active' : '' }}">Generales</button>             
                            <button wire:click="$set('tabActivo', 'tab2')"
                                class="tab-button {{ $tabActivo === 'tab2' ? 'active' : '' }}">Costos</button>
                            <button wire:click="$set('tabActivo', 'tab3')"
                                class="tab-button {{ $tabActivo === 'tab3' ? 'active' : '' }}">Dependencias</button>
                        </div>
                        <div class="tab-content-wrapper flex-grow-1 bg-white p-2" style="min-height: 0;"> 
                            <div class="col-10 tab-content h-100 overflow-auto {{ $tabActivo === 'tab1' ? 'active' : 'd-none' }}">
                                <div class="row">
                                    <div class="col-2">
                                        <button wire:click="costosPDF('sin')" class="bot botMenu">🖨️❌💲</button>
                                        <button wire:click="costosPDF('con')" class="bot botVerde">🖨️✅💲</button>
                                    </div>
                                    <div class="col-10">
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