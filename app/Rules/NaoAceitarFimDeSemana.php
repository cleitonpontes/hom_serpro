<?php

namespace App\Rules;

use App\Models\Feriado;
use App\Models\Fornecedor;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Carbon;

class NaoAceitarFimDeSemana implements Rule
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

        $data = Carbon::createFromFormat('Y-m-d', $value);

        return !($data->isWeekend());
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'A data de publicação escolhida não pode ser final de semana.';
    }
}
