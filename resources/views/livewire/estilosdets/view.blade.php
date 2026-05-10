@section('title', __('Estilosdets'))
<div class="container-fluid p-0">
    <div class="row g-0 justify-content-center">
        <div class="col-12">
            <div class="cardSec">
                <div class="cardSec-header">
                    <span>Style Materials</span>
                    <span class="badge bg-success fs-5">{{$estilo->estilo}}-{{$estilo->id}}</span>
                    <div>
                        <input wire:model.live="keyWord" type="text" class="inpSolo" onfocus="this.select()" placeholder="Search">
                    </div>
                    <div>
                        <button class="bot botVerde" wire:click="create" title="New Estilosdet">
                            <i class="bi bi-file-earmark-plus"></i>
                        </button>
                    </div>
                </div>
                <div class="cardSec-body">
                    @include('livewire.estilosdets.modals')
                    <div class="tablaCont">
                        <table class="table tabBase ch">
                            <thead>
                                <tr>
                                    <th>Quantity</th>
                                    <th>Material</th>
                                    <th>Size</th>
                                    <th>Shape</th>
                                    <th>Assembly Style</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($estilosdets as $row)
                                    <tr>
                                        <td>{{ $row->cantidad }}</td>
                                        <td>{{ $row->Material->material }}</td>
                                        <td>{{ $row->Size->size ?? '' }}</td>
                                        <td>{{ $row->Forma->forma ?? '' }}</td>
                                        <td>{{ $row->estiloY }}</td>
                                        <td width="60">
                                            <div class="d-flex justify-content-around align-items-center gap-1">
                                                <button wire:click="edit({{ $row->id }})" class="bot botNaranja" title="Edit">
                                                    <i class="bi-pencil-square"></i>
                                                </button>
                                                <button wire:click="destroy({{ $row->id }})" class="bot botRojo" onclick="confirm('Are you sure you want to delete this record?') || event.stopImmediatePropagation()">
                                                    <i class="bi-trash3-fill"></i>
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