<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class AteMaiorQueAPartir implements Rule
{
    private $from;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($from)
    {
        $this->from = $from;
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
        return $value > $this->from;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'O campo "Até" tem que ser maior do que o campo "A partir de"';
    }
}
