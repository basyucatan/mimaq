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
            'password'=>Hash::make('abcd'),'activo'=>true,'IdDepto'=>$IdDeptoAdmin])->assignRole('SuperAdmin');
        User::create(['name'=>'User','email'=>'user@gmail.com','telefono'=>'9995',
            'password'=>Hash::make('dcba'),'activo'=>true,'IdDepto'=>$IdDeptoAdmin])->assignRole('User');
        User::create(['name'=>'Director','email'=>'director@gmail.com','telefono'=>'9991001001',
            'password'=>Hash::make('director$'),'activo'=>true,'IdDepto'=>$IdDeptoAdmin])->assignRole('Director');
        User::create(['name'=>'Gerente','email'=>'gerente@gmail.com','telefono'=>'9991001002',
            'password'=>Hash::make('gerente$'),'activo'=>true,'IdDepto'=>$IdDeptoAdmin])->assignRole('Admin');
        User::create(['name'=>'Reyna','email'=>'reyna@gmail.com','telefono'=>'99910',
            'password'=>Hash::make('control$'),'activo'=>true,'IdDepto'=>$IdDeptoAdmin])->assignRole('Admin');
        User::create(['name'=>'Martha','email'=>'martha@gmail.com','telefono'=>'99911',
            'password'=>Hash::make('qc$'),'activo'=>true,'IdDepto'=>$IdDeptoAdmin])->assignRole('Admin'); 
        User::create(['name'=>'Jaime','email'=>'jaime@gmail.com','telefono'=>'99912',
            'password'=>Hash::make('jaime$'),'activo'=>true,'IdDepto'=>$IdDeptoAdmin])->assignRole('Admin');
        User::create(['name'=>'Cristofer','email'=>'cristofer@gmail.com','telefono'=>'99913',
            'password'=>Hash::make('cristofer$'),'activo'=>true,'IdDepto'=>$IdDeptoAdmin])->assignRole('Admin');
        User::create(['name'=>'Javier','email'=>'javier@gmail.com','telefono'=>'99914',
            'password'=>Hash::make('javier$'),'activo'=>true,'IdDepto'=>$IdDeptoAdmin])->assignRole('Admin');
        User::create(['name'=>'Gabriel','email'=>'gabriel@gmail.com','telefono'=>'99915',
            'password'=>Hash::make('gabriel$'),'activo'=>true,'IdDepto'=>$IdDeptoAdmin])->assignRole('Admin');
        User::create(['name'=>'Victor','email'=>'victor@gmail.com','telefono'=>'99916',
            'password'=>Hash::make('victor$'),'activo'=>true,'IdDepto'=>$IdDeptoAdmin])->assignRole('Admin');
    }
}
