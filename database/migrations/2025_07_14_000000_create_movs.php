<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('ordens', function (Blueprint $table) {
            $table->id();
            $table->foreignId('IdCliente')->nullable()->constrained('clientes')->restrictOnDelete();
            $table->string('orden', 30)->unique();
            $table->enum('estatus', ['abierto', 'cerrado'])->default('abierto');
            $table->date('fechaVen');
            $table->json('adicionales')->nullable();
        });
        Schema::create('lotes', function (Blueprint $table) {
            $table->id();
            $table->integer('lote')->unsigned();
            $table->foreignId('IdOrden')->constrained('ordens')->cascadeOnDelete();
            $table->json('adicionales')->nullable();
            $table->unique(['IdOrden', 'lote']);
        });
        Schema::create('folios', function (Blueprint $table) {
            $table->id();
            $table->foreignId('IdLote')->constrained('lotes')->cascadeOnDelete();
            $table->foreignId('IdEstilo')->nullable()->constrained('estilos')->nullOnDelete();
            $table->string('jobStyle', 30)->nullable();
            $table->string('productoFinal', 100)->nullable();
            $table->string('abreviatura', 50)->nullable();
            $table->integer('cantidad');
            $table->smallInteger('totalBandejas');
            $table->decimal('precioU', 12, 4);
            $table->date('fechaVen');
            $table->string('periodo', 4)->index();
            $table->integer('consecutivoMensual');
            $table->enum('estatus', ['abierto', 'proceso', 'cerrado'])->default('abierto');
            $table->json('alertas')->nullable();
            $table->json('adicionales')->nullable();
            $table->timestamps();
        });
        Schema::create('pedimentos', function (Blueprint $table) {
            $table->id();
            $table->string('pedimento', 25)->unique();
            $table->enum('regimen', ['IN', 'RT', 'AF']); // IN=Import, RT=Export
            $table->date('fecha');
            $table->decimal('tipoCambio', 12, 4);
            $table->json('adicionales')->nullable();
            $table->timestamps();
        });
        Schema::create('facturas', function (Blueprint $table) {
            $table->id();
            $table->string('factura', 20);
            $table->foreignId('IdPedimento')->nullable()->constrained('pedimentos')->nullOnDelete();
            $table->date('fecha');
            $table->enum('estatus', ['abierto', 'recibido', 'cerrado'])->default('abierto');
            $table->json('guias')->nullable();
            $table->json('adicionales')->nullable();
            $table->timestamps();
            $table->index(['factura', 'IdPedimento']);
        });
        Schema::create('facImportsDets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('IdFactura')->constrained('facturas')->cascadeOnDelete();
            $table->foreignId('IdMaterial')->nullable()->constrained('materials')->restrictOnDelete();
            $table->string('IdEntradaMex', 20)->unique();
            $table->string('arancel', 20);
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
            $table->json('diferencias')->nullable();
            $table->json('adicionales')->nullable();
            $table->timestamps();
            $table->index(['IdFactura', 'IdMaterial']);
        });
        Schema::create('foliosMats', function (Blueprint $table) {
            $table->id();
            $table->foreignId('IdFolio')->constrained('folios')->cascadeOnDelete();
            $table->foreignId('IdFacImportsDet')->nullable()->constrained('facImportsDets')->nullOnDelete();
            $table->foreignId('IdMaterial')->constrained('materials')->restrictOnDelete();
            $table->foreignId('IdTipo')->constrained('tipos')->restrictOnDelete();
            $table->decimal('cantidad', 12, 4);
            $table->decimal('pesoG', 12, 4);
            $table->boolean('integrado')->default(false);
            $table->timestamps();
            $table->index(['IdFolio', 'IdFacImportsDet']);
        });
        Schema::create('referenciasMovs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('IdFacImportsDet')->constrained('facImportsDets')->restrictOnDelete();
            $table->foreignId('IdMaterial')->nullable()->constrained('materials')->nullOnDelete();
            $table->foreignId('IdDeptoOri')->nullable()->constrained('deptos')->nullOnDelete();
            $table->foreignId('IdDeptoDes')->nullable()->constrained('deptos')->nullOnDelete();
            $table->enum('tipo', ['entrada', 'salida', 'traspaso', 'ajuste', 'consumo']);
            $table->decimal('cantidad', 12, 4);
            $table->decimal('pesoG', 12, 4);
            $table->enum('tipoDoc', ['import', 'folio', 'export', 'ajuste', 'bandeja'])->index();
            $table->bigInteger('IdDoc')->index();
            $table->string('glosa', 150)->nullable();
            $table->json('adicionales')->nullable();
            $table->enum('estatus', ['abierto', 'cerrado'])->default('abierto');
            $table->timestamps();
            $table->index(['IdFacImportsDet', 'IdDeptoOri', 'IdDeptoDes'], 'idx_trazabilidad');
        });
        Schema::create('existencias', function (Blueprint $table) {
            $table->id();
            $table->foreignId('IdFacImportsDet')->constrained('facImportsDets')->cascadeOnDelete();
            $table->foreignId('IdDepto')->constrained('deptos')->cascadeOnDelete();
            $table->decimal('cantidad', 12, 4)->default(0);
            $table->decimal('pesoG', 12, 4)->default(0);
            $table->unique(['IdFacImportsDet', 'IdDepto']);
            $table->index(['IdDepto', 'cantidad'], 'idx_existencia_saldos');
            $table->timestamps();
        });
        Schema::create('bandejas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('IdFolio')->constrained('folios')->cascadeOnDelete();
            $table->integer('numeroBandeja');
            $table->foreignId('IdFacturaExport')->nullable()->constrained('facturas')->nullOnDelete();
            $table->integer('cantidad');
            $table->decimal('precioU', 12, 4)->nullable();
            $table->decimal('valorA', 12, 4)->nullable();
            $table->decimal('castingIni', 12, 4);
            $table->decimal('castingFin', 12, 4);
            $table->decimal('piedrasG', 12, 4)->default(0);
            $table->decimal('diamantesG', 12, 4)->default(0);
            $table->decimal('miscG', 12, 4)->default(0);
            $table->foreignId('IdProcesoActual')->nullable()->constrained('procesos');
            $table->boolean('enBoveda')->default(false);
            $table->boolean('habilitada')->default(false);
            $table->enum('estatus', ['pendiente', 'proceso', 'terminado', 'exportado'])->default('pendiente');
            $table->json('adicionales')->nullable();
            $table->timestamps();
            $table->index(['IdFolio', 'estatus']);
        });
        Schema::create('bandejasMovs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('IdBandeja')->constrained('bandejas')->cascadeOnDelete();
            $table->foreignId('IdProceso')->constrained('procesos')->restrictOnDelete();
            $table->foreignId('IdProcesoSig')->nullable()->constrained('procesos')->nullOnDelete();
            $table->foreignId('IdUser')->nullable()->constrained('users')->restrictOnDelete();
            $table->foreignId('IdRegistrador')->nullable()->constrained('empleados')->nullOnDelete();
            $table->foreignId('IdEmpleado')->nullable()->constrained('empleados')->nullOnDelete();
            $table->decimal('pesoEntrada', 12, 4);
            $table->decimal('pesoSalida', 12, 4)->nullable();
            $table->dateTime('fechaHEntrada');
            $table->dateTime('fechaHSalida')->nullable();
            $table->json('adicionales')->nullable();
            $table->timestamps();
        });
        Schema::create('facExportsDets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('IdFactura')->constrained('facturas')->cascadeOnDelete();
            $table->foreignId('IdBandeja')->nullable()->constrained('bandejas')->nullOnDelete();
            $table->string('productoFinal', 100);
            $table->string('arancel', 20);
            $table->integer('cantidad');
            $table->decimal('precioU', 12, 4)->nullable();
            $table->decimal('valorA', 6, 2)->nullable();
            $table->decimal('pesoG', 12, 4);
            $table->decimal('castingIni', 12, 4);
            $table->decimal('castingG', 12, 4);
            $table->decimal('piedrasG', 12, 4)->default(0);
            $table->decimal('diamantesG', 12, 4)->default(0);
            $table->decimal('miscG', 12, 4)->default(0);
            $table->json('adicionales')->nullable();
            $table->index(['IdFactura', 'IdBandeja']);
        });
        Schema::create('facExportsMats', function (Blueprint $table) {
            $table->id();
            $table->foreignId('IdFacExportsDet')->constrained('facExportsDets')->cascadeOnDelete();
            $table->foreignId('IdFacImportsDet')->constrained('facImportsDets')->cascadeOnDelete();
            $table->decimal('cantidad', 12, 4);
            $table->decimal('pesoG', 12, 4);
            $table->index(['IdFacExportsDet', 'IdFacImportsDet'], 'idx_exports_mats_imports');
        });

    }
};

