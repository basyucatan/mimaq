<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {         
        Schema::create('deptos', function (Blueprint $table) {
            $table->id();
            $table->string('depto', 30)->unique();
            $table->string('deptoI', 30)->unique();
            $table->smallInteger('orden')->nullable();
        });
        Schema::create('procesos', function (Blueprint $table) {
            $table->id();
            $table->string('proceso', 50);
            $table->string('procesoI', 50);
            $table->foreignId('IdDepto')->constrained('deptos')->cascadeOnDelete();
            $table->decimal('PMaxMerma', 8, 4)->default(0);
        });
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->foreignId('IdDepto')->nullable()->constrained('deptos')->nullOnDelete();
            $table->string('name')->unique();
            $table->string('telefono')->unique();
            $table->string('email')->nullable()->unique();
            $table->string('password');
            $table->boolean('activo')->default(true);
            $table->json('adicionales')->nullable();
            $table->rememberToken();
        });
        $this->insertarDatos();
    }
    private function insertarDatos(): void
    {
        $deptosData = [
            ['0 BOVEDA', '0 BOVEDA', 10],
            ['1 C. PRODUCCION', '1 CONTROL ROOM', 10],
            ['2 ENGARCE', '2 SETTING', 20],
            ['3 PULIDO', '3 POLISH', 30],
            ['4 LAVADO', '4 WASH OUT', 40],
            ['5 JOYERIA', '5 JEWELRY', 50],
            ['6 CONTROL DE CALIDAD', '6 QC', 60],
            ['7 RHODIO', '7 RHODIUM', 70],
            ['8 EXPORT', '8 EXPORT', 70],
            ['Admin', 'Admin', 80]
        ];
        foreach ($deptosData as $d) {
            DB::table('deptos')->insert([
                'depto' => $d[0],
                'deptoI' => $d[1],
                'orden' => $d[2]
            ]);
        }
        $procesos = [
            ['05 VALIDACION', '05 VALIDATE', '1 C. PRODUCCION', 0.00],
            ['10 DISTRIBUCION 2', '10 CONTROL ROOM 2', '1 C. PRODUCCION', 0.00],
            ['00 DISTRIBUCION', '00 CONTROL ROOM', '1 C. PRODUCCION', 0.00],
            ['40 ENGARCE1', '40 SETTING', '2 ENGARCE', 0.05],
            ['41 ENGARCE2', '41 SETTING 2', '2 ENGARCE', 0.05],
            ['43 BOVEDA (ENGARCE 2)', '43 VAULT (SETTING 2)', '2 ENGARCE', 0.00],
            ['42 BOVEDA (ENGARCE 1)', '42 VAULT (SETTING)', '2 ENGARCE', 0.00],
            ['44 ENGARCE3', '44 SETTING 3', '2 ENGARCE', 0.00],
            ['61 LIMPIEZA (PULIDO)', '61 GRINDING', '3 PULIDO', 0.03],
            ['62 PREPULIDO', '62 PREPOLISH', '3 PULIDO', 0.00],
            ['63 PULIDO', '63 POLISH', '3 PULIDO', 0.00],
            ['65 BOVEDA (PULIDO)', '65 VAULT (POLISH)', '3 PULIDO', 0.00],
            ['64 LAPA', '64 LAP', '3 PULIDO', 0.00],
            ['33 LAVADO LAPA', '33 WASH OUT LAP', '4 LAVADO', 0.00],
            ['31 LAVADO PREPULIDO', '31 WASH PREPOLISH', '4 LAVADO', 0.00],
            ['32 LAVADO PULIDO', '32 WASH POLISH', '4 LAVADO', 0.00],
            ['34 TOMBOLA', '34 TUMBLE', '4 LAVADO', 0.00],
            ['52 LIMPIEZA (JOYERIA)', '52 GRINDING', '5 JOYERIA', 0.03],
            ['51 JOYERIA', '51 JEWELRY', '5 JOYERIA', 0.00],
            ['85 BOVEDA (EMPAQUE)', '85 VAULT (PACKING)', '6 CONTROL DE CALIDAD', 0.00],
            ['81 Q.C. 1', '81 Q.C. SECOND SETTING', '6 CONTROL DE CALIDAD', 0.00],
            ['85 EXPORT', '85 EXPORT', '6 CONTROL DE CALIDAD', 0.00],
            ['84 PROYECTO DE EXPORT # 1', '84 EXPORT PROJECT', '6 CONTROL DE CALIDAD', 0.00],
            ['83 EMPAQUE', '83 PACKING', '6 CONTROL DE CALIDAD', 0.00],
            ['80 Q.C.', '80 Q.C.', '6 CONTROL DE CALIDAD', 0.00],
            ['71 RHODIO', '71 RHODIO', '7 RHODIO', 0.00]
        ];
        foreach ($procesos as $p) {
            $idDepto = DB::table('deptos')->where('depto', $p[2])->value('id');
            if ($idDepto) {
                DB::table('procesos')->insert([
                    'proceso' => $p[0],
                    'procesoI' => $p[1],
                    'IdDepto' => $idDepto,
                    'PMaxMerma' => $p[3]
                ]);
            }
        }
    }  
};

// Schema::create('etapas', function (Blueprint $table) {
//     $table->string('etapa', 20); // Cadena de texto de máximo 20 caracteres
//     $table->tinyInteger('tiny_integer_column'); // entero -128 a 127
//     $table->smallInteger('small_integer_column'); // entero  -32,768 a 32,767
//     $table->integer('integer_column'); // entero -2,147,483,648 a 2,147,483,647
//     $table->bigInteger('big_integer_column'); // entero -9,223,372,036,854,775,808 a 9,223,372,036,854,775,807
//     $table->float('float_column', 8, 2); // real (8 dígitos, 2 decimales)
//     $table->double('double_column', 15, 8); // real (15 dígitos, 8 decimales)
//     $table->decimal('decimal_column', 10, 2); // decimal (10 dígitos, 2 decimales): -999,999.99 a 999,999.99
//     $table->boolean('boolean_column')->default(true); 
//     $table->date('date_column'); // Fecha (YYYY-MM-DD)
//     $table->time('time_column'); // Hora (HH:MM:SS)
//     $table->dateTime('datetime_column'); // Fecha y hora (YYYY-MM-DD HH:MM:SS)
//     $table->timestamp('timestamp_column'); // Marca de tiempo
//     $table->text('text_column'); // Texto largo
//     $table->json('json_column'); // Datos JSON
//     $table->enum('enum_column', ['option1', 'option2', 'option3']); // Enumeración
//     $table->timestamps(); // Fecha y hora de creación y actualización
//     $table->softDeletes(); // Soft delete (marca de tiempo de eliminación)
// });

