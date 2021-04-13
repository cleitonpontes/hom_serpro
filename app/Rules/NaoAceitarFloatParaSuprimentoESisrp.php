<?php

namespace App\Rules;


use App\Models\Codigoitem;
use App\Models\MinutaEmpenho;
use Illuminate\Contracts\Validation\Rule;

class NaoAceitarFloatParaSuprimentoESisrp implements Rule
{

    /**
     * @var array
     */
    private $qtd;
    private $modMinuta;
    private $tipoEmpenhoPor;
    private $tipoCompraId;

    /**
     * Create a new rule instance.
     *
     * @param $tipo_alteracao
     */
    public function __construct($objeto)
    {
        $this->qtd = $objeto->qtd;
        $this->modMinuta = MinutaEmpenho::find($objeto->minuta_id);
        $this->tipoEmpenhoPor = $objeto->tipo_empenho_por;
        $this->tipoCompraId = $tipoCompraId = $this->retornaIdCodItem('Tipo Compra','SISRP');

    }

    /**
     * Determine if the validation rule passes.
     *
     * @param string $attribute
     * @param mixed $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        foreach ($this->qtd as $index => $qtd) {

            if ($this->tipoEmpenhoPor == 'Suprimento'){
               return (floor($qtd) == $qtd);
            }
            if ($this->tipoEmpenhoPor == 'Compra') {
                if ($this->tipoCompraId == $this->modMinuta->compra->tipo_compra_id) {
                    return (floor($qtd) == $qtd);
                }
            }
        }

        return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'O campo :attribute deve ser um número inteiro.';
    }

    public function retornaIdCodItem($descCodigo, $descCodItem)
    {
        return Codigoitem::whereHas('codigo', function ($query) use ($descCodigo) {
            $query->where('descricao', '=', $descCodigo)
                ->whereNull('deleted_at');
        })
            ->whereNull('deleted_at')
            ->where('descricao', '=', $descCodItem)
            ->first()->id;
    }

}
