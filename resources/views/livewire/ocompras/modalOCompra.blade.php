<div class="row g-1">
    <div class="col-4">
        <label class="etiBase">Division</label>
        <select wire:model="IdDivision" class="inpBase">
            <option value=""></option>
            @foreach ($divisions as $key => $value)
            <option value="{{ $key }}">{{ $value }}</option>
            @endforeach
        </select>
        @error('IdDivision') <span class="text-danger">{{ $message }}</span> @enderror
    </div>
    <div class="col-8">
        <label class="etiBase">Cliente</label>
        <select wire:model="IdCliente" wire:change="elegirCliente" class="inpBase">
            <option value=""></option>
            @foreach ($clientes as $key => $value)
            <option value="{{ $key }}">{{ $value }}</option>
            @endforeach
        </select>
        @error('IdCliente') <span class="text-danger">{{ $message }}</span> @enderror
    </div>
    <div class="col-6">
        <label class="etiBase">Obra</label>
        <select wire:model="IdObra" class="inpBase">
            <option value=""></option>
            @foreach ($obras as $key => $value)
            <option value="{{ $key }}">{{ $value }}</option>
            @endforeach
        </select>
        @error('IdObra') <span class="text-danger">{{ $message }}</span> @enderror
    </div>
    <div class="col-6">
        <label class="etiBase">Concepto Compra</label>
        <input type="text" wire:model="concepto" class="inpBase">
        @error('concepto') <span class="text-danger">{{ $message }}</span> @enderror
    </div>
    <div class="col-9 position-relative">
        <label class="etiBase">Proveedor</label>
        <input type="text" wire:model.live="keyWordProv" onfocus="this.select()" class="inpBase" placeholder="Buscar proveedor...">
        @error('IdProveedor') <span class="text-danger">{{ $message }}</span> @enderror
        @if(count($provs) > 0)
        <div class="position-absolute bg-white w-100" style="z-index: 2100;">
            @foreach($provs as $p)
            <a href="javascript:void(0)" wire:click="elegirProv({{ $p->id }}, '{{ $p->empresa }}')" class="d-block p-2 border-bottom text-decoration-none small text-dark">{{ $p->empresa }}</a>
            @endforeach
        </div>
        @endif
    </div>
    <div class="col-3">
        <label class="etiBase">Cuenta</label>
        <select wire:model="IdCuentaProv" class="inpBase">
            <option value=""></option>
            @foreach ($cuentas as $key => $value)
            <option value="{{ $key }}">{{ $value }}</option>
            @endforeach
        </select>
        @error('IdCuentaProv') <span class="text-danger">{{ $message }}</span> @enderror
    </div>
    <div class="col-3">
        <label class="etiBase">% Desc.</label>
        <input type="number" wire:model.live="porDescuento" class="inpBase">
    </div>
    <div class="col-4">
        <label class="etiBase">Cond. Pago</label>
        <select wire:model="IdCondPago" class="inpBase">
            <option value=""></option>
            @foreach ($condsPago as $key => $value)
            <option value="{{ $key }}">{{ $value }}</option>
            @endforeach
        </select>
        @error('IdCondPago') <span class="text-danger">{{ $message }}</span> @enderror
    </div>  
    <div class="col-5">
        <label class="etiBase">Cond. Flete</label>
        <select wire:model="IdCondFlete" class="inpBase">
            <option value=""></option>
            @foreach ($condsFlete as $key => $value)
            <option value="{{ $key }}">{{ $value }}</option>
            @endforeach
        </select>
        @error('IdCondFlete') <span class="text-danger">{{ $message }}</span> @enderror
    </div>          
<Flete>