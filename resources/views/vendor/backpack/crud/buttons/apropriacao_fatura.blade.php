@if ($crud->hasAccess('update'))
    <a href="{{ route('apropriacao.fatura.create', ['contrato' => $entry->contrato_id, 'id' => $entry->getKey()]) }}"
       class="btn btn-xs btn-default"
       title="Apropriação de fatura"
    >
        <i class="fa fa-file-text-o"></i>
    </a>
@endif

