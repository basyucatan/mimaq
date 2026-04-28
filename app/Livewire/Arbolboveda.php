<?php

namespace App\Livewire;
use Livewire\Component;
use App\Models\{Material, Facimportsdet};

class Arbolboveda extends Component
{
    public $keyWord = '';
    public $expandir = ['Material' => []];
    public function elegir($tipo, $id)
    {
        if ($tipo == 'Material') {
            $this->expandir['Material'][$id] = !($this->expandir['Material'][$id] ?? false);
        } else {
            // Aquí podrías disparar un evento si quieres que al dar click 
            // a la partida se cargue en algún lado
            $this->dispatch('partidaSeleccionada', $id);
        }
    }
    public function replegarTodo()
    {
        $this->keyWord = '';
        $this->expandir = ['Material' => []];
    }
public function render()
{
    $keyWord = !empty($this->keyWord) ? '%' . $this->keyWord . '%' : null;

    $consulta = Material::query()->whereHas('facimportsdets', function($q) use ($keyWord) {
        $q->whereHas('referenciasmovs', function($query) {
            $query->where('estatus', 'boveda');
        });
        if ($keyWord) {
            $q->where('IdEntradaMex', 'like', $keyWord);
        }
    });

    $arbol = $consulta->with(['facimportsdets' => function($q) use ($keyWord) {
        $q->whereHas('referenciasmovs', function($query) {
            $query->where('estatus', 'boveda');
        })
        ->when($keyWord, function($query) use ($keyWord) {
            $query->where('IdEntradaMex', 'like', $keyWord);
        })
        ->withSum(['referenciasmovs as stock' => function($query) {
            $query->where('estatus', 'boveda');
        }], 'cantidad');
    }])->get();

    // LÓGICA DE AUTO-DESPLIEGUE:
    if (!empty($this->keyWord)) {
        foreach ($arbol as $m) {
            $this->expandir['Material'][$m->id] = true;
        }
    }

    return view('livewire.arbolboveda.view', ['arbol' => $arbol]);
}
}