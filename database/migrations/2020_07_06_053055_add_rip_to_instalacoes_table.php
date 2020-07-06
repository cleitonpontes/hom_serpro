<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddRipToInstalacoesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('instalacoes', function (Blueprint $table) {
            $table->boolean('rip')->nullable()->after('nome');
            // Tipo (Código Itens): Próprio, Locado, Cedido
            $table->integer('tipo_id')->nullable()->after('rip');
            $table->string('endereco')->nullable()->after('tipo_id');
            $table->string('bairro')->nullable()->after('endereco');
            $table->integer('municipio_id')->nullable()->after('bairro');
            // Estado já presente no relacionamento com Município!
            // $table->integer('uf')->nullable();
            $table->string('latitude')->nullable()->after('municipio_id');
            $table->string('longitude')->nullable()->after('latitude');

            $table->foreign('municipio_id')->references('id')->on('municipios');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('instalacoes', function (Blueprint $table) {
            $table->dropColumn([
                'rip',
                'tipo_id',
                'endereco',
                'bairro',
                'cidade_id',
                'uf',
                'latitude',
                'longitude'
            ]);
        });
    }
}
