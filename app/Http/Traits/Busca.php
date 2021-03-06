<?php

namespace App\Http\Traits;

trait Busca
{
    public function buscaDadosCurl($url) : array
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_TIMEOUT, 180);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 180);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_URL, $url);
        $data = curl_exec($ch);

        curl_close($ch);

        return json_decode($data, true);
    }

    public function buscaDadosFileGetContents($url, $context = null) : array
    {
        if($context){
            return json_decode(file_get_contents($url, false, $context), true);
        }

        return json_decode(file_get_contents($url), true);
    }


}
