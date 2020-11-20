<a href="javascript:void(0)" onclick="confirmarExclusaoMovimentacao('/gescon/movimentacao/{{ $entry->getKey()}}/excluir')" data-route="{{ url($crud->route.'/'.$entry->getKey()) }}" class="btn btn-xs btn-default" data-button-type="delete" title="{{ trans('backpack::crud.delete') }}"><i class="fa fa-trash"></i> </a>
<script>
    // Função que mostrar uma caixa de confirmação para então proceder com a exclusão da movimentação.
    function confirmarExclusaoMovimentacao(url){
        var resposta = confirm("Confirma exclusão da movimentação?");
        if (resposta == true) {
            window.location.href=url;
        } else {
            return;
        }
    }
</script>
