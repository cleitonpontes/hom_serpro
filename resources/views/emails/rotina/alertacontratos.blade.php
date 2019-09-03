@component('mail::message')
# Rotina de Alerta {{ $nomerotina }}

{{ $texto }}

@component('mail::table')
    | Número | Processo | Fornecedor | Objeto | Valor Global (R$) | Vig. início | Vig. Fim |
    | :----: | :------: | ---------- | ---------- | ------ |-----------------: | :---------: | :------: |
@foreach($contratos as $contrato)
    | {{$contrato['numero']}} | {{$contrato['processo']}} | {{ $contrato['cpf_cnpj_idgener'] . ' - ' . $contrato['nome'] }} | {{$contrato['objeto']}} {{number_format($contrato['valor_global'],2,',','.')}} | {{ implode("/",array_reverse(explode("-", $contrato['vigencia_inicio']))) }} | {{ implode("/",array_reverse(explode("-", $contrato['vigencia_fim']))) }} |
@endforeach
@endcomponent

EM CASO DE DÚVIDAS LIGUE PARA SUA SUPERINTENDÊNCIA DE ADMINISTRAÇÃO NOS TELEFONES: {{ $telefones }}.

Atenciosamente,<br>
{{ config('app.name') }}<br><br>

E-mail Gerado Automaticamente. Por favor não responda.
@endcomponent
