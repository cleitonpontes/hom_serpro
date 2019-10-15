<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterEmpenhodetalhadoAddContasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('empenhodetalhado', function (Blueprint $table) {
            $table->decimal('rpnpaliquidinsc',17,2)->default(0); //5.3.1.1.1.01.00 = RP NAO PROCESSADOS A LIQUIDAR INSCRITOS
            $table->decimal('rpnpemliquidinsc',17,2)->default(0); //5.3.1.1.1.02.00 = RP NAO PROCESSADOS EM LIQUIDACAO INSCRITOS
            $table->decimal('reinscrpnpaliquidbloq',17,2)->default(0); //5.3.1.2.1.00.00 = REINSCRICAO RPNP A LIQUIDAR/BLOQUEADOS
            $table->decimal('reinscrpnpemliquid',17,2)->default(0); //5.3.1.2.2.00.00 = REINSCRICAO RP NAO PROCESSADO EM LIQUIDACAO
            $table->decimal('rpnprestab',17,2)->default(0); //5.3.1.3.0.00.00 = RP NAO PROCESSADOS RESTABELECIDOS
            $table->decimal('rpnpaliquidtransfdeb',17,2)->default(0); //5.3.1.6.1.00.00 = RPNP A LIQUIDAR RECEBIDO POR TRANSFERENCIA
            $table->decimal('rpnpaliquidemliquidtransfdeb',17,2)->default(0); //5.3.1.6.2.00.00 = RPNP A LIQ EM LIQ RECEBIDO POR TRANSFERENCIA
            $table->decimal('rpnpliquidapgtransfdeb',17,2)->default(0); //5.3.1.6.3.00.00 = RPNP LIQ A PAGAR RECEBIDOS POR TRANSFERENCIA
            $table->decimal('rpnpbloqtransfdeb',17,2)->default(0); //5.3.1.6.4.00.00 = RPNP BLOQUEADOS RECEBIDOS POR TRANSFERENCIA
            $table->decimal('rppinsc',17,2)->default(0); //5.3.2.1.0.00.00 = RP PROCESSADOS - INSCRITOS
            $table->decimal('rppexecant',17,2)->default(0); //5.3.2.2.0.00.00 = RP PROCESSADOS - EXERCICIOS ANTERIORES
            $table->decimal('rpptrasf',17,2)->default(0); //5.3.2.6.0.00.00 = RP PROCESSADOS RECEBIDOS POR TRANSFERENCIA
            $table->decimal('rpnpaliquidtransfcred',17,2)->default(0); //6.3.1.6.1.00.00 = RPNP A LIQUIDAR TRANSFERIDO
            $table->decimal('rpnpaliquidemliquidtransfcred',17,2)->default(0); //6.3.1.6.2.00.00 = RPNP A LIQUIDAR EM LIQUIDACAO TRANSFERIDO
            $table->decimal('rpnpliquidapgtransfcred',17,2)->default(0); //6.3.1.6.3.00.00 = RPNP LIQUIDADOS A PAGAR TRANSFERIDOS
            $table->decimal('rpnpbloqtransfcred',17,2)->default(0); //6.3.1.6.4.00.00 = RPNP BLOQUEADOS TRANSFERIDOS
            $table->decimal('rpptransffusao',17,2)->default(0); //6.3.2.6.0.00.00 = RPP TRANSFERIDOS POR FUSAO/CISAO/EXTINCAO
            $table->decimal('ajusterpexecant',17,2)->default(0); //6.3.2.9.1.02.00 =  AJUSTE DE CONTROLE RP DE EXERC ANTERIORES
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('empenhodetalhado', function ($table) {
            $table->dropColumn('rpnpaliquidinsc');
            $table->dropColumn('rpnpemliquidinsc');
            $table->dropColumn('reinscrpnpaliquidbloq');
            $table->dropColumn('reinscrpnpemliquid');
            $table->dropColumn('rpnprestab');
            $table->dropColumn('rpnpaliquidtransfdeb');
            $table->dropColumn('rpnpaliquidemliquidtransfdeb');
            $table->dropColumn('rpnpliquidapgtransfdeb');
            $table->dropColumn('rpnpbloqtransfdeb');
            $table->dropColumn('rppinsc');
            $table->dropColumn('rppexecant');
            $table->dropColumn('rpptrasf');
            $table->dropColumn('rpnpaliquidtransfcred');
            $table->dropColumn('rpnpaliquidemliquidtransfcred');
            $table->dropColumn('rpnpliquidapgtransfcred');
            $table->dropColumn('rpnpbloqtransfcred');
            $table->dropColumn('rpptransffusao');
            $table->dropColumn('ajusterpexecant');
        });
    }
}
