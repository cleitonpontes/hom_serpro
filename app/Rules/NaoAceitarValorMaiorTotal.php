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
     * Create a new rule instance.
     *
     * @param array $valor_total_item
     */
//    public function __construct($from)
    public function __construct(array $valor_total_item)
    {
        $this->valor_total_item = $valor_total_item;
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
        $index = substr($attribute, strpos($attribute, '.') + 1);
        $valor_selecionado = $this->retornaFormatoAmericano($value);

        return $valor_selecionado <= $this->valor_total_item[$index];
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
