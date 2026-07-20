<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\PruebaRequestController;

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

    Route::view('temp', 'livewire.bandejas.index');

    Route::view('welcome', 'livewire.welcome.index');
    Route::view('mensajes', 'livewire.mensajes.index');
    Route::view('catalogos', 'livewire.catalogos.index');
    Route::view('homologacion', 'livewire.homologacion.index');
    
    Route::view('arancels', 'livewire.arancels.index');
    Route::view('clases', 'livewire.clases.index');
    Route::view('deptos', 'livewire.deptos.index');
    Route::view('empleados', 'livewire.empleados.index');
    Route::view('estilos', 'livewire.estilos.index');
    Route::view('formas', 'livewire.formas.index');
    Route::view('materials', 'livewire.materials.index');
    Route::view('origens', 'livewire.origens.index');
    Route::view('permisos', 'livewire.permisos.index');
    Route::view('sizes', 'livewire.sizes.index');
    Route::view('tipos', 'livewire.tipos.index');
    Route::view('unidads', 'livewire.unidads.index');

    Route::view('facimports', 'livewire.facimports.index');
    Route::view('facexports', 'livewire.facexports.index');
    Route::view('recibirimports', 'livewire.recibirimports.index');
    Route::view('clientes', 'livewire.clientes.index');
    Route::view('ordens', 'livewire.ordens.index');
    Route::view('lotes', 'livewire.lotes.index');
    Route::view('kardex', 'livewire.kardex.index');
    Route::view('bandejas', 'livewire.bandejas.index');
    Route::view('adminfolios', 'livewire.adminfolios.index');

    Route::view('fotosestilos', 'livewire.estilos.fotosindex');
    Route::view('goaduanas', 'livewire.goaduanas.index');


});
