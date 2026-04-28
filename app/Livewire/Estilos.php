<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use Livewire\Attributes\Computed;
use App\Models\{Util, Estilo};
use Illuminate\Support\Facades\DB;

class Estilos extends Component
{
    use WithPagination, WithFileUploads;

	protected $paginationTheme = 'bootstrap';
    public $verModalEstilo=false, $selected_id, $keyWord, $estilo, $IdClase, 
        $fotoSubida,
        $foto;
	public $clases = [];

    public function mount(){
        $this->clases = DB::table('clases')->where('IdTipo', 1)->orWhere('IdTipo', 6)
            ->orderBy('IdAccess', 'asc')
            ->pluck('clase', 'id')->toArray();
    }
    public function updatedKeyWord()
	{
		$this->resetPage();
	}
    #[Computed]
	public function filteredEstilos()
	{
		$keyWord = '%' . $this->keyWord . '%';
		return Estilo::Where('id','>',0)
			->where(function ($query) use ($keyWord) {
				$query
						->orWhere('estilo', 'LIKE', $keyWord)
						->orWhere('IdClase', 'LIKE', $keyWord)
						->orWhere('foto', 'LIKE', $keyWord);
			})
			->paginate(12);
	}

	public function render()
	{
		return view('livewire.estilos.view', [
			'estilos' => $this->filteredEstilos,
		]);
	}
	
    public function cancel()
    {
        $this->resetInput();
        $this->verModalEstilo = false;
    }
    public function resetInput()
    {
        $this->resetExcept('clases');
        $this->fotoSubida = null;
        $this->foto = null;
        $this->selected_id = null;
    }
    public function edit($id)
    {
        $this->selected_id = $id;
		$this->fill(Estilo::findOrFail($id)->toArray());
        $this->verModalEstilo = true;
    }
    public function create()
    {
        $this->resetInnput();
        $this->verModalEstilo = true;
    } 

    public function save()
    {
        $this->validate([
            'estilo' => 'required',
            'IdClase' => 'required',
            'fotoSubida' => $this->selected_id ? 'nullable|image|max:2048' : 'required|image|max:2048',
        ]);
        $nombreFoto = $this->foto;
        if ($this->fotoSubida) {
            if ($this->selected_id) {
                $estiloActual = Estilo::find($this->selected_id);
                if ($estiloActual && $estiloActual->foto) {
                    Util::borrarArchivo('estilos', $estiloActual->foto);
                }
            }
            $nombreFoto = Util::guardarFoto($this->fotoSubida, $this->estilo, "estilos");
        }
        Estilo::updateOrCreate(
            ['id' => $this->selected_id],
            [
                'estilo' => $this->estilo,
                'IdClase' => $this->IdClase,
                'foto' => $nombreFoto
            ]
        );
        $this->resetInput();
        $this->verModalEstilo = false;
    }
    public function borrarFoto()
    {
        if ($this->selected_id) {
            $estilo = Estilo::find($this->selected_id);
            if ($estilo && $estilo->foto) {
                Util::borrarArchivo('estilos', $estilo->foto);
                $estilo->update(['foto' => null]);
                $this->foto = null;
            }
        }
        $this->fotoSubida = null;
    }
    public function paginationView()
    {
        return 'livewire.paginacionBase';
    }
    public function destroy($id)
    {
        if ($id) {
            Estilo::where('id', $id)->delete();
        }
    }    
}