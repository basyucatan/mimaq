<?php
namespace App\Livewire;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Computed;
use App\Models\Catalogo;
use App\Models\Util;
class Catalogos extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';
    public $catalogo = 'condicionesPago';
    public $verModal = false;
    public $selected_id, $keyWord;
    public $campos = [];
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
    public function filteredItems()
    {
        $items = Catalogo::all($this->catalogo);
        if ($this->keyWord) {
            $items = $items->filter(function ($item) {
                return collect($item)->except('id')->some(fn($val) => str_contains(strtolower($val ?? ''), strtolower($this->keyWord)));
            });
        }
        return $items;
    }
    #[Computed]
    public function columnas()
    {
        $firstItem = Catalogo::all($this->catalogo)->first();
        return $firstItem ? array_keys($firstItem) : ['id'];
    }
    public function render()
    {
        $allData = Catalogo::all(null)->toArray();
        return view('livewire.catalogos.view', [
            'items' => $this->filteredItems,
            'catalogos' => array_keys($allData ?: config('settings.catalogos', [])),
            'cols' => $this->columnas,
        ]);
    }
    public function resetInput()
    {
        $this->selected_id = null;
        $this->campos = [];
        foreach ($this->columnas as $col) {
            if ($col !== 'id') $this->campos[$col] = '';
        }
    }
    public function create()
    {
        $this->resetInput();
        $this->verModal = true;
    }
    public function edit($id)
    {
        $item = Catalogo::find($this->catalogo, $id);
        $this->selected_id = $id;
        $this->campos = collect($item)->except('id')->toArray();
        $this->verModal = true;
    }
    public function save()
    {
        $reglas = [];
        foreach ($this->campos as $key => $val) $reglas["campos.$key"] = 'required';
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