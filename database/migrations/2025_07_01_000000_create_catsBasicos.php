<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('roles', function (Blueprint $table) {
                $table->tinyInteger('nivel')->default(1);
            });         
        Schema::create('unidads', function (Blueprint $table) {
            $table->id();
            $table->string('unidad', 50);
            $table->string('abreviatura', 10);
            $table->enum('tipo', ['pieza','longitud','area','peso','tiempo', 'otro']);
            $table->double('factorConversion')->default(1);
        });     

        Schema::create('monedas', function (Blueprint $table) {
            $table->id();
            $table->string('moneda', 20);
            $table->string('centavos', 20);
            $table->string('simbolo', 1);
            $table->string('abreviatura', 5);
            $table->double('tipoCambio');
        });    
        Schema::create('colorables', function (Blueprint $table) { //'Aluminio', 'PVC', 'Acero',
            $table->id();
            $table->string('colorable',30);
            // $table->enum('tipo', ['Perfil', 'Vidrio', 'Herraje', 'Otro']);
        });         
        Schema::create('colors', function (Blueprint $table) {
            $table->id();
            $table->foreignId('IdColorable')->constrained('colorables')->onDelete('cascade');
            $table->foreignId('IdColorHerraje')->nullable()->constrained('colors')->onDelete('set null');
            $table->string('color', 50);
            $table->string('colorHex', 7); 
            $table->string('colorRgba', 25);
        });
        Schema::create('pendientes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('IdUserEmite')->constrained('users')->onDelete('cascade');
            $table->foreignId('IdUserRecibe')->nullable()->constrained('users')->onDelete('cascade');
            $table->enum('urgencia', ['Ayer', 'Hoy', 'Semana', 'Mes', 'Otro'])->default('Semana');
            $table->string('titulo',100);
            $table->text('descripcion');
            $table->date('fechaEmision');
            $table->timestamp('fechaProg')->nullable();
            $table->timestamp('fechaCumple')->nullable();
            $table->json('adicionales')->nullable();
        }); 

    }
 

    public function down()
    {
        Schema::dropIfExists('negocios');
        Schema::dropIfExists('unidads');
        Schema::dropIfExists('monedas');
        Schema::dropIfExists('colorables');
        Schema::dropIfExists('colors');
        Schema::dropIfExists('pendientes');
    }
};
