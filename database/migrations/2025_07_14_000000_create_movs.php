<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {  
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
            $table->string('factura', 20);
            $table->foreignId('IdPedimento')->nullable()->constrained('pedimentos')->nullOnDelete();
            $table->date('fecha');
            $table->decimal('tipoCambio', 12, 4);
            $table->enum('estatus', ['abierto', 'cerrado'])->default('abierto');
            $table->json('adicionales')->nullable();
            $table->timestamps();
        });
        Schema::create('facImportsDets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('IdFactura')->constrained('facturas')->cascadeOnDelete();
            $table->string('IdEntradaMex',20)->unique();
            $table->foreignId('IdOrigen')->nullable()->constrained('origens')->nullOnDelete();
            $table->foreignId('IdMaterial')->nullable()->constrained('materials')->nullOnDelete();
            $table->decimal('cantidad',10,4);
            $table->decimal('precioU',10,4);
            $table->decimal('pesoEnUMat',10,4);
            $table->decimal('pesoG',10,4);
            $table->foreignId('IdSize')->nullable()->constrained('sizes')->nullOnDelete();
            $table->foreignId('IdForma')->nullable()->constrained('formas')->nullOnDelete();
            $table->foreignId('IdEstilo')->nullable()->constrained('estilos')->nullOnDelete();
            $table->string('estiloY',20)->nullable();
            $table->json('adicionales')->nullable();
        });        
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
        });                
        Schema::create('folios', function (Blueprint $table) {
            $table->id();
            $table->foreignId('IdLote')->constrained('lotes')->cascadeOnDelete();
            $table->foreignId('IdEstilo')->nullable()->constrained('estilos')->nullOnDelete();
            $table->string('jobStyle',30)->nullable();
            $table->integer('cantidad');
            $table->smallInteger('totalBandejas');
            $table->decimal('precioU',10,4);
            $table->date('fechaVen');            
            $table->enum('estatus', ['abierto', 'cerrado'])->default('abierto');
            $table->json('adicionales')->nullable();
        });     
        Schema::create('foliosMats', function (Blueprint $table) {
            $table->id();
            $table->foreignId('IdFolio')->constrained('folios')->cascadeOnDelete();
            $table->foreignId('IdFacImportsDet')->nullable()->constrained('facImportsDets')->nullOnDelete();
            $table->foreignId('IdEstilosDet')->nullable()->constrained('estilosDets')->nullOnDelete();
            $table->decimal('cantidad',10,4);
            $table->decimal('pesoEnUMat',10,4);
            $table->decimal('pesoG',8,4);
            $table->boolean('integrado')->default(false);
            $table->json('adicionales')->nullable();
        });        
        Schema::create('bandejas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('IdFolio')->constrained('folios')->cascadeOnDelete();
            $table->integer('cantidad');
            $table->json('adicionales')->nullable();
        });                        
        Schema::create('bandejasMovs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('IdBandeja')->constrained('bandejas')->cascadeOnDelete();
            $table->dateTime('fechaH');
            $table->dateTime('fechaHInicio')->nullable();
            $table->dateTime('fechaHFin')->nullable();
            $table->foreignId('IdCaptura')->constrained('users')->restrictOnDelete();
            $table->foreignId('IdEmpleado')->constrained('empleados')->restrictOnDelete();
            $table->foreignId('IdDepto')->constrained('deptos')->restrictOnDelete();
            $table->decimal('pesoFinalPz',8,4);
            $table->decimal('pesoFinalO',8,4);
            $table->decimal('pesoFinalD',8,4);
            $table->decimal('pesoFinalP',8,4);
            $table->decimal('pesoFinalM',8,4);
            $table->decimal('pesoFinalL',8,4);
            $table->json('adicionales')->nullable();
            $table->timestamps();
        });
    }
};

