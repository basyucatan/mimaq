<?php
namespace App\Livewire;
use Livewire\{Component, WithPagination};
use App\Models\{Util, Material, Factura, Facimportsdet, Cliente, Orden, 
    Lote, Estilo, Folio, Estilosdet};
use Livewire\Attributes\Computed;
use App\Traits\GestionFacImports;
class Facimportsdets extends Component
{
    use WithPagination, GestionFacImports;
    protected $paginationTheme = 'bootstrap';
    protected $listeners = [
        'IdFacturaElecta' => 'IdFacturaElecta',
    ];
    public function IdFacturaElecta()
    {
        $this->factura = Factura::find($this->IdFactura);
    }    
    public function elegirMaterial()
    {
        $material = Material::find($this->IdMaterial);
        $this->arancel = $material->clase->arancel->arancel;
    }     
    public function mount()
    {
        $this->getArrays();
    }
    public function updatedKeyWord()
    {
        $this->resetPage();
    }
    #[Computed]
    public function filteredFacimportsdets()
    {
        $keyWord = '%' . $this->keyWord . '%';
        return Facimportsdet::where('IdFactura', $this->IdFactura)
            ->where(function ($query) use ($keyWord) {
                $query->where('IdEntradaMex', 'LIKE', $keyWord)
                    ->orWhereHas('material', fn($q) =>
                        $q->where('material', 'LIKE', $keyWord)
                        ->orWhere('materialI', 'LIKE', $keyWord))
                    ->orWhereHas('Estilo', fn($q) => $q->where('estilo', 'LIKE', $keyWord))
                    ->orWhereHas('forma', fn($q) => $q->where('forma', 'LIKE', $keyWord))
                    ->orWhereHas('origen', fn($q) => $q->where('origen', 'LIKE', $keyWord))
                    ->orWhereHas('size', fn($q) => $q->where('size', 'LIKE', $keyWord))
                    ->orWhereHas('folio.lote.orden.cliente', fn($q) => $q->where('cliente', 'LIKE', $keyWord))
                    ->orWhereHas('folio.lote.orden', fn($q) => $q->where('orden', 'LIKE', $keyWord))
                    ->orWhereHas('folio.lote', fn($q) => $q->where('lote', 'LIKE', $keyWord))
                    ->orWhere('adicionales->orden', 'LIKE', $keyWord)
                    ->orWhere('adicionales->lote', 'LIKE', $keyWord);
            })->orderBy('id', 'desc')->paginate(10);
    }
    public function render()
    {
        return view('livewire.facimportsdets.view', ['facimportsdets' => $this->filteredFacimportsdets]);
    }
    public function generarConEstilo()
    {
        $this->validate([
            'IdEstilo' => 'required',
            'cantidadEstilo' => 'required|numeric'
        ]);
        $estiloPrincipal = Estilo::find($this->IdEstilo);
        $cantidadEstilo = $this->cantidadEstilo;
        $detalles = Estilosdet::where('IdEstilo', $this->IdEstilo)->get();
        $this->IdFolio = null;
        if (!empty($this->cliente) && !empty($this->orden) && !empty($this->lote)) {
            $objCliente = Cliente::firstOrCreate([
                'cliente' => strtoupper($this->cliente)
            ]);
            $objOrden = Orden::firstOrCreate(
                ['orden' => strtoupper($this->orden)],
                [
                    'IdCliente' => $objCliente->id,
                    'fechaVen' => now()->addDays(7),
                    'estatus' => 'abierto'
                ]
            );
            $objLote = Lote::firstOrCreate([
                'IdOrden' => $objOrden->id,
                'lote' => strtoupper($this->lote)
            ]);
            $objFolio = Folio::create([
                'IdLote' => $objLote->id,
                'IdEstilo' => $this->IdEstilo,
                'productoFinal' => $estiloPrincipal->descripcion ?? 'PRODUCTO TERMINADO',
                'cantidad' => $this->cantidadEstilo,
                'totalBandejas' => 1,
                'precioU' => 0,
                'fechaVen' => $objOrden->fechaVen,
                'estatus' => 'abierto'
            ]);
            $this->IdFolio = $objFolio->id;
        }
        $this->selected_id = null;
        foreach ($detalles as $det) {
            $this->IdMaterial = $det->IdMaterial;
            $this->estiloY = $det->estiloY;
            $this->IdOrigen = 2;
            $this->cantidad = $cantidadEstilo * $det->cantidad;
            $this->IdFolio = $objFolio->id ?? null;
            $this->pesoEnUMat = 0;
            $this->precioU = 0;
            $this->save();
        }
        $this->clientes = Util::getArray('clientes');
    }
    public function crearEstilo()
    {
        $this->resetInput();
        $this->verModalEstilos = true;
    }    
    public function paginationView()
    {
        return 'livewire.paginacionBase';
    }

}