<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('ocomprasdets', function (Blueprint $table) {
            $table->decimal('cantidad',10,4)->change();
            $table->decimal('cantidadRec',10,4)->nullable()->after('costoU');
            $table->decimal('costoURec', 12, 5)->nullable();
        });
    }
};


