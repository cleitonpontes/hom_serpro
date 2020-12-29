<?php

namespace App\Rules;

use App\Models\MinutaEmpenho;
use App\XML\ApiCIPI;
use Illuminate\Contracts\Validation\Rule;

class ValidaIdCipi implements Rule
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
        if ($value) {
            $apiCipi = new ApiCIPI();
            $retorno = $apiCipi->executaConsulta('ValidaCipi', ['id' => $value]);

            if ($retorno == "false") {
                return false;
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
        return 'Id CIPI Inválido.';
    }
}
