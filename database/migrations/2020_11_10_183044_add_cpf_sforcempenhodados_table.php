<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCpfSforcempenhodadosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sforcempenhodados', function (Blueprint $table) {
            $table->string('cpf_user')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sforcempenhodados', function (Blueprint $table) {
            $table->dropColumn('cpf_user');
        });
    }
}
