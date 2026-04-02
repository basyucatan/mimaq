<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
class UserSeeder extends Seeder
{    
    public function run()
    {
        User::create(['name'=>'Basilio','email'=>'basilio_hh@hotmail.com','telefono'=>'9991',
            'password'=>Hash::make('1234'),'activo'=>true,'IdDepto'=>9])->assignRole('SuperAdmin');
        User::create(['name'=>'User','email'=>'user@gmail.com','telefono'=>'9995',
            'password'=>Hash::make('4321'),'activo'=>true,'IdDepto'=>9])->assignRole('User');
        User::create(['name'=>'Director','email'=>'director@gmail.com','telefono'=>'9991001001',
            'password'=>Hash::make('director$'),'activo'=>true,'IdDepto'=>9])->assignRole('Director');
        User::create(['name'=>'Gerente','email'=>'gerente@gmail.com','telefono'=>'9991001002',
            'password'=>Hash::make('gerente$'),'activo'=>true,'IdDepto'=>9])->assignRole('Admin');
    }
}
