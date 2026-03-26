<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('clases', function (Blueprint $table) { // perfiles, cristales, herrajes, accesorios etc.
            $table->id();
            $table->string('clase',50);
            $table->tinyInteger('orden')->unsigned()->default(250); 
        });                                          
        Schema::create('marcas', function (Blueprint $table) { //cuprum, aluplast, etc.
            $table->id();
            $table->string('marca',50);
            $table->foreignId('IdColorable')->nullable()->constrained('colorables')->onDelete('cascade');
            $table->string('foto',250)->nullable();
        });         
        Schema::create('lineas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('IdMarca')->constrained('marcas')->onDelete('cascade');
            $table->foreignId('IdDivision')->nullable()->constrained('divisions')->onDelete('set null');
            $table->foreignId('IdColorable')->nullable()->constrained('colorables')->onDelete('set null');
            $table->string('linea', 200)->default('nueva línea');
            $table->integer('orden')->unsigned()->default(10000);
        });      
        Schema::create('tipos', function (Blueprint $table) { //Hoja, Marco, Junquillo, refuerzo, etc.
            $table->id();
            $table->string('tipo',30);
            $table->tinyInteger('orden')->unsigned()->default(200);
        }); 
        Schema::create('vidrios', function (Blueprint $table) { //transparente 6mm, tintex 3mm
            $table->id();
            $table->string('vidrio',50);
            $table->float('grosor'); //en mm
        });                
        Schema::create('barras', function (Blueprint $table) {
            $table->id();
            $table->float('longitud');
            $table->string('descripcion',20)->nullable();
        });
        Schema::create('panels', function (Blueprint $table) {
            $table->id();
            $table->string('panel',30);
            $table->float('ancho');
            $table->float('alto');
        });    
        Schema::create('laminas', function (Blueprint $table) {
            $table->id();
            $table->string('lamina');
            $table->string('codigo',30);
            $table->string('codigoCinta',30);
            $table->float('pesoML');
            $table->string('calibre',10);
            $table->float('dUtil');
        });
        Schema::create('guias', function (Blueprint $table) {
            $table->id();
            $table->string('guia');
            $table->bigInteger('IdMaterial')->nullable();
        });                      
        Schema::create('materials', function (Blueprint $table) { // chambrana, zoclo, cabezal, etc.
            $table->id();
            $table->foreignId('IdClase')->constrained('clases')->onDelete('cascade');
            $table->foreignId('IdLinea')->nullable()->constrained('lineas')->onDelete('cascade');
            $table->foreignId('IdUnidad')->constrained('unidads')->onDelete('restrict');
            $table->tinyInteger('IdTipo')->nullable()->unsigned()->nullable(); //Hoja, Marco, Junquillo, alma, etc.            
            $table->string('referencia',30)->nullable()->unique();
            $table->string('material',250);
            $table->string('foto')->nullable()->default('101.jpg');
            $table->decimal('KgxMetro', 8, 4)->nullable();
            $table->decimal('rendimiento', 8, 4)->nullable();
            $table->foreignId('IdUnidadRend')->nullable()->constrained('unidads')->onDelete('set null');
            $table->json('adicionales')->nullable();
        });     
        Schema::create('materialscostos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('IdMaterial')->constrained('materials')->onDelete('cascade');
            $table->string('referencia',30)->nullable()->unique();
            $table->json('ubicacion')->nullable();
            $table->foreignId('IdMoneda')->constrained('monedas')->onDelete('cascade')->default(1);
            $table->foreignId('IdColor')->nullable()->constrained('colors')->onDelete('set null');
            $table->foreignId('IdVidrio')->nullable()->constrained('vidrios')->onDelete('set null');
            $table->foreignId('IdBarra')->nullable()->constrained('barras')->onDelete('set null');
            $table->foreignId('IdPanel')->nullable()->constrained('panels')->onDelete('set null');
            $table->enum('direccion', ['Izquierda','Derecha', 'otro'])->nullable();
            $table->double('costo')->nullable();
            $table->timestamps();
        });   
        Schema::create('reglas', function (Blueprint $table) { //para vincular dos materiales
            $table->id();
            $table->foreignId('IdLinea')->nullable()->constrained('lineas')->onDelete('set null');
            $table->foreignId('IdMaterial')->constrained('materials')->onDelete('cascade');
            $table->foreignId('IdTipo')->nullable();
            $table->foreignId('IdMatRelacion')->constrained('materials')->onDelete('restrict');
            $table->enum('baseCalculo', ['unidad', 'longitud', 'area','perimetro'])->default('unidad');
            $table->enum('efectoCalculo', ['unidad', 'longitud', 'area'])->default('unidad');
            $table->tinyInteger('grosorVidrio')->nullable();
            $table->decimal('factor',8,4,true);
            $table->decimal('descuento',8,0)->nullable();
        });          
        Schema::create('tablaherrajes', function (Blueprint $table) { //OXXO, ox, batiente
            $table->id();
            $table->foreignId('IdLinea')->constrained('lineas')->onDelete('cascade');
            $table->string('tablaHerraje',50);
            $table->string('fichaTecnica',250)->nullable();
            $table->json('adicionales')->nullable();
        });   
        Schema::create('tablaherrajesdets', function (Blueprint $table) { //perfiles, herrajes, etc.
            $table->id();
            $table->foreignId('IdTablaHerraje')->constrained('tablaherrajes')->onDelete('cascade');
            $table->Integer('cantidad');
            $table->foreignId('IdMaterial')->constrained('materials')->onDelete('cascade');
            $table->double('rangoMenor')->nullable();
            $table->double('rangoMayor')->nullable();
            $table->double('factorExtra')->nullable();
            $table->json('adicionales')->nullable();
        });                       
  
    }
 

    public function down()
    {
        Schema::dropIfExists('clases');
        Schema::dropIfExists('marcas');
        Schema::dropIfExists('lineas');
        Schema::dropIfExists('tipos');
        Schema::dropIfExists('vidrios');
        Schema::dropIfExists('barras');
        Schema::dropIfExists('panels');
        Schema::dropIfExists('laminas');
        Schema::dropIfExists('guias');
        Schema::dropIfExists('materials');
        Schema::dropIfExists('materialscostos');
        Schema::dropIfExists('reglas');
        Schema::dropIfExists('tablaherrajes');
        Schema::dropIfExists('tablaherrajesdets');
    }
};
