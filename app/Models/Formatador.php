<?php

namespace App\Models;

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
        try {
            $data = \DateTime::createFromFormat('Y-m-d', $campo);
            $retorno = $data->format('d/m/Y');
        } catch (\Exception $e) {
            $retorno = '';
        }

        return $retorno;
    }

    /**
     * Retorna $campo numérico formatado no padrão pt-Br: 0.000,00
     *
     * @param $campo
     * @return string
     * @author Anderson Sathler <asathler@gmail.com>
     */
    public function retornaCampoFormatadoComoNumero($campo)
    {
        try {
            $retorno = number_format($campo, 2, ',', '.');
        } catch (\Exception $e) {
            $retorno = '';
        }

        return $retorno;
    }

}
