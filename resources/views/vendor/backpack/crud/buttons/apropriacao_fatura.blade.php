@php
$faturaApropriada = isset($entry->contratofaturas_id);

$hint = 'Apropriação de fatura';
$link = route('apropriacao.fatura.create', ['contrato' => $entry->contrato_id, 'id' => $entry->getKey()]);
$cor = '';
$disabled = '';

if ($faturaApropriada) {
    $hint = 'Fatura já apropriada';
    $link = '#!';
    $cor = 'btn-danger';
    $disabled = 'disabled';
}
@endphp

@if (backpack_user()->hasRole('Execução Financeira'))
    <a href="{{ $link }}"
       class="btn btn-xs btn-default {{ $cor }}"
       title="{{ $hint }}"
       {{ $disabled }}
    >
        <i class="fa fa-file-text-o"></i>
    </a>
@endif
