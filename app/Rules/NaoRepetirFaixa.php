<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use App\Http\Traits\Formatador;
use App\Models\Glosa;



class NaoRepetirFaixa implements Rule
{
    use Formatador;

    private $contratoitem_servico_indicador_id;
    private $faixa;
    private $from;
    /**
     * @var string
     */
    private $to;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($contratoitem_servico_indicador_id, $from)
    {
        $this->contratoitem_servico_indicador_id = $contratoitem_servico_indicador_id;
        $this->from = $this->retornaFormatoAmericano($from);
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
        $this->to = $this->retornaFormatoAmericano($value);

        $glosas = Glosa::where('contratoitem_servico_indicador_id'
            , $this->contratoitem_servico_indicador_id
        )->where(function ($query)  {
            $query->where('from', '<=', $this->from)
                ->Where('to', '>=', $this->from)
                ->orWhere(function ($query)  {
                    $query->where('from', '<=', $this->to)
                        ->Where('to', '>=', $this->to);
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
