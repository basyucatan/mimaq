<?php
namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Computed;
use App\Models\Catalogo;

class Catalogos extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $catalogo = null;
    public $verModal = false;
    public $selected_id, $keyWord;
    public $campos = [];

    public function mount()
    {
        $all = Catalogo::all(null);
        $this->catalogo = $all->keys()->first();
    }

    public function updatedCatalogo()
    {
        $this->resetPage();
        $this->resetInput();
    }

    public function updatedKeyWord()
    {
        $this->resetPage();
    }

    #[Computed]
    public function itemsNormalizados()
    {
        $data = Catalogo::all(null)[$this->catalogo] ?? null;

        if (is_null($data)) return collect();

        // 🔥 Caso 1: lista de registros
        if (is_array($data) && array_is_list($data)) {
            return collect($data);
        }

        // 🔥 Caso 2: objeto único → lo convertimos a lista
        if (is_array($data)) {
            return collect([array_merge(['id' => 1], $data)]);
        }

        // 🔥 Caso 3: valor simple
        return collect([
            ['id' => 1, 'valor' => $data]
        ]);
    }

    #[Computed]
    public function filteredItems()
    {
        $items = $this->itemsNormalizados;

        if ($this->keyWord) {
            $items = $items->filter(function ($item) {
                return collect($item)
                    ->except('id')
                    ->some(fn($val) => str_contains(strtolower((string)$val), strtolower($this->keyWord)));
            });
        }

        return $items;
    }

    #[Computed]
    public function columnas()
    {
        $first = $this->itemsNormalizados->first();
        return $first ? array_keys($first) : ['id'];
    }

    public function render()
    {
        $allData = Catalogo::all(null);

        return view('livewire.catalogos.view', [
            'items' => $this->filteredItems,
            'catalogos' => $allData->keys(),
            'cols' => $this->columnas,
        ]);
    }

    public function resetInput()
    {
        $this->selected_id = null;
        $this->campos = [];

        foreach ($this->columnas as $col) {
            if ($col !== 'id') {
                $this->campos[$col] = '';
            }
        }
    }

    public function create()
    {
        $this->resetInput();
        $this->verModal = true;
    }

    public function edit($id)
    {
        $item = $this->itemsNormalizados->firstWhere('id', $id);

        if (!$item) return;

        $this->selected_id = $id;
        $this->campos = collect($item)->except('id')->toArray();
        $this->verModal = true;
    }

    public function save()
    {
        $reglas = [];

        foreach ($this->campos as $key => $val) {
            $reglas["campos.$key"] = 'required';
        }

        $this->validate($reglas);

        $itemData = array_merge(['id' => $this->selected_id], $this->campos);

        Catalogo::saveItem($this->catalogo, $itemData);

        $this->cancel();
    }

    public function destroy($id)
    {
        Catalogo::delete($this->catalogo, $id);
    }

    public function cancel()
    {
        $this->resetInput();
        $this->verModal = false;
    }
}