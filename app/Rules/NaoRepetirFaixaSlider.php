<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use App\Models\Glosa;

class NaoRepetirFaixaSlider implements Rule
{
    private $contratoitem_servico_indicador_id;
    private $faixa;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($contratoitem_servico_indicador_id)
    {
        $this->contratoitem_servico_indicador_id = $contratoitem_servico_indicador_id;
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
        $this->faixa = $value;
        $faixa = explode(";", $value);
        $glosas = Glosa::where('contratoitem_servico_indicador_id'
            , $this->contratoitem_servico_indicador_id
        )->where(function ($query) use ($faixa) {
            $query->where('from', '<=', $faixa[0])
                ->Where('to', '>=', $faixa[0])
                ->orWhere(function ($query) use ($faixa) {
                    $query->where('from', '<=', $faixa[1])
                        ->Where('to', '>=', $faixa[1]);

                });
        })->get();
        return $glosas->isEmpty();
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return "A faixa de ajuste selecionada está em uso.";
    }
}
