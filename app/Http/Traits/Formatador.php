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

}
