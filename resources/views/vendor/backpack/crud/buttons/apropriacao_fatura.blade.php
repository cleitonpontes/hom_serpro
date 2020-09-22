@if ($crud->hasAccess('update'))
    <a href="{{ route('apropriacao.fatura.create', $entry->getKey()) }}"
       class="btn btn-xs btn-default"
       title="Apropriação"
    >
        <i class="fa fa-file-text-o"></i>
    </a>
@endif
