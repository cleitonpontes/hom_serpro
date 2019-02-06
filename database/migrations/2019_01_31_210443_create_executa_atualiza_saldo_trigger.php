<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class CreateExecutaAtualizaSaldoTrigger extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
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
    }
}
