<?php
namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\On;
use App\Models\{Folio, Foliosmat, Referenciasmov};
use Illuminate\Support\Facades\DB;
class Adminfolios extends Component
{
    public $orden, $lote, $IdFolio;
    public $materialesSeleccionados = []; 
    public $idFacturaBusqueda;

    #[On('IdFolioElecto')]
    public function elegirFolio($id)
    {
        $this->IdFolio = $id;
    }
    public function getMaterialesDisponiblesProperty()
    {
        return Referenciasmov::where('estatus', 'boveda')
            ->select('IdFacImportsDet', DB::raw('SUM(cantidad) as saldoCant'), DB::raw('SUM(pesoG) as saldoPeso'))
            ->groupBy('IdFacImportsDet')
            ->having('saldoCant', '>', 0)
            ->with('Referencia.material')
            ->get();
    }

    public function confirmarFolio()
    {
        $this->validate([
            'orden' => 'required',
            'lote' => 'required',
            'materialesSeleccionados' => 'required|array|min:1'
        ]);

        DB::transaction(function () {
            $folio = Folio::create([
                'orden' => $this->orden,
                'lote' => $this->lote,
                'estatus' => 'produccion',
                'fechaCreacion' => now()
            ]);
            foreach ($this->materialesSeleccionados as $idDet => $datos) {
                Foliosmat::create([
                    'IdFolio' => $folio->id,
                    'IdFacImportsDet' => $idDet,
                    'cantidad' => $datos['cantidad'],
                    'pesoG' => $datos['pesoG']
                ]);
                Referenciasmov::create([
                    'IdFacImportsDet' => $idDet,
                    'IdDoc' => $folio->id,
                    'tipoDoc' => 'folioSal',
                    'estatus' => 'proceso',
                    'cantidad' => $datos['cantidad'] * -1,
                    'pesoG' => $datos['pesoG'] * -1,
                    'adicionales' => [
                        'orden' => $this->orden,
                        'lote' => $this->lote
                    ]
                ]);
            }
        });

        $this->reset();
        session()->flash('message', '✅ Folio enviado a producción correctamente.');
    }
    public function render()
    {
        return view('livewire.adminfolios.view');
    }
}