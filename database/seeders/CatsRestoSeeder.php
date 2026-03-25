<?php

namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CatsRestoSeeder extends Seeder
{
    public function run()
    {
$empresas = array(
  array('id' => 1, 'IdNegocio' => 1, 'tipo' => 'cliente', 'empresa' => 'GASTOS', 'razonSocial' => 'GASTOS GENERAL', 'rfc' => 'GAS', 'direccion' => '', 'gmaps' => null, 'telefono' => '9991234567', 'email' => 'gastos@test.com', 'adicionales' => null),
  array('id' => 2, 'IdNegocio' => 1, 'tipo' => 'proveedor', 'empresa' => 'PROV. FRANCIS', 'razonSocial' => 'FRANCIS MARTIN VALLE CHAN', 'rfc' => 'VACF791204MF5', 'direccion' => 'Calle 100 #125, Centro, Mérida', 'gmaps' => null, 'telefono' => '9991234569', 'email' => 'francis@test.com', 'adicionales' => null),
  array('id' => 3, 'IdNegocio' => 1, 'tipo' => 'proveedor', 'empresa' => 'PROV. GENERAL', 'razonSocial' => '', 'rfc' => '', 'direccion' => '', 'gmaps' => null, 'telefono' => '999', 'email' => NULL, 'adicionales' => null)
);
DB::table('empresas')->insert($empresas);  
$empresasCuentas = array(
  array('id' => '1','IdEmpresa' => '2','banco' => 'Banorte','cuenta' => '0279557443','cuentaClabe' => '072910002795574433'),
);
DB::table('empresascuentas')->insert($empresasCuentas); 
$obras = array(
  array('id' => '1','IdEmpresa' => '1','obra' => 'Administración'),
  array('id' => '2','IdEmpresa' => '1','obra' => 'Operación')
);
DB::table('obras')->insert($obras); 

// Materiales
$vidrios = array(
  array('id' => '1','vidrio' => 'Vidrio flotado','grosor' => '3.00'),
  array('id' => '2','vidrio' => 'Vidrio flotado','grosor' => '4.00'),
  array('id' => '3','vidrio' => 'Vidrio flotado','grosor' => '5.00'),
  array('id' => '4','vidrio' => 'Vidrio flotado','grosor' => '6.00'),
  array('id' => '5','vidrio' => 'Vidrio flotado','grosor' => '9.00'),
  array('id' => '6','vidrio' => 'Vidrio flotado','grosor' => '12.00')
);
DB::table('vidrios')->insert($vidrios);

$barras = array(
  array('id' => '1','longitud' => '6100.00','descripcion' => 'Barra 6m'),
  array('id' => '2','longitud' => '4400.00','descripcion' => 'Barra 4.4m'),
  array('id' => '3','longitud' => '5850.00','descripcion' => 'Barra 5.85m'),
  array('id' => '4','longitud' => '6050.00','descripcion' => 'Barra 6.05m'),
  array('id' => '5','longitud' => '6500.00','descripcion' => 'Barra 6.5m')
);
DB::table('barras')->insert($barras);

$panels = array(
    array('id' => 1, 'panel' => 'Panel 3x2.60m', 'ancho' => 3.00, 'alto' => 2.60),
    array('id' => 2, 'panel' => 'Panel de 3.60x2.60m', 'ancho' => 3.60, 'alto' => 2.60),
    array('id' => 3, 'panel' => 'Panel de 2.60x1.80m', 'ancho' => 2.60, 'alto' => 1.80),
    array('id' => 4, 'panel' => 'Panel de 2.60x1.50m', 'ancho' => 2.60, 'alto' => 1.50),
    array('id' => 5, 'panel' => 'Panel de 2.60x1.20m', 'ancho' => 2.60, 'alto' => 1.20),
);
DB::table('panels')->insert($panels);

$clases = array(
  array('id' => '1','clase' => 'Perfiles','orden' => '10'),
  array('id' => '2','clase' => 'Paneles','orden' => '20'),
  array('id' => '3','clase' => 'Herrajes','orden' => '30'),
  array('id' => '4','clase' => 'Accesorios','orden' => '40'),
  array('id' => '5','clase' => 'Consumibles','orden' => '50'),
  array('id' => '6','clase' => 'Fijación','orden' => '60'),
  array('id' => '7','clase' => 'Otros','orden' => '70')
);
DB::table('clases')->insert($clases);

$marcas = array(
  array('id' => '1','marca' => 'Cuprum','IdColorable' => '1','foto' => 'cuprum.jpg'),
  array('id' => '2','marca' => 'Aluplast','IdColorable' => '2','foto' => 'aluplast.jpg'),
  array('id' => '3','marca' => 'Millet','IdColorable' => '4','foto' => 'millet.jpg'),
  array('id' => '4','marca' => 'Robin - Fernandez','IdColorable' => '5','foto' => 'robinFernandez.jpg'),
  array('id' => '5','marca' => 'DMT','IdColorable' => NULL,'foto' => 'dmt.jpg'),
  array('id' => '6','marca' => 'Herrajes Aluplast','IdColorable' => NULL,'foto' => 'roto.jpg'),
  array('id' => '7','marca' => 'Emerita','IdColorable' => NULL,'foto' => NULL),
);
DB::table('marcas')->insert($marcas);  

$lineas = array(
  array('id' => '1','IdMarca' => '1','IdColorable' => '1','linea' => 'Corrediza 3"','orden' => '10'),
  array('id' => '12','IdMarca' => '2','IdColorable' => '2','linea' => 'Corrediza 60','orden' => '10000'),
  array('id' => '16','IdMarca' => '3','IdColorable' => NULL,'linea' => 'Vidrio','orden' => '10000'),
  array('id' => '17','IdMarca' => '4','IdColorable' => NULL,'linea' => 'Herrajes y accesorios','orden' => '10000'),
  array('id' => '18','IdMarca' => '5','IdColorable' => NULL,'linea' => 'Herrajes y accesorios','orden' => '10000'),
  array('id' => '19','IdMarca' => '6','IdColorable' => NULL,'linea' => 'Herrajes Varios','orden' => '10000'),
  array('id' => '20','IdMarca' => '7','IdColorable' => '3','linea' => 'Nueva','orden' => '10000'),
);
DB::table('lineas')->insert($lineas); 

$tipos = array(
  array('id' => '1','tipo' => 'Marco','orden' => '10'),
  array('id' => '2','tipo' => 'Hoja','orden' => '20'),
  array('id' => '3','tipo' => 'Junquillo','orden' => '30'),
  array('id' => '4','tipo' => 'Refuerzo','orden' => '40')
);
DB::table('tipos')->insert($tipos);

$laminas = array(
    array('id' => 1, 'lamina' => 'EUROPEA 22', 'codigo' => 'E22', 'codigoCinta' => 'M2165', 'pesoML' => 0.96, 'calibre' => '22', 'dUtil' => 0.09),
    array('id' => 2, 'lamina' => 'EUROPEA 22 L', 'codigo' => 'E22L', 'codigoCinta' => 'M854', 'pesoML' => 0.86, 'calibre' => '22', 'dUtil' => 0.09),
    array('id' => 3, 'lamina' => 'EUROPEA 24 LIGERA', 'codigo' => 'E24L', 'codigoCinta' => '', 'pesoML' => 0.686, 'calibre' => '24', 'dUtil' => 0.09),
    array('id' => 4, 'lamina' => 'EUROPEA 24', 'codigo' => 'E24', 'codigoCinta' => 'M2157', 'pesoML' => 0.744, 'calibre' => '24', 'dUtil' => 0.09),
    array('id' => 5, 'lamina' => 'EUROPEA', 'codigo' => 'E25', 'codigoCinta' => 'GZ3555', 'pesoML' => 0.6, 'calibre' => '25', 'dUtil' => 0.09),
    array('id' => 6, 'lamina' => 'EUROPEA', 'codigo' => 'E26', 'codigoCinta' => 'Y858', 'pesoML' => 0.55, 'calibre' => '26', 'dUtil' => 0.09),
    array('id' => 7, 'lamina' => 'EUROPEA MULTIPERFORADA', 'codigo' => 'E24M', 'codigoCinta' => '', 'pesoML' => 0.744, 'calibre' => '24', 'dUtil' => 0.07),
    array('id' => 8, 'lamina' => 'TABLETA MULTIPERFORADA 22', 'codigo' => 'T22M', 'codigoCinta' => 'M2166', 'pesoML' => 0.84, 'calibre' => '22', 'dUtil' => 0.07),
    array('id' => 9, 'lamina' => 'TABLETA 24', 'codigo' => 'T24', 'codigoCinta' => '', 'pesoML' => 0.684, 'calibre' => '24', 'dUtil' => 0.07),
    array('id' => 10, 'lamina' => 'TABLETA 22', 'codigo' => 'T22', 'codigoCinta' => 'GZ3555', 'pesoML' => 0.82, 'calibre' => '22', 'dUtil' => 0.07),
    array('id' => 11, 'lamina' => 'SECCION TUBULAR IMPULSO KIT', 'codigo' => 'TUB', 'codigoCinta' => '', 'pesoML' => 0, 'calibre' => '18', 'dUtil' => 0.085)
);
DB::table('laminas')->insert($laminas);

$guias = array(
    array('id' => 1, 'guia' => 'CARRILERA TIPO EUROPEA C-16 5CM', 'idMaterial' => null),
    array('id' => 2, 'guia' => 'CARRILERA TIPO PLANA C-16 3.05CM', 'idMaterial' => null),
    array('id' => 3, 'guia' => 'CARRILERA TIPO MECANISMO C-16 7CM 6', 'idMaterial' => null),
    array('id' => 4, 'guia' => 'CARRILERA TIPO EUROPEA C-14 10 CM', 'idMaterial' => null),
    array('id' => 5, 'guia' => 'CARRILERA TIPO PLANA C-14 10 CM', 'idMaterial' => null)
);
DB::table('guias')->insert($guias);


    }
}
