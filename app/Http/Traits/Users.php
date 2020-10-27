<?php
/**
 * Created by PhpStorm.
 * User: heles.junior
 * Date: 28/11/2018
 * Time: 15:58
 */
namespace App\Http\Traits;

use App\Models\Unidade;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;

trait Users{
    public function buscaUg()
    {
        $ug = [];

        $ugprimaria = Unidade::select(DB::raw("CONCAT(codigo,' - ',nomeresumido) AS nome"), 'id')
            ->where('id', '=', backpack_user()->ugprimaria)
            ->where('tipo', '=', 'E')
            ->pluck('nome', 'id')
            ->toArray();

        $ugsecundaria = Unidade::select(DB::raw("CONCAT(codigo,' - ',nomeresumido) AS nome"), 'id')
            ->whereHas('users', function ($query) {
                $query->where('user_id', '=', backpack_user()->id);
            })
            ->where('tipo', '=', 'E')
            ->pluck('nome', 'id')
            ->toArray();

        $ug = $ugprimaria + $ugsecundaria;

        asort($ug);

        return $ug;
    }
}
