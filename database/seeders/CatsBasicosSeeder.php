<?php

namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CatsBasicosSeeder extends Seeder
{
    public function run()
    {
$unidads = array(
  array('id' => '1','tipo' => 'pieza','unidad' => 'pieza','abreviatura' => 'pz','factorConversion' => '1'),
  array('id' => '2','tipo' => 'longitud','unidad' => 'metro','abreviatura' => 'm','factorConversion' => '1'),
  array('id' => '3','tipo' => 'area','unidad' => 'metro cuadrado','abreviatura' => 'm²','factorConversion' => '1')
);
DB::table('unidads')->insert($unidads);

$monedas = array(
  array('id' => '1','moneda' => 'Peso Mexicano','centavos' => 'centavos','simbolo' => '$','abreviatura' => 'MXN','tipoCambio' => '1'),
  array('id' => '2','moneda' => 'Euro','centavos' => 'centavos','simbolo' => '€','abreviatura' => 'EUR','tipoCambio' => '22'),
  array('id' => '3','moneda' => 'Dólar Americano','centavos' => 'cents','simbolo' => '$','abreviatura' => 'USD','tipoCambio' => '19')
);
DB::table('monedas')->insert($monedas);

$colorables = array(
  array('id' => '1','colorable' => 'Aluminio'),
  array('id' => '2','colorable' => 'PVC'),
  array('id' => '3','colorable' => 'Acero'),
  array('id' => '4','colorable' => 'Vidrio'),
  array('id' => '5','colorable' => 'Herraje')
);
DB::table('colorables')->insert($colorables);

$colors = [
    ['id' => '5', 'IdColorable' => '4', 'IdColorHerraje' => null, 'color' => 'Transparente', 'colorHex' => '#ffffff', 'colorRgba' => 'rgba(255, 255, 255, 0)'],
    ['id' => '6', 'IdColorable' => '4', 'IdColorHerraje' => null, 'color' => 'Tintex', 'colorHex' => '#196c34', 'colorRgba' => 'rgba(25, 108, 52, 0.2)'],
    ['id' => '7', 'IdColorable' => '4', 'IdColorHerraje' => null, 'color' => 'Filtrasol', 'colorHex' => '#524128', 'colorRgba' => 'rgba(82, 65, 40, 0.3)'],
    ['id' => '9', 'IdColorable' => '3', 'IdColorHerraje' => null, 'color' => 'Negro', 'colorHex' => '#000000', 'colorRgba' => 'rgba(0, 0, 0, 1)'],
    ['id' => '10', 'IdColorable' => '5', 'IdColorHerraje' => null, 'color' => 'Blanco', 'colorHex' => '#ffffff', 'colorRgba' => 'rgba(255, 255, 255, 1)'],
    ['id' => '11', 'IdColorable' => '5', 'IdColorHerraje' => null, 'color' => 'Negro', 'colorHex' => '#000000', 'colorRgba' => 'rgba(0, 0, 0, 1)'],
    ['id' => '12', 'IdColorable' => '5', 'IdColorHerraje' => null, 'color' => 'café', 'colorHex' => '#6e3e07', 'colorRgba' => 'rgba(110, 62, 7, 1)'],
    ['id' => '1', 'IdColorable' => '1', 'IdColorHerraje' => '10', 'color' => 'Blanco', 'colorHex' => '#ffffff', 'colorRgba' => 'rgba(255, 255, 255, 1)'],
    ['id' => '2', 'IdColorable' => '1', 'IdColorHerraje' => '11', 'color' => 'Aluminio natural', 'colorHex' => '#cbcdd7', 'colorRgba' => 'rgba(203, 205, 215, 1)'],
    ['id' => '3', 'IdColorable' => '1', 'IdColorHerraje' => '11', 'color' => 'Champagne', 'colorHex' => '#ece9a2', 'colorRgba' => 'rgba(236, 233, 162, 1)'],
    ['id' => '4', 'IdColorable' => '1', 'IdColorHerraje' => '11', 'color' => 'Bronce', 'colorHex' => '#46360c', 'colorRgba' => 'rgba(70, 54, 12, 1)'],
    ['id' => '8', 'IdColorable' => '2', 'IdColorHerraje' => '10', 'color' => 'Blanco', 'colorHex' => '#ffffff', 'colorRgba' => 'rgba(255, 255, 255, 1)'],
    ['id' => '13', 'IdColorable' => '2', 'IdColorHerraje' => '11', 'color' => 'Nussbaum', 'colorHex' => '#4e2b04', 'colorRgba' => 'rgba(78, 43, 4, 1)'],
    ['id' => '14', 'IdColorable' => '2', 'IdColorHerraje' => '12', 'color' => 'Golden Oak', 'colorHex' => '#b07003', 'colorRgba' => 'rgba(176, 112, 3, 1)'],
    ['id' => '15', 'IdColorable' => '2', 'IdColorHerraje' => '11', 'color' => 'Bronce', 'colorHex' => '#342a0f', 'colorRgba' => 'rgba(52, 42, 15, 1)'],
    ['id' => '16', 'IdColorable' => '2', 'IdColorHerraje' => '12', 'color' => 'Woodec Turner Oak Malt', 'colorHex' => '#d8bf46', 'colorRgba' => 'rgba(216, 191, 70, 1)'],
    ['id' => '17', 'IdColorable' => '2', 'IdColorHerraje' => '10', 'color' => 'Silver', 'colorHex' => '#d5dcda', 'colorRgba' => 'rgba(213, 220, 218, 1)'],
    ['id' => '18', 'IdColorable' => '2', 'IdColorHerraje' => '11', 'color' => 'Mahagoni', 'colorHex' => '#af5f1d', 'colorRgba' => 'rgba(175, 95, 29, 1)'],
    ['id' => '19', 'IdColorable' => '2', 'IdColorHerraje' => '11', 'color' => 'Nusbaun', 'colorHex' => '#8e5310', 'colorRgba' => 'rgba(142, 83, 16, 1)'],
    ['id' => '20', 'IdColorable' => '2', 'IdColorHerraje' => '12', 'color' => 'Brown Dekor', 'colorHex' => '#2b1503', 'colorRgba' => 'rgba(43, 21, 3, 1)'],
    ['id' => '21', 'IdColorable' => '2', 'IdColorHerraje' => '11', 'color' => 'Woodec Sheffield Oak Alpine', 'colorHex' => '#eabc57', 'colorRgba' => 'rgba(234, 188, 87, 1)'],
    ['id' => '23', 'IdColorable' => '2', 'IdColorHerraje' => '11', 'color' => 'Woodec Sheffield Oak Concrete', 'colorHex' => '#a69164', 'colorRgba' => 'rgba(166, 145, 100, 1)'],
    ['id' => '24', 'IdColorable' => '2', 'IdColorHerraje' => '11', 'color' => 'Jet Black Matt', 'colorHex' => '#1b1d1d', 'colorRgba' => 'rgba(27, 29, 29, 1)'],
    ['id' => '25', 'IdColorable' => '2', 'IdColorHerraje' => '11', 'color' => 'Gris Antracita', 'colorHex' => '#464444', 'colorRgba' => 'rgba(70, 68, 68, 1)'],
    ['id' => '26', 'IdColorable' => '2', 'IdColorHerraje' => '11', 'color' => 'Dark Chocolatte Ceylon', 'colorHex' => '#241c05', 'colorRgba' => 'rgba(36, 28, 5, 1)'],
    ['id' => '27', 'IdColorable' => '2', 'IdColorHerraje' => '12', 'color' => 'Sheffield', 'colorHex' => '#e6cb6b', 'colorRgba' => 'rgba(230, 203, 107, 1)'],
    ['id' => '28', 'IdColorable' => '3', 'IdColorHerraje' => '10', 'color' => 'Blanco', 'colorHex' => '#ffffff', 'colorRgba' => 'rgba(255, 255, 255, 1)']
];
DB::table('colors')->insert($colors);
    }
}
