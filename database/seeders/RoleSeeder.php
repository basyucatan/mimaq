<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleSeeder extends Seeder
{
    public function run()
    {
        $role1 = Role::firstOrCreate(['name' => 'superAdmin'], ['nivel' => 1]);
        $role2 = Role::firstOrCreate(['name' => 'director'],   ['nivel' => 2]);
        $role3 = Role::firstOrCreate(['name' => 'gerente'],    ['nivel' => 3]);
        $role4 = Role::firstOrCreate(['name' => 'admin'],      ['nivel' => 4]);
        $role5 = Role::firstOrCreate(['name' => 'adminUSA'],   ['nivel' => 4]);
        $role6 = Role::firstOrCreate(['name' => 'tecnico'],    ['nivel' => 5]);
        $role7 = Role::firstOrCreate(['name' => 'operador'],   ['nivel' => 6]);

        Permission::firstOrCreate(['name' => 'cerrar factura'])
            ->syncRoles([$role1,$role2,$role3]); 
    }
}