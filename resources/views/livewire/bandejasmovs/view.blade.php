@section('title', __('Bandejasmovs'))
<div class="container-fluid p-0">
    <div class="row g-0 justify-content-center">
        <div class="col-12">
            <div class="cardPrin-body">
                @include('livewire.bandejasmovs.modals')
                <div class="tablaCont">
                    <table class="table tabBase ch">
                        <thead>
                            <tr>
                                <th>Proceso</th>
                                <th>Empleado</th>
                                <th>Registró</th>
                                <th>Peso Entrada</th>
                                <th>Peso Salida</th>
                                <th>Entrada</th>
                                <th>Salida</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($bandejasmovs as $row)
                                <tr>
                                    <td>{{ $row->Proceso->proceso }}</td>
                                    <td>{{ $row->Empleado->empleado ?? ''}}</td>
                                    <td>{{ $row->Registrador->empleado ?? ''}}</td>
                                    <td>{{ $row->pesoEntrada }}</td>
                                    <td>{{ $row->pesoSalida }}</td>
                                    <td>{{ Util::formatFecha($row->fechaHEntrada,'DDMMM HH:mm') }}</td>
                                    <td>{{ Util::formatFecha($row->fechaHSalida,'DDMMM HH:mm') }}</td>
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
