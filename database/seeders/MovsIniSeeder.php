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
$facturas = array(
  array('id' => '1','factura' => '390','IdPedimento' => '1','fecha' => '2024-04-08','tipoCambio' => '18.1200','estatus' => 'abierto','adicionales' => NULL,'created_at' => '2026-04-22 17:06:50','updated_at' => '2026-04-22 17:06:50'),
  array('id' => '2','factura' => '391','IdPedimento' => '1','fecha' => '2024-04-10','tipoCambio' => '17.9500','estatus' => 'abierto','adicionales' => NULL,'created_at' => '2026-04-22 17:06:50','updated_at' => '2026-04-22 17:06:50'),
  array('id' => '3','factura' => '392','IdPedimento' => '1','fecha' => '2024-04-11','tipoCambio' => '18.4300','estatus' => 'abierto','adicionales' => NULL,'created_at' => '2026-04-22 17:06:50','updated_at' => '2026-04-22 17:06:50'),
  array('id' => '4','factura' => '393','IdPedimento' => '2','fecha' => '2024-04-15','tipoCambio' => '18.0100','estatus' => 'abierto','adicionales' => NULL,'created_at' => '2026-04-22 17:06:50','updated_at' => '2026-04-22 17:06:50'),
  array('id' => '5','factura' => '394','IdPedimento' => '2','fecha' => '2024-04-16','tipoCambio' => '17.8800','estatus' => 'abierto','adicionales' => NULL,'created_at' => '2026-04-22 17:06:50','updated_at' => '2026-04-22 17:06:50'),
  array('id' => '6','factura' => '395','IdPedimento' => '2','fecha' => '2024-04-17','tipoCambio' => '18.7600','estatus' => 'abierto','adicionales' => NULL,'created_at' => '2026-04-22 17:06:50','updated_at' => '2026-04-22 17:06:50'),
  array('id' => '7','factura' => '396','IdPedimento' => '2','fecha' => '2024-04-19','tipoCambio' => '18.2100','estatus' => 'abierto','adicionales' => NULL,'created_at' => '2026-04-22 17:06:50','updated_at' => '2026-04-22 17:06:50'),
  array('id' => '8','factura' => '397','IdPedimento' => '3','fecha' => '2024-04-22','tipoCambio' => '17.9900','estatus' => 'cerrado','adicionales' => '{"viadE":"406186745024","guiaA":"FEDEX","nPaq":"1"}','created_at' => '2026-04-22 17:06:50','updated_at' => '2026-04-22 18:12:33'),
  array('id' => '9','factura' => '398','IdPedimento' => '3','fecha' => '2024-04-22','tipoCambio' => '17.9900','estatus' => 'abierto','adicionales' => '{"viadE":"406186745024","guiaA":"FEDEX","nPaq":"1"}','created_at' => '2026-04-22 17:06:50','updated_at' => '2026-04-22 18:12:27'),
  array('id' => '10','factura' => '399','IdPedimento' => '3','fecha' => '2024-04-21','tipoCambio' => '18.1100','estatus' => 'abierto','adicionales' => NULL,'created_at' => '2026-04-22 17:06:50','updated_at' => '2026-04-22 17:06:50')
);
DB::table('facturas')->insert($facturas);
$facimportsdets = array(
  array('id' => '1','IdFactura' => '8','IdEntradaMex' => '397-1','IdOrigen' => '2','IdMaterial' => '634','cantidad' => '1.0000','precioU' => '1.0000','pesoEnUMat' => '0.0700','pesoG' => '0.1085','IdSize' => NULL,'IdForma' => NULL,'IdEstilo' => '819','estiloY' => NULL,'adicionales' => '{"orden":"KAREN","lote":"106","kt":"14K","color":"W"}'),
  array('id' => '2','IdFactura' => '8','IdEntradaMex' => '397-2','IdOrigen' => '2','IdMaterial' => '640','cantidad' => '38.0000','precioU' => '0.4200','pesoEnUMat' => '0.1900','pesoG' => '0.0380','IdSize' => '2','IdForma' => '20','IdEstilo' => NULL,'estiloY' => NULL,'adicionales' => '{"orden":"KAREN","lote":"106","kt":null,"color":null}'),
  array('id' => '3','IdFactura' => '8','IdEntradaMex' => '397-3','IdOrigen' => '2','IdMaterial' => '630','cantidad' => '1.0000','precioU' => '1.0000','pesoEnUMat' => '1.7000','pesoG' => '0.3400','IdSize' => '22','IdForma' => '9','IdEstilo' => NULL,'estiloY' => NULL,'adicionales' => '{"orden":"KAREN","lote":"106","kt":null,"color":null}'),
  array('id' => '4','IdFactura' => '8','IdEntradaMex' => '397-4','IdOrigen' => '2','IdMaterial' => '696','cantidad' => '2.0000','precioU' => '5.0000','pesoEnUMat' => '2.0000','pesoG' => '3.1000','IdSize' => NULL,'IdForma' => NULL,'IdEstilo' => '2938','estiloY' => NULL,'adicionales' => '{"orden":"karen","lote":"107","kt":null,"color":null}'),
  array('id' => '5','IdFactura' => '8','IdEntradaMex' => '397-5','IdOrigen' => '2','IdMaterial' => '652','cantidad' => '2.0000','precioU' => '0.5000','pesoEnUMat' => '0.0400','pesoG' => '0.0620','IdSize' => NULL,'IdForma' => NULL,'IdEstilo' => NULL,'estiloY' => NULL,'adicionales' => '{"orden":"karen","lote":"107","kt":"10K","color":"W"}'),
  array('id' => '6','IdFactura' => '8','IdEntradaMex' => '397-6','IdOrigen' => '2','IdMaterial' => '640','cantidad' => '84.0000','precioU' => '0.2900','pesoEnUMat' => '0.1300','pesoG' => '0.0260','IdSize' => '151','IdForma' => '11','IdEstilo' => NULL,'estiloY' => NULL,'adicionales' => '{"orden":"karen","lote":"107","kt":null,"color":null}'),
  array('id' => '8','IdFactura' => '8','IdEntradaMex' => '397-7','IdOrigen' => '2','IdMaterial' => '634','cantidad' => '1.0000','precioU' => '125.0000','pesoEnUMat' => '3.1000','pesoG' => '4.8050','IdSize' => NULL,'IdForma' => NULL,'IdEstilo' => '723','estiloY' => NULL,'adicionales' => '{"orden":"karen","lote":"108","kt":"10K","color":"Y"}'),
  array('id' => '9','IdFactura' => '8','IdEntradaMex' => '397-8','IdOrigen' => '2','IdMaterial' => '667','cantidad' => '1.0000','precioU' => '1.0000','pesoEnUMat' => '2.0000','pesoG' => '0.4000','IdSize' => '47','IdForma' => '1','IdEstilo' => NULL,'estiloY' => NULL,'adicionales' => '{"orden":"karen","lote":"108","kt":null,"color":null}'),
  array('id' => '10','IdFactura' => '8','IdEntradaMex' => '397-9','IdOrigen' => '2','IdMaterial' => '640','cantidad' => '36.0000','precioU' => '0.5600','pesoEnUMat' => '0.1800','pesoG' => '0.0360','IdSize' => '2','IdForma' => '20','IdEstilo' => NULL,'estiloY' => NULL,'adicionales' => '{"orden":"karen","lote":"108","kt":null,"color":null}'),
  array('id' => '11','IdFactura' => '8','IdEntradaMex' => '397-10','IdOrigen' => '1','IdMaterial' => '640','cantidad' => '100.0000','precioU' => '0.5000','pesoEnUMat' => '0.7000','pesoG' => '0.1400','IdSize' => '2','IdForma' => '20','IdEstilo' => NULL,'estiloY' => NULL,'adicionales' => '{"kt":null,"color":null,"orden":null,"lote":null}')
);
DB::table('facimportsdets')->insert($facimportsdets);
    }
}
