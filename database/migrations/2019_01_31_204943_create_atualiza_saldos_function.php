<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class CreateAtualizaSaldosFunction extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $sql = '';
        $sql .= 'CREATE FUNCTION ';
        $sql .= '    atualiza_saldos() ';
        $sql .= 'RETURNS TRIGGER ';
        $sql .= '    AS $saldo_atualizado$ ';
        $sql .= '';
        $sql .= 'BEGIN ';
        $sql .= '    UPDATE ';
        $sql .= '        empenhos ';
        $sql .= '    SET ';
        $sql .= '        aliquidar   = origem.aliquidar, ';
        $sql .= '        liquidado   = origem.liquidado, ';
        $sql .= '        pago        = origem.pago, ';
        $sql .= '        empenhado   = origem.empenhado ';
        $sql .= '    FROM ';
        $sql .= '        ( ';
        $sql .= '        SELECT ';
        $sql .= '            empenho_id, ';
        $sql .= '            coalesce(sum(empaliquidar), 0)               as aliquidar, ';
        $sql .= '            coalesce(sum(empliquidado), 0)               as liquidado, ';
        $sql .= '            coalesce(sum(emppago), 0)                    as pago, ';
        // Abaixo: Total do valor empenhado
        $sql .= '            coalesce(sum(empaliquidar), 0) + ';
        $sql .= '            coalesce(sum(empliquidado), 0) + ';
        $sql .= '            coalesce(sum(emppago), 0)                    as empenhado ';
        $sql .= '        FROM ';
        $sql .= '            empenhodetalhado ';
        $sql .= '        WHERE ';
        $sql .= '            empenho_id = new.empenho_id ';
        $sql .= '        GROUP BY ';
        $sql .= '            empenho_id ';
        $sql .= '        ) origem ';
        $sql .= '    WHERE ';
        $sql .= '        id = origem.empenho_id; ';
        $sql .= '';
        $sql .= '    RETURN NULL; ';
        $sql .= 'END; ';
        $sql .= '$saldo_atualizado$ LANGUAGE plpgsql; ';
        
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
        $sql .= 'DROP FUNCTION IF EXISTS ';
        $sql .= '    atualiza_saldos() ';

        DB::statement($sql);
    }
}
