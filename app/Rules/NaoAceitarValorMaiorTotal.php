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
     * Create a new rule instance.
     *
     * @param $from
     */
//    public function __construct($from)
    public function __construct()
    {
//        dd($from);
//        $this->from = $this->retornaFormatoAmericano($from);
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
//        dd($attribute, $value);
        return $this->retornaFormatoAmericano($value) > $this->from;
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
