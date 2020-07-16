<?php

namespace App\Http\Traits;

trait Formatador
{

    /**
     * Retorna $campo data formatado no padrão pt-Br: dd/mm/yyyy
     *
     * @param $campo
     * @return string
     * @author Anderson Sathler <asathler@gmail.com>
     */
    public function retornaDataAPartirDeCampo($campo)
    {
        if (is_null($campo)) {
            return '';
        }

        try {
            $data = \DateTime::createFromFormat('Y-m-d', $campo);
            $retorno = $data->format('d/m/Y');
        } catch (\Exception $e) {
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
        } catch (\Exception $e) {
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

    public function formataDataSiasg($dado)
    {
        $data = \DateTime::createFromFormat('Ymd', $dado);
        $retorno = $data->format('Y-m-d');
        return $retorno;
    }

    public function formataDecimalSiasg($dado)
    {
        return number_format($dado,2,'.','');
    }

    public function formataIntengerSiasg($dado)
    {
        return number_format($dado,0);
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
            $d[0] = substr($dado, 0, 2);
            $d[1] = substr($dado, 2, 3);
            $d[2] = substr($dado, 5, 3);
            $d[3] = substr($dado, 8, 4);
            $d[4] = substr($dado, 12, 2);

            $retorno = $d[0] . '.' . $d[1] . '.' . $d[2] . '/' . $d[3] . '-' . $d[4];

        }

        if ($tipo == 'FISICA') {
            $d[0] = substr($dado, 0, 3);
            $d[1] = substr($dado, 3, 3);
            $d[2] = substr($dado, 6, 3);
            $d[3] = substr($dado, 9, 2);

            $retorno = $d[0] . '.' . $d[1] . '.' . $d[2] . '-' . $d[3];
        }

        return $retorno;
    }

    public function retornaTipoFornecedor($dado)
    {
        $retorno = 'UG';

        if (strlen($dado) == 9) {
            $retorno = 'IDGENERICO';
        }

        if (strlen($dado) == 11) {
            $retorno = 'FISICA';
        }

        if (strlen($dado) == 14) {
            $retorno = 'JURIDICA';
        }

        return $retorno;
    }

}
