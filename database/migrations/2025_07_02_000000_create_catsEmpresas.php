<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {         
        Schema::create('divsCajas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('IdDivision')->nullable()->constrained('divisions')->onDelete('cascade');
            $table->string('caja',50);
        });         
        Schema::create('divsBodegas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('IdDivision')->nullable()->constrained('divisions')->onDelete('cascade');
            $table->string('bodega',50);
        });                      
        Schema::create('empresas', function (Blueprint $table) { 
            $table->id();
            $table->foreignId('IdNegocio')->nullable()->constrained('negocios')->onDelete('cascade');
            $table->enum('tipo', ['cliente', 'proveedor', 'ambos']);
            $table->string('empresa',100);
            $table->string('razonSocial',200);
            $table->string('rfc',20);
            $table->string('direccion',200)->nullable();
            $table->string('gmaps',250)->nullable();
            $table->string('telefono',100)->unique()->nullable();
            $table->string('email',100)->unique()->nullable();
            $table->json('adicionales')->nullable();
        });
        Schema::create('empresassucs', function (Blueprint $table) { 
            $table->id();
            $table->foreignId('IdEmpresa')->constrained('empresas')->onDelete('cascade');
            $table->string('sucursal',100);
            $table->string('direccion',200)->nullable();
            $table->string('gmaps',250)->nullable();
            $table->json('adicionales')->nullable();
        });
        Schema::create('empresasCuentas', function (Blueprint $table) { 
            $table->id();
            $table->foreignId('IdEmpresa')->constrained('empresas')->onDelete('cascade');
            $table->string('banco',100);
            $table->string('cuenta',20)->nullable();
            $table->string('cuentaClabe',20);
            $table->json('adicionales')->nullable();
        });        
        Schema::create('empresasContactos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('IdEmpresa')->constrained('empresas')->onDelete('cascade');
            $table->string('contacto',100);
            $table->string('telefono',50)->unique();
            $table->json('adicionales')->nullable();
        });
        Schema::create('obras', function (Blueprint $table) { 
            $table->id();
            $table->foreignId('IdEmpresa')->constrained('empresas')->setNullOnDelete();
            $table->string('obra',200);
            $table->string('gmaps',250)->nullable();
            $table->enum('estatus', ['vigente', 'cancelada', 'entregada'])->default('vigente');
            $table->json('adicionales')->nullable();
        });        
    }
 

    public function down()
    {
        Schema::dropIfExists('empresas');
        Schema::dropIfExists('empresassucs');
        Schema::dropIfExists('contactos');
        Schema::dropIfExists('obras');
    }
};
