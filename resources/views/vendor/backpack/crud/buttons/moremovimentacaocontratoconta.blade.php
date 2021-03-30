<div class="btn-group">
    <button type="button" title="Mais" class="btn btn-xs btn-default dropdown-toggle dropdown-toggle-split"
            data-toggle="dropdown"  aria-haspopup="true" aria-expanded="false" title="Mais"><i class="fa fa-gears"></i>
        <span class="caret"></span>
        <span class="sr-only">Toggle Dropdown</span>
    </button>
    <ul class="dropdown-menu dropdown-menu-right">
        <li class="dropdown-header"><i class="fa fa-list"></i> Conta-Depósito Vinculada:</li>
        <li><a href="/gescon/contrato/contratoconta/movimentacaocontratoconta/{{ $entry->getKey()}}/lancamento">&nbsp;&nbsp;&nbsp;<i class="fa fa-angle-right"></i>Lançamentos</a></li>
        <!-- <li><a href="javascript:void(0)" onclick="confirmarExclusaoMovimentacao('/gescon/movimentacao/{{ $entry->getKey()}}/excluir')" >&nbsp;&nbsp;&nbsp;<i class="fa fa-angle-right"></i>Excluir movimentação</a></li> -->
    </ul>
</div>


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
