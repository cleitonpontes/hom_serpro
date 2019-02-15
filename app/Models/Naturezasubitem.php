<?php

namespace App\Models;

use Backpack\CRUD\CrudTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;
use Spatie\Activitylog\Traits\LogsActivity;

class Naturezasubitem extends Model
{
    use CrudTrait;
    use LogsActivity;


    protected static $logFillable = true;
    protected static $logName = 'naturezasubitem';

    protected $table = 'naturezasubitem';

    public function naturezadespesa()
    {
        return $this->belongsTo(Naturezadespesa::class, 'naturezadespesa_id');
    }


    public function retornaNdDetalhada()
    {
        $sql = '';
        $sql .= 'SELECT ';
        $sql .= '	"N"."codigo" || "S"."codigo" || \' - \' || "S"."descricao"  AS nome, ';
        $sql .= '	"N"."codigo" || "S"."codigo"  AS id ';
        $sql .= 'from "naturezasubitem" as "S" ';
        $sql .= '	left join "naturezadespesa" as "N" ';
        $sql .= '		on "N"."id" = "naturezadespesa_id" ';
        $sql .= 'where ';
        $sql .= '	"S"."situacao" = true ';
        $sql .= '	and "S"."codigo" <> \'00\' ';
        $sql .= 'order by  ';
        $sql .= '	"N"."codigo" asc, ';
        $sql .= '	"S"."codigo" asc ';

        $dados = DB::select($sql);

        foreach ($dados as $ds){
            $retorno [$ds->id] =  $ds->nome;
        }

        return $retorno;

    }


}
