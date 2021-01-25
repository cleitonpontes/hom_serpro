<?php

namespace App\Http\Traits;

use App\Models\Feriado;
use DateTime;
use Exception;
use Illuminate\Support\Carbon;

trait Formatador
{

    /**
     * Retorna $campo data formatado no padrão pt-Br: dd/mm/yyyy
     *
     * @param $campo
     * @return string
     * @author Anderson Sathler <asathler@gmail.com>
     */
    public function retornaDataAPartirDeCampo($campo, $formatoOrigem = 'Y-m-d', $formatoDestino = 'd/m/Y')
    {
        if (is_null($campo)) {
            return '';
        }

        try {
            $data = DateTime::createFromFormat($formatoOrigem, $campo);
            $retorno = ($data !== false) ? $data->format($formatoDestino) : '';
        } catch (Exception $e) {
            $retorno = '';
        }

        return $retorno;
    }

    /**
     * Retorna $campo numérico formatado no padrão pt-Br: 0.000,00, incluindo ou não 'R$ ' segundo $prefix
     *
     * @param $campo
     * @return string
     * @author Anderson Sathler <asathler@gmail.com>
     */
    public function retornaCampoFormatadoComoNumero($campo, $prefix = false)
    {
        try {
            $numero = number_format($campo, 2, ',', '.');
            $numeroPrefixado = ($prefix === true ? 'R$ ' : '') . $numero;
            $retorno = ($campo < 0) ? "($numeroPrefixado)" : $numeroPrefixado;
        } catch (Exception $e) {
            $retorno = '';
        }

        return $retorno;
    }

    public function formataProcesso($mask, $str)
    {
        $str = str_replace(" ", "", $str);

        for ($i = 0; $i < strlen($str); $i++) {
            $mask[strpos($mask, "#")] = $str[$i];
        }

        return $mask;
    }

    public function formataDataSiasg($data)
    {
        return $this->retornaDataAPartirDeCampo($data, 'Ymd', 'Y-m-d');
    }

    public function formataDecimalSiasg($dado)
    {
        return number_format($dado, 2, '.', '');
    }

    public function formataIntengerSiasg($dado)
    {
        return number_format($dado, 0, '', '');
    }

    public function formataNumeroContratoLicitacao($dado): string
    {
        $d[0] = substr($dado, 0, 5);
        $d[1] = substr($dado, 5, 4);

        return $d[0] . '/' . $d[1];
    }

    public function formataCnpjCpf($dado)
    {
        $retorno = $dado;
        $tipo = $this->retornaTipoFornecedor($dado);

        if ($tipo == 'JURIDICA') {
            $retorno = $this->formataCnpj($dado);
        }

        if ($tipo == 'FISICA') {
            $retorno = $this->formataCpf($dado);
        }

        return $retorno;
    }

    public function retornaTipoFornecedor($dado)
    {

        if (strlen($dado) == 9 || $dado === 'ESTRANGEIRO') {
            return 'IDGENERICO';
        }

        if (strlen($dado) == 11) {
            return 'FISICA';
        }

        if (strlen($dado) == 14) {
            return 'JURIDICA';
        }
        return 'UG';
    }

    public function formataCnpj($numero)
    {
        $d[0] = substr($numero, 0, 2);
        $d[1] = substr($numero, 2, 3);
        $d[2] = substr($numero, 5, 3);
        $d[3] = substr($numero, 8, 4);
        $d[4] = substr($numero, 12, 2);

        return $d[0] . '.' . $d[1] . '.' . $d[2] . '/' . $d[3] . '-' . $d[4];
    }

    public function formataCpf($numero)
    {
        $d[0] = substr($numero, 0, 3);
        $d[1] = substr($numero, 3, 3);
        $d[2] = substr($numero, 6, 3);
        $d[3] = substr($numero, 9, 2);

        return $d[0] . '.' . $d[1] . '.' . $d[2] . '-' . $d[3];
    }

    public function retornaMascaraCpf($cpf)
    {
        return '***' . substr($cpf, 3, 9) . '**';
    }

    public function retornaFormatoAmericano($valor)
    {
        return str_replace(',', '.', str_replace('.', '', $valor));
    }

    /**
     * Retorna campo com a descricao detalhada para visualização na tabela
     * @param string $descricao
     * @param string $descricaocompleta
     * @return string
     */
    public function retornaDescricaoDetalhada(string $descricao = null, string $descricaocompleta = null): string
    {
        if ($descricao == null) {
            return '';
        }

        $retorno = $descricao . ' <i class="fa fa-info-circle" title="' . $descricaocompleta . '"></i>';

        return $retorno;
    }

    public function removeMascaraCPF($cpfComMask)
    {
        $cpf = str_replace('.', '', $cpfComMask);
        $cpf = str_replace('-', '', $cpf);
        return $cpf;
    }


    public function verificaDataDiaUtil($data)
    {
        $hoje = date('Y-m-d');
        $data_publicacao = Carbon::createFromFormat('Y-m-d', $data);
        $proximoDiaUtil = $data_publicacao;
        $hoje_ate18hs = Carbon::createFromFormat('Y-m-d', $hoje)->setTime(18, 00, 00);
        $hoje_pos18hs = Carbon::createFromFormat('Y-m-d', $hoje)->setTime(23, 59, 59);
        $feriados = Feriado::select('data')->pluck('data')->toArray();

        if ($data_publicacao->lessThanOrEqualTo($hoje_ate18hs)) {
            $proximoDiaUtil = $data_publicacao->nextWeekday();
            (in_array($proximoDiaUtil->toDateString(), $feriados)) ? $proximoDiaUtil->nextWeekday() : '';
        }

        if ($data_publicacao->isAfter($hoje_ate18hs) && $data_publicacao->lessThan($hoje_pos18hs)) {
            $proximoDiaUtil = $data_publicacao->nextWeekday()->addWeekday();
            (in_array($proximoDiaUtil->toDateString(), $feriados)) ? $proximoDiaUtil->nextWeekday() : '';
        }

        if ($data_publicacao->isAfter($hoje_pos18hs)) {
            $proximoDiaUtil = (!$data_publicacao->isWeekday()) ? $data_publicacao->nextWeekday() : $proximoDiaUtil;
            (in_array($proximoDiaUtil->toDateString(), $feriados)) ? $proximoDiaUtil->nextWeekday() : '';
        }

        return ($proximoDiaUtil->toDateString());

    }

    function validaCPF($cpf) {

        // Extrai somente os números
        $cpf = preg_replace( '/[^0-9]/is', '', $cpf );

        // Verifica se foi informado todos os digitos corretamente
        if (strlen($cpf) != 11) {
            return false;
        }

        // Verifica se foi informada uma sequência de digitos repetidos. Ex: 111.111.111-11
        if (preg_match('/(\d)\1{10}/', $cpf)) {
            return false;
        }

        // Faz o calculo para validar o CPF
        for ($t = 9; $t < 11; $t++) {
            for ($d = 0, $c = 0; $c < $t; $c++) {
                $d += $cpf[$c] * (($t + 1) - $c);
            }
            $d = ((10 * $d) % 11) % 10;
            if ($cpf[$c] != $d) {
                return false;
            }
        }
        return true;

    }

    public function retornaSomenteNumeros($value)
    {
        return preg_replace("/\D/", '',$value);
    }


}
