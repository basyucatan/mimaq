<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {        
        Schema::create('presupuestos', function (Blueprint $table) { 
            $table->id();
            $table->foreignId('IdCliente')->constrained('empresas')->onDelete('restrict');
            $table->foreignId('IdObra')->nullable()->constrained('obras')->nullOnDelete();
            $table->foreignId('IdColorable')->nullable()->constrained('colorables')->onDelete('cascade');
            $table->foreignId('IdColorPerfil')->nullable()->constrained('colors')->nullOnDelete();
            $table->foreignId('IdVidrio')->nullable()->constrained('vidrios')->nullOnDelete();
            $table->foreignId('IdColorVidrio')->nullable()->constrained('colors')->nullOnDelete();
            $table->date('fecha');
            $table->float('porDescuento')->nullable()->default(0);
            $table->float('porRecargo')->nullable()->default(0);
            $table->text('descripcion')->nullable();
            $table->string('obs')->nullable();
            $table->enum('estatus', ['edicion', 'aprobado', 'comprado', 
                'cortado', 'ensamblado', 'producido', 'instalado', 'terminado']);
            $table->json('adicionales')->nullable();
            $table->timestamps();
        });  
        
        Schema::create('modelospre', function (Blueprint $table) {
            $table->id();
            $table->smallInteger('consecutivo');
            $table->string('foto')->nullable();
            $table->foreignId('IdPresupuesto')->constrained('presupuestos')->onDelete('cascade');
            $table->foreignId('IdModelo')->constrained('modelos')->onDelete('restrict');
            $table->foreignId('IdDivision')->nullable()->constrained('divisions')->onDelete('set null');
            $table->foreignId('IdColorable')->nullable()->constrained('colorables')->onDelete('cascade');
            $table->foreignId('IdColorPerfil')->nullable()->constrained('colors')->nullOnDelete();
            $table->foreignId('IdVidrio')->nullable()->constrained('vidrios')->nullOnDelete();
            $table->foreignId('IdColorVidrio')->nullable()->constrained('colors')->nullOnDelete();
            $table->foreignId('IdLamina')->nullable()->constrained('laminas')->nullOnDelete();
            $table->foreignId('IdGuia')->nullable()->constrained('guias')->nullOnDelete();
            $table->enum('tipo', ['Puerta', 'Ventana', 'Cortina', 'Herreria', 'otro'])->default('ventana');
            $table->string('descripcion')->nullable();
            $table->string('ubicacion',50)->nullable();
            $table->float('cantidad')->default(1);
            $table->float('ancho')->default(1000);
            $table->float('alto')->default(1000);
            $table->enum('direccion', ['Izquierda','Derecha', 'otro'])->nullable();
            $table->boolean('precioManual')->default(false);
            $table->boolean('actualizado')->default(false);
            $table->double('precioU')->nullable();
            $table->double('costoU')->nullable();
            $table->float('porDescuento')->nullable()->default(0);
            $table->float('porRecargo')->nullable()->default(0);            
            $table->json('costeo')->nullable();
            $table->json('divisiones')->nullable();
            $table->json('adicionales')->nullable();
            $table->longText('svg')->nullable();
            $table->timestamps();
        });

        Schema::create('modelopremats', function (Blueprint $table) { 
            $table->id();
            $table->foreignId('IdModeloPre')->constrained('modelospre')->onDelete('cascade');
            $table->foreignId('IdMaterialCosto')->nullable()->constrained('materialscostos')->nullOnDelete();
            $table->foreignId('IdTablaHerraje')->nullable()->constrained('tablaherrajes')->onDelete('restrict');
            $table->Integer('cantidadHerraje')->nullable();
            $table->boolean('principal')->default(false);
            $table->Integer('cantidad');
            $table->foreignId('IdMaterial')->nullable()->constrained('materials')->onDelete('restrict');
            $table->string('diferenciador')->nullable(); //a veces se usa de manera diferente
            $table->tinyInteger('IdTipo')->unsigned()->nullable(); //Hoja, Marco, Junquillo, alma, etc.
            $table->string('posicion',2)->nullable();
            $table->string('formula')->nullable();
            $table->boolean('errFormula')->nullable();
            $table->string('dimensiones')->nullable();
            $table->double('costo')->nullable();
            $table->string('tipCosto')->nullable();
            $table->string('obs')->nullable();
            $table->json('adicionales')->nullable();
            $table->timestamps();
        });             
        Schema::create('movinventarios', function (Blueprint $table) {
            $table->id();
            $table->foreignId('IdUserOri')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('IdUserDes')->nullable()->constrained('users')->nullOnDelete();
            $table->enum('tipo', [
                'Compra', 'InvFisico', 'Corte', 'Traspaso', 
                'Ensamble', 'Entrega', 'Devolucion']);
            $table->foreignId('IdMatCosto')->constrained('materialscostos')->onDelete('cascade');
            $table->foreignId('IdDeptoOri')->nullable()->constrained('deptos')->nullOnDelete();
            $table->foreignId('IdDeptoDes')->nullable()->constrained('deptos')->nullOnDelete();
            $table->dateTime('fechaH')->useCurrent();
            $table->decimal('cantidad', 12, 3)->default(0);
            $table->decimal('valorU', 12, 3)->default(0);
            $table->string('dimensiones')->nullable();
            $table->json('adicionales')->nullable();
            $table->timestamps();
            $table->index(['IdMatCosto', 'IdDeptoOri', 'IdDeptoDes', 'fechaH']);
        });
        Schema::create('ocompras', function (Blueprint $table) { 
            $table->id();
            $table->foreignId('IdDivision')->constrained('divisions')->onDelete('restrict');
            $table->foreignId('IdProveedor')->constrained('empresas')->onDelete('restrict');
            $table->foreignId('IdCuentaProv')->nullable()->constrained('empresascuentas')->onDelete('restrict');
            $table->foreignId('IdUser')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('IdAprobo')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('IdRecibio')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('IdObra')->nullable()->constrained('obras')->nullOnDelete();
            $table->bigInteger('IdCondPago')->default(1);
            $table->bigInteger('IdCondFlete')->default(1);
            $table->dateTime('fechaHSol');
            $table->dateTime('fechaRec')->nullable();
            $table->dateTime('fechaPago')->nullable();
            $table->dateTime('fechaComp')->nullable();
            $table->decimal('porDescuento', 4, 2)->default(0);
            $table->decimal('subtotal', 14, 2)->default(0);
            $table->string('concepto')->nullable();
            $table->enum('estatus', ['edicion', 'aprobado', 'comprado','recibido', 'completado']);
            $table->json('adicionales')->nullable();
            $table->timestamps();
        });  
        Schema::create('ocomprasdets', function (Blueprint $table) { 
            $table->id();
            $table->foreignId('IdOCompra')->constrained('ocompras')->onDelete('cascade');
            $table->foreignId('IdMatCosto')->constrained('materialscostos')->onDelete('restrict');
            $table->float('cantidad')->nullable()->default(0);
            $table->float('costoU')->nullable()->default(0);
        });                  
        Schema::create('traspasos', function (Blueprint $table) {
            $table->id();
            $table->enum('tipo', [
                'Compra', 'InvFisico', 'Corte', 'Traspaso', 
                'Ensamble', 'Entrega', 'Devolucion']);
            $table->foreignId('IdUserOri')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('IdUserDes')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('IdDeptoOri')->nullable()->constrained('deptos')->nullOnDelete();
            $table->foreignId('IdDeptoDes')->nullable()->constrained('deptos')->nullOnDelete();
            $table->dateTime('fecha')->useCurrent();
            $table->enum('estatus', ['Abierto', 'Cerrado', 'Cancelado'])->default('Abierto');
            $table->json('adicionales')->nullable();
            $table->timestamps();
            $table->index(['tipo', 'estatus']);
        });       
        Schema::create('traspasosdets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('IdTraspaso')->constrained('traspasos')->cascadeOnDelete();
            $table->foreignId('IdMatCosto')->nullable()->constrained('materialscostos')->nullOnDelete();
            $table->decimal('cantidad', 12, 3)->default(0);
            $table->decimal('valorU', 12, 3)->default(0);
            $table->string('dimensiones', 100)->nullable();
            $table->json('adicionales')->nullable();
        });
        Schema::create('presucortes', function (Blueprint $table) {
            $table->id();
            $table->enum('tipo', ['perfil', 'vidrio'])->default('perfil');
            $table->foreignId('IdPresupuesto')->constrained('presupuestos')->cascadeOnDelete();
            $table->foreignId('IdMaterialCosto')->nullable()->constrained('materialscostos')->nullOnDelete();
            $table->decimal('cantidad', 12, 3)->default(0);
            $table->json('adicionales')->nullable();
        });        

    }
    public function down()
    {
        Schema::dropIfExists('presupuestos');
        Schema::dropIfExists('modelosPre');
        Schema::dropIfExists('modelopremats');
        Schema::dropIfExists('movinventarios');        
        Schema::dropIfExists('traspasos');        
        Schema::dropIfExists('traspasosdets');        
        Schema::dropIfExists('presucortes');        
    }
};

