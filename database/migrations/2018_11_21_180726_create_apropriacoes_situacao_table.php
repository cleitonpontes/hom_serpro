<?php
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateApropriacoesSituacaoTable extends Migration
{
    
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('apropriacoes_situacao', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('apropriacao_id');
            $table->string('conta', 8);
            $table->string('situacao', 8);
            $table->string('vpd', 9);
            $table->decimal('valor_agrupado', 15, 2)->default(0);
        });
    }
    
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('apropriacoes_situacao');
    }
    
}
