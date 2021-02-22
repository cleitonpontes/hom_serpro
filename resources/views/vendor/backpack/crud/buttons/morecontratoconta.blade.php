<div class="btn-group">
    <button type="button" title="Mais" class="btn btn-xs btn-default dropdown-toggle dropdown-toggle-split"
            data-toggle="dropdown"  aria-haspopup="true" aria-expanded="false" title="Mais"><i class="fa fa-gears"></i>
        <span class="caret"></span>
        <span class="sr-only">Toggle Dropdown</span>
    </button>
    <ul class="dropdown-menu dropdown-menu-right">
        <li class="dropdown-header"><i class="fa fa-list"></i> Conta Vinculada:</li>
        <li><a href="/gescon/contrato/contratoconta/{{$entry->getKey()}}/extratocontratoconta">&nbsp;&nbsp;&nbsp;<i class="fa fa-angle-right"></i>Extrato de Lançamentos</a></li>


        <li><a href="/gescon/contrato/contratoconta/{{$entry->getKey()}}/movimentacaocontratoconta">&nbsp;&nbsp;&nbsp;<i class="fa fa-angle-right"></i>Movimentações</a></li>
        <li><a href="/gescon/contrato/contratoconta/{{$entry->getKey()}}/depositocontratoconta/create">&nbsp;&nbsp;&nbsp;<i class="fa fa-angle-right"></i>Nova Provisão</a></li>
        <li><a href="/gescon/contrato/contratoconta/{{$entry->getKey()}}/funcionarioscontratoconta">&nbsp;&nbsp;&nbsp;<i class="fa fa-angle-right"></i>Empregados / Liberação</a></li>
        <li><a href="/gescon/contrato/contratoconta/{{$entry->getKey()}}/funcoescontratoconta">&nbsp;&nbsp;&nbsp;<i class="fa fa-angle-right"></i>Funções / Repactuação</a></li>
        <li><a href="/gescon/contrato/contratoconta/{{$entry->getKey()}}/encerramentocontratoconta/create">&nbsp;&nbsp;&nbsp;<i class="fa fa-angle-right"></i>Encerrar Conta Vinculada</a></li>
    </ul>
</div>
