<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterContratohistoricoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('contratohistorico', function (Blueprint $table) {
            $table->dropColumn('fundamento_legal');
            $table->date('data_publicacao')->nullable(false)->change();
            $table->boolean('publicado')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('contratohistorico', function (Blueprint $table) {
            $table->string('fundamento_legal')->nullable()->change();
            $table->dropColumn('publicado');
            $table->date('data_publicacao')->nullable(true)->change();

        });
    }
}
