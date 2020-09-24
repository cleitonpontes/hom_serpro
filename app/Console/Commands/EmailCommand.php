<?php

namespace App\Console\Commands;

use App\Http\Controllers\Admin\AlertaContratoController;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

class EmailCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'contratos:email {tipo : [diario, mensal, ambos]}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Envio de emails de alertas aos usuários responsáveis por contratos da unidades que ' .
                             'optam pelo seu recebimento';

    private $tiposValidos = ['diario', 'mensal', 'ambos'];

    const TIPO_ENVIO_CANCELADO = 0;
    const TIPO_ENVIO_DIARIO = 1;
    const TIPO_ENVIO_MENSAL = 2;
    const TIPO_ENVIO_AMBOS = 3;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $tipoEnvio = Str::lower($this->argument('tipo'));

        if (!$this->ehTipoValido($tipoEnvio)) {
            $this->error("Tipo '" . $tipoEnvio . "' inválido!");
            $this->line('Os tipos de envio válidos são: <info>' . implode(', ', $this->tiposValidos) . '</info>');

            return false;
        }

        $this->$tipoEnvio();
    }

    private function ehTipoValido($tipoEnvio)
    {
        return in_array($tipoEnvio, $this->tiposValidos);
    }

    private function diario()
    {
        $this->line('Envio de emails diários...');

        $email = new AlertaContratoController();
        $email->emailDiario();

        $this->info('Alertas diários de contratos por unidade / usuário foram incluídos na fila de envio');
        $this->line('');
    }

    private function mensal()
    {
        $this->line('Envio de email mensal...');

        $email = new AlertaContratoController();
        $email->extratoMensal();

        $this->info('Alertas mensais de contratos por unidade / usuário foram incluídos na fila de envio');
        $this->line('');
    }

    private function ambos()
    {
        $this->diario();
        $this->mensal();
    }

    private function perguntaTipoDeEnvio()
    {
        return $this->choice(
            'Qual o tipo de envio de email desejado?',
            [
                self::TIPO_ENVIO_DIARIO => 'Diário: Envia email de alerta diário antecedência de vencimentos observando suas periodicidades',
                self::TIPO_ENVIO_MENSAL => 'Mensal: Envia email de alerta mensal conforme dia selecionado pela unidade',
                self::TIPO_ENVIO_AMBOS => 'Ambos, diário e mensal',
                self::TIPO_ENVIO_CANCELADO => 'Cancelar envio de email'
            ]
        );
    }
}
