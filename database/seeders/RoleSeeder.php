<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleSeeder extends Seeder
{
    public function run()
    {
        $role1 = Role::create(['name' => 'SuperAdmin', 'nivel' => 1]);
        $role2 = Role::create(['name' => 'Director', 'nivel' => 2]);
        $role3 = Role::create(['name' => 'Admin', 'nivel' => 3]);
        $role4 = Role::create(['name' => 'Tecnico', 'nivel' => 4]);
        $role5 = Role::create(['name' => 'Operador', 'nivel' => 5]);
        $role6 = Role::create(['name' => 'User', 'nivel' => 6]);

        Permission::create(['name' => 'user'])
            ->syncRoles([$role1, $role2, $role3, $role4, $role5, $role6]);           
        Permission::create(['name' => 'adminMax'])
            ->syncRoles([$role1]); // Solo SuperAdmin
        Permission::create(['name' => 'admin'])
            ->syncRoles([$role1, $role2]); // Solo SuperAdmin y Admin
    }
}
