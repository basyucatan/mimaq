<?php

namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MovsIniSeeder extends Seeder
{    
    public function run()
    {
$pedimentos = [
    ['id'=>1,'pedimento'=>'101','regimen'=>'IN','fecha'=>'2024-04-08','adicionales'=>null,'created_at'=>now(),'updated_at'=>now()],
    ['id'=>2,'pedimento'=>'102','regimen'=>'IN','fecha'=>'2024-04-15','adicionales'=>null,'created_at'=>now(),'updated_at'=>now()],
    ['id'=>3,'pedimento'=>'103','regimen'=>'IN','fecha'=>'2024-04-22','adicionales'=>null,'created_at'=>now(),'updated_at'=>now()],
];
DB::table('pedimentos')->insert($pedimentos);

$facturas = [
    ['id'=>1,'factura'=>'390','IdPedimento'=>1,'fecha'=>'2024-04-08','tipoCambio'=>'18.1200','estatus'=>'abierto','adicionales'=>null,'created_at'=>now(),'updated_at'=>now()],
    ['id'=>2,'factura'=>'391','IdPedimento'=>1,'fecha'=>'2024-04-10','tipoCambio'=>'17.9500','estatus'=>'abierto','adicionales'=>null,'created_at'=>now(),'updated_at'=>now()],
    ['id'=>3,'factura'=>'392','IdPedimento'=>1,'fecha'=>'2024-04-11','tipoCambio'=>'18.4300','estatus'=>'abierto','adicionales'=>null,'created_at'=>now(),'updated_at'=>now()],

    ['id'=>4,'factura'=>'393','IdPedimento'=>2,'fecha'=>'2024-04-15','tipoCambio'=>'18.0100','estatus'=>'abierto','adicionales'=>null,'created_at'=>now(),'updated_at'=>now()],
    ['id'=>5,'factura'=>'394','IdPedimento'=>2,'fecha'=>'2024-04-16','tipoCambio'=>'17.8800','estatus'=>'abierto','adicionales'=>null,'created_at'=>now(),'updated_at'=>now()],
    ['id'=>6,'factura'=>'395','IdPedimento'=>2,'fecha'=>'2024-04-17','tipoCambio'=>'18.7600','estatus'=>'abierto','adicionales'=>null,'created_at'=>now(),'updated_at'=>now()],
    ['id'=>7,'factura'=>'396','IdPedimento'=>2,'fecha'=>'2024-04-19','tipoCambio'=>'18.2100','estatus'=>'abierto','adicionales'=>null,'created_at'=>now(),'updated_at'=>now()],

    ['id'=>8,'factura'=>'397','IdPedimento'=>3,'fecha'=>'2024-04-22','tipoCambio'=>'17.9900','estatus'=>'abierto','adicionales'=>null,'created_at'=>now(),'updated_at'=>now()],
    ['id'=>9,'factura'=>'398','IdPedimento'=>3,'fecha'=>'2024-04-22','tipoCambio'=>'18.3400','estatus'=>'abierto','adicionales'=>null,'created_at'=>now(),'updated_at'=>now()],
    ['id'=>10,'factura'=>'399','IdPedimento'=>3,'fecha'=>'2024-04-21','tipoCambio'=>'18.1100','estatus'=>'abierto','adicionales'=>null,'created_at'=>now(),'updated_at'=>now()],
];
DB::table('facturas')->insert($facturas);

    }
}
