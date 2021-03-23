<?php

namespace App\Http\Controllers\Empenho\Minuta;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\ContratoItemMinutaEmpenho;
use App\Models\CompraItemMinutaEmpenho;
use Alert;

class FixContratoCompraMinutaController extends Controller
{
    public function update(Request $request)
    {
        $tipoMinuta = $request['tipo_minuta'];
        foreach ($request['id'] as $key => $id) {
            $coItemMinuta = $tipoMinuta === 'Contrato' ?
                ContratoItemMinutaEmpenho::find($id) :
                CompraItemMinutaEmpenho::find($id);
            $this->atualizarNumSeq($coItemMinuta, $request['numseq'][$key]);
        }
        Alert::success('Dados atualizados!')->flash();

        return redirect()->route('ajusteminuta.atualizar.contrato.compra',
            [
                'minuta_id' => $request['id_minuta'],
                'id_remessa' => $request['id_remessa']
            ]);
    }

    private function atualizarNumSeq($coItemMinuta, $numSeq)
    {
        $coItemMinuta->numseq = $numSeq;
        $coItemMinuta->save();
    }
}
