<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Computed;
use App\Models\User;
use App\Models\Util;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Hash;

class Users extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';
    public $verModalUser = false,$verModalRol = false,$verModalPermiso = false,
        $vistaActiva = 'usuarios',$selected_id,$keyWord = '',$IdRolFiltro = '',
        $nivelMinimo,
        $password,$passwordConf,$name,$telefono,$email,$nivel = 6;
    public $rolesSeleccionados = [], $permisosSeleccionados = [], $roles = [];
    public function mount()
    {
        $this->roles = Util::getArray('roles', 'name');
    }
    public function updatingKeyWord()
    {
        $this->resetPage();
    }

    public function updatingIdRolFiltro()
    {
        $this->resetPage();
    }

    public function updatingVistaActiva()
    {
        $this->resetPage();
        $this->keyWord = '';
    }

    #[Computed]
    public function puedeGestionarEstructura()
    {
        return auth()->user()->hasAnyRole(['superAdmin', 'director', 'gerente']) || auth()->user()->roles->min('nivel') <= 3;
    }

    #[Computed]
    public function listado()
    {
        $keyWord = '%' . $this->keyWord . '%';
        $miNivelMaximo = auth()->user()->roles->min('nivel') ?? 6;
        if ($this->vistaActiva === 'usuarios') {
            return User::whereHas('roles', function ($query) use ($miNivelMaximo) {
                $query->where('nivel', '>=', $miNivelMaximo);
            })
            ->when($this->IdRolFiltro, function ($query) {
                $query->whereHas('roles', function ($q) {
                    $q->where('id', $this->IdRolFiltro);
                });
            })
            ->where(function ($query) use ($keyWord) {
                $query->orWhere('name', 'LIKE', $keyWord)
                    ->orWhere('telefono', 'LIKE', $keyWord)
                    ->orWhere('email', 'LIKE', $keyWord);
            })
            ->paginate(10);
        }
        if ($this->vistaActiva === 'roles' && $this->puedeGestionarEstructura) {
            return Role::where('nivel', '>=', $miNivelMaximo)
                ->where('name', 'LIKE', $keyWord)
                ->paginate(10);
        }
        if ($this->vistaActiva === 'permisos' && $this->puedeGestionarEstructura) {
            return Permission::where('name', 'LIKE', $keyWord)
                ->paginate(10);
        }
        return collect();
    }

    #[Computed]
    public function todosRoles()
    {
        $miNivelMaximo = auth()->user()->roles->min('nivel') ?? 6;
        return Role::where('nivel', '>=', $miNivelMaximo)->get();
    }

    #[Computed]
    public function todosPermisos()
    {
        return Permission::all();
    }

    public function render()
    {
        return view('livewire.users.view');
    }

    public function cancel()
    {
        $this->resetInput();
        $this->verModalUser = false;
        $this->verModalRol = false;
        $this->verModalPermiso = false;
    }

    public function create()
    {
        $this->resetInput();
        if ($this->vistaActiva === 'usuarios') {
            $this->verModalUser = true;
        } elseif ($this->vistaActiva === 'roles' && $this->puedeGestionarEstructura) {
            $this->verModalRol = true;
        } elseif ($this->vistaActiva === 'permisos' && $this->puedeGestionarEstructura) {
            $this->verModalPermiso = true;
        }
    }

    public function resetInput()
    {
        $this->selected_id = null;
        $this->name = '';
        $this->telefono = '';
        $this->email = '';
        $this->password = '';
        $this->passwordConf = '';
        $this->nivel = 6;
        $this->rolesSeleccionados = [];
        $this->permisosSeleccionados = [];
        $this->resetErrorBag();
    }

    public function edit($id)
    {
        $this->resetInput();
        $this->selected_id = $id;
        if ($this->vistaActiva === 'usuarios') {
            $user = User::findOrFail($id);
            $this->fill($user->only(['name', 'telefono', 'email']));
            $this->rolesSeleccionados = $user->roles->pluck('name')->toArray();
            $this->permisosSeleccionados = $user->getDirectPermissions()->pluck('name')->toArray();
            $this->verModalUser = true;
        } elseif ($this->vistaActiva === 'roles' && $this->puedeGestionarEstructura) {
            $rol = Role::findOrFail($id);
            $this->name = $rol->name;
            $this->nivel = $rol->nivel;
            $this->permisosSeleccionados = $rol->permissions->pluck('name')->toArray();
            $this->verModalRol = true;
        } elseif ($this->vistaActiva === 'permisos' && $this->puedeGestionarEstructura) {
            $permiso = Permission::findOrFail($id);
            $this->name = $permiso->name;
            $this->verModalPermiso = true;
        }
    }

    public function save()
    {
        if ($this->verModalUser) {
            $this->guardarUsuario();
        } elseif ($this->verModalRol && $this->puedeGestionarEstructura) {
            $this->guardarRol();
        } elseif ($this->verModalPermiso && $this->puedeGestionarEstructura) {
            $this->guardarPermiso();
        }
    }

    private function guardarUsuario()
    {
        $reglas = [
            'name' => 'required',
            'telefono' => 'required|numeric|min_digits:4',
            'email' => 'nullable|email',
        ];
        if (!$this->selected_id || !empty($this->passwordConf)) {
            $reglas['password'] = 'required|min:4|same:passwordConf';
        }
        $this->validate($reglas);
        $datos = [
            'name' => $this->name,
            'telefono' => $this->telefono,
            'email' => $this->email,
        ];
        if (!empty($this->passwordConf) && !empty($this->password)) {
            $datos['password'] = Hash::make($this->password);
        }
        $user = User::updateOrCreate(
            ['id' => $this->selected_id],
            $datos
        );
        $user->syncRoles($this->rolesSeleccionados);
        $user->syncPermissions($this->permisosSeleccionados);
        $this->cancel();
    }

    private function guardarRol()
    {
        $this->validate([
            'name' => 'required',
            'nivel' => 'required|integer|min:1',
        ]);
        $rol = Role::updateOrCreate(
            ['id' => $this->selected_id],
            ['name' => $this->name, 'nivel' => $this->nivel]
        );
        $rol->syncPermissions($this->permisosSeleccionados);
        $this->roles = Util::getArray('roles', 'name');
        $this->cancel();
    }

    private function guardarPermiso()
    {
        $this->validate([
            'name' => 'required',
        ]);
        Permission::updateOrCreate(
            ['id' => $this->selected_id],
            ['name' => $this->name]
        );
        $this->cancel();
    }

    public function destroy($id)
    {
        if ($id) {
            if ($this->vistaActiva === 'usuarios') {
                User::where('id', $id)->delete();
            } elseif ($this->vistaActiva === 'roles' && $this->puedeGestionarEstructura) {
                Role::where('id', $id)->delete();
                $this->roles = Util::getArray('roles', 'name');
            } elseif ($this->vistaActiva === 'permisos' && $this->puedeGestionarEstructura) {
                Permission::where('id', $id)->delete();
            }
        }
    }
}