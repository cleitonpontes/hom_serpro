<?php

use Illuminate\Database\Seeder;

class UnidadeconfiguracaoTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {



        \DB::table('unidadeconfiguracao')->insert(array (
            0 =>
            array (
                'id' => 55000001,
                'unidade_id' => 55000001,
                'user1_id' => 55002598,
                'user2_id' => NULL,
                'user3_id' => NULL,
                'user4_id' => NULL,
            'telefone1' => '(61) 3030-8068',
                'telefone2' => NULL,
                'email_diario' => true,
                'email_diario_periodicidade' => '30;60;90;120;150;180;210',
            'email_diario_texto' => '<p>Prezado(a) Sr(a).&nbsp;!!nomeresponsavel!! ,&nbsp;</p>

<p>1. O Contrato em quest&atilde;o ter&aacute; a vig&ecirc;ncia encerrada no per&iacute;odo especificado.</p>

<p>2. &nbsp;Na hip&oacute;tese de contrato prorrog&aacute;vel, solicito encaminhar resposta, quanto &agrave; necessidade de &nbsp;prorroga&ccedil;&atilde;o ou n&atilde;o, em 5 (cinco) dias &uacute;teis.</p>

<p>3. No caso de contrato n&atilde;o prorrog&aacute;vel, ser&aacute; necess&aacute;ria elabora&ccedil;&atilde;o de Estudo Preliminar, de acordo com o planejamento das contrata&ccedil;&otilde;es, para a contrata&ccedil;&atilde;o. Nesse caso, a Se&ccedil;&atilde;o de Apoio ao Requisitante (SEARE/CODAQ/SAD) entrar&aacute; em contato com essas unidades para confirmar a necessidade de formaliza&ccedil;&atilde;o de nova contrata&ccedil;&atilde;o, bem como orientar quanto &agrave; elabora&ccedil;&atilde;o do mencionado documento.</p>

<p>4. &Agrave; ASAG-SAD, para ci&ecirc;ncia e incluir no monitoramento da unidade.</p>',
                'email_mensal' => true,
                'email_mensal_dia' => 1,
            'email_mensal_texto' => '<p>Prezado(a) Sr(a).&nbsp;!!nomeresponsavel!! ,&nbsp;<br />
<br />
Segue rela&ccedil;&atilde;o de contratos de sua responsabilidade:</p>',
                'created_at' => '2020-03-24 18:55:23',
                'updated_at' => '2020-05-05 20:48:16',
            ),
        ));


    }
}
