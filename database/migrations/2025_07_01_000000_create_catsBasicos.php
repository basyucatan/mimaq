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
            $table->string('unidad',20)->unique();
            $table->string('unidadI',20);
            $table->float('factorC',8,4)->default(1);
            $table->enum('grupo', ['peso', 'joyería', 'otro'])->default('otro');
            $table->json('adicionales')->nullable();
            $table->integer('IdAccess')->nullable();
        }); 
        Schema::create('sizes', function (Blueprint $table) {
            $table->id();
            $table->string('size',20)->unique();
            $table->bigInteger('IdAccess')->nullable();
        });
        Schema::create('formas', function (Blueprint $table) {
            $table->id();
            $table->string('forma',20)->unique();
            $table->bigInteger('IdAccess')->nullable();
        });
        Schema::create('origens', function (Blueprint $table) {
            $table->id();
            $table->string('origen',20)->unique();
            $table->bigInteger('IdAccess')->nullable();
        });
        Schema::create('permisos', function (Blueprint $table) {
            $table->id();
            $table->date('fecha');
            $table->string('permiso',20);
            $table->string('IdAccess',30)->nullable();
        });        
        Schema::create('arancels', function (Blueprint $table) {
            $table->id();
            $table->string('arancel',20)->unique();
            $table->string('arancelUSA',20);
            $table->string('descripcion',100)->unique();
            $table->foreignId('IdPermiso')->constrained('permisos')->cascadeOnDelete();
        });  
        Schema::create('tipos', function (Blueprint $table) { //casting, diamante, piedra
            $table->id();
            $table->string('tipo',20)->unique();
            $table->json('adicionales')->nullable();
        });                   
        Schema::create('clases', function (Blueprint $table) {
            $table->id();
            $table->string('IdAccess',20)->nullable()->unique();
            $table->foreignId('IdTipo')->constrained('tipos')->cascadeOnDelete();
            $table->foreignId('IdArancel')->constrained('arancels')->restrictOnDelete();
            $table->string('clase',50);
            $table->string('claseI',50);
            $table->json('adicionales')->nullable();
        });  
        Schema::create('materials', function (Blueprint $table) {
            $table->id();
            $table->integer('IdAccess')->nullable();
            $table->foreignId('IdClase')->constrained('clases')->cascadeOnDelete();
            $table->foreignId('IdUnidad')->constrained('unidads')->cascadeOnDelete();
            $table->foreignId('IdUnidadP')->constrained('unidads')->cascadeOnDelete();
            $table->string('material',50)->unique();
            $table->string('materialI',50);
            $table->string('materialFiscal',50);
            $table->string('abreviatura',10)->nullable()->unique();
            $table->json('adicionales')->nullable();
        });          
        Schema::create('estilos', function (Blueprint $table) {
            $table->id();
            $table->string('estilo',20)->unique();
            $table->foreignId('IdClase')->constrained('clases')->cascadeOnDelete();
            $table->string('foto')->nullable();
        });           
        Schema::create('estilosDets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('IdEstilo')->constrained('estilos')->cascadeOnDelete();
            $table->float('cantidad',10,3);
            $table->foreignId('IdMaterial')->nullable()->constrained('materials')->nullOnDelete();
            $table->foreignId('IdSize')->nullable()->constrained('sizes')->nullOnDelete();
            $table->foreignId('IdForma')->nullable()->constrained('formas')->nullOnDelete();
            $table->string('IdEstiloY',30)->nullable();
            $table->json('adicionales')->nullable();
        });  
        Schema::create('clientes', function (Blueprint $table) {
            $table->id();
            $table->string('cliente',50);
            $table->json('adicionales')->nullable();
        });                              
        Schema::create('empleados', function (Blueprint $table) {
            $table->id();
            $table->string('empleado',50);
            $table->json('adicionales')->nullable();
        }); 
    }

    public function down()
    {
    }
};

