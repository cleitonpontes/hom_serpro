<div class="btn-group">
    <button type="button" title="Mais" class="btn btn-xs btn-default dropdown-toggle dropdown-toggle-split"
            data-toggle="dropdown"  aria-haspopup="true" aria-expanded="false" title="Mais"><i class="fa fa-gears"></i>
        <span class="caret"></span>
        <span class="sr-only">Toggle Dropdown</span>
    </button>
    <ul class="dropdown-menu dropdown-menu-right">
        <li class="dropdown-header"><i class="fa fa-list"></i> Conta Vinculada:</li>
        <li><a href="/gescon/contrato/contratoconta/movimentacaocontratoconta/{{ $entry->getKey()}}/lancamento">&nbsp;&nbsp;&nbsp;<i class="fa fa-angle-right"></i>Lançamentos</a></li>
        <li><a href="/gescon/movimentacao/{{ $entry->getKey()}}/excluir">&nbsp;&nbsp;&nbsp;<i class="fa fa-angle-right"></i>Excluir movimentação</a></li>

        <!-- <li><a href="/gescon/contrato/contratoconta/{{\Route::current()->parameter('contratoconta_id')}}/excluirmovimentacao">&nbsp;&nbsp;&nbsp;<i class="fa fa-angle-right"></i>Excluir Movimentação</a></li> -->
        <!-- $contratoconta_id = \Route::current()->parameter('contratoconta_id'); -->


        <!-- /gescon/contrato/contratoconta/26/movimentacaocontratoconta -->
    </ul>
</div>
