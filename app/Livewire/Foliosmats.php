<?php
namespace App\Livewire;
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\{Foliosmat, Material, Util, Existencia};
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\{Computed, On};
class Foliosmats extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';
    public $verModalFoliosmat = false, $selected_id, $keyWord, $IdFolio, 
        $IdFacImportsDet, $IdTipo, $IdMaterial, $cantidad, $pesoG, $integrado;
    public $materials = [], $referencias = [], $tipos = [];
    #[On('refreshFolios')]
    public function refreshChild(){}
    public function updatedIdMaterial()
    {
        $this->IdFacImportsDet = null;
        $this->pesoG = 0;
        $this->integrado = false;
        if(!$this->IdMaterial) return;
        $this->IdTipo = DB::table('materials')
            ->join('clases', 'clases.id', '=', 'materials.IdClase')
            ->where('materials.id', $this->IdMaterial)
            ->value('clases.IdTipo');
        $this->resetErrorBag(['IdMaterial', 'IdFacImportsDet', 'cantidad']);
        $this->cargarReferencias();
        $this->validarDisponibilidad();
    }
    public function updatedIdTipo()
    {
        if(!$this->IdTipo){
            $this->materials = Util::getArray('materials');
            return;
        }
        $this->IdMaterial = null;
        $this->IdFacImportsDet = null;
        $this->pesoG = 0;
        $this->materials = DB::table('materials')
            ->join('clases', 'clases.id', '=', 'materials.IdClase')
            ->where('clases.IdTipo', $this->IdTipo)
            ->select('materials.*')
            ->orderby('materials.material')
            ->pluck('material','id')
            ->toArray();        
    }    
    public function updatedIdFacImportsDet()
    {
        if (!$this->IdFacImportsDet) {
            $this->pesoG = 0;
            $this->integrado = false;
            return;
        }
        $this->validarDisponibilidad();
    }
    public function updatedCantidad()
    {
        $this->validarDisponibilidad();
    }
public function validarDisponibilidad()
{
    $this->resetErrorBag(['cantidad', 'IdFacImportsDet']);
    $solicitado = floatval($this->cantidad);
    if (!$this->IdFacImportsDet) {
        $this->pesoG = 0;
        // Quitamos: $this->integrado = false; <-- Ya no lo movemos aquí
        if ($solicitado <= 0) $this->addError('cantidad', "Ingrese una cantidad válida.");
        return;
    }
    $idDeptoBoveda = DB::table('deptos')->where('depto', '0 BOVEDA')->value('id');
    $existencia = Existencia::where('IdFacImportsDet', $this->IdFacImportsDet)
        ->where('IdDepto', $idDeptoBoveda)->first();
    if ($existencia) {
        $fisico = floatval($existencia->cantidad);
        $pesoTotal = floatval($existencia->pesoG);
        $apartado = Foliosmat::where('IdFacImportsDet', $this->IdFacImportsDet)
            ->where('integrado', true)
            ->when($this->selected_id, function($q) {
                $q->where('id', '!=', $this->selected_id);
            })->sum('cantidad');
        $disponibleReal = $fisico - $apartado;
        if ($solicitado <= 0) {
            $this->addError('cantidad', "Ingrese una cantidad válida.");
            $this->pesoG = 0;
        } elseif ($solicitado > $disponibleReal) {
            $msj = $apartado > 0
                ? "Atención: Hay {$apartado} pz reservadas. Máximo libre: {$disponibleReal} pz."
                : "Atención: El máximo libre es {$disponibleReal} pz.";
            $this->addError('cantidad', $msj);
            $this->pesoG = 0;
        } elseif ($solicitado == $fisico) {
            $this->pesoG = $pesoTotal;
        } else {
            $this->pesoG = round(($pesoTotal / $fisico) * $solicitado, 4);
        }
    }
}
    private function cargarReferencias()
    {
        $this->referencias = [];
        $idDeptoBoveda = DB::table('deptos')->where('depto', '0 BOVEDA')->value('id');
        if (!$idDeptoBoveda || !$this->IdMaterial) return;
        $queryExistencias = Existencia::with(['facimportsdet.size', 'facimportsdet.forma', 'facimportsdet.estilo'])
            ->where('IdDepto', $idDeptoBoveda)
            ->whereHas('facimportsdet', function ($query) {
                $query->where('IdMaterial', $this->IdMaterial);
            })
            ->where(function($query) {
                $query->where('cantidad', '>', 0)->orWhere('IdFacImportsDet', $this->IdFacImportsDet);
            })->get();
        $this->referencias = $queryExistencias->mapWithKeys(function ($item) {
            $det = $item->facimportsdet;
            $entrada = $det->IdEntradaMex ?? 'S/N';
            $stock = number_format($item->cantidad, 0, '.', '');
            return [$item->IdFacImportsDet => "{$entrada} [{$stock} pz] - {$det->propsTot}"];
        })->toArray();
    }
public function edit($id)
{
    $this->selected_id = $id;
    $folioMat = Foliosmat::findOrFail($id);
    $this->fill($folioMat->toArray());
    // Mantenemos el valor de 'integrado' tal cual viene de la BD
    $this->cargarReferencias();
    $this->verModalFoliosmat = true;
}

public function save()
{
    $this->validarDisponibilidad();
    $this->validate([
        'IdFolio' => 'required',
        'IdMaterial' => 'required',
        'cantidad' => 'required|numeric|min:0.001',
        'pesoG' => 'required|numeric',
    ]);
    Foliosmat::updateOrCreate(['id' => $this->selected_id], [
        'IdFolio' => $this->IdFolio,
        'IdFacImportsDet' => $this->IdFacImportsDet ?: null,
        'IdMaterial' => $this->IdMaterial,
        'cantidad' => $this->cantidad,
        'pesoG' => $this->pesoG,
        'integrado' => $this->integrado // Se guarda el estado previo o false por defecto
    ]);
    $this->cancel();
}
    public function create()
    {
        $this->resetInput();
        $this->selected_id = null;
        $this->integrado = false;
        $this->cantidad = 1;
        $this->pesoG = 0;
        $this->verModalFoliosmat = true;
    }
    public function cancel() { $this->resetInput(); $this->verModalFoliosmat = false; }
    public function resetInput() { 
        $this->resetExcept('materials','tipos', 'IdFolio'); $this->referencias = []; 
    }
    public function mount() { 
        $this->tipos = Util::getArray('tipos'); 
        $this->materials = Util::getArray('materials'); 
    }
    public function render() { return view('livewire.foliosmats.view', ['foliosmats' => $this->filteredFoliosmats]); }
    public function destroy($id){if ($id) {Foliosmat::where('id', $id)->delete();}}
    #[Computed]
    public function filteredFoliosmats()
    {
        return Foliosmat::query()
            ->select('foliosmats.*')
            ->join('materials', 'materials.id', '=', 'foliosmats.IdMaterial')
            ->join('clases', 'clases.id', '=', 'materials.IdClase')
            ->where('foliosmats.IdFolio', $this->IdFolio)
            ->where('foliosmats.cantidad', 'LIKE', '%'.$this->keyWord.'%')
            ->orderBy('clases.IdAccess')
            ->paginate(12);
    }
}