<?php

namespace App\Rules;

use App\Models\Fornecedor;
use Illuminate\Contracts\Validation\Rule;

class NaoAceitarEstrangeiro implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
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
        $fornecedor = Fornecedor::find($value)->cpf_cnpj_idgener;
        return false === strpos($fornecedor, "ESTRANGEIRO");
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'O Credor não pode ser estrangeiro.';
    }
}
