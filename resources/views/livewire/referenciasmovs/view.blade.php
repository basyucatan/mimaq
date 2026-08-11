@section('title', __('Referenciasmovs'))
<div class="container-fluid p-0">
    <div class="row g-0 justify-content-center">
        <div class="col-12">
            <div class="cardPrin">
                <div class="cardPrin-header" style="cursor: move;">
                    <span>Movimientos</span>
                    <div class="me-2 position-relative" style="display:inline-block;">
                        <input wire:model.lazy="keyWord" class="inpSolo" 
                        wire:keydown.escape="$set('keyWord','')"
                        onfocus="this.select()" placeholder="Buscar...">
                        @if($keyWord)
                            <span wire:click="$set('keyWord','')" 
                                class="bot botNegro botChico"
                                style="position: absolute; right: 6px; top: 50%; transform: translateY(-50%); cursor: pointer;">
                                X
                            </span>
                        @endif
                    </div>
                </div>
                <div class="cardPrin-body" style="max-height: 60vh">
                    <div class="d-flex justify-content-end mb-2">
                        {{ $referenciasmovs->links() }}
                    </div>
                    @include('livewire.referenciasmovs.modals')
                    <div class="tablaCont">
                        <table class="table tabBase ch">
                            <thead>
                                <tr>
                                    <th>Referencia</th>
                                    <th>Material</th>
                                    <th>Cantidad</th>
                                    <th>Pesog</th>
                                    <th>Diferencias</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($referenciasmovs as $row)
                                    <tr>
                                        <td>{{ $row->Referencia->IdEntradaMex }}</td>
                                        <td><strong>{{ $row->Material->material }}</strong>
                                            {{ $row->Referencia->propsTot }}
                                            @if($row->Referencia->ordenInfo)
                                                <br><small class="text-muted">{{ $row->Referencia->ordenInfo }}</small>
                                            @endif
                                            @if ($row->Referencia->ordenInfo)
                                                <br><small class="text-muted">{{ $row->ordenInfo }}</small>
                                            @endif
                                        </td>
                                        <td>{{ $row->cantidad }}</td>
                                        <td>{{ $row->pesoG }}</td>
                                        <td>{{ $row->difsFormat }}</td>
                                        <td width="60">
                                            <div class="d-flex justify-content-around align-items-center gap-1">
                                                <button wire:click="edit({{ $row->id }})" class="bot botNaranja botChico"
                                                    title="Editar">
                                                    <i class="bi-pencil-square"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
