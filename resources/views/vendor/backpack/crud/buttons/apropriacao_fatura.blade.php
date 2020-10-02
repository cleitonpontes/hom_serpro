{{-- Uso deste componente DEPENDE de bulk_apropriacao_fatura.blade.php --}}
@if ($crud->hasAccess('update'))
    <a href="{{ route('apropriacao.fatura.create', ['id' => $entry->getKey(), 'contrato' => $entry->contrato_id]) }}"
       class="btn btn-xs btn-default"
       title="Apropriação de fatura"
    >
        <i class="fa fa-file-text-o"></i>
    </a>
@endif

