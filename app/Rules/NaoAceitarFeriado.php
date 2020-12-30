<?php

namespace App\Rules;

use App\Models\Feriado;
use App\Models\Fornecedor;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Carbon;

class NaoAceitarFeriado implements Rule
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

        $feriados = Feriado::select('data')->get()->toArray();
        $search = $value;

        $teste = array_keys(
            array_filter(
                $feriados,
                function ($value) use ($search) {
                    return (strpos($value['data'], $search) !== false);
                }
            )
        );
        if (count($teste)) {
            return false;
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
        return 'A data de publicação escolhida não pode ser feriado.';
    }
}
