<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDocumentosiafiTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('documentosiafi', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('empenho_id');
            $table->date('data');
            $table->string('numero');
            $table->string('docorigem');
            $table->decimal('valor',17,2);
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('empenho_id')->references('id')->on('empenhos')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('documentosiafi');
    }
}
