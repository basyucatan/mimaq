<?php
namespace App\Livewire;
use Livewire\{Component, WithPagination};
use Livewire\Attributes\{Computed, On};
use App\Models\Folio;
use App\Traits\{Utilfun};
class Folios extends Component
{
    use WithPagination, Utilfun;
    protected $paginationTheme = 'bootstrap';
    public $verModalFolio=false, $selected_id, $keyWord, $IdLote, $IdFolio, $folio,
        $IdEstilo, $jobStyle, $cantidad, $totalBandejas, $precioU, $fechaVen, $estatus;
    public $confirmadoExceso = false;
    public $adicionales = [];
    #[On('refreshFolios')]
    public function refresh()
    {
        if ($this->IdFolio) {
            $this->folio = Folio::find($this->IdFolio);
        }
    }   
    public function mount()
    {
        $this->folio = Folio::find($this->IdFolio);
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
                $query->orWhere('IdLote', 'LIKE', $keyWord)
                ->orWhere('IdEstilo', 'LIKE', $keyWord)
                ->orWhere('jobStyle', 'LIKE', $keyWord)
                ->orWhere('cantidad', 'LIKE', $keyWord)
                ->orWhere('totalBandejas', 'LIKE', $keyWord)
                ->orWhere('precioU', 'LIKE', $keyWord)
                ->orWhere('fechaVen', 'LIKE', $keyWord)
                ->orWhere('estatus', 'LIKE', $keyWord);
            })->paginate(12);
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
                'IdLote' => $this->IdLote,
                'IdEstilo' => $this->IdEstilo,
                'jobStyle' => $this->jobStyle,
                'cantidad' => $this->cantidad,
                'totalBandejas' => $this->totalBandejas,
                'precioU' => $this->precioU,
                'fechaVen' => $this->fechaVen,
                'estatus' => $this->estatus
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
        if ($id) Folio::where('id', $id)->delete();
    }
}