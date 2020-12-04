<div class="btn-group">
    <button type="button" title="Mais" class="btn btn-xs btn-default dropdown-toggle dropdown-toggle-split"
            data-toggle="dropdown"  aria-haspopup="true" aria-expanded="false" title="Mais"><i class="fa fa-gears"></i>
        <span class="caret"></span>
        <span class="sr-only">Toggle Dropdown</span>
    </button>
    <ul class="dropdown-menu dropdown-menu-right">
        {{--        <li class="dropdown-header"><i class="fa fa-list"></i> Itens Contrato:</li>--}}
        <li><a href="/empenho/minuta/{{$entry->getKey()}}/alteracao">&nbsp;&nbsp;&nbsp;<i class="fa fa-angle-right"></i>Alterar Minuta</a></li>
    </ul>
</div>
