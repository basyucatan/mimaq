<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('aperturas', function (Blueprint $table) {
            $table->id();
            $table->string('apertura', 20);
            $table->text('d');
            $table->string('emoji', 10)->nullable();
        });         
        Schema::create('modelos', function (Blueprint $table) { //OXXO, ox, batiente
            $table->id();
            $table->foreignId('IdLinea')->constrained('lineas')->onDelete('cascade');
            $table->string('modelo',200)->default('nuevo Modelo');
            $table->string('foto',250)->nullable();
            $table->string('fichaTecnica',250)->nullable();
            $table->enum('estatus', ['revision', 'optimizado', 'publicado'])->default('revision');
            $table->json('jsonSvg')->nullable();
            $table->timestamps();
        });   

        Schema::create('modelosmats', function (Blueprint $table) { //perfiles, herrajes, etc.
            $table->id();
            $table->foreignId('IdModelo')->constrained('modelos')->onDelete('cascade');
            $table->boolean('principal')->default(false);
            $table->Integer('cantidad');
            $table->foreignId('IdMaterial')->nullable()->constrained('materials')->onDelete('restrict');
            $table->foreignId('IdTablaHerraje')->nullable()->constrained('tablaherrajes')->onDelete('restrict');
            $table->Integer('cantidadHerraje')->nullable();
            $table->string('diferenciador')->nullable(); //a veces se usa de manera diferente
            $table->tinyInteger('IdTipo')->unsigned()->nullable(); //Hoja, Marco, Junquillo, alma, etc.
            $table->string('posicion',2)->nullable();
            $table->string('formula')->nullable();
            $table->boolean('errFormula')->nullable();
            $table->string('dimensiones')->nullable();
            $table->double('costo')->nullable();
            $table->string('tipCosto')->nullable();
            $table->json('adicionales')->nullable();
            $table->string('obs')->nullable();    
            $table->timestamps();        
        });                        
  
    }
 

    public function down()
    {
        Schema::dropIfExists('aperturas');
        Schema::dropIfExists('modelos');
        Schema::dropIfExists('modelosmats');
    }
};
