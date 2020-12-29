<?php

namespace App\Rules;

use App\Models\MinutaEmpenho;
use Illuminate\Contracts\Validation\Rule;
use App\Http\Traits\Formatador;
use Illuminate\Support\Facades\DB;


class ObrigatorioSeNaturezaIgual implements Rule
{
    use Formatador;

    private $minuta_id;
    private $natureza_cipi;

    /**
     * Create a new rule instance.
     *
     * @param $minuta_id
     * @param $natureza_cipi
     */
    public function __construct($natureza_cipi, $minuta_id)
    {
        $this->natureza_cipi = $natureza_cipi;
        $this->minuta_id = $minuta_id;

    }

    /**
     * Determine if the validation rule passes.
     *
     * @param string $attribute
     * @param mixed $value
     * @return bool
     */
    public function passes($attribute, $value): bool
    {

        $natureza = MinutaEmpenho::join(
            'saldo_contabil',
            'saldo_contabil.id',
            '=',
            'minutaempenhos.saldo_contabil_id'
        )
            ->join(
                'naturezadespesa',
                'naturezadespesa.codigo',
                '=',
                DB::raw("SUBSTRING(saldo_contabil.conta_corrente,18,6)")
            )
            ->where('minutaempenhos.id', $this->minuta_id)
            ->whereIn('naturezadespesa.codigo', $this->natureza_cipi)
            ->select(
                [
                    DB::raw("SUBSTRING(saldo_contabil.conta_corrente,18,6) AS natureza_despesa")
                ])
            ->first();

        if (!isset($natureza->id)) {
            return true;
        }

        return false;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public
    function message()
    {
        return 'Campo obrigatório para a natureza da despesa do Crédito Orçamentário. ';
    }
}
