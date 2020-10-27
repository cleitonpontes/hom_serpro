<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use App\Http\Traits\Formatador;

class ValorMaximoFaixaAjuste implements Rule
{
    use Formatador;

    /**
     * @var string|string[]
     */
    private $vlrmeta;

    /**
     * Create a new rule instance.
     *
     * @param $vlrmeta
     */
    public function __construct($vlrmeta)
    {
        $this->vlrmeta = $this->retornaFormatoAmericano($vlrmeta);
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
        return $this->vlrmeta > $this->retornaFormatoAmericano($value);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message(): string
    {
        return 'O valor máximo da faixa de ajuste tem que ser menor do que o valor da Meta: ' . $this->vlrmeta;
    }
}
