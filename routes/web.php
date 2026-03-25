<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::view('registro', 'livewire.registro.index');

Route::middleware("auth")->group(function () {
    Route::view('users', 'livewire.users.index');
    Route::view('deptos', 'livewire.deptos.index');
    Route::view('roles', 'livewire.roles.index');
    Route::view('permisos', 'livewire.permissions.index');
    Route::view('gestionPermisos', 'livewire.gestionPermisos.index');    

    Route::view('welcome', 'livewire.welcome.index');
    Route::view('mensajes', 'livewire.mensajes.index');

    Route::view('catalogos', 'livewire.catalogos.index');
    Route::view('unidads', 'livewire.unidads.index');
    Route::view('monedas', 'livewire.monedas.index');
    Route::view('colorables', 'livewire.colorables.index');
    Route::view('colors', 'livewire.colors.index');
    Route::view('pendientes', 'livewire.pendientes.index');

    Route::view('negocios', 'livewire.negocios.index');
    Route::view('divisions', 'livewire.divisions.index');
    Route::view('divsbodegas', 'livewire.divsbodegas.index');
    Route::view('obras', 'livewire.obras.index');
    Route::view('empresas', 'livewire.empresas.index');
    Route::view('clientes', 'livewire.empresas.index', ['tipo' => 'cliente']);
    Route::view('proveedores', 'livewire.empresas.index', ['tipo' => 'proveedor']);
    
    Route::view('marcas', 'livewire.marcas.index');
    Route::view('lineas', 'livewire.lineas.index');
    Route::view('modelos', 'livewire.modelos.index');

    Route::view('fichamats', 'livewire.fichamats.index');
    Route::view('vidrios', 'livewire.vidrios.index');
    Route::view('clases', 'livewire.clases.index');
    Route::view('tablaherrajes', 'livewire.tablaherrajes.index');

    Route::view('barras', 'livewire.barras.index');
    Route::view('panels', 'livewire.panels.index');
    Route::view('tipos', 'livewire.tipos.index');

    Route::view('laminas', 'livewire.laminas.index');
    Route::view('guias', 'livewire.guias.index');

    Route::view('ocompras', 'livewire.ocompras.index');
    Route::view('recepcionoc', 'livewire.ocompras.recepcion.index');
    Route::view('ocomprasdets', 'livewire.ocompras.index');

    Route::view('kardex', 'livewire.kardex.index');
    Route::view('guias', 'livewire.guias.index');
    
    Route::view('materials', 'livewire.materials.index');
    Route::view('invfisicos', 'livewire.invfisicos.index');
    Route::view('compras', 'livewire.compras.index');
    Route::view('cortes', 'livewire.cortes.index');
    Route::view('guias', 'livewire.guias.index');
});
