<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\User;
use Livewire\Attributes\Computed;
use App\Models\Util;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;

class Users extends Component
{
    use WithPagination;

	protected $paginationTheme = 'bootstrap';
    public $verModalUser=false, $selected_id, $keyWord, $password, $passwordConf,
        $name, $telefono, $email, $IdRol;
    public $roles = [];

	public function mount(){
        $this->roles = Util::getArray('roles','name');
    }
	
public function render()
{
    $keyWord = '%' . $this->keyWord . '%';
    $miNivelMaximo = auth()->user()->roles->max('nivel');
    $users = User::whereHas('roles', function ($query) use ($miNivelMaximo) {
        $query->where('nivel', '>=', $miNivelMaximo);
    })
    ->where(function ($query) use ($keyWord) {
        $query->orWhere('name', 'LIKE', $keyWord)
            ->orWhere('telefono', 'LIKE', $keyWord)
            ->orWhere('email', 'LIKE', $keyWord);
    })
    ->paginate(10);
    return view('livewire.users.view', compact(['users']));
}
	
    public function cancel()
    {
        $this->resetInput();
        $this->verModalUser = false;
    }

    public function create()
    {
        $this->resetInput();
        $this->verModalUser = true;
    } 
    public function resetInput()
    {
        $this->resetExcept('roles');
    } 
    public function edit($id)
    {
        $this->selected_id = $id;
        $user = User::findOrFail($id);
		$this->fill($user->toArray());
        $this->IdRol = $user->roles->pluck('id')->first();
        $this->password = '';
        $this->passwordConf = '';
        $this->verModalUser = true;
    }
   
    public function save()
    {
        $this->validate([
		'name' => 'required',
		'telefono' => 'required|digits:10',
		'email' => 'required',
        'password' => 'required|min:4|same:passwordConf'
        ]);
        $IdRolUser = auth()->user()->roles->pluck('id')->first();
        if ($this->IdRol < $IdRolUser) {
            $this->addError('IdRol', 'No tienes permisos para asignar este rol.');
            return;
        }        
        $datos = [
            'name'     => $this->name,
            'telefono' => $this->telefono,
            'email'    => $this->email,
        ];

        if ($this->password) {
            $datos['password'] = Hash::make($this->password);
        }

        $user = User::updateOrCreate(
            ['id' => $this->selected_id],
            $datos
        );
        $rol = Role::find($this->IdRol);
        if ($rol) {
            $user->syncRoles([$rol->name]);
        }

        $this->resetInput();
        $this->verModalUser = false;
    }

    public function destroy($id)
    {
        if ($id) {
            User::where('id', $id)->delete();
        }
    }
}