<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class CreateSituacoesView extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $sql = '';
        $sql .= 'CREATE VIEW ';
        $sql .= '    situacoes ';
        $sql .= 'AS ( ';
        $sql .= '    SELECT ';
        $sql .= '        S.nd                         AS natureza, ';
        $sql .= '        S.ddp_nivel                  AS nivel, ';
        $sql .= '        E.categoria_ddp              AS categoria, ';
        $sql .= '        R.codigo                     AS rubrica, ';
        $sql .= '        E.codigo                     AS situacao, ';
        $sql .= '        S.vpd                        AS vpd ';
        $sql .= '    FROM ';
        $sql .= '        rhsituacao                   AS S ';
        $sql .= '    LEFT JOIN ';
        $sql .= '        rhsituacao_rhrubrica         AS Z on ';
        $sql .= '            Z.rhsituacao_id = S.id ';
        $sql .= '    LEFT JOIN ';
        $sql .= '        rhrubrica                    AS R on ';
        $sql .= '            R.id = Z.rhrubrica_id ';
        $sql .= '    LEFT JOIN ';
        $sql .= '        execsfsituacao               AS E on ';
        $sql .= '            E.id = S.execsfsituacao_id ';
        $sql .= '    WHERE ';
        $sql .= "        R.situacao = 'ATIVA' ";
        $sql .= ') ';
        
        DB::statement($sql);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $sql = '';
        $sql .= 'DROP VIEW IF EXISTS ';
        $sql .= '    situacoes ';
        
        DB::statement($sql);
    }
    
}
