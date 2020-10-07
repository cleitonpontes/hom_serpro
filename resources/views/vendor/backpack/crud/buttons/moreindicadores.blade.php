{{--{{ dd(get_defined_vars()['__data']) }}--}}
{{--{{dd($entry->contratoitem_servico_id)}}--}}
<div class="btn-group">
    <button type="button" title="Mais" class="btn btn-xs btn-default dropdown-toggle dropdown-toggle-split"
            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" title="Mais"><i class="fa fa-gears"></i>
        <span class="caret"></span>
        <span class="sr-only">Toggle Dropdown</span>
    </button>
    <ul class="dropdown-menu dropdown-menu-right">
        <li><a href="/gescon/meus-servicos/{{$entry->contratoitem_servico_id}}/indicadores">&nbsp;&nbsp;&nbsp;<i
                    class="fa fa-angle-right"></i>Vincular indicadores</a></li>

    </ul>
</div>
