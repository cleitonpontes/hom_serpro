<?php

namespace App\Rules;

use App\Models\Fornecedor;
use Illuminate\Contracts\Validation\Rule;

class NaoAceitarZero implements Rule
{
    /**
     * @var array
     */
    private $tipo_alteracao;

    /**
     * Create a new rule instance.
     *
     * @param array $tipo_alteracao
     */
    public function __construct(array $tipo_alteracao)
    {
        $this->tipo_alteracao = $tipo_alteracao;
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

        $index = substr($attribute, strpos($attribute, '.') + 1);
        $tipo_alteracao = $this->tipo_alteracao[$index];
        //CASO NÃO SEJA CANCELAMENTO/NENHUMA
        if ((strpos($tipo_alteracao, 'NENHUMA') === false)
            && (strpos($tipo_alteracao, 'CANCELAMENTO') === false)) {
            return !(empty((float)str_replace(',','.',str_replace('.','',$value))));
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
        return 'O campo :attribute não pode estar com valor zero.';
    }
}
