<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('negocios', function (Blueprint $table) {
            $table->id();
            $table->string('negocio', 200);
            $table->string('razonSocial', 200)->nullable();
            $table->string('logo', 200)->nullable();
            $table->json('adicionales')->nullable();
        });      
        Schema::create('divisions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('IdNegocio')->constrained('negocios')->onDelete('cascade');
            $table->string('division',50);
        });        
        Schema::create('divscajas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('IdDivision')->nullable()->constrained('divisions')->onDelete('cascade');
            $table->string('caja',50);
        });         
        Schema::create('divsbodegas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('IdDivision')->nullable()->constrained('divisions')->onDelete('cascade');
            $table->string('bodega',50);
        });          
        Schema::create('deptos', function (Blueprint $table) {
            $table->id();
            $table->string('depto',20);
        });                          
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->foreignId('IdDivision')->nullable()->constrained('divisions')->nullOnDelete();
            $table->foreignId('IdDepto')->nullable()->constrained('deptos')->nullOnDelete();
            $table->string('name');
            $table->string('telefono')->unique();
            $table->string('email')->unique();
            $table->string('password');
            $table->boolean('activo')->default(true);
            $table->json('adicionales')->nullable();
            $table->rememberToken();
        });
        $this->insertarDatos();
    }
    private function insertarDatos(): void
    {
        DB::table('negocios')->insert([
            ['id' => 1, 'negocio' => 'Emerita', 'razonSocial' => 'PRACTISUR S.A. DE C.V.', 'logo' => 'logo.png']
        ]);
        DB::table('divisions')->insert([
            ['id' => 1, 'IdNegocio' => 1, 'division' => 'Ventanas'],
            ['id' => 2, 'IdNegocio' => 1, 'division' => 'Cortinas'],
            ['id' => 3, 'IdNegocio' => 1, 'division' => 'Herrería']
        ]);
        DB::table('divsbodegas')->insert([
            ['id' => 1, 'IdDivision' => 1, 'bodega' => 'Ventanas'],
            ['id' => 2, 'IdDivision' => 1, 'bodega' => 'Cortinas']
        ]);
        DB::table('deptos')->insert([
            ['id' => 1, 'depto' => 'Compras'],
            ['id' => 2, 'depto' => 'AlmacenMP'],
            ['id' => 3, 'depto' => 'Retales'],
            ['id' => 4, 'depto' => 'Corte'],
            ['id' => 5, 'depto' => 'Ensamble'],
            ['id' => 6, 'depto' => 'AlmacénPT'],
            ['id' => 7, 'depto' => 'Instalación'],
            ['id' => 8, 'depto' => 'Entregas'],
            ['id' => 9, 'depto' => 'Administración'],
        ]);
    }    
    public function down(): void
    {
        Schema::dropIfExists('negocios');
        Schema::dropIfExists('divisions');
        Schema::dropIfExists('deptos');
        Schema::dropIfExists('users');
    }
};

// Ejecutar una sola migracion
//php artisan migrate
//Si se trata de algo más específico:
//php artisan migrate --path=database/migrations/2025_07_14_000000_create_movs.php
//php artisan migrate:rollback --path=database/migrations/2025_07_14_000000_create_movs.php

/* 
Schema::create('etapas', function (Blueprint $table) {
    $table->string('etapa', 20); // Cadena de texto de máximo 20 caracteres
    $table->tinyInteger('tiny_integer_column'); // entero -128 a 127
    $table->smallInteger('small_integer_column'); // entero  -32,768 a 32,767
    $table->integer('integer_column'); // entero -2,147,483,648 a 2,147,483,647
    $table->bigInteger('big_integer_column'); // entero -9,223,372,036,854,775,808 a 9,223,372,036,854,775,807
    $table->float('float_column', 8, 2); // reak (8 dígitos, 2 decimales)
    $table->double('double_column', 15, 8); // real (15 dígitos, 8 decimales)
    $table->decimal('decimal_column', 10, 2); // decimal (10 dígitos, 2 decimales): -999,999.99 a 999,999.99
    $table->boolean('boolean_column')->default(true); 
    $table->date('date_column'); // Fecha (YYYY-MM-DD)
    $table->time('time_column'); // Hora (HH:MM:SS)
    $table->dateTime('datetime_column'); // Fecha y hora (YYYY-MM-DD HH:MM:SS)
    $table->timestamp('timestamp_column'); // Marca de tiempo
    $table->text('text_column'); // Texto largo
    $table->json('json_column'); // Datos JSON
    $table->enum('enum_column', ['option1', 'option2', 'option3']); // Enumeración
    $table->timestamps(); // Fecha y hora de creación y actualización
    $table->softDeletes(); // Soft delete (marca de tiempo de eliminación)
});

 */