<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
return new class extends Migration
{
    public function up()
    {
        Schema::table('materialscostos', function (Blueprint $table) {
            $table->dropForeign(['IdMaterial']);
            $table->foreign('IdMaterial')
                ->references('id')
                ->on('materials')
                ->onDelete('cascade');
        });        
        Schema::table('colorables', function (Blueprint $table) {
            $table->enum('tipo', ['Perfil', 'Vidrio', 'Herraje', 'Otro'])->default('Perfil');
        });    
        DB::table('divsbodegas')->insert([
            ['id' => 1, 'IdDivision' => 1, 'bodega' => 'Ventanas'],
            ['id' => 2, 'IdDivision' => 2, 'bodega' => 'Cortinas'],
            ['id' => 3, 'IdDivision' => 3, 'bodega' => 'Herrería']
        ]);                            
        DB::table('users')->whereNull('IdDivision')->update(['IdDivision' => 1]);

        Schema::create('movinventarios', function (Blueprint $table) {
            $table->id();
            $table->foreignId('IdUserOri')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('IdUserDes')->nullable()->constrained('users')->nullOnDelete();
            $table->enum('tipo', ['Compra','InvFisico','Corte','Traspaso','Ensamble','Entrega','Devolucion']);
            $table->foreignId('IdBodega')->constrained('divsbodegas')->cascadeOnDelete();
            $table->foreignId('IdDepto')->constrained('deptos')->cascadeOnDelete();
            $table->foreignId('IdBodegaOri')->nullable()->constrained('divsbodegas')->nullOnDelete();
            $table->foreignId('IdDeptoOri')->nullable()->constrained('deptos')->nullOnDelete();
            $table->foreignId('IdMatCosto')->constrained('materialscostos')->cascadeOnDelete();
            $table->dateTime('fechaH')->useCurrent();
            $table->decimal('cantidad',12,4)->default(0);
            $table->decimal('valorU',12,5)->default(0);
            $table->string('dimensiones')->nullable();
            $table->json('adicionales')->nullable();
            $table->timestamps();
            $table->index(['IdMatCosto','IdBodega','IdDepto','fechaH'],'idx_kardex');
        });       
    }
public function down()
{
    Schema::dropIfExists('movinventarios');
    DB::table('users')->where('IdDivision', 1)->update(['IdDivision' => null]);
    Schema::table('colorables', function (Blueprint $table) {
        $table->dropColumn('tipo');
    });
}    
};