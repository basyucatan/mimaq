<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {  
        Schema::create('ordens', function (Blueprint $table) {
            $table->id();
            $table->foreignId('IdCliente')->nullable()->constrained('clientes')->restrictOnDelete();
            $table->string('orden', 30)->unique();
            $table->enum('estatus', ['abierto', 'cerrado'])->default('abierto');
            $table->date('fechaVen');
            $table->json('alertas')->nullable();
            $table->json('adicionales')->nullable();
        });   
        Schema::create('lotes', function (Blueprint $table) {
            $table->id();
            $table->integer('lote')->unsigned();
            $table->foreignId('IdOrden')->constrained('ordens')->cascadeOnDelete();
            $table->json('alertas')->nullable();
            $table->json('adicionales')->nullable();
            $table->unique(['IdOrden','lote']);
        });        
        Schema::create('folios', function (Blueprint $table) {
            $table->id();
            $table->foreignId('IdLote')->constrained('lotes')->cascadeOnDelete();
            $table->foreignId('IdEstilo')->nullable()->constrained('estilos')->nullOnDelete();
            $table->string('productoFinal', 100)->nullable();
            $table->string('jobStyle', 30)->nullable();
            $table->integer('cantidad');
            $table->smallInteger('totalBandejas');
            $table->decimal('precioU', 12, 4);
            $table->date('fechaVen');
            $table->decimal('cantidadSurtida', 12, 4)->default(0);
            $table->enum('estatus', ['abierto', 'proceso', 'cerrado'])->default('abierto');
            $table->json('adicionales')->nullable();
            $table->timestamps();
        });
        Schema::create('pedimentos', function (Blueprint $table) {
            $table->id();
            $table->string('pedimento', 25)->unique();
            $table->enum('regimen', ['IN', 'RT', 'AF']); // IN=Import, RT=Export
            $table->date('fecha');
            $table->json('adicionales')->nullable();
            $table->timestamps();
        }); 
        Schema::create('facturas', function (Blueprint $table) {
            $table->id();
            $table->string('factura',20);
            $table->foreignId('IdPedimento')->nullable()->constrained('pedimentos')->nullOnDelete();
            $table->date('fecha');
            $table->decimal('tipoCambio',12,4);
            $table->enum('estatus',['abierto','recibido','cerrado'])->default('abierto');
            $table->json('adicionales')->nullable();
            $table->timestamps();
            $table->index(['factura','IdPedimento']);
        });      
        Schema::create('facImportsDets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('IdFactura')->constrained('facturas')->cascadeOnDelete();
            $table->foreignId('IdMaterial')->nullable()->constrained('materials')->restrictOnDelete();
            $table->string('IdEntradaMex', 20)->unique();
            $table->string('arancel',20);
            $table->foreignId('IdOrigen')->nullable()->constrained('origens')->nullOnDelete();
            $table->foreignId('IdFolio')->nullable()->constrained('folios')->nullOnDelete();
            $table->decimal('cantidad', 12, 4);
            $table->decimal('precioU', 12, 4);
            $table->decimal('pesoEnUMat', 12, 4);
            $table->decimal('pesoG', 12, 4);
            $table->foreignId('IdSize')->nullable()->constrained('sizes')->nullOnDelete();
            $table->foreignId('IdForma')->nullable()->constrained('formas')->nullOnDelete();
            $table->foreignId('IdEstilo')->nullable()->constrained('estilos')->nullOnDelete();
            $table->string('estiloY', 20)->nullable();
            $table->json('adicionales')->nullable();
            $table->timestamps();
            $table->index(['IdFactura', 'IdMaterial']);
        });
        Schema::create('referenciasMovs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('IdFacImportsDet')->constrained('facImportsDets')->restrictOnDelete();
            $table->foreignId('IdMaterial')->nullable()->constrained('materials')->nullOnDelete();
            $table->foreignId('IdDeptoOri')->nullable()->constrained('deptos')->nullOnDelete();
            $table->foreignId('IdDeptoDes')->nullable()->constrained('deptos')->nullOnDelete();
            $table->enum('tipo', ['entrada', 'salida', 'traspaso', 'ajuste']);
            $table->decimal('cantidad', 12, 4);
            $table->decimal('pesoG', 12, 4);
            $table->enum('tipoDoc', ['import', 'folio', 'export', 'ajuste'])->index();
            $table->bigInteger('IdDoc')->index();
            $table->string('glosa', 100)->nullable();
            $table->json('diferencias')->nullable();
            $table->json('adicionales')->nullable();
            $table->enum('estatus', ['abierto', 'cerrado'])->default('abierto');
            $table->timestamps();
            $table->index(['IdFacImportsDet', 'IdDeptoOri', 'IdDeptoDes'], 'idx_trazabilidad_deptos');
        });
        Schema::create('existencias', function (Blueprint $table) {
            $table->id();
            $table->foreignId('IdFacImportsDet')->constrained('facImportsDets')->cascadeOnDelete();
            $table->foreignId('IdDepto')->constrained('deptos')->cascadeOnDelete(); 
            $table->decimal('cantidad', 12, 4)->default(0);
            $table->decimal('pesoG', 12, 4)->default(0);
            $table->unique(['IdFacImportsDet', 'IdDepto']);
            $table->index(['IdDepto', 'IdFacImportsDet'], 'idx_match_ubicacion');
            $table->timestamps();
        });
        Schema::create('foliosMats', function (Blueprint $table) {
            $table->id();
            $table->foreignId('IdFolio')->constrained('folios')->cascadeOnDelete();
            $table->foreignId('IdFacImportsDet')->nullable()->constrained('facImportsDets')->nullOnDelete();
            $table->foreignId('IdMaterial')->constrained('materials')->restrictOnDelete();
            $table->decimal('cantidad', 12, 4);
            $table->decimal('pesoG', 12, 4);
            $table->boolean('integrado')->default(false);
            $table->timestamps();
            $table->index(['IdFolio', 'IdFacImportsDet']);
        });
        Schema::create('bandejas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('IdFolio')->constrained('folios')->cascadeOnDelete();
            $table->foreignId('IdFacturaExport')->nullable()->constrained('facturas')->nullOnDelete();
            $table->integer('cantidad');
            $table->decimal('pesoMetalInicial', 12, 4);
            $table->decimal('pesoMetalActual', 12, 4);
            $table->decimal('pesoPiedrasConstante', 12, 4)->default(0);
            $table->decimal('mermaMetalAcumulada', 12, 4)->default(0);
            $table->foreignId('IdProcesoActual')->nullable()->constrained('procesos');
            $table->boolean('enBoveda')->default(false);
            $table->boolean('habilitada')->default(false);
            $table->enum('estatus', ['pendiente', 'proceso', 'terminado', 'exportado', 'rechazado'])->default('pendiente');
            $table->timestamps();
            $table->index(['IdFolio', 'estatus']);
        });
        Schema::create('bandejasMovs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('IdBandeja')->constrained('bandejas')->cascadeOnDelete();
            $table->foreignId('IdProceso')->constrained('procesos')->restrictOnDelete();
            $table->foreignId('IdEmpleado')->nullable()->constrained('empleados')->nullOnDelete();
            $table->decimal('pesoMetalEntrada', 12, 4);
            $table->decimal('pesoMetalSalida', 12, 4);
            $table->decimal('mermaMetal', 12, 4);
            $table->dateTime('fechaH');
            $table->timestamps();
        });

    }
};

