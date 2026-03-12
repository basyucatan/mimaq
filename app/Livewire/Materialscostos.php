<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Materialscosto;
use Livewire\Attributes\Computed;
use App\Models\Util;
use Illuminate\Support\Facades\DB;
use App\Services\TcpdfService;
use Illuminate\Validation\Rule;

class Materialscostos extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';
    public $verModalMaterialscosto = false, $verModalArbolDes = false,
        $verModalOriDes = false, $verModalUbi = false,
        $selected_id, $keyWord, $IdMaterial,
        $referencia, $IdColor, $padre,
        $monedas = [], $colors = [], $unidads = [], $barras = [], $panels = [], $vidrios = [],
        $direcciones = [], $direccion, $materialPreExiste, $IdVidrio, $IdBarra, $IdPanel,
        $IdUnidad, $IdMoneda, $costo;

    public $ubi = [], $origen = [], $destino = [];
    public $clases = [], $marcas = [], $lineas = [], $zonas = [], $pasillos = [],
        $anaqueles = [], $posiciones = [];

    protected $listeners = [
        'cargarMaterialCostos' => 'cargarMaterial',
    ];
    public function cargarMaterial()
    {
        $this->cargarArrays();
    }

    #[Computed]
    public function filteredMaterialscostos()
    {
        $keyWord = '%' . $this->keyWord . '%';
        $matCostos = Materialscosto::where('IdMaterial', $this->IdMaterial)
            ->when($this->keyWord, function ($query, $keyWord) {
                $keyWord = "%$keyWord%";
                $query->where(function ($q) use ($keyWord) {
                    $q->where('referencia', 'LIKE', $keyWord)
                        ->orWhere('costo', 'LIKE', $keyWord)
                        ->orWhereHas('barra', fn($q) => $q->where('descripcion', 'LIKE', $keyWord))
                        ->orWhereHas('color', fn($q) => $q->where('color', 'LIKE', $keyWord));
                });
            })
            ->get();
        return $matCostos;
    }

    public function render()
    {
        $costos = Materialscosto::All();
        return view('livewire.materialscostos.view', [
            'materialscostos' => $this->filteredMaterialscostos,
            'costos' => $costos,
        ]);
    }

    public function editUbi($id)
    {
        $matCosto = Materialscosto::find($id);
        $this->selected_id = $id;
        $this->ubi = [];
        foreach (['zona', 'pasillo', 'anaquel', 'posicion'] as $campo) {
            $this->ubi[$campo] = $matCosto->ubicacion[$campo] ?? null;
        }
        $this->verModalUbi = true;
    }

    public function saveUbi()
    {
        $this->validateOnly('ubi.zona', [
            'ubi.zona'     => 'required|string|max:30',
            'ubi.pasillo'  => 'required|string|max:10',
            'ubi.anaquel'  => 'required|string|max:10',
            'ubi.posicion' => 'required|string|max:5',
        ]);
        $material = Materialscosto::find($this->selected_id);
        if (!$material) return;
        $this->ubi = array_map(fn($v) => strtoupper(trim($v)), $this->ubi);
        $material->ubicacion = $this->ubi;
        $material->save();
        $this->dispatch('sweetalert', \App\Helpers\SweetAlert::mensaje('✅ Ok', 400, 'success'));
        $this->verModalUbi = false;
    }

    private function llenarArraysUbi()
    {
        $this->zonas = [];
        $this->pasillos = [];
        $this->anaqueles = [];
        $this->posiciones = [];
        $matCostos = Materialscosto::whereNotNull('ubicacion')->pluck('ubicacion');
        foreach ($matCostos as $mat) {
            if (!$mat) continue;
            if (!empty($mat['zona'])) {
                $this->zonas[] = $mat['zona'];
            }
            if (!empty($mat['pasillo'])) {
                $this->pasillos[] = $mat['pasillo'];
            }
            if (!empty($mat['anaquel'])) {
                $this->anaqueles[] = $mat['anaquel'];
            }
            if (!empty($mat['posicion'])) {
                $this->posiciones[] = $mat['posicion'];
            }
        }
        $this->zonas = array_combine(array_values(array_unique($this->zonas)), array_values(array_unique($this->zonas)));
        $this->pasillos = array_combine(array_values(array_unique($this->pasillos)), array_values(array_unique($this->pasillos)));
        $this->anaqueles = array_combine(array_values(array_unique($this->anaqueles)), array_values(array_unique($this->anaqueles)));
        $this->posiciones = array_combine(array_values(array_unique($this->posiciones)), array_values(array_unique($this->posiciones)));
        ksort($this->zonas);
        ksort($this->pasillos);
        ksort($this->anaqueles);
        ksort($this->posiciones);
    }

    public function editArbolDes()
    {
        $this->clases = DB::table('clases')->orderBy('orden')->pluck('clase', 'id');
        $this->marcas = Util::getArray('marcas');
        $this->lineas = Util::getArray('lineas');
        $this->llenarArraysUbi();
        $this->verModalArbolDes = true;
    }

    public function cambioArbol($opcion)
    {
        if ($opcion == 'clase') {
            $IdsLineas  = DB::table('materials')
                ->where('IdClase', $this->origen['IdClase'])
                ->pluck('IdLinea');
            $lineas = DB::table('lineas')
                ->whereIn('id', $IdsLineas)
                ->orderBy('linea')
                ->pluck('IdMarca')
                ->toArray();
            $this->marcas = DB::table('marcas')
                ->whereIn('id', $lineas)
                ->orderBy('marca')
                ->pluck('marca', 'id');
            $this->origen['IdMarca'] = null;
            $this->origen['IdLinea'] = null;
            $this->lineas = [];
        }
        if ($opcion == 'marca') {
            $this->lineas = DB::table('lineas')
                ->where('IdMarca', $this->origen['IdMarca'])
                ->orderBy('orden')
                ->pluck('linea', 'id');
            $this->origen['IdLinea'] = null;
        }
    }
    public function saveArbolDes()
    {
        if (empty($this->origen['IdClase']) || empty($this->origen['IdLinea'])) {
            $this->dispatch('sweetalert', \App\Helpers\SweetAlert::mensaje(
                '❌ Debes seleccionar Clase y Línea',
                500,
                'error'
            ));
            return;
        }
        foreach ($this->destino as $campo => $valor) {
            $valor = trim($valor);
            $this->destino[$campo] = $valor === '' ? null : strtoupper($valor);
        }
        $query = Materialscosto::whereHas('material', function ($q) {
            $q->when(
                !empty($this->origen['IdClase']),
                fn($q2) => $q2->where('IdClase', $this->origen['IdClase'])
            );
            $q->when(
                !empty($this->origen['IdLinea']),
                fn($q2) => $q2->where('IdLinea', $this->origen['IdLinea'])
            );
            $q->when(!empty($this->origen['IdMarca']), function ($q2) {
                $q2->whereHas('Linea', function ($q3) {
                    $q3->where('IdMarca', $this->origen['IdMarca']);
                });
            });
        });
        $matCostos = $query->get();
        if ($matCostos->isEmpty()) {
            $this->dispatch('sweetalert', \App\Helpers\SweetAlert::mensaje(
                '❌ No hay materiales que mover',
                1000,
                'error'
            ));
            return;
        }

        foreach ($matCostos as $m) {
            $zonaDestino = $this->destino['zona'];
            $pasilloDestino = $this->destino['pasillo'];
            $anaquelDestino = !empty($this->destino['anaquel'])
                ? $this->destino['anaquel']
                : (!empty($m->ubicacion['anaquel']) ? $m->ubicacion['anaquel'] : 'TEMP');
            $posDestino = !empty($this->destino['posicion'])
                ? $this->destino['posicion']
                : (!empty($m->ubicacion['posicion']) ? $m->ubicacion['posicion'] : 1);
            $clavePos = $zonaDestino . '|' . $pasilloDestino . '|' . $anaquelDestino . '|' . $posDestino;
            if (!isset($grupoPosiciones[$clavePos])) {
                $ocupado = Materialscosto::where('ubicacion->zona', $zonaDestino)
                    ->where('ubicacion->pasillo', $pasilloDestino)
                    ->where('ubicacion->anaquel', $anaquelDestino)
                    ->where('ubicacion->posicion', $posDestino)
                    ->where('id', '!=', $m->id)
                    ->exists();

                $grupoPosiciones[$clavePos] = $ocupado
                    ? ((int) Materialscosto::where('ubicacion->zona', $zonaDestino)
                        ->where('ubicacion->pasillo', $pasilloDestino)
                        ->where('ubicacion->anaquel', $anaquelDestino)
                        ->max('ubicacion->posicion') ?? 0) + 1
                    : $posDestino;
            }

            $m->ubicacion = [
                'zona' => $zonaDestino,
                'pasillo' => $pasilloDestino,
                'anaquel' => $anaquelDestino,
                'posicion' => $grupoPosiciones[$clavePos],
            ];
            $m->save();
        }


        $this->dispatch('sweetalert', \App\Helpers\SweetAlert::mensaje(
            '✅ Materiales reorganizados según Árbol',
            1000,
            'success'
        ));

        $this->verModalArbolDes = false;
        $this->origen = [];
        $this->destino = [];
    }

    public function editOriDes()
    {
        $this->llenarArraysUbi();
        $this->verModalOriDes = true;
    }
    public function cambioOri($opcion)
    {
        switch ($opcion) {
            case 'zona':
                $this->origen['pasillo'] = null;
                $this->origen['anaquel'] = null;
                $this->origen['posicion'] = null;
                $this->pasillos = Materialscosto::whereNotNull('ubicacion')
                    ->where('ubicacion->zona', $this->origen['zona'])
                    ->get()->pluck('ubicacion')
                    ->map(fn($ubi) => strtoupper(trim($ubi['pasillo'] ?? '')))
                    ->filter()->unique()->sort()->mapWithKeys(fn($v) => [$v => $v])->toArray();

                break;
            case 'pasillo':
                $this->origen['anaquel'] = null;
                $this->origen['posicion'] = null;
                $this->anaqueles = Materialscosto::whereNotNull('ubicacion')
                    ->where('ubicacion->zona', $this->origen['zona'])
                    ->where('ubicacion->pasillo', $this->origen['pasillo'])
                    ->pluck('ubicacion')
                    ->map(fn($ubi) => strtoupper(trim($ubi['anaquel'] ?? '')))
                    ->filter()->unique()->sort()->mapWithKeys(fn($v) => [$v => $v])->toArray();
                break;
            case 'anaquel':
                $this->origen['posicion'] = null;
                $this->posiciones = Materialscosto::whereNotNull('ubicacion')
                    ->where('ubicacion->zona', $this->origen['zona'])
                    ->where('ubicacion->pasillo', $this->origen['pasillo'])
                    ->where('ubicacion->anaquel', $this->origen['anaquel'])
                    ->pluck('ubicacion')
                    ->map(fn($ubi) => strtoupper(trim($ubi['posicion'] ?? '')))
                    ->filter()->unique()->sort()->mapWithKeys(fn($v) => [$v => $v])->toArray();
                break;
        }
    }

    public function saveOriDes()
    {
        if (
            empty($this->origen['zona']) || empty($this->origen['pasillo'])
            || empty($this->destino['zona']) || empty($this->destino['pasillo'])
        ) {
            $this->dispatch('sweetalert', \App\Helpers\SweetAlert::mensaje(
                '❌ Falta zona y/o pasillo',
                500,
                'error'
            ));
            return;
        }

        foreach ($this->origen as $campo => $valor) {
            $valor = trim($valor);
            $this->origen[$campo] = $valor === '' ? null : strtoupper($valor);
        }
        foreach ($this->destino as $campo => $valor) {
            $valor = trim($valor);
            $this->destino[$campo] = $valor === '' ? null : strtoupper($valor);
        }

        $query = Materialscosto::where('ubicacion->zona', $this->origen['zona'])
            ->where('ubicacion->pasillo', $this->origen['pasillo']);
        $query->when(
            !empty($this->origen['anaquel']),
            fn($q) => $q->where('ubicacion->anaquel', $this->origen['anaquel'])
        );
        $query->when(
            !empty($this->origen['posicion']),
            fn($q) => $q->where('ubicacion->posicion', $this->origen['posicion'])
        );
        $matCostos = $query->get();

        if ($matCostos->isEmpty()) {
            $this->dispatch('sweetalert', \App\Helpers\SweetAlert::mensaje(
                '❌ Sin materiales que mover',
                1000,
                'error'
            ));
            return;
        }

        $grupoPosiciones = [];
        foreach ($matCostos as $m) {
            $zonaDestino = $this->destino['zona'];
            $pasilloDestino = $this->destino['pasillo'];
            $anaquelDestino = !empty($this->destino['anaquel'])
                ? $this->destino['anaquel']
                : (!empty($m->ubicacion['anaquel']) ? $m->ubicacion['anaquel'] : 'TEMP');
            $posDestino = !empty($this->destino['posicion'])
                ? $this->destino['posicion']
                : (!empty($m->ubicacion['posicion']) ? $m->ubicacion['posicion'] : 1);

            $clavePos = $zonaDestino . '|' . $pasilloDestino . '|' . $anaquelDestino . '|' . $posDestino;
            if (!isset($grupoPosiciones[$clavePos])) {
                $ocupado = Materialscosto::where('ubicacion->zona', $zonaDestino)
                    ->where('ubicacion->pasillo', $pasilloDestino)
                    ->where('ubicacion->anaquel', $anaquelDestino)
                    ->where('ubicacion->posicion', $posDestino)
                    ->where('id', '!=', $m->id)
                    ->exists();

                $grupoPosiciones[$clavePos] = $ocupado
                    ? ((int) Materialscosto::where('ubicacion->zona', $zonaDestino)
                        ->where('ubicacion->pasillo', $pasilloDestino)
                        ->where('ubicacion->anaquel', $anaquelDestino)
                        ->max('ubicacion->posicion') ?? 0) + 1
                    : $posDestino;
            }

            $m->ubicacion = [
                'zona' => $zonaDestino,
                'pasillo' => $pasilloDestino,
                'anaquel' => $anaquelDestino,
                'posicion' => $grupoPosiciones[$clavePos],
            ];
            $m->save();
        }



        $this->dispatch('sweetalert', \App\Helpers\SweetAlert::mensaje(
            '✅ Ubicación actualizada',
            1000,
            'success'
        ));
        $this->origen = [];
        $this->destino = [];
        $this->verModalOriDes = false;
    }



    public function ubicacionesPDF()
    {
        $matCostos = Materialscosto::whereNotNull('ubicacion')
            ->with(['material', 'Color'])
            ->orderBy('IdMaterial')
            ->get();
        $ubis = [];
        foreach ($matCostos as $msc) {
            $ubic = $msc->ubicacion ?? [];
            $zona = $ubic['zona'] ?? 'SIN ZONA';
            $pasillo = $ubic['pasillo'] ?? 'SIN PASILLO';
            $anaquel = $ubic['anaquel'] ?? 'SIN ANAQUEL';
            $posicion = $ubic['posicion'] ?? 'SIN POSICION';
            $ubis[$zona][$pasillo][$anaquel][$posicion][] = $msc;
        }
        $negocio = \App\Models\Negocio::find(1);
        $pdf = new TcpdfService();
        $pdf->AddPage();
        $pdf->setEqualColumns(3, 62);
        $pdf->SetFont('', '', 9);
        $pdf->Image(public_path('img/' . $negocio->logo), $pdf->GetX(), $pdf->GetY(), 30, 0);
        $pdf->SetY($pdf->getImageRBY() + 1);
        $pdf->SetFont('', 'B', 14);
        $pdf->Write(0, "Ubicaciones de Almacén\n", '', 0, '', true);
        $pdf->SetFont('', '', 9);
        $primeraZona = true;
        $contador = 1;
        foreach ($ubis as $zona => $pasillos) {
            if (!$primeraZona) {
                $pdf->resetColumns();
                $pdf->AddPage();
                $pdf->setEqualColumns(3, 62);
            }
            $primeraZona = false;
            ksort($pasillos, SORT_NATURAL);
            $pdf->Cell(0, 8, $zona, $pdf->aplicarEstilo('nivel1'), 1, 'C', true);
            foreach ($pasillos as $pasillo => $anaqueles) {
                $pdf->Cell(0, 7, "PASILLO $pasillo", $pdf->aplicarEstilo('nivel2'), 1, 'L', true);
                ksort($anaqueles, SORT_NUMERIC);
                foreach ($anaqueles as $anaquel => $niveles) {
                    $pdf->Cell(0, 6, "ANAQUEL $anaquel", $pdf->aplicarEstilo('nivel3'), 1, 'C', true);
                    ksort($niveles, SORT_NUMERIC);
                    foreach ($niveles as $nivel => $items) {
                        $pdf->Cell(0, 6, "Nivel $nivel", $pdf->aplicarEstilo('nivel4'), 1, 'L', true);
                        usort($items, function ($a, $b) {
                            return strcmp(($a->material->material ?? '') . ' ' . $a->referencia, ($b->material->material ?? '') . ' ' . $b->referencia);
                        });
                        $pdf->fillRow = false;
                        foreach ($items as $msc) {
                            $style = $pdf->fillRow ? 'odd' : 'even';
                            $pdf->fillRow = !$pdf->fillRow;
                            $pdf->aplicarFondo($msc->Color?->colorRgba ?? $style);
                            $pdf->Cell(8, 6, $contador, 1, 0, 'C', true);
                            $pdf->SetTextColor(0, 0, 0);
                            $referencia = $msc->referencia ?? 'SIN REF';
                            $material = mb_strimwidth($msc->material->material ?? 'SIN MATERIAL', 0, 20, '...');
                            $existencia = round($msc->existencia(3) + $msc->existencia(2));
                            $pdf->Cell(0, 6, "$referencia | $material | $existencia", $pdf->aplicarEstilo($style), 1, 'L', true);
                            $contador++;
                        }
                    }
                }
            }
        }
        $folder = public_path('ubicaciones');
        if (!file_exists($folder)) mkdir($folder, 0775, true);
        $path = $folder . '/Ubicaciones.pdf';
        $pdf->Output($path, 'F');
        return response()->file($path, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="Ubicaciones.pdf"'
        ]);
    }

    public function variantes()
    {
        $material = \App\Models\Material::find($this->IdMaterial);
        if (!(($material->IdClase == 1 && in_array($material->IdTipo, [1, 2, 3]))
            || $material->IdClase == 3)) {
            $this->dispatch('sweetalert', \App\Helpers\SweetAlert::mensaje(
                '❌ Este tipo/clase no genera variantes',
                2000,
                'error'
            ));
            return;
        }
        if ($material->Clase->clase == 'Perfiles') {
            $IdColorable = $material->Linea->IdColorablePerfil ?? null;
        }
        if ($material->Clase->clase == 'Herrajes') {
            $IdColorable = 5;
        }
        $tipoColorable = DB::table('colorables')->where('id', $IdColorable)->value('tipo');
        $colors = DB::table('colors')
            ->where('IdColorable', $IdColorable)
            ->pluck('color', 'id');
        $existentes = Materialscosto::where('IdMaterial', $this->IdMaterial)->get();
        $idsExistentes = $existentes->pluck('IdColor')->toArray();
        $nuevos = 0;
        foreach ($colors as $id => $color) {
            if (!in_array($id, $idsExistentes)) {
                $colorBase = strtoupper(explode(' ', $color)[0]);
                $baseReferencia = $material->referencia . '|' . $colorBase;
                $colisiones = Materialscosto::where('IdMaterial', $this->IdMaterial)
                    ->where('referencia', 'like', $baseReferencia . '%')
                    ->get();
                $referencia = $baseReferencia;
                if ($colisiones->where('IdColor', '!=', $id)->isNotEmpty()) {
                    $referencia .= '|C' . $id;
                }
                $nuevo = [
                    'IdMaterial' => $this->IdMaterial,
                    'referencia' => $referencia ?? '',
                    'IdColor' => $id,
                    'IdMoneda' => ($IdColorable == 2) ? 2 : 1,
                ];
                if ($tipoColorable === 'Perfil') {
                    $nuevo['IdBarra'] = 1;
                }
                Materialscosto::create($nuevo);
                $nuevos++;
            }
        }

        if ($colors->count() == 0) {
            $err = '❌ No hay variantes!';
        } elseif ($nuevos == 0) {
            $err = '❌ Hay costos pre-existentes!';
        } else {
            $err = "✅ Se generaron $nuevos costos satisfactoriamente!";
        }
        $this->dispatch('sweetalert', \App\Helpers\SweetAlert::mensaje(
            $err,
            1500,
            str_starts_with($err, '✅') ? 'success' : 'warning'
        ));
    }

    public function cargarArrays()
    {
        $this->unidads = Util::getArray('unidads');
        $this->monedas = Util::getArray('monedas');
        $this->barras = Util::getArray('barras', 'descripcion');
        $this->panels = Util::getArray('panels');
        $this->direcciones = Util::getArray('materialscostos', 'direccion');
        $this->colors = DB::table('colors')
            ->pluck('color', 'id');
        $this->vidrios = DB::table('vidrios')
            ->select(DB::raw("id, CONCAT(vidrio, ' ', ROUND(grosor, 0), 'mm') as vidriomm"))
            ->orderBy('vidriomm')
            ->pluck('vidriomm', 'id')
            ->toArray();
        $material = \App\Models\Material::find($this->IdMaterial);
        if (!$material) return;
        $IdColorable = null;
        if ($material->Clase->clase == 'Perfiles') {
            $IdColorable = $material->Linea->IdColorablePerfil ?? null;
        }
        if ($material->Clase->clase == 'Herrajes') {
            $IdColorable = 5;
        }
        if ($material->Clase->clase == 'Accesorios') {
            $IdColorable = 6;
        }
        if (!$IdColorable)
            return;
        $this->colors = DB::table('colors')
            ->where('IdColorable', $IdColorable)
            ->pluck('color', 'id');
    }
    public function mount()
    {
        if (!$this->IdMaterial) return;
        $this->cargarArrays();
        $material = \App\Models\Material::Where('id', $this->IdMaterial)->first();
        switch ($material->Clase->clase) {
            case 'Perfiles':
                if ($material->Linea->IdColorablePerfil) {
                    $this->colors = DB::table('colors')
                        ->where('IdColorable', $material->Linea->Colorable->id)->pluck('color', 'id');
                }
                break;
            case 'Vidrios':
                $this->colors = DB::table('colors')
                    ->where('IdColorable', 4)->pluck('color', 'id');
                break;
            case 'Herrajes':
                $this->colors = DB::table('colors')
                    ->where('IdColorable', 5)->pluck('color', 'id');
                break;
            default:
                $this->colors = Util::getArray('colors');
                return;
        }
    }
    public function cancel()
    {
        $this->resetInput();
        $this->verModalMaterialscosto = false;
        $this->verModalUbi = false;
        $this->verModalArbolDes = false;
    }
public function resetInput()
{
    $this->reset([
        'selected_id',
        'referencia',
        'IdColor',
        'direccion',
        'IdVidrio',
        'IdBarra',
        'IdPanel',
        'IdMoneda',
        'costo',
        'verModalMaterialscosto',
        'verModalUbi',
        'verModalArbolDes',
        'verModalOriDes'
    ]);
}

    public function edit($id)
    {
        $this->selected_id = $id;
        $this->fill(Materialscosto::findOrFail($id)->toArray());
        $this->verModalMaterialscosto = true;
    }
    public function create()
    {
        $this->resetInput();
        $this->IdMoneda = 1;
        $this->verModalMaterialscosto = true;
    }

    public function save()
    {
        $material = DB::table('materials')->find($this->IdMaterial);
        $this->validate([
            'IdMaterial' => 'required',
            'referencia' => 'required',
            'costo' => 'required',
            'IdMoneda' => 'required',
            'IdBarra' => $material && $material->IdClase == 1 ? 'required' : 'nullable',
            'IdPanel' => $material && $material->IdClase == 2 ? 'required' : 'nullable',
        ]);

        Materialscosto::updateOrCreate(
            ['id' => $this->selected_id],
            [
                'IdMaterial' => $this->IdMaterial,
                'referencia' => strtoupper($this->referencia ?? ''),
                'direccion' => $this->direccion,
                'IdColor' => trim($this->IdColor) === '' ? null : $this->IdColor,
                'IdVidrio' => trim($this->IdVidrio) === '' ? null : $this->IdVidrio,
                'IdPanel' => trim($this->IdPanel) === '' ? null : $this->IdPanel,
                'IdBarra' => trim($this->IdBarra) === '' ? null : $this->IdBarra,
                'IdMoneda' => $this->IdMoneda,
                'costo' => $this->costo
            ]
        );
        $this->resetInput();
        $this->verModalMaterialscosto = false;
    }

    public function destroy($id)
    {
        if ($id) {
            Materialscosto::where('id', $id)->delete();
        }
    }
}
