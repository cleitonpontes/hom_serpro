<?php

return [
    'host' => env('ACESSOGOV_HOST'),
    'client_id' => env('ACESSOGOV_CLIENT_ID'),
    'secret' => env('ACESSOGOV_SECRET'),
    'response_type' => 'code',
    'scope' => 'openid+email+phone+profile+govbr_confiabilidades',
];
