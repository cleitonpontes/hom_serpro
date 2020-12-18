<?php

namespace App\Rules;

use App\Models\MinutaEmpenho;
use Illuminate\Contracts\Validation\Rule;

class NaoAceitarMinutaCompraDiferente implements Rule
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
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $minutas = MinutaEmpenho::select('compra_id')
            ->whereIn('id', $value)
            ->distinct('compra_id')->pluck('compra_id')->toArray();

        if((is_array($minutas) ? count($minutas) : 0) > 1){
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
        return 'As Minutas selecionadas devem ser da mesma compra .';
    }
}
