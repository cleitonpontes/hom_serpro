<div class="btn-group">
    <button type="button" title="Mais" class="btn btn-xs btn-default dropdown-toggle dropdown-toggle-split"
            data-toggle="dropdown"  aria-haspopup="true" aria-expanded="false"><i class="fa fa-gears"></i> Mais
        <span class="caret"></span>
        <span class="sr-only">Toggle Dropdown</span>
    </button>
    <ul class="dropdown-menu dropdown-menu-right">
        <li class="dropdown-header"><i class="fa fa-list"></i> Itens Contrato:</li>
        <li><a href="/contrato/{{$entry->getKey()}}/empenho">&nbsp;&nbsp;&nbsp;<i class="fa fa-angle-right"></i>Empenhos</a></li>
        <li><a href="/contrato/{{$entry->getKey()}}/fatura">&nbsp;&nbsp;&nbsp;<i class="fa fa-angle-right"></i>Faturas</a></li>
        <li><a href="/contrato/{{$entry->getKey()}}/garantia">&nbsp;&nbsp;&nbsp;<i class="fa fa-angle-right"></i>Garantias</a></li>
        <li><a href="/contrato/{{$entry->getKey()}}/ocorrencia">&nbsp;&nbsp;&nbsp;<i class="fa fa-angle-right"></i>Ocorrências</a></li>
        <li><a href="/contrato/{{$entry->getKey()}}/terceirizado">&nbsp;&nbsp;&nbsp;<i class="fa fa-angle-right"></i>Terceirizados</a></li>
        <li class="dropdown-header"><i class="fa fa-edit"></i> Modificar Contrato:</li>
        <li><a href="/contrato/{{$entry->getKey()}}/aditivo">&nbsp;&nbsp;&nbsp;<i class="fa fa-angle-right"></i>Termo Aditivo</a></li>
        <li><a href="/contrato/{{$entry->getKey()}}/apostilamento">&nbsp;&nbsp;&nbsp;<i class="fa fa-angle-right"></i>Termo Apostilamento</a></li>
        <li><a href="/contrato/{{$entry->getKey()}}/rescisao">&nbsp;&nbsp;&nbsp;<i class="fa fa-angle-right"></i>Termo Rescisão</a></li>
    </ul>
</div>