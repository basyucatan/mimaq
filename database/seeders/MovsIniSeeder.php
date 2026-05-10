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
  array('id' => '1','IdCliente' => '1','orden' => 'W400','estatus' => 'abierto','fechaVen' => '2026-05-13','alertas' => NULL,'adicionales' => NULL),
  array('id' => '2','IdCliente' => '2','orden' => '1561','estatus' => 'abierto','fechaVen' => '2026-05-13','alertas' => NULL,'adicionales' => NULL),
  array('id' => '3','IdCliente' => '3','orden' => '5151','estatus' => 'abierto','fechaVen' => '2026-05-14','alertas' => NULL,'adicionales' => NULL),
  array('id' => '4','IdCliente' => '4','orden' => 'DSF624','estatus' => 'abierto','fechaVen' => '2026-05-14','alertas' => NULL,'adicionales' => NULL)
);
DB::table('ordens')->insert($ordens);
$lotes = array(
  array('id' => '1','lote' => '141561','IdOrden' => '1','alertas' => NULL,'adicionales' => NULL),
  array('id' => '2','lote' => '126546','IdOrden' => '2','alertas' => NULL,'adicionales' => NULL),
  array('id' => '3','lote' => '125616','IdOrden' => '3','alertas' => NULL,'adicionales' => NULL),
  array('id' => '4','lote' => '65596','IdOrden' => '4','alertas' => NULL,'adicionales' => NULL)
);
DB::table('lotes')->insert($lotes);
$folios = array(
  array('id' => '1','IdLote' => '1','IdEstilo' => '723','productoFinal' => NULL,'jobStyle' => NULL,'cantidad' => '1','totalBandejas' => '1','precioU' => '0.0000','fechaVen' => '2026-05-13','cantidadSurtida' => '0.0000','estatus' => 'abierto','adicionales' => NULL,'created_at' => NULL,'updated_at' => NULL),
  array('id' => '2','IdLote' => '2','IdEstilo' => '2881','productoFinal' => NULL,'jobStyle' => NULL,'cantidad' => '2','totalBandejas' => '1','precioU' => '0.0000','fechaVen' => '2026-05-13','cantidadSurtida' => '0.0000','estatus' => 'abierto','adicionales' => NULL,'created_at' => NULL,'updated_at' => NULL),
  array('id' => '3','IdLote' => '3','IdEstilo' => '1303','productoFinal' => NULL,'jobStyle' => NULL,'cantidad' => '100','totalBandejas' => '1','precioU' => '0.0000','fechaVen' => '2026-05-14','cantidadSurtida' => '0.0000','estatus' => 'abierto','adicionales' => NULL,'created_at' => NULL,'updated_at' => NULL),
  array('id' => '4','IdLote' => '4','IdEstilo' => '262','productoFinal' => NULL,'jobStyle' => NULL,'cantidad' => '20','totalBandejas' => '1','precioU' => '0.0000','fechaVen' => '2026-05-14','cantidadSurtida' => '0.0000','estatus' => 'abierto','adicionales' => NULL,'created_at' => NULL,'updated_at' => NULL)
);
DB::table('folios')->insert($folios);
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
  array('id' => '8','factura' => '397','IdPedimento' => '3','fecha' => '2024-04-22','tipoCambio' => '17.9900','estatus' => 'abierto','adicionales' => '{"viadE":"406186745024","guiaA":"FEDEX","nPaq":"1"}','created_at' => '2026-04-22 17:06:50','updated_at' => '2026-04-22 18:12:33'),
  array('id' => '9','factura' => '398','IdPedimento' => '3','fecha' => '2024-04-22','tipoCambio' => '17.9900','estatus' => 'abierto','adicionales' => '{"viadE":"406186745024","guiaA":"FEDEX","nPaq":"1"}','created_at' => '2026-04-22 17:06:50','updated_at' => '2026-04-22 18:12:27'),
  array('id' => '10','factura' => '399','IdPedimento' => '3','fecha' => '2024-04-21','tipoCambio' => '18.1100','estatus' => 'abierto','adicionales' => NULL,'created_at' => '2026-04-22 17:06:50','updated_at' => '2026-04-22 17:06:50')
);
DB::table('facturas')->insert($facturas);
$facimportsdets = array(
  array('id' => '1','IdFactura' => '8','IdMaterial' => '634','IdEntradaMex' => '397-1','arancel' => '71131999','IdOrigen' => '2','IdFolio' => '1','cantidad' => '1.0000','precioU' => '1.0000','pesoEnUMat' => '0.8000','pesoG' => '1.2400','IdSize' => NULL,'IdForma' => NULL,'IdEstilo' => '723','estiloY' => NULL,'adicionales' => '{"kt":"14K","color":"Y"}','created_at' => NULL,'updated_at' => NULL),
  array('id' => '2','IdFactura' => '8','IdMaterial' => '656','IdEntradaMex' => '397-2','arancel' => '71049999','IdOrigen' => '2','IdFolio' => '1','cantidad' => '1.0000','precioU' => '1.0000','pesoEnUMat' => '0.5000','pesoG' => '0.1000','IdSize' => '199','IdForma' => '2','IdEstilo' => NULL,'estiloY' => NULL,'adicionales' => '{"kt":null,"color":null}','created_at' => NULL,'updated_at' => NULL),
  array('id' => '3','IdFactura' => '8','IdMaterial' => '640','IdEntradaMex' => '397-3','arancel' => '71023999','IdOrigen' => '2','IdFolio' => '1','cantidad' => '36.0000','precioU' => '1.0000','pesoEnUMat' => '8.0000','pesoG' => '1.6000','IdSize' => '98','IdForma' => '11','IdEstilo' => NULL,'estiloY' => NULL,'adicionales' => '{"kt":null,"color":null}','created_at' => NULL,'updated_at' => NULL),
  array('id' => '4','IdFactura' => '8','IdMaterial' => '640','IdEntradaMex' => '397-4','arancel' => '71023999','IdOrigen' => '4','IdFolio' => NULL,'cantidad' => '600.0000','precioU' => '1.0000','pesoEnUMat' => '32.0000','pesoG' => '6.4000','IdSize' => '98','IdForma' => '11','IdEstilo' => NULL,'estiloY' => NULL,'adicionales' => '{"kt":null,"color":null}','created_at' => NULL,'updated_at' => NULL),
  array('id' => '5','IdFactura' => '8','IdMaterial' => '675','IdEntradaMex' => '397-5','arancel' => '71131199','IdOrigen' => '2','IdFolio' => '2','cantidad' => '2.0000','precioU' => '1.0000','pesoEnUMat' => '3.0000','pesoG' => '4.6500','IdSize' => NULL,'IdForma' => NULL,'IdEstilo' => '2881','estiloY' => NULL,'adicionales' => '{"kt":null,"color":null}','created_at' => NULL,'updated_at' => NULL),
  array('id' => '6','IdFactura' => '8','IdMaterial' => '640','IdEntradaMex' => '397-6','arancel' => '71023999','IdOrigen' => '1','IdFolio' => '2','cantidad' => '4.0000','precioU' => '1.0000','pesoEnUMat' => '0.3000','pesoG' => '0.0600','IdSize' => '1','IdForma' => '5','IdEstilo' => NULL,'estiloY' => NULL,'adicionales' => '{"kt":null,"color":null}','created_at' => NULL,'updated_at' => NULL),
  array('id' => '7','IdFactura' => '8','IdMaterial' => '643','IdEntradaMex' => '397-7','arancel' => '71039999','IdOrigen' => '2','IdFolio' => '2','cantidad' => '2.0000','precioU' => '2.0000','pesoEnUMat' => '0.2500','pesoG' => '0.0500','IdSize' => '45','IdForma' => '8','IdEstilo' => NULL,'estiloY' => NULL,'adicionales' => '{"kt":null,"color":null}','created_at' => NULL,'updated_at' => NULL),
  array('id' => '8','IdFactura' => '8','IdMaterial' => '634','IdEntradaMex' => '397-8','arancel' => '71131999','IdOrigen' => '2','IdFolio' => '3','cantidad' => '100.0000','precioU' => '0.0000','pesoEnUMat' => '0.0000','pesoG' => '0.0000','IdSize' => NULL,'IdForma' => NULL,'IdEstilo' => '1303','estiloY' => NULL,'adicionales' => '{"kt":"10K","color":"Y"}','created_at' => NULL,'updated_at' => NULL),
  array('id' => '9','IdFactura' => '8','IdMaterial' => '640','IdEntradaMex' => '397-9','arancel' => '71023999','IdOrigen' => '2','IdFolio' => '3','cantidad' => '1400.0000','precioU' => '1.0000','pesoEnUMat' => '150.0000','pesoG' => '30.0000','IdSize' => '98','IdForma' => '8','IdEstilo' => NULL,'estiloY' => NULL,'adicionales' => '{"kt":null,"color":null}','created_at' => NULL,'updated_at' => NULL),
  array('id' => '10','IdFactura' => '8','IdMaterial' => '688','IdEntradaMex' => '397-10','arancel' => '71049999','IdOrigen' => '2','IdFolio' => '3','cantidad' => '100.0000','precioU' => '2.3000','pesoEnUMat' => '6.4300','pesoG' => '1.2860','IdSize' => '110','IdForma' => '67','IdEstilo' => NULL,'estiloY' => NULL,'adicionales' => '{"kt":null,"color":null}','created_at' => NULL,'updated_at' => NULL),
  array('id' => '11','IdFactura' => '8','IdMaterial' => '630','IdEntradaMex' => '397-11','arancel' => '71049999','IdOrigen' => '2','IdFolio' => '3','cantidad' => '100.0000','precioU' => '5.0000','pesoEnUMat' => '6.3500','pesoG' => '1.2700','IdSize' => '167','IdForma' => '64','IdEstilo' => NULL,'estiloY' => NULL,'adicionales' => '{"kt":null,"color":null}','created_at' => NULL,'updated_at' => NULL),
  array('id' => '12','IdFactura' => '8','IdMaterial' => '634','IdEntradaMex' => '397-12','arancel' => '71131999','IdOrigen' => '2','IdFolio' => '4','cantidad' => '20.0000','precioU' => '2.0000','pesoEnUMat' => '40.0000','pesoG' => '62.0000','IdSize' => NULL,'IdForma' => NULL,'IdEstilo' => '262','estiloY' => NULL,'adicionales' => '{"kt":"10K","color":"Y"}','created_at' => NULL,'updated_at' => NULL),
  array('id' => '13','IdFactura' => '8','IdMaterial' => '640','IdEntradaMex' => '397-13','arancel' => '71023999','IdOrigen' => '2','IdFolio' => '4','cantidad' => '120.0000','precioU' => '1.0000','pesoEnUMat' => '6.2200','pesoG' => '1.2440','IdSize' => '98','IdForma' => '8','IdEstilo' => NULL,'estiloY' => NULL,'adicionales' => '{"kt":null,"color":null}','created_at' => NULL,'updated_at' => NULL),
  array('id' => '14','IdFactura' => '8','IdMaterial' => '667','IdEntradaMex' => '397-14','arancel' => '71039999','IdOrigen' => '2','IdFolio' => '4','cantidad' => '20.0000','precioU' => '1.0000','pesoEnUMat' => '1.3000','pesoG' => '0.2600','IdSize' => '48','IdForma' => '15','IdEstilo' => NULL,'estiloY' => NULL,'adicionales' => '{"kt":null,"color":null}','created_at' => NULL,'updated_at' => NULL)
);
DB::table('facimportsdets')->insert($facimportsdets);

    }
}
