<div class="cardSec">
    <div class="cardSec-body" style="padding: 10px; max-height: 500px; overflow-y: auto;">
        <div class="table-responsive">
            <table class="table tabBase ch">
                <thead>
                    <tr>
                        <th style="width:20%">Material</th>
                        <th style="width:7%">Qty</th>
                        <th style="width:7%">UnitPr</th>
                        <th style="width:10%">Weight</th>
                        <th style="width:12%">Style</th>
                        <th style="width:5%">Kt</th>
                        <th style="width:5%">Color</th>
                        <th style="width:10%">Size</th>
                        <th style="width:8%">Shape</th>
                        <th style="width:7%">Assembly</th>
                        <th style="width:7%">Origin</th>
                        <th style="width:2%"></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($precaptura as $index => $linea)
                        <tr wire:key="fila-precaptura-{{ $index }}">
                            <td>
                                <select wire:change="cambiarMaterial({{ $index }}, $event.target.value)" class="inpSolo inpChico">
                                    <option value=""></option>
                                    @foreach ($materials as $key => $value)
                                        <option value="{{ $key }}" {{ $linea['IdMaterial'] == $key ? 'selected' : '' }}>{{ $value }}</option>
                                    @endforeach
                                </select>
                            </td>
                            <td>
                                <input type="text" wire:model="precaptura.{{ $index }}.cantidad" class="inpSolo inpChico">
                            </td>
                            <td>
                                <input type="text" wire:model="precaptura.{{ $index }}.precioU" class="inpSolo inpChico">
                            </td>
                            <td>
                                <div class="input-group input-group-sm flex-nowrap" style="max-width: 120px;">
                                    <input type="text" wire:model="precaptura.{{ $index }}.pesoEnUMat" class="form-control inpSolo inpChico text-end pe-1">
                                    <span class="input-group-text p-1 text-secondary bg-light border-start-0" style="font-size: 0.75rem; min-width: 42px; text-align: center;">
                                        {{ $linea['unidadP'] }}
                                    </span>
                                </div>
                            </td>
                            <td>
                                <select wire:model="precaptura.{{ $index }}.IdEstilo" class="inpSolo inpChico">
                                    <option value=""></option>
                                    @foreach ($estilos as $key => $value)
                                        <option value="{{ $key }}">{{ $value }}</option>
                                    @endforeach
                                </select>
                            </td>
                            <td>
                                <select wire:model="precaptura.{{ $index }}.kt" class="inpSolo inpChico">
                                    <option value=""></option>
                                    @foreach ($kts as $key => $value)
                                        <option value="{{ $value }}">{{ $value }}</option>
                                    @endforeach
                                </select>
                            </td>
                            <td>
                                <select wire:model="precaptura.{{ $index }}.color" class="inpSolo inpChico">
                                    <option value=""></option>
                                    @foreach ($colors as $key => $value)
                                        <option value="{{ $value }}">{{ $value }}</option>
                                    @endforeach
                                </select>
                            </td>
                            <td>
                                <select wire:model="precaptura.{{ $index }}.IdSize" class="inpSolo inpChico">
                                    <option value=""></option>
                                    @foreach ($sizes as $key => $value)
                                        <option value="{{ $key }}">{{ $value }}</option>
                                    @endforeach
                                </select>
                            </td>
                            <td>
                                <select wire:model="precaptura.{{ $index }}.IdForma" class="inpSolo inpChico">
                                    <option value=""></option>
                                    @foreach ($formas as $key => $value)
                                        <option value="{{ $key }}">{{ $value }}</option>
                                    @endforeach
                                </select>
                            </td>
                            <td>
                                <input type="text" wire:model="precaptura.{{ $index }}.estiloY" class="inpSolo inpChico">
                            </td>
                            <td>
                                <select wire:model="precaptura.{{ $index }}.IdOrigen" class="inpSolo inpChico">
                                    <option value=""></option>
                                    @foreach ($origens as $key => $value)
                                        <option value="{{ $key }}">{{ $value }}</option>
                                    @endforeach
                                </select>
                            </td>
                            <td class="text-center align-middle">
                                <button type="button" class="bot botRojo botChico" wire:click="eliminarComponente({{ $index }})" title="Eliminar fila">
                                    <i class="bi bi-trash-fill"></i>
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>