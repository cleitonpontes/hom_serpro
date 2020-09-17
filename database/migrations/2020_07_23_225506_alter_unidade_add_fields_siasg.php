<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterUnidadeAddFieldsSiasg extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('unidades', function (Blueprint $table) {
            $table->boolean('sisg')->default(false);
            $table->integer('municipio_id')->unsigned()->nullable();
            $table->string('esfera')->nullable();
            $table->string('poder')->nullable();
            $table->string('tipo_adm')->nullable();
            $table->boolean('aderiu_siasg')->default(true);
            $table->boolean('utiliza_siafi')->default(true);
            $table->string('codigo_siorg')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('unidades', function (Blueprint $table) {
            $table->dropColumn('sisg');
            $table->dropColumn('municipio_id');
            $table->dropColumn('esfera');
            $table->dropColumn('poder');
            $table->dropColumn('tipo_adm');
            $table->dropColumn('aderiu_siasg');
            $table->dropColumn('utiliza_siafi');
            $table->dropColumn('codigo_siorg');
        });
    }
}
