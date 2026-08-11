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
            throw new \Exception('No existe el depto admin');
        }
        $this->crear(['Basilio'],'superAdmin',1,9991,$IdDeptoAdmin);
        $this->crear(['Reyna','Victor','Control'],'admin',100,99910,$IdDeptoAdmin);
        $this->crear(['Eugene'],'adminUSA',103,99913,$IdDeptoAdmin);
        $this->crear(['Javier','Gabriel','Jaime'],'director',200,99920,$IdDeptoAdmin);
    }
    private function crear($users, $rol, $IdIni, $telIni, $IdDepto)
    {
        foreach ($users as $indice => $nombre) {
            User::create([
                'id' => $IdIni + $indice,
                'name' => $nombre,
                'telefono' => (string)($telIni + $indice),
                'password' => Hash::make($nombre . '$'),
                'activo' => true,
                'IdDepto' => $IdDepto
            ])->assignRole($rol);
        }
    }
}
