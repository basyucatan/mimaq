<div class="card">
    <div class="card-header fw-bold text-danger">Alta de Material</div>
    <div class="card-body">
        <div class="row g-1">
            <div class="col-6">
                <label class="etiChico">Código</label>
                <input type="text" wire:model="nuevoMat.referencia" class="inpChico">
                @error('nuevoMat.referencia') <span class="text-danger">{{ $message }}</span> @enderror
            </div>      
            <div class="col-6">                                              
                <label class="etiChico">Unidad</label>
                <select wire:model="nuevoMat.IdUnidad" class="inpChico">
                    <option value=""></option>
                    @foreach ($unidads as $key => $value)
                        <option value="{{ $key }}">{{ $value }}</option>
                    @endforeach
                </select>
                @error('nuevoMat.IdUnidad') <span class="text-danger">{{ $message }}</span> @enderror
            </div>                                                                                                                                                                                                               
            <div class="col-12">
                <label class="etiChico">Material</label>
                <input type="text" wire:model="nuevoMat.material" class="inpChico">
                @error('nuevoMat.material') <span class="text-danger">{{ $message }}</span> @enderror
            </div>    
            <div class="col-6">
                <label class="etiChico">Costo</label>
                <input type="number" wire:model="nuevoMat.costo" wire:change="calCostoDep('costo')"
                     onfocus="this.select()" class="inpChico">
                @error('nuevoMat.costo') <span class="text-danger">{{ $message }}</span> @enderror
            </div>  
            <div class="col-6">
                <label class="etiChico">Neto</label>
                <input type="number" wire:model="nuevoMat.neto" wire:change="calCostoDep('neto')"
                    onfocus="this.select()" class="inpChico">
                @error('nuevoMat.neto') <span class="text-danger">{{ $message }}</span> @enderror
            </div>              
            <div class="col-6">
                <label class="etiChico">Moneda</label>
                <select wire:model="nuevoMat.IdMoneda" class="inpChico">
                    <option value=""></option>
                    @foreach ($monedas as $key => $value)
                        <option value="{{ $key }}">{{ $value }}</option>
                    @endforeach
                </select>
                @error('nuevoMat.IdMoneda') <span class="text-danger">{{ $message }}</span> @enderror
            </div>                                                                                        
            <div class="col-6">
                <label class="etiChico">Marca</label>
                <select wire:model="nuevoMat.IdMarca" wire:change="elegirMarca" class="inpChico">
                    <option value=""></option>
                    @foreach ($marcas as $key => $value)
                        <option value="{{ $key }}">{{ $value }}</option>
                    @endforeach
                </select>
            </div>  
            <div class="col-6">
                <label class="etiChico">Linea</label>
                <select wire:model="nuevoMat.IdLinea" wire:change="elegirLinea" class="inpChico">
                    <option value=""></option>
                    @foreach ($lineas as $key => $value)
                        <option value="{{ $key }}">{{ $value }}</option>
                    @endforeach
                </select>
            </div> 
            <div class="col-6">
                <label class="etiChico">Clase</label>
                <select wire:model="nuevoMat.IdClase" class="inpChico">
                    <option value=""></option>
                    @foreach ($clases as $key => $value)
                        <option value="{{ $key }}">{{ $value }}</option>
                    @endforeach
                </select>
                @error('nuevoMat.IdClase') <span class="text-danger">{{ $message }}</span> @enderror
            </div>                                              
            <div class="col-6">
                <label class="etiChico">Color</label>
                <select wire:model="nuevoMat.IdColor" class="inpChico">
                    <option value=""></option>
                    @foreach ($colors as $key => $value)
                        <option value="{{ $key }}">{{ $value }}</option>
                    @endforeach
                </select>
            </div>
            <div class="card-footer">
                <button wire:click="crearMaterial" type="button" class="bot botVerde">Guardar y Agregar</button>
            </div>            
        </div>                                        
    </div>
</div>