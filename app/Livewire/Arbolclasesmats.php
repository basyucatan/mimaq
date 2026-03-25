<?php
namespace App\Livewire;
use Livewire\Component;
use App\Models\Clase;
use Illuminate\Support\Collection;
class Arbolclasesmats extends Component
{
    public $arbol = [];
    public $expand = [];
    public $mostrarRaiz = true;
    public $keyWord = '';
    public function mount()
    {
        $this->cargarArbol();
    }
    public function cargarArbol()
    {
        $this->arbol = $this->crearConsulta()->toArray();
        $this->expand = [];
    }
    protected function crearConsulta(): Collection
    {
        $query = Clase::query()
            ->with(['materials' => function ($q) {
                $q->with(['Materialscostos' => function ($mc) {
                    $mc->orderBy('referencia', 'asc');
                }]);
                $q->orderBy('material', 'asc')->orderBy('referencia', 'asc');
                if ($this->keyWord) {
                    $kw = "%{$this->keyWord}%";
                    $q->where(function ($sub) use ($kw) {
                        $sub->where('material', 'like', $kw)
                            ->orWhere('referencia', 'like', $kw)
                            ->orWhereHas('Materialscostos', function ($msc) use ($kw) {
                                $msc->where('referencia', 'like', $kw);
                            });
                    });
                }
            }])
            ->orderBy('orden', 'asc');
        if ($this->keyWord) {
            $kw = "%{$this->keyWord}%";
            $query->where(function ($q) use ($kw) {
                $q->where('clase', 'like', $kw)
                ->orWhereHas('materials', function ($sub) use ($kw) {
                    $sub->where('material', 'like', $kw)
                        ->orWhere('referencia', 'like', $kw)
                        ->orWhereHas('Materialscostos', function ($msc) use ($kw) {
                            $msc->where('referencia', 'like', $kw);
                        });
                });
            });
        }
        return $query->get();
    }
    public function updatedKeyWord()
    {
        $this->cargarArbol();
        $this->expandirTodo();
    }
    protected function expandirTodo()
    {
        foreach ($this->arbol as $clase) {
            $this->expand['Clase'][$clase['id']] = true;
            foreach ($clase['materials'] ?? [] as $mat) {
                $this->expand['Material'][$mat['id']] = true;
                foreach ($mat['materialscostos'] ?? [] as $costo) {
                    $this->expand['Materialscosto'][$costo['id']] = true;
                }
            }
        }
    }
    public function toggle($tipo, $id)
    {
        $this->expand[$tipo][$id] = !($this->expand[$tipo][$id] ?? false);
        if ($tipo !== 'Clase') {$this->elegir($tipo, $id);}
    }
    public function elegir($tipo, $id)
    {
        $this->dispatch('IdArbolElecto', $tipo, $id);
    }
    public function agregar($tipo, $id)
    {
        $this->dispatch('IdArbolAgregar', $tipo, $id);
    }    
    public function render()
    {
        return view('livewire.arbolclasesmats.view');
    }
}