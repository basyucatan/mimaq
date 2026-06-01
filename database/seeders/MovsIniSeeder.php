<?php

namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Material;
use App\Models\Facimportsdet;
class MovsIniSeeder extends Seeder
{    
    public function run()
    {
$ordens = array(
  array('id' => '1','IdCliente' => '1','orden' => 'W400','estatus' => 'abierto','fechaVen' => '2026-05-13','adicionales' => NULL),
  array('id' => '2','IdCliente' => '2','orden' => '1561','estatus' => 'abierto','fechaVen' => '2026-05-13','adicionales' => NULL),
  array('id' => '3','IdCliente' => '3','orden' => '5151','estatus' => 'abierto','fechaVen' => '2026-05-14','adicionales' => NULL),
  array('id' => '4','IdCliente' => '4','orden' => 'DSF624','estatus' => 'abierto','fechaVen' => '2026-05-14','adicionales' => NULL)
);
DB::table('ordens')->insert($ordens);
$lotes = array(
  array('id' => '1','lote' => '141561','IdOrden' => '1','adicionales' => NULL),
  array('id' => '2','lote' => '126546','IdOrden' => '2','adicionales' => NULL),
  array('id' => '3','lote' => '125616','IdOrden' => '3','adicionales' => NULL),
  array('id' => '4','lote' => '65596','IdOrden' => '4','adicionales' => NULL)
);
DB::table('lotes')->insert($lotes);
$folios = array(
  array('id' => '1','IdLote' => '1','IdEstilo' => '723','jobStyle' => 'R5321','productoFinal' => 'ANILLO DE ORO 24K Y','abreviatura' => '1R|36D|1CT','cantidad' => '1','totalBandejas' => '1','precioU' => '0.0000','fechaVen' => '2026-05-13','periodo' => '2605','consecutivoMensual' => '1','estatus' => 'abierto','alertas' => NULL,'adicionales' => '{"composicion":{"634":{"cantidad":1,"tipo":"CASTING","idTipo":1},"656":{"cantidad":1,"tipo":"PIEDRA","idTipo":7},"640":{"cantidad":36,"tipo":"DIAMANTE","idTipo":2}},"kt":"24K","color":"Y"}','created_at' => NULL,'updated_at' => NULL),
  array('id' => '2','IdLote' => '2','IdEstilo' => '2881','jobStyle' => 'R15009','productoFinal' => 'ANILLO DE PLATA','abreviatura' => '1SR|2D|1ET|1BT','cantidad' => '2','totalBandejas' => '1','precioU' => '0.0000','fechaVen' => '2026-05-13','periodo' => '2605','consecutivoMensual' => '2','estatus' => 'abierto','alertas' => NULL,'adicionales' => '{"composicion":{"675":{"cantidad":1,"tipo":"CASTING","idTipo":1},"640":{"cantidad":2,"tipo":"DIAMANTE","idTipo":2},"630":{"cantidad":1,"tipo":"PIEDRA","idTipo":7},"643":{"cantidad":1,"tipo":"PIEDRA","idTipo":7}}}','created_at' => NULL,'updated_at' => NULL),
  array('id' => '3','IdLote' => '3','IdEstilo' => '1303','jobStyle' => 'R5794','productoFinal' => 'ANILLO DE ORO 10K Y','abreviatura' => '1R|14D|1CWS|1ET','cantidad' => '100','totalBandejas' => '10','precioU' => '0.0000','fechaVen' => '2026-05-14','periodo' => '2605','consecutivoMensual' => '3','estatus' => 'abierto','alertas' => NULL,'adicionales' => '{"composicion":{"634":{"cantidad":1,"tipo":"CASTING","idTipo":1},"640":{"cantidad":14,"tipo":"DIAMANTE","idTipo":2},"688":{"cantidad":1,"tipo":"PIEDRA","idTipo":7},"630":{"cantidad":1,"tipo":"PIEDRA","idTipo":7}},"kt":"10K","color":"Y"}','created_at' => NULL,'updated_at' => NULL),
  array('id' => '4','IdLote' => '4','IdEstilo' => '262','jobStyle' => 'R4227','productoFinal' => 'ANILLO DE ORO 14K W','abreviatura' => '1R|6D|1BO','cantidad' => '27','totalBandejas' => '3','precioU' => '0.0000','fechaVen' => '2026-05-14','periodo' => '2605','consecutivoMensual' => '4','estatus' => 'abierto','alertas' => NULL,'adicionales' => '{"composicion":{"634":{"cantidad":1,"tipo":"CASTING","idTipo":1},"640":{"cantidad":6,"tipo":"DIAMANTE","idTipo":2},"667":{"cantidad":1,"tipo":"PIEDRA","idTipo":7}},"kt":"14K","color":"W"}','created_at' => NULL,'updated_at' => NULL)
);
DB::table('folios')->insert($folios);
$pedimentos = [
    ['id'=>1,'pedimento'=>'101','regimen'=>'IN','fecha'=>'2024-04-08','tipoCambio' => '17.9900','adicionales'=>null,'created_at'=>now(),'updated_at'=>now()],
    ['id'=>2,'pedimento'=>'102','regimen'=>'IN','fecha'=>'2024-04-15','tipoCambio' => '17.9900','adicionales'=>null,'created_at'=>now(),'updated_at'=>now()],
    ['id'=>3,'pedimento'=>'103','regimen'=>'RT','fecha'=>'2024-04-22','tipoCambio' => '17.9900','adicionales'=>null,'created_at'=>now(),'updated_at'=>now()],
];
DB::table('pedimentos')->insert($pedimentos);
$facturas = array(
  array('id' => '1','factura' => '393','IdPedimento' => '1','fecha' => '2024-04-15','estatus' => 'abierto','adicionales' => NULL,'created_at' => '2026-04-22 17:06:50','updated_at' => '2026-04-22 17:06:50'),
  array('id' => '2','factura' => '397','IdPedimento' => '2','fecha' => '2024-04-22','estatus' => 'cerrado','adicionales' => '{"viadE":"406186745024","guiaA":"FEDEX","nPaq":"1"}','created_at' => '2026-04-22 17:06:50','updated_at' => '2026-04-22 18:12:33'),
  array('id' => '3','factura' => '398','IdPedimento' => '2','fecha' => '2024-04-22','estatus' => 'abierto','adicionales' => '{"viadE":"406186745024","guiaA":"FEDEX","nPaq":"1"}','created_at' => '2026-04-22 17:06:50','updated_at' => '2026-04-22 18:12:27'),
  array('id' => '4','factura' => '399','IdPedimento' => '2','fecha' => '2024-04-21','estatus' => 'abierto','adicionales' => NULL,'created_at' => '2026-04-22 17:06:50','updated_at' => '2026-04-22 17:06:50'),
  array('id' => '5','factura' => '150','IdPedimento' => '3','fecha' => '2026-05-20','estatus' => 'abierto','adicionales' => '{"viadE":"FEDEX","guiaA":"","nPaq":1}','created_at' => '2026-05-21 00:23:48','updated_at' => '2026-05-21 00:23:48')
);
DB::table('facturas')->insert($facturas);
$facimportsdets = array(
  array('id' => '1','IdFactura' => '2','IdMaterial' => '634','IdEntradaMex' => '397-1','arancel' => '71131999','IdOrigen' => '2','IdFolio' => '1','cantidad' => '1.0000','precioU' => '1.0000','pesoEnUMat' => '0.8000','pesoG' => '1.2400','IdSize' => NULL,'IdForma' => NULL,'IdEstilo' => '723','estiloY' => NULL,'diferencias' => NULL,'adicionales' => '{"kt":"14K","color":"Y"}','created_at' => NULL,'updated_at' => NULL),
  array('id' => '2','IdFactura' => '2','IdMaterial' => '656','IdEntradaMex' => '397-2','arancel' => '71049999','IdOrigen' => '2','IdFolio' => '1','cantidad' => '1.0000','precioU' => '1.0000','pesoEnUMat' => '0.5000','pesoG' => '0.1000','IdSize' => '199','IdForma' => '2','IdEstilo' => NULL,'estiloY' => NULL,'diferencias' => NULL,'adicionales' => '{"kt":null,"color":null}','created_at' => NULL,'updated_at' => NULL),
  array('id' => '3','IdFactura' => '2','IdMaterial' => '640','IdEntradaMex' => '397-3','arancel' => '71023999','IdOrigen' => '2','IdFolio' => '1','cantidad' => '36.0000','precioU' => '1.0000','pesoEnUMat' => '8.0000','pesoG' => '1.6000','IdSize' => '98','IdForma' => '11','IdEstilo' => NULL,'estiloY' => NULL,'diferencias' => NULL,'adicionales' => '{"kt":null,"color":null}','created_at' => NULL,'updated_at' => NULL),
  array('id' => '4','IdFactura' => '2','IdMaterial' => '640','IdEntradaMex' => '397-4','arancel' => '71023999','IdOrigen' => '4','IdFolio' => NULL,'cantidad' => '600.0000','precioU' => '1.0000','pesoEnUMat' => '32.0000','pesoG' => '6.4000','IdSize' => '98','IdForma' => '11','IdEstilo' => NULL,'estiloY' => NULL,'diferencias' => NULL,'adicionales' => '{"kt":null,"color":null}','created_at' => NULL,'updated_at' => NULL),
  array('id' => '5','IdFactura' => '2','IdMaterial' => '675','IdEntradaMex' => '397-5','arancel' => '71131199','IdOrigen' => '2','IdFolio' => '2','cantidad' => '2.0000','precioU' => '1.0000','pesoEnUMat' => '3.0000','pesoG' => '4.6500','IdSize' => NULL,'IdForma' => NULL,'IdEstilo' => '2881','estiloY' => NULL,'diferencias' => NULL,'adicionales' => '{"kt":null,"color":null}','created_at' => NULL,'updated_at' => NULL),
  array('id' => '6','IdFactura' => '2','IdMaterial' => '640','IdEntradaMex' => '397-6','arancel' => '71023999','IdOrigen' => '1','IdFolio' => '2','cantidad' => '4.0000','precioU' => '1.0000','pesoEnUMat' => '0.3000','pesoG' => '0.0600','IdSize' => '1','IdForma' => '5','IdEstilo' => NULL,'estiloY' => NULL,'diferencias' => NULL,'adicionales' => '{"kt":null,"color":null}','created_at' => NULL,'updated_at' => NULL),
  array('id' => '7','IdFactura' => '2','IdMaterial' => '643','IdEntradaMex' => '397-7','arancel' => '71039999','IdOrigen' => '2','IdFolio' => '2','cantidad' => '2.0000','precioU' => '2.0000','pesoEnUMat' => '0.2500','pesoG' => '0.0500','IdSize' => '45','IdForma' => '8','IdEstilo' => NULL,'estiloY' => NULL,'diferencias' => NULL,'adicionales' => '{"kt":null,"color":null}','created_at' => NULL,'updated_at' => NULL),
  array('id' => '8','IdFactura' => '2','IdMaterial' => '634','IdEntradaMex' => '397-8','arancel' => '71131999','IdOrigen' => '2','IdFolio' => '3','cantidad' => '100.0000','precioU' => '1.0000','pesoEnUMat' => '22.0000','pesoG' => '34.1000','IdSize' => NULL,'IdForma' => NULL,'IdEstilo' => '1303','estiloY' => NULL,'diferencias' => NULL,'adicionales' => '{"kt":"10K","color":"Y"}','created_at' => NULL,'updated_at' => NULL),
  array('id' => '9','IdFactura' => '2','IdMaterial' => '640','IdEntradaMex' => '397-9','arancel' => '71023999','IdOrigen' => '2','IdFolio' => '3','cantidad' => '1400.0000','precioU' => '1.0000','pesoEnUMat' => '150.0000','pesoG' => '30.0000','IdSize' => '98','IdForma' => '8','IdEstilo' => NULL,'estiloY' => NULL,'diferencias' => NULL,'adicionales' => '{"kt":null,"color":null}','created_at' => NULL,'updated_at' => NULL),
  array('id' => '10','IdFactura' => '2','IdMaterial' => '688','IdEntradaMex' => '397-10','arancel' => '71049999','IdOrigen' => '2','IdFolio' => '3','cantidad' => '100.0000','precioU' => '2.3000','pesoEnUMat' => '6.4300','pesoG' => '1.2860','IdSize' => '110','IdForma' => '67','IdEstilo' => NULL,'estiloY' => NULL,'diferencias' => NULL,'adicionales' => '{"kt":null,"color":null}','created_at' => NULL,'updated_at' => NULL),
  array('id' => '11','IdFactura' => '2','IdMaterial' => '630','IdEntradaMex' => '397-11','arancel' => '71049999','IdOrigen' => '2','IdFolio' => '3','cantidad' => '100.0000','precioU' => '5.0000','pesoEnUMat' => '6.3500','pesoG' => '1.2700','IdSize' => '167','IdForma' => '64','IdEstilo' => NULL,'estiloY' => NULL,'diferencias' => NULL,'adicionales' => '{"kt":null,"color":null}','created_at' => NULL,'updated_at' => NULL),
  array('id' => '12','IdFactura' => '2','IdMaterial' => '634','IdEntradaMex' => '397-12','arancel' => '71131999','IdOrigen' => '2','IdFolio' => '4','cantidad' => '27.0000','precioU' => '1.0000','pesoEnUMat' => '40.0000','pesoG' => '62.0000','IdSize' => NULL,'IdForma' => NULL,'IdEstilo' => '262','estiloY' => NULL,'diferencias' => NULL,'adicionales' => '{"kt":null,"color":null}','created_at' => NULL,'updated_at' => NULL),
  array('id' => '13','IdFactura' => '2','IdMaterial' => '640','IdEntradaMex' => '397-13','arancel' => '71023999','IdOrigen' => '2','IdFolio' => '4','cantidad' => '162.0000','precioU' => '1.0000','pesoEnUMat' => '6.2200','pesoG' => '1.2440','IdSize' => '201','IdForma' => '11','IdEstilo' => NULL,'estiloY' => NULL,'diferencias' => NULL,'adicionales' => '{"kt":null,"color":null}','created_at' => NULL,'updated_at' => NULL),
  array('id' => '14','IdFactura' => '2','IdMaterial' => '667','IdEntradaMex' => '397-14','arancel' => '71039999','IdOrigen' => '2','IdFolio' => '4','cantidad' => '27.0000','precioU' => '1.0000','pesoEnUMat' => '1.3000','pesoG' => '0.2600','IdSize' => '47','IdForma' => '5','IdEstilo' => NULL,'estiloY' => NULL,'diferencias' => NULL,'adicionales' => '{"kt":null,"color":null}','created_at' => NULL,'updated_at' => NULL)
);
DB::table('facimportsdets')->insert($facimportsdets);

    }
}
