<?php
namespace App\Livewire;
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Empresa;
use Livewire\Attributes\Computed;
class Empresas extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';
    public $verModalEmpresa = false;
    public $selected_id, $keyWord, $IdNegocio=1, $tipo, $empresa, $direccion, 
        $razonSocial, $rfc, $tituloTipo,
		$gmaps, $telefono, $email, $adicionales;
    public $tipoContexto;
    public function mount($tipoContexto = 'cliente')
    {
        $this->tipoContexto = $tipoContexto;
        $this->tipo = $this->tipoContexto;
        $this->tituloTipo = $this->tipo == 'cliente' ? 'Clientes' : 'Proveedores';
    }
    public function updatedKeyWord()
    {
        $this->resetPage();
    }
    #[Computed]
    public function filteredEmpresas()
    {
        $keyWord = '%' . $this->keyWord . '%';
        return Empresa::where('tipo', $this->tipoContexto)
            ->where(function ($query) use ($keyWord) {
                $query->where('empresa', 'LIKE', $keyWord)
                    ->orWhere('IdNegocio', 'LIKE', $keyWord)
                    ->orWhere('direccion', 'LIKE', $keyWord)
                    ->orWhere('telefono', 'LIKE', $keyWord)
                    ->orWhere('email', 'LIKE', $keyWord);
            })
            ->paginate(12);
    }
    public function render()
    {
        return view('livewire.empresas.view', [
            'empresas' => $this->filteredEmpresas,
        ]);
    }
    public function cancel()
    {
        $this->resetInput();
        $this->verModalEmpresa = false;
    }
    public function resetInput()
    {
        $this->reset(['selected_id', 'empresa', 'razonSocial', 'rfc',
            'direccion', 'gmaps', 'telefono', 'email', 'adicionales']);
        $this->tipo = $this->tipoContexto;
    }
    public function edit($id)
    {
        $this->selected_id = $id;
        $this->fill(Empresa::findOrFail($id)->toArray());
        $this->verModalEmpresa = true;
    }
    public function create()
    {
        $this->resetInput();
        $this->verModalEmpresa = true;
    }
    public function save()
    {
        $this->validate([
            'tipo' => 'required',
            'empresa' => 'required',
            'telefono' => 'nullable|numeric|digits:10'
            ]);
        Empresa::updateOrCreate(
            ['id' => $this->selected_id],
            [
                'IdNegocio' => $this->IdNegocio,
                'tipo' => $this->tipo,
                'empresa' => $this->empresa,
                'razonSocial' => $this->razonSocial,
                'rfc' => $this->rfc,
                'direccion' => $this->direccion,
                'gmaps' => $this->gmaps,
                'telefono' => $this->telefono,
                'email' => $this->email,
                'adicionales' => $this->adicionales
            ]
        );
        $this->cancel();
    }
    public function destroy($id)
    {
        if ($id) Empresa::where('id', $id)->delete();
    }
}