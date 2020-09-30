<div class="btn-group">
    <button type="button" title="Mais" class="btn btn-xs btn-default dropdown-toggle dropdown-toggle-split"
            data-toggle="dropdown"  aria-haspopup="true" aria-expanded="false" title="Mais"><i class="fa fa-gears"></i>
        <span class="caret"></span>
        <span class="sr-only">Toggle Dropdown</span>
    </button>
    <ul class="dropdown-menu dropdown-menu-right">
        <li class="dropdown-header"><i class="fa fa-list"></i> Conta Vinculada:</li>
        <!-- <li><a href="/gescon/contrato/contratoconta/{{$entry->getKey()}}/movimentacaocontratoconta">&nbsp;&nbsp;&nbsp;<i class="fa fa-angle-right"></i>Movimentações</a></li> -->
        <!-- <li><a href="/gescon/contrato/contratoconta/{{$entry->contratoconta_id}}/depositocontratoconta/create">&nbsp;&nbsp;&nbsp;<i class="fa fa-angle-right"></i>Novo Depósito</a></li> -->

        <li><a href="/gescon/contrato/contratoconta/movimentacaocontratoconta/{{ $entry->getKey()}}/lancamento">&nbsp;&nbsp;&nbsp;<i class="fa fa-angle-right"></i>Lançamentos</a></li>

        <!-- <li><a href="/gescon/contrato/{{$entry->getKey()}}/contratocontas">&nbsp;&nbsp;&nbsp;<i class="fa fa-angle-right"></i>Retiradas</a></li>
        <li><a href="/gescon/contrato/{{$entry->getKey()}}/cronograma">&nbsp;&nbsp;&nbsp;<i class="fa fa-angle-right"></i>Repactuações</a></li>
        <li><a href="/gescon/contrato/{{$entry->getKey()}}/cronograma">&nbsp;&nbsp;&nbsp;<i class="fa fa-angle-right"></i>Relat depós. e retir.</a></li>
        <li><a href="/gescon/contrato/{{$entry->getKey()}}/cronograma">&nbsp;&nbsp;&nbsp;<i class="fa fa-angle-right"></i>Termo de encerramento</a></li> -->
    </ul>
</div>
