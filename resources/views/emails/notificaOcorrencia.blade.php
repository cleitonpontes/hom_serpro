
@component('mail::message')
# Notificação Ocorrência

Foi registrado uma Ocorrência por um Usuário do Comprasnet - Gestão de Contratos, com as seguintes informações:

* Órgão: **{{ $dadosocorrencia['orgao'] }}**
* Unidade: **{{ $dadosocorrencia['unidade'] }}**
* Fornecedor: **{{ $dadosocorrencia['fornecedor'] }}**
* Número Contrato: **{{ $dadosocorrencia['contrato_numero'] }}**
* Responsável pela Ocorrência: **{{ $dadosocorrencia['user'] }}**
* Situação da Ocorrência: **{{ $dadosocorrencia['situacao'] }}**
* Data da Ocorrência: **{{ implode('/', array_reverse(explode('-', $dadosocorrencia['data']))) }}**
* Texto Ocorrência: **{{ $dadosocorrencia['textoocorrencia'] }}**

Atenciosamente,<br>
{{ config('app.name') }}<br><br>

E-mail Gerado Automaticamente. Por favor não responda.
@endcomponent
