<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

use App\Models\Codigo;
use App\Models\Codigoitem;
use App\Models\Encargo;


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


        // atualização do codigoitem décimo terceiro
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
        // atualização do encargo décimo terceiro
        $tipo_id_encargo_decimo_terceiro = $codigoitem_id_decimo_terceiro->id;
        $encargo_decimo_terceiro = Encargo::where([
            'tipo_id' => $tipo_id_encargo_decimo_terceiro,
        ])->first();
        if(is_object($encargo_decimo_terceiro)){
            Encargo::updateOrCreate(
                ['tipo_id' => $encargo_decimo_terceiro->tipo_id],   // aqui é a chave ou algum unique
                [
                    'percentual' => '8.33',
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
        // atualização do encargo férias
        $tipo_id_encargo_ferias = $codigoitem_id_ferias->id;
        $encargo_ferias = Encargo::where([
            'tipo_id' => $tipo_id_encargo_ferias,
        ])->first();
        if(is_object($encargo_ferias)){
            Encargo::updateOrCreate(
                ['tipo_id' => $encargo_ferias->tipo_id],   // aqui é a chave ou algum unique
                [
                    'percentual' => '12.10',
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
        // atualização do encargo fgts
        $tipo_id_encargo_fgts = $codigoitem_id_fgts->id;
        $encargo_fgts = Encargo::where([
            'tipo_id' => $tipo_id_encargo_fgts,
        ])->first();
        if(is_object($encargo_fgts)){
            Encargo::updateOrCreate(
                ['tipo_id' => $encargo_fgts->tipo_id],   // aqui é a chave ou algum unique
                [
                    'percentual' => '4.00',
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
        // atualização do encargo grupo a
        $tipo_id_encargo_grupo_a = $codigoitem_id_grupo_a->id;
        $encargo_grupo_a = Encargo::where([
            'tipo_id' => $tipo_id_encargo_grupo_a,
        ])->first();
        if(is_object($encargo_grupo_a)){
            Encargo::updateOrCreate(
                ['tipo_id' => $encargo_grupo_a->tipo_id],   // aqui é a chave ou algum unique
                [
                    'percentual' => '7.82',
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
