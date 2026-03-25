<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
class UserSeeder extends Seeder
{    
    public function run()
    {
        User::create(['name'=>'Basilio','email'=>'basilio_hh@hotmail.com','telefono'=>'9991',
            'password'=>Hash::make('1234'),'activo'=>true,'IdDepto'=>9,'IdDivision'=>1])->assignRole('SuperAdmin');
        User::create(['name'=>'User','email'=>'user@gmail.com','telefono'=>'9995',
            'password'=>Hash::make('4321'),'activo'=>true,'IdDepto'=>9,'IdDivision'=>1])->assignRole('User');
        User::create(['name'=>'Claudio','email'=>'claudio@gmail.com','telefono'=>'9992025595',
            'password'=>Hash::make('claudio#'),'activo'=>true,'IdDepto'=>9,'IdDivision'=>1])->assignRole('Director');
        User::create(['name'=>'Gaby','email'=>'gaby@gmail.com','telefono'=>'9994757015',
            'password'=>Hash::make('gaby1234'),'activo'=>true,'IdDepto'=>9,'IdDivision'=>1])->assignRole('Admin');
        User::create(['name'=>'Adriana','email'=>'adriana@gmail.com','telefono'=>'9997483321',
            'password'=>Hash::make('adriana1234'),'activo'=>true,'IdDepto'=>9,'IdDivision'=>3])->assignRole('Admin');
        User::create(['name'=>'Sandra','email'=>'sandra@gmail.com','telefono'=>'9992799069',
            'password'=>Hash::make('sandra1234'),'activo'=>true,'IdDepto'=>9,'IdDivision'=>1])->assignRole('Admin');
        User::create(['name'=>'Swemy','email'=>'swemy@gmail.com','telefono'=>'9998020084',
            'password'=>Hash::make('swemy1234'),'activo'=>true,'IdDepto'=>9,'IdDivision'=>1])->assignRole('Admin');
        User::create(['name'=>'Julio','email'=>'julio@gmail.com','telefono'=>'9994762824',
            'password'=>Hash::make('julio1234'),'activo'=>true,'IdDepto'=>9,'IdDivision'=>2])->assignRole('Admin');
    }
}
