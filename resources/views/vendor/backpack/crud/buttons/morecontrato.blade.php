<div class="btn-group">
    <button type="button" title="Mais" class="btn btn-xs btn-default dropdown-toggle dropdown-toggle-split"
            data-toggle="dropdown"  aria-haspopup="true" aria-expanded="false"><i class="fa fa-gears"></i> Mais
        <span class="caret"></span>
        <span class="sr-only">Toggle Dropdown</span>
    </button>
    <ul class="dropdown-menu dropdown-menu-right">
        <li class="dropdown-header"><i class="fa fa-list"></i> Itens Contrato:</li>
        <li><a href="/gescon/contrato/{{$entry->getKey()}}/empenhos">&nbsp;&nbsp;&nbsp;<i class="fa fa-angle-right"></i>Empenhos</a></li>
        <li><a href="/gescon/contrato/{{$entry->getKey()}}/garantias">&nbsp;&nbsp;&nbsp;<i class="fa fa-angle-right"></i>Garantias</a></li>
        <li><a href="/gescon/contrato/{{$entry->getKey()}}/responsaveis">&nbsp;&nbsp;&nbsp;<i class="fa fa-angle-right"></i>Responsáveis</a></li>
        <li class="dropdown-header"><i class="fa fa-edit"></i> Modificar Contrato:</li>
        <li><a href="/gescon/contrato/{{$entry->getKey()}}/aditivos">&nbsp;&nbsp;&nbsp;<i class="fa fa-angle-right"></i>Termo Aditivo</a></li>
        <li><a href="/gescon/contrato/{{$entry->getKey()}}/apostilamentos">&nbsp;&nbsp;&nbsp;<i class="fa fa-angle-right"></i>Termo Apostilamento</a></li>
        <li><a href="/gescon/contrato/{{$entry->getKey()}}/rescisao">&nbsp;&nbsp;&nbsp;<i class="fa fa-angle-right"></i>Termo Rescisão</a></li>
    </ul>
</div>
