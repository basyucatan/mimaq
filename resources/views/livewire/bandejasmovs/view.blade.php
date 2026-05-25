@section('title', __('Bandejasmovs'))
<div class="container-fluid p-0">
    <div class="row g-0 justify-content-center">
        <div class="col-12">
            <div class="cardPrin">
                <div class="cardPrin-header">
                    <span>Bandejasmovs</span>
                </div>
                <div class="cardPrin-body">
                    @include('livewire.bandejasmovs.modals')
                    <div class="tablaCont">
                        <table class="table tabBase ch">
                            <thead>
                                <tr>
                                    <th>Proceso</th>
                                    <th>Empleado</th>
                                    <th>Pesoentrada</th>
                                    <th>Pesosalida</th>
                                    <th>Fechahentrada</th>
                                    <th>Fechahsalida</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($bandejasmovs as $row)
                                    <tr>

                                        <td>{{ $row->Proceso->proceso }}</td>
                                        <td>{{ $row->Empleado->empleado ?? ''}}</td>
                                        <td>{{ $row->pesoEntrada }}</td>
                                        <td>{{ $row->pesoSalida }}</td>
                                        <td>{{ $row->fechaHEntrada }}</td>
                                        <td>{{ $row->fechaHSalida }}</td>
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
