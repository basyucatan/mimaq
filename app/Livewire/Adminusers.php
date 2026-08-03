<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\User;
use App\Models\Depto;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class Adminusers extends Component
{
    use WithPagination;

    public $activeTab = 'users';
    public $userAction = 'list';
    public $selectedUserId = null;
    
    // Campos de Usuario
    public $name, $telefono, $email, $password, $passwordConf, $IdDepto, $activo = true;
    public $userRoles = [];
    public $userPermissions = [];

    // Campos de Roles y Permisos
    public $newRoleName = '', $newRoleNivel = 10;
    public $newPermissionName = '';
    public $selectedRoleForPermissions = null;
    public $rolePermissions = [];

    public $keyWord = '';

    public function updatingKeyWord()
    {
        $this->resetPage();
    }

    public function getAuthUserMaxLevel()
    {
        $authUser = Auth::user();
        if (!$authUser) return 999;
        
        $minNivel = $authUser->roles()->min('nivel');
        return $minNivel !== null ? $minNivel : 999;
    }

    public function isSuperAdmin()
    {
        return $this->getAuthUserMaxLevel() === 1;
    }

    public function canManageRolesAndPermissions()
    {
        return $this->getAuthUserMaxLevel() <= 2;
    }

    public function render()
    {
        $authLevel = $this->getAuthUserMaxLevel();

        $usersQuery = User::with(['Depto', 'roles', 'permissions'])
            ->whereHas('roles', function($q) use ($authLevel) {
                if ($authLevel == 1) {
                    $q->where('nivel', '>=', 1);
                } else {
                    $q->where('nivel', '>', $authLevel);
                }
            })
            ->where(function($query) {
                $query->where('name', 'like', '%' . $this->keyWord . '%')
                      ->orWhere('email', 'like', '%' . $this->keyWord . '%')
                      ->orWhere('telefono', 'like', '%' . $this->keyWord . '%');
            });

        $users = $usersQuery->paginate(10);

        $availableRoles = Role::where('nivel', '>', $authLevel)->get();
        if ($authLevel <= 2) {
            $availableRoles = Role::where('nivel', '>=', $authLevel)->get();
        }

        return view('livewire.adminusers.view', [
            'users' => $users,
            'roles' => $availableRoles,
            'allRoles' => Role::all(),
            'permissions' => Permission::all(),
            'deptos' => Depto::all(),
        ]);
    }

    public function switchTab($tab)
    {
        if ($tab == 'roles' && !$this->canManageRolesAndPermissions()) {
            session()->flash('error', 'No tienes autorización para gestionar roles.');
            return;
        }

        if ($tab == 'permissions' && !$this->canManageRolesAndPermissions()) {
            session()->flash('error', 'No tienes autorización para gestionar permisos.');
            return;
        }

        $this->activeTab = $tab;
        $this->resetValidation();
        if ($tab == 'users') {
            $this->userAction = 'list';
        }
    }

    public function createNewUser()
    {
        $this->resetUserForm();
        $this->userAction = 'create';
    }

    public function editUser($id)
    {
        $user = User::with(['roles', 'permissions'])->find($id);
        
        if ($user) {
            $targetLevel = $user->roles()->min('nivel') ?? 999;
            $authLevel = $this->getAuthUserMaxLevel();

            if ($targetLevel <= $authLevel) {
                session()->flash('error', 'Acción denegada: Solo un superior jerárquico puede modificar a este usuario.');
                return;
            }
        }

        $this->resetUserForm();
        $this->selectedUserId = $id;

        if ($user) {
            $this->name = $user->name;
            $this->telefono = $user->telefono;
            $this->email = $user->email;
            $this->IdDepto = $user->IdDepto;
            $this->activo = $user->activo;
            $this->userRoles = $user->roles->pluck('name')->toArray();
            $this->userPermissions = $user->permissions->pluck('name')->toArray();
        }
        $this->userAction = 'edit';
    }

    private function resetUserForm()
    {
        $this->selectedUserId = null;
        $this->name = '';
        $this->telefono = '';
        $this->email = '';
        $this->password = '';
        $this->passwordConf = '';
        $this->IdDepto = '';
        $this->activo = true;
        $this->userRoles = [];
        $this->userPermissions = [];
    }

    public function saveUser()
    {
        $rules = [
            'name' => 'required|string|max:255',
            'telefono' => 'nullable|string|max:20',
            'email' => 'nullable|email|unique:users,email,' . $this->selectedUserId,
        ];

        if ($this->userAction == 'create') {
            $rules['password'] = 'required|min:6|same:passwordConf';
        } else if (!empty($this->password)) {
            $rules['password'] = 'min:6|same:passwordConf';
        }

        $this->validate($rules);

        $authLevel = $this->getAuthUserMaxLevel();
        
        if ($authLevel > 1 && !empty($this->userRoles)) {
            $minAssignedRoleLevel = Role::whereIn('name', $this->userRoles)->min('nivel');
            if ($minAssignedRoleLevel <= $authLevel) {
                session()->flash('error', 'No puedes asignar un rol de igual o mayor jerarquía a la tuya.');
                return;
            }
        }

        // Bloquear asignación de permisos directos críticos si no es SuperAdmin
        if (!$this->isSuperAdmin() && !empty($this->userPermissions)) {
            if (in_array('adminMax', $this->userPermissions)) {
                session()->flash('error', 'No tienes permisos para asignar el permiso directo adminMax.');
                return;
            }
        }

        if ($this->selectedUserId) {
            $user = User::find($this->selectedUserId);
            $targetLevel = $user->roles()->min('nivel') ?? 999;
            
            if ($targetLevel <= $authLevel) {
                session()->flash('error', 'No tienes permisos para actualizar a este usuario.');
                return;
            }

            $data = [
                'name' => $this->name,
                'telefono' => $this->telefono,
                'email' => $this->email,
                'IdDepto' => $this->IdDepto ?: null,
                'activo' => $this->activo,
            ];

            if (!empty($this->password)) {
                $data['password'] = Hash::make($this->password);
            }

            $user->update($data);
            $user->syncRoles($this->userRoles);
            $user->syncPermissions($this->userPermissions);

            session()->flash('message', 'Usuario actualizado con éxito.');
        } else {
            $user = User::create([
                'name' => $this->name,
                'telefono' => $this->telefono,
                'email' => $this->email,
                'password' => Hash::make($this->password),
                'IdDepto' => $this->IdDepto ?: null,
                'activo' => $this->activo,
            ]);

            $user->syncRoles($this->userRoles);
            $user->syncPermissions($this->userPermissions);

            session()->flash('message', 'Usuario creado con éxito.');
        }

        $this->userAction = 'list';
        $this->resetUserForm();
    }

    public function deleteUser($id)
    {
        $user = User::find($id);
        if ($user) {
            $targetLevel = $user->roles()->min('nivel') ?? 999;
            $authLevel = $this->getAuthUserMaxLevel();

            if ($targetLevel <= $authLevel) {
                session()->flash('error', 'Acción denegada: No puedes eliminar a un usuario de igual o mayor jerarquía.');
                return;
            }
        }

        User::destroy($id);
        session()->flash('message', 'Usuario eliminado correctamente.');
    }

    public function createRole()
    {
        if (!$this->canManageRolesAndPermissions()) {
            session()->flash('error', 'No tienes permisos para crear roles.');
            return;
        }

        $this->validate([
            'newRoleName' => 'required|unique:roles,name',
            'newRoleNivel' => 'required|integer|min:1'
        ]);

        Role::create(['name' => $this->newRoleName, 'nivel' => $this->newRoleNivel]);
        $this->newRoleName = '';
        $this->newRoleNivel = 10;
        session()->flash('message', 'Rol creado exitosamente.');
    }

    public function deleteRole($id)
    {
        if (!$this->canManageRolesAndPermissions()) {
            session()->flash('error', 'No tienes permisos para eliminar roles.');
            return;
        }

        $role = Role::find($id);
        if ($role) {
            if (in_array($role->name, ['SuperAdmin', 'Director']) && !$this->isSuperAdmin()) {
                session()->flash('error', 'Solo el SuperAdmin puede eliminar roles críticos.');
                return;
            }

            $role->delete();
            session()->flash('message', 'Rol eliminado.');
        }
    }

    public function selectRoleForPermissions($id)
    {
        if (!$this->canManageRolesAndPermissions()) return;

        $this->selectedRoleForPermissions = $id;
        $role = Role::find($id);
        $this->rolePermissions = $role ? $role->permissions->pluck('id')->toArray() : [];
    }

    public function updateRolePermissions()
    {
        if (!$this->canManageRolesAndPermissions()) {
            session()->flash('error', 'No tienes permisos para modificar permisos de roles.');
            return;
        }

        if ($this->selectedRoleForPermissions) {
            $role = Role::find($this->selectedRoleForPermissions);
            $permissions = Permission::whereIn('id', $this->rolePermissions)->get();

            // REGLA CLAVE: Si el usuario actual NO es SuperAdmin, filtramos/bloqueamos si intentan inyectar el permiso adminMax
            if (!$this->isSuperAdmin()) {
                $adminMaxPermission = Permission::where('name', 'adminMax')->first();
                if ($adminMaxPermission && $permissions->contains($adminMaxPermission)) {
                    session()->flash('error', 'Acción denegada: Solo el SuperAdmin puede asignar o incluir el permiso adminMax en un rol.');
                    return;
                }
            }

            $role->syncPermissions($permissions);
            session()->flash('message', 'Permisos del rol actualizados correctamente.');
        }
    }

    public function createPermission()
    {
        if (!$this->canManageRolesAndPermissions()) {
            session()->flash('error', 'No tienes permisos para crear permisos.');
            return;
        }

        $this->validate(['newPermissionName' => 'required|unique:permissions,name']);
        Permission::create(['name' => $this->newPermissionName]);
        $this->newPermissionName = '';
        session()->flash('message', 'Permiso creado exitosamente.');
    }

    public function deletePermission($id)
    {
        if (!$this->canManageRolesAndPermissions()) {
            session()->flash('error', 'No tienes permisos para eliminar permisos.');
            return;
        }

        $permission = Permission::find($id);
        if ($permission) {
            if ($permission->name === 'adminMax' && !$this->isSuperAdmin()) {
                session()->flash('error', 'Acción denegada: El permiso adminMax está protegido y solo puede ser gestionado por el SuperAdmin.');
                return;
            }

            $permission->delete();
            session()->flash('message', 'Permiso eliminado.');
        }
    }
}