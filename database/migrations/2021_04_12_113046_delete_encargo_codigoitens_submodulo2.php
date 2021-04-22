<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

use App\Models\Codigoitem;
use App\Models\Codigo;
use App\Models\Encargo;


class DeleteEncargoCodigoitensSubmodulo2 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        $item = Encargo::where('deleted_at', null)
        ->join('codigoitens', 'codigoitens.id', 'encargos.tipo_id')
        ->where('codigoitens.descricao', 'Incidência do Submódulo 2.2 sobre férias, 1/3 (um terço) constitucional de férias e 13o (décimo terceiro) salário')
        ->delete();

        $item = Codigoitem::where('descricao', 'Incidência do Submódulo 2.2 sobre férias, 1/3 (um terço) constitucional de férias e 13o (décimo terceiro) salário')->delete();

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {


        // $encargo = Encargo::where('deleted_at', '<>', null)
        // ->join('codigoitens', 'codigoitens.id', 'encargos.tipo_id')
        // ->where('codigoitens.descricao', 'Incidência do Submódulo 2.2 sobre férias, 1/3 (um terço) constitucional de férias e 13o (décimo terceiro) salário')->first();



        // $codigoitem = Codigoitem::where('id', $encargo->tipo_id)->first();



        // Encargo::updateOrCreate(
        //     ['deleted_at' => null],
        //     [
        //         'id' => $encargo->id,
        //     ]);


        // Codigoitem::updateOrCreate(
        //     ['deleted_at' => null],
        //     [
        //         'id' => $encargo->tipo_id,
        //     ]);


    }
}
