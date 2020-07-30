<div class="btn-group">
    <button type="button" title="Mais" class="btn btn-xs btn-default dropdown-toggle dropdown-toggle-split"
            data-toggle="dropdown"  aria-haspopup="true" aria-expanded="false" title="Mais"><i class="fa fa-gears"></i>
        <span class="caret"></span>
        <span class="sr-only">Toggle Dropdown</span>
    </button>
    <ul class="dropdown-menu dropdown-menu-right">
        @if((backpack_user()->hasRole('Administrador') or (backpack_user()->hasRole('Administrador Órgão') and !backpack_user()->unidade->sisg) ))
        <li><a href="/admin/orgao/{{$entry->getKey()}}/configuracao">&nbsp;&nbsp;&nbsp;<i class="fa fa-indent"></i>Configuração</a></li>
        @endif
        <li><a href="/admin/orgao/{{$entry->getKey()}}/subcategorias">&nbsp;&nbsp;&nbsp;<i class="fa fa-indent"></i>Subcategorias</a></li>
    </ul>
</div>
