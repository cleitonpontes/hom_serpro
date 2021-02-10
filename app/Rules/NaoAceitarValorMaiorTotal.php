<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use App\Http\Traits\Formatador;

class NaoAceitarValorMaiorTotal implements Rule
{
    use Formatador;

    /**
     * @var string|string[]
     */
    private $from;
    /**
     * @var array
     */
    private $valor_total_item;
    /**
     * @var array
     */
    private $tipo_alteracao;
    /**
     * @var array
     */
    private $vlr_empenhado;


    /**
     * Create a new rule instance.
     *
     * @param array $tipo_alteracao
     * @param array $valor_total_item
     * @param array $vlr_total_item
     */
    public function __construct(
        array $tipo_alteracao,
        array $valor_total_item,
        array $vlr_total_item,
        string $tipo_empenho_por
    ) {
        $this->valor_total_item = $valor_total_item;
        $this->tipo_alteracao = $tipo_alteracao;
        $this->vlr_empenhado = $vlr_total_item;
        $this->tipo_empenho_por = $tipo_empenho_por;
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
        //esta regra não vale para suprimento
        if ($this->tipo_empenho_por === 'Suprimento') {
            return true;
        }
        $index = substr($attribute, strpos($attribute, '.') + 1);
        $tipo_alteracao = $this->tipo_alteracao[$index];
        if (strpos($tipo_alteracao, 'REFORÇO') !== false) {
            $valor_selecionado = $this->retornaFormatoAmericano($value);

            return $valor_selecionado <= $this->valor_total_item[$index];
        }
        if (strpos($tipo_alteracao, 'ANULAÇÃO') !== false) {
            $valor_selecionado = $this->retornaFormatoAmericano($value);
            return $valor_selecionado <= $this->vlr_empenhado[$index];
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
        return 'O valor selecionado não pode ser maior do que o valor total do item.';
    }
}
