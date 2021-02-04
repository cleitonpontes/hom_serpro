<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

use App\Models\Codigo;
use App\Models\Codigoitem;


class UpdateCodigoitensEncargoIn52017 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $codigo = Codigo::where([
            'descricao' => 'Tipo Encargos'
        ])->first();


        // atualização do décimo terceiro
        $codigoitem_id_decimo_terceiro = Codigoitem::where([
            'codigo_id' => $codigo->id,
            'descricao' => 'Décimo Terceiro Salário'
        ])->first();
        if(is_object($codigoitem_id_decimo_terceiro)){
            Codigoitem::updateOrCreate(
                ['id' => $codigoitem_id_decimo_terceiro->id],   // aqui é a chave ou algum unique
                [
                    'descricao' => '13º (décimo terceiro) salário',
                    'updated_at' => now(),
                ]
            );
        }




        // atualização férias
        $codigoitem_id_ferias = Codigoitem::where([
            'codigo_id' => $codigo->id,
            'descricao' => 'Férias e Adicional'
        ])->first();
        if(is_object($codigoitem_id_ferias)){
            Codigoitem::updateOrCreate(
                ['id' => $codigoitem_id_ferias->id],   // aqui é a chave ou algum unique
                [
                    'descricao' => 'Férias e 1/3 (um terço) constitucional de férias',
                    'updated_at' => now(),
                ]
            );
        }



        // fgts
        $codigoitem_id_fgts = Codigoitem::where([
            'codigo_id' => $codigo->id,
            'descricao' => 'Rescisão e Adicional do FGTS'
        ])->first();
        if(is_object($codigoitem_id_fgts)){
            Codigoitem::updateOrCreate(
                ['id' => $codigoitem_id_fgts->id],   // aqui é a chave ou algum unique
                [
                    'descricao' => 'Multa sobre o FGTS para as rescisões sem justa causa',
                    'updated_at' => now(),
                ]
            );
        }




        // módulo grupo A
        $codigoitem_id_grupo_a = Codigoitem::where([
            'codigo_id' => $codigo->id,
            'descricao' => 'Grupo "A" sobre 13o. Salário e Férias'
        ])->first();
        if(is_object($codigoitem_id_grupo_a)){
            Codigoitem::updateOrCreate(
                ['id' => $codigoitem_id_grupo_a->id],   // aqui é a chave ou algum unique
                [
                    'descricao' => 'Incidência do Submódulo 2.2 sobre férias, 1/3 (um terço) constitucional de férias e 13o (décimo terceiro) salário',
                    'updated_at' => now(),
                ]
            );
        }


    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

    }
}
