<?php

namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
class MovsIniSeeder extends Seeder
{    
    public function run()
    {
      
// $ordens = array(
//   array('id' => '1','IdCliente' => '11','orden' => '100','estatus' => 'abierto','fechaVen' => '2026-07-20','adicionales' => NULL),
//   array('id' => '2','IdCliente' => '7','orden' => '200','estatus' => 'abierto','fechaVen' => '2026-07-20','adicionales' => NULL)
// );
// DB::table('ordens')->insert($ordens);
// $lotes = array(
//   array('id' => '1','lote' => '100100','IdOrden' => '1','adicionales' => NULL),
//   array('id' => '2','lote' => '200200','IdOrden' => '2','adicionales' => NULL)
// );
// DB::table('lotes')->insert($lotes);
// $folios = array(
//   array('id' => '1','IdLote' => '1','IdEstilo' => '723','jobStyle' => 'R5321','productoFinal' => 'ANILLO DE ORO','abreviatura' => '1R|36D|1CT','cantidad' => '5','totalBandejas' => '1','precioU' => '0.0000','fechaVen' => '2026-07-20','periodo' => '2607','consecutivoMensual' => '1','estatus' => 'abierto','alertas' => NULL,'adicionales' => '{"composicion":{"634":{"cantidad":1,"tipo":"CASTING","idTipo":1},"656":{"cantidad":1,"tipo":"PIEDRA","idTipo":7},"640":{"cantidad":36,"tipo":"DIAMANTE","idTipo":2}}}','created_at' => NULL,'updated_at' => NULL),
//   array('id' => '2','IdLote' => '2','IdEstilo' => '2525','jobStyle' => 'P1868','productoFinal' => 'PENDANTE DE ORO','abreviatura' => '1P|20D|1PE|1CH','cantidad' => '3','totalBandejas' => '1','precioU' => '0.0000','fechaVen' => '2026-07-20','periodo' => '2607','consecutivoMensual' => '2','estatus' => 'abierto','alertas' => NULL,'adicionales' => '{"composicion":{"635":{"cantidad":1,"tipo":"CASTING","idTipo":1},"640":{"cantidad":20,"tipo":"DIAMANTE","idTipo":2},"670":{"cantidad":1,"tipo":"METAL AUX","idTipo":6},"639":{"cantidad":1,"tipo":"PIEDRA","idTipo":7}}}','created_at' => NULL,'updated_at' => NULL)
// );
// DB::table('folios')->insert($folios);
$pedimentos = array(
  array('id' => '3','pedimento' => '103','regimen' => 'RT','fecha' => '2024-04-22','tipoCambio' => '18.5000','adicionales' => NULL,'created_at' => '2026-05-25 05:12:59','updated_at' => '2026-05-25 05:12:59'),
  array('id' => '6','pedimento' => '1953','regimen' => 'IN','fecha' => '2026-05-29','tipoCambio' => '18.5000','adicionales' => NULL,'created_at' => '2026-05-29 16:19:50','updated_at' => '2026-05-29 16:19:50')
);
DB::table('pedimentos')->insert($pedimentos);
$facturas = array(
  array('id' => '5','factura' => '150','IdPedimento' => '3','fecha' => '2026-05-20','estatus' => 'abierto','guias' => NULL,'adicionales' => '{"viadE":"FEDEX","guiaA":"","nPaq":1}','created_at' => '2026-05-21 00:23:48','updated_at' => '2026-05-21 00:23:48'),
  array('id' => '7','factura' => '1953','IdPedimento' => '6','fecha' => '2026-05-11','estatus' => 'cerrado','guias' => '["55-626546256","66-5656421"]','adicionales' => '{"viadE":"FEDEX","guiaA":"483975964716","nPaq":1}','created_at' => '2026-05-29 16:22:00','updated_at' => '2026-07-13 23:20:57')
);
DB::table('facturas')->insert($facturas);

// DB::table('facimportsdets')->insert($facimportsdets);

    }
}
