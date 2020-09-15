<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeFieldValorInContratodespesaacessoriaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('contratodespesaacessoria', function (Blueprint $table) {
            $table->decimal('valor',17,2)->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('contratodespesaacessoria', function (Blueprint $table) {
            $table->decimal('valor')->change();
        });
    }
}
