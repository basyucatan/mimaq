<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Computed;
use App\Models\{Facimportsdet, Existencia, Referenciasmov, Folio, Foliosmat, Estilosdet};
use Livewire\Attributes\On;
use App\Traits\Utilfun;
use Illuminate\Support\Facades\DB;
class Folios extends Component
{
    use WithPagination, Utilfun;

	protected $paginationTheme = 'bootstrap';
    public $verModalFolio=false, $selected_id, $keyWord, $IdLote, $IdFolio, $folio,
		$IdEstilo, $jobStyle, $cantidad, $totalBandejas, $precioU, $fechaVen, $estatus;
	
	public $adicionales = [];
    #[On('refreshFolios')]
    public function refresh()
    {
        if ($this->IdFolio) {
            $this->folio = Folio::find($this->IdFolio);
        }
    }   
    public function mount(){
		$this->folio = Folio::find($this->IdFolio);
	}
public function procesar()
{
    if (!$this->IdFolio) return;
    $materialesSurtir = Foliosmat::where('IdFolio', $this->IdFolio)->whereNotNull('IdFacImportsDet')->where('integrado', false)->get();
    if ($materialesSurtir->isEmpty()) {
        $this->alerta('⚠️ No hay materiales con referencia pendientes de procesar', 'warning', 2500);
        return;
    }
    $idDeptoBoveda = DB::table('deptos')->where('depto', '0 BOVEDA')->value('id');
    foreach ($materialesSurtir as $item) {
        $existencia = Existencia::where('IdFacImportsDet', $item->IdFacImportsDet)->where('IdDepto', $idDeptoBoveda)->first();
        if ($existencia && $existencia->cantidad >= $item->cantidad) {
            $existencia->update([
                'cantidad' => DB::raw("cantidad - $item->cantidad"),
                'pesoG' => DB::raw("pesoG - $item->pesoG")
            ]);
            Referenciasmov::create([
                'IdFacImportsDet' => $item->IdFacImportsDet,
                'IdMaterial' => $item->IdMaterial,
                'IdDeptoOri' => $idDeptoBoveda,
                'IdDeptoDes' => null,
                'tipo' => 'salida',
                'cantidad' => $item->cantidad,
                'pesoG' => $item->pesoG,
                'tipoDoc' => 'folio',
                'IdDoc' => $this->IdFolio,
                'glosa' => 'Salida a Folio #' . $this->IdFolio,
                'estatus' => 'cerrado'
            ]);
            $item->update(['integrado' => true]);
        }
    }
    $this->alerta("✅ Materiales procesados y descontados de Bóveda", 'success', 2500);
    $this->dispatch('refreshFolios');
}
public function generarMateriales()
{
    if (!$this->folio) return;
    Foliosmat::where('IdFolio', $this->IdFolio)->delete();
    $idDeptoBoveda = DB::table('deptos')->where('depto', '0 BOVEDA')->value('id');
    if (!$idDeptoBoveda) {
        $this->alerta('❌ Error: El departamento "0 BOVEDA" no existe', 'error', 3000);
        return;
    }
    $reservados = Facimportsdet::with(['material', 'existencias' => function($query) use ($idDeptoBoveda) {
        $query->where('IdDepto', $idDeptoBoveda);
    }])
    ->where('IdFolio', $this->IdFolio)
    ->get();
    if ($reservados->isNotEmpty()) {
        $conteoCreados = 0;
        $pendientesBoveda = [];
        foreach ($reservados as $row) {
            $existenciaEnBoveda = $row->existencias->first();
            $cantidadStock = $existenciaEnBoveda->cantidad ?? 0;
            $pesoStock = $existenciaEnBoveda->pesoG ?? 0;
            if ($cantidadStock > 0) {
                $cantidadAsignada = min($row->cantidad, $cantidadStock);
                if ($cantidadAsignada == $cantidadStock) {
                    $pesoFinal = $pesoStock;
                } else {
                    $pesoProporcional = ($row->cantidad > 0) 
                        ? round(($row->pesoG / $row->cantidad) * $cantidadAsignada, 4)
                        : 0;
                    $pesoFinal = min($pesoProporcional, $pesoStock);
                }
                Foliosmat::create([
                    'IdFolio' => $this->IdFolio,
                    'IdFacImportsDet' => $row->id,
                    'IdMaterial' => $row->IdMaterial,
                    'cantidad' => $cantidadAsignada,
                    'pesoG' => $pesoFinal,
                    'integrado' => false,
                ]);
                $conteoCreados++;
            } else {
                $pendientesBoveda[] = ($row->material->material ?? 'Mat Indefinido') . " ({$row->IdEntradaMex})";
            }
        }
        if (!empty($pendientesBoveda)) {
            $lista = implode(', ', $pendientesBoveda);
            $this->alerta("⚠️ Sin existencia en Bóveda: $lista", 'warning', 4000);
        }
        if ($conteoCreados > 0) {
            $this->alerta("✅ Se vincularon $conteoCreados materiales desde Bóveda", 'success', 2000);
        }
    } else {
        $estilosdets = Estilosdet::where('IdEstilo', $this->folio->IdEstilo)->get();
        foreach ($estilosdets as $row) {
            Foliosmat::create([
                'IdFolio' => $this->IdFolio,
                'IdMaterial' => $row->IdMaterial,
                'cantidad' => $row->cantidad * $this->folio->cantidad,
                'pesoG' => 0,
                'integrado' => false,
            ]);
        }
        $this->alerta("ℹ️ Materiales proyectados según estructura de estilo", 'info', 2000);
    }
    $this->dispatch('refreshFolios');
}
    public function limpiar()
	{
        if (!$this->IdFolio) return;
        $movimientos = Foliosmat::where('IdFolio', $this->IdFolio);
        if ($movimientos->exists()) {
            $movimientos->delete();
            $this->alerta('🗑️ Registros eliminados', 'success', 1000);
        } else {
            $this->alerta('⚠️ No hay registros para limpiar', 'warning', 1000);
        }
        $this->dispatch('refreshFolios');
	}
    public function updatedKeyWord()
	{
		$this->resetPage();
	}
    #[Computed]
	public function filteredFolios()
	{
		$keyWord = '%' . $this->keyWord . '%';
		return Folio::Where('id', $this->IdFolio)
			->where(function ($query) use ($keyWord) {
				$query
                ->orWhere('IdLote', 'LIKE', $keyWord)
                ->orWhere('IdEstilo', 'LIKE', $keyWord)
                ->orWhere('jobStyle', 'LIKE', $keyWord)
                ->orWhere('cantidad', 'LIKE', $keyWord)
                ->orWhere('totalBandejas', 'LIKE', $keyWord)
                ->orWhere('precioU', 'LIKE', $keyWord)
                ->orWhere('fechaVen', 'LIKE', $keyWord)
                ->orWhere('estatus', 'LIKE', $keyWord);
			})
			->paginate(12);
	}

	public function render()
	{
		return view('livewire.folios.view', [
			'folios' => $this->filteredFolios,
		]);
	}
	
    public function cancel()
    {
        $this->resetInput();
        $this->verModalFolio = false;
    }

    public function resetInput()
    {
        $this->reset();
    }

    public function edit($id)
    {
        $this->selected_id = $id;
		$this->fill(Folio::findOrFail($id)->toArray());
        $this->verModalFolio = true;
    }
    public function create()
    {
        $this->resetInput();
        $this->verModalFolio = true;
    }    
    public function save()
    {
        $this->validate([
		'IdLote' => 'required',
		'cantidad' => 'required',
		'totalBandejas' => 'required',
		'precioU' => 'required',
		'fechaVen' => 'required',
		'estatus' => 'required',
        ]);

        Folio::updateOrCreate(
			['id' => $this->selected_id],
			[
				'IdLote' => $this-> IdLote,
				'IdEstilo' => $this-> IdEstilo,
				'jobStyle' => $this-> jobStyle,
				'cantidad' => $this-> cantidad,
				'totalBandejas' => $this-> totalBandejas,
				'precioU' => $this-> precioU,
				'fechaVen' => $this-> fechaVen,
				'estatus' => $this-> estatus
			]
		);
        $this->resetInput();
        $this->verModalFolio = false;
    }
    public function paginationView()
    {
        return 'livewire.paginacionBase';
    }
    public function destroy($id)
    {
        if ($id) {
            Folio::where('id', $id)->delete();
        }
    }
}