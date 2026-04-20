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
        $IdDeptoAdmin = \App\Models\Depto::where('depto','Admin')->value('id');
        if (!$IdDeptoAdmin) {
            throw new \Exception('No existe el depto Admin');
        }
        User::create(['name'=>'Basilio','email'=>'basilio_hh@hotmail.com','telefono'=>'9991',
            'password'=>Hash::make('1234'),'activo'=>true,'IdDepto'=>$IdDeptoAdmin])->assignRole('SuperAdmin');
        User::create(['name'=>'User','email'=>'user@gmail.com','telefono'=>'9995',
            'password'=>Hash::make('4321'),'activo'=>true,'IdDepto'=>$IdDeptoAdmin])->assignRole('User');
        User::create(['name'=>'Director','email'=>'director@gmail.com','telefono'=>'9991001001',
            'password'=>Hash::make('director$'),'activo'=>true,'IdDepto'=>$IdDeptoAdmin])->assignRole('Director');
        User::create(['name'=>'Gerente','email'=>'gerente@gmail.com','telefono'=>'9991001002',
            'password'=>Hash::make('gerente$'),'activo'=>true,'IdDepto'=>$IdDeptoAdmin])->assignRole('Admin');
    }
}
