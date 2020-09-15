<div class="btn-group">
    <button type="button" title="Mais" class="btn btn-xs btn-default dropdown-toggle dropdown-toggle-split"
            data-toggle="dropdown"  aria-haspopup="true" aria-expanded="false" title="Mais"><i class="fa fa-gears"></i>
        <span class="caret"></span>
        <span class="sr-only">Toggle Dropdown</span>
    </button>
    <ul class="dropdown-menu dropdown-menu-right">
        <li class="dropdown-header"><i class="fa fa-list"></i> Itens Contrato:</li>
        <li><a href="/gescon/meus-contratos/{{$entry->getKey()}}/faturas">&nbsp;&nbsp;&nbsp;<i
                    class="fa fa-angle-right"></i>Faturas</a></li>
        <li><a href="/gescon/meus-contratos/{{$entry->getKey()}}/ocorrencias">&nbsp;&nbsp;&nbsp;<i
                    class="fa fa-angle-right"></i>Ocorrências</a></li>
        <li><a href="/gescon/meus-contratos/{{$entry->getKey()}}/terceirizados">&nbsp;&nbsp;&nbsp;<i
                    class="fa fa-angle-right"></i>Terceirizados</a></li>
        <li class="dropdown-header"><i class="fa fa-list"></i> Instrumento de Medição de Resultados:</li>
        <li><a href="/gescon/meus-contratos/{{$entry->getKey()}}/servicos">&nbsp;&nbsp;&nbsp;<i
                    class="fa fa-angle-right"></i>Serviços</a></li>

    </ul>
</div>
