<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class ValorMaximoFaixaAjuste implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($vlrmeta)
    {
        $this->vlrmeta = $vlrmeta;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        return $this->vlrmeta > $value;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'O valor máximo da faixa de ajuste tem que ser menor do que o valor da Meta: ' . $this->vlrmeta;
    }
}
