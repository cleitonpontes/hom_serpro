<div class="btn-group">
    <button type="button" title="Mais" class="btn btn-xs btn-default dropdown-toggle dropdown-toggle-split"
            data-toggle="dropdown"  aria-haspopup="true" aria-expanded="false" title="Mais"><i class="fa fa-gears"></i>
        <span class="caret"></span>
        <span class="sr-only">Toggle Dropdown</span>
    </button>
    <ul class="dropdown-menu dropdown-menu-right">
        <li class="dropdown-header"><i class="fa fa-list"></i> Ações:</li>
        <li><a href="/gescon/contrato/contratoconta/{{\Route::current()->parameter('contratoconta_id')}}/{{$entry->getKey()}}/repactuacaocontratoconta/create">&nbsp;&nbsp;&nbsp;<i class="fa fa-angle-right"></i>Nova repactuação para esta função</a></li>
        <!-- <li><a href="/gescon/contrato/contratoconta/{{$entry->getKey()}}/funcionarioscontratoconta">&nbsp;&nbsp;&nbsp;<i class="fa fa-angle-right"></i>Nova Retirada2</a></li> -->
    </ul>
</div>
