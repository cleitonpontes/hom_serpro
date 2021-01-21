<?php

use App\Models\Codigoitem;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterSforcempenhodadosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::table('sforcempenhodados', function (Blueprint $table) {
            $table->integer('tipoempenho')->nullable()->change(); //1 - Ordinário, 3 - Estimativo ou 5 - Global
            $table->date('dtemis')->nullable()->change();
            $table->string('codfavorecido', 14)->nullable()->change(); //cnpj ou cpf ou ug ou idgenerico
            $table->integer('codamparolegal')->nullable()->change(); // codigo do amparo legal
            $table->string('txtdescricao', 468)->nullable()->change();
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
            $table->integer('tipoempenho')->change(); //1 - Ordinário, 3 - Estimativo ou 5 - Global
            $table->date('dtemis')->change();
            $table->string('codfavorecido', 14)->change(); //cnpj ou cpf ou ug ou idgenerico
            $table->integer('codamparolegal')->change(); // codigo do amparo legal
            $table->string('txtdescricao', 468)->change();
        });
    }
}
