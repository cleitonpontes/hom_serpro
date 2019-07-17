<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class AlterAtualizaSaldoFunction extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //deleta function de executar a atualização
        $sql = '';
        $sql .= 'DROP TRIGGER IF EXISTS ';
        $sql .= '    executa_atualiza_saldo ';
        $sql .= 'ON ';
        $sql .= '    empenhodetalhado; ';
        DB::statement($sql);

        //deleta trigger de atualiza saldo
        $sql = '';
        $sql .= 'DROP FUNCTION IF EXISTS ';
        $sql .= '    atualiza_saldos() ';
        DB::statement($sql);

        //cria trigger de atualiza saldo
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
        $sql .= '        empenhado   = origem.rpinscrito ';
        $sql .= '        empenhado   = origem.rpaliquidar ';
        $sql .= '        empenhado   = origem.rpliquidado ';
        $sql .= '        empenhado   = origem.rppago ';
        $sql .= '    FROM ';
        $sql .= '        ( ';
        $sql .= '        SELECT ';
        $sql .= '            empenho_id, ';
        //empenho aliquidar
        $sql .= '            coalesce(sum(empaliquidar), 0) + ';
        $sql .= '            coalesce(sum(empemliquidacao), 0)               as aliquidar, ';
        //empenho liquidado
        $sql .= '            coalesce(sum(emprpp), 0) + ';
        $sql .= '            coalesce(sum(empliquidado), 0)               as liquidado, ';
        //empenho pago
        $sql .= '            coalesce(sum(emppago), 0)                    as pago, ';
        // Abaixo: Total do valor empenhado
        $sql .= '            coalesce(sum(empaliquidar), 0) + ';
        $sql .= '            coalesce(sum(empemliquidacao), 0) + ';
        $sql .= '            coalesce(sum(empliquidado), 0) + ';
        $sql .= '            coalesce(sum(empaliqrpnp), 0) + ';
        $sql .= '            coalesce(sum(empemliqrpnp), 0) + ';
        $sql .= '            coalesce(sum(emprpp), 0) + ';
        $sql .= '            coalesce(sum(emppago), 0)                    as empenhado ';

        //rp aliquidar
        $sql .= '            coalesce(sum(rpnpaliquidar), 0) + ';
        $sql .= '            coalesce(sum(rpnpaliquidaremliquidacao), 0)               as rpaliquidar, ';

        //rp liquidado
        $sql .= '            coalesce(sum(rpnpaliquidar), 0) + ';
        $sql .= '            coalesce(sum(rpnpaliquidaremliquidacao), 0) + ';
        $sql .= '            coalesce(sum(rpnpliquidado), 0) + ';
        $sql .= '            coalesce(sum(rpnpaliquidarbloq), 0) + ';
        $sql .= '            coalesce(sum(rpnpaliquidaremliquidbloq), 0) + ';
        $sql .= '            coalesce(sum(rppliquidado), 0)               as rpliquidado, ';

        //rp pago
        $sql .= '            coalesce(sum(rpnppago), 0) + ';
        $sql .= '            coalesce(sum(rpppago), 0)               as rppago, ';

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


        //cria function de executar a atualização
        $sql = '';
        $sql .= 'CREATE TRIGGER ';
        $sql .= '    executa_atualiza_saldo ';
        $sql .= 'AFTER ';
        $sql .= '    insert or update or delete ';
        $sql .= 'ON ';
        $sql .= '    empenhodetalhado ';
        $sql .= 'FOR EACH ROW ';
        $sql .= '    EXECUTE PROCEDURE ';
        $sql .= '        atualiza_saldos(); ';
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
        $sql .= 'DROP TRIGGER IF EXISTS ';
        $sql .= '    executa_atualiza_saldo ';
        $sql .= 'ON ';
        $sql .= '    empenhodetalhado; ';
        DB::statement($sql);

        $sql = '';
        $sql .= 'DROP FUNCTION IF EXISTS ';
        $sql .= '    atualiza_saldos() ';
        DB::statement($sql);
    }
}
