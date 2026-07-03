<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Referenciasmov;
use Livewire\Attributes\Computed;
use App\Models\{Util, Material, Facimportsdet};
use Livewire\Attributes\On;
class Referenciasmovs extends Component
{
    use WithPagination;

	protected $paginationTheme = 'bootstrap';
    public $verModalReferenciasmov=false, $selected_id, $keyWord, $IdFacImportsDet, $IdMaterial,
        $IdDoc, $tipoDoc, $estatus, $cantidad, $pesoG, $diferencias;	
	public $adicionales = [], $materials = [];
    #[On('refreshRefsMovs')]
    public function refresh(){}    
    public function mount(){
        $this->materials = Util::getArray('materials');
    }
    public function updatedKeyWord()
	{
		$this->resetPage();
	}
    #[Computed]
    public function filteredReferenciasmovs()
    {
        $keyWord = '%' . $this->keyWord . '%';
        return Referenciasmov::where('IdDoc', $this->IdDoc)
            ->where('tipoDoc', $this->tipoDoc)
            ->where(function ($query) use ($keyWord) {
                $query->where('IdDoc', 'LIKE', $keyWord)
                    ->orWhereHas('Referencia', function ($q) use ($keyWord) {
                        $q->where('IdEntradaMex', 'LIKE', $keyWord)
                            ->orWhere('estiloY', 'LIKE', $keyWord)
                            ->orWhere('adicionales->orden', 'LIKE', $keyWord)
                            ->orWhere('adicionales->lote', 'LIKE', $keyWord)
                            ->orWhereHas('material', fn($sq) => $sq->where('material', 'LIKE', $keyWord))
                            ->orWhereHas('Estilo', fn($sq) => $sq->where('estilo', 'LIKE', $keyWord))
                            ->orWhereHas('forma', fn($sq) => $sq->where('forma', 'LIKE', $keyWord))
                            ->orWhereHas('origen', fn($sq) => $sq->where('origen', 'LIKE', $keyWord))
                            ->orWhereHas('size', fn($sq) => $sq->where('size', 'LIKE', $keyWord));
                    });
            })
            ->orderBy('id', 'desc')
            ->paginate(10);
    }
	public function render()
	{
		return view('livewire.referenciasmovs.view', [
			'referenciasmovs' => $this->filteredReferenciasmovs,
		]);
	}
	
    public function cancel()
    {
        $this->resetInput();
        $this->verModalReferenciasmov = false;
    }

    public function resetInput()
    {
        $this->resetExcept('IdDoc','tipoDoc','materials');
    }
    public function edit($id)
    {
        $this->selected_id = $id;
        $registro = Referenciasmov::findOrFail($id);
        $this->IdMaterial = $registro->Referencia->IdMaterial;
        $this->fill($registro->toArray());
        if (is_array($registro->Referencia->diferencias)) {
            $soloTexto = collect($registro->Referencia->diferencias)->first(fn($v, $k) => is_numeric($k));
            $this->diferencias = $soloTexto;
        }
        $this->verModalReferenciasmov = true;
    }
    public function create()
    {
        $this->resetInput();
        $this->verModalReferenciasmov = true;
    }   
public function save()
{
    $this->validate([
        'IdDoc' => 'required',
        'tipoDoc' => 'required',
        'cantidad' => 'required',
        'pesoG' => 'required',
    ]);
    $nuevasDiferencias = [];
    if ($this->diferencias) {
        $nuevasDiferencias[] = $this->diferencias;
    }
    if ($this->IdFacImportsDet) {
        $refImport = Facimportsdet::find($this->IdFacImportsDet);
        if ($refImport) {
            if ($this->IdMaterial != $refImport->IdMaterial) {
                $materialReal = Material::find($this->IdMaterial);
                $nuevasDiferencias['material'] = "Físicamente llegó: " . ($materialReal->material ?? 'S/N');
            }
            if ($this->cantidad != $refImport->cantidad) {
                $nuevasDiferencias['cantidad'] = $this->cantidad - $refImport->cantidad;
            }
            if ($this->pesoG != $refImport->pesoG) {
                $nuevasDiferencias['pesoG'] = round($this->pesoG - $refImport->pesoG, 4);
            }
            $diferenciasActuales = $refImport->diferencias ?? [];
            if (is_array($diferenciasActuales)) {
                $diferenciasActuales = collect($diferenciasActuales)->reject(fn($v, $k) => is_numeric($k) || $k === 'cantidad' || $k === 'pesoG' || $k === 'material')->toArray();
            }
            $diferenciasFinales = array_merge($diferenciasActuales, $nuevasDiferencias);
            $refImport->update([
                'diferencias' => !empty($diferenciasFinales) ? $diferenciasFinales : null
            ]);
        }
    }
    Referenciasmov::updateOrCreate(
        ['id' => $this->selected_id],
        [
            'IdFacImportsDet' => $this->IdFacImportsDet,
            'IdDeptoDes' => 1,
            'tipo' => 'entrada',
            'IdDoc' => $this->IdDoc,
            'tipoDoc' => $this->tipoDoc,
            'cantidad' => $this->cantidad,
            'pesoG' => $this->pesoG,
            'glosa' => !empty($nuevasDiferencias) ? "Ingreso con discrepancias detectadas" : "Ingreso estándar"
        ]
    );
    $this->resetInput();
    $this->verModalReferenciasmov = false;
    $this->dispatch('refreshRefsMovs');
}
    public function paginationView()
    {
        return 'livewire.paginacionBase';
    }
    public function destroy($id)
    {
        if ($id) {
            Referenciasmov::where('id', $id)->delete();
        }
    }
}