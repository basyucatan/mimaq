<?php
namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\On;
use App\Models\{Folio, FoliosMat, Referenciasmov, Facimportsdet};
use Illuminate\Support\Facades\DB;

class Adminfolios extends Component
{
    public $orden, $lote, $IdFolio;
    public $materialesSeleccionados = []; // Array de IdFacImportsDet => ['cantidad', 'pesoG']
    public $idFacturaBusqueda;

    #[On('IdFolioElecto')]
    public function elegirFolio($id)
    {
        $this->IdFolio = $id;
    }
    public function getMaterialesDisponiblesProperty()
    {
        // Solo materiales que tienen saldo en Bóveda
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
            // 1. Crear el Folio
            $folio = Folio::create([
                'orden' => $this->orden,
                'lote' => $this->lote,
                'estatus' => 'produccion',
                'fechaCreacion' => now()
            ]);

            foreach ($this->materialesSeleccionados as $idDet => $datos) {
                // 2. Registrar en foliosMats (Referencia del Folio)
                FoliosMat::create([
                    'IdFolio' => $folio->id,
                    'IdFacImportsDet' => $idDet,
                    'cantidad' => $datos['cantidad'],
                    'pesoG' => $datos['pesoG']
                ]);

                // 3. Generar movimiento de salida en referenciasMovs
                // NOTA: Los valores van en NEGATIVO para descontar de Bóveda
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