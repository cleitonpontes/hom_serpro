    @php
        $url = '';
        $disabled = 'disabled';
        if ($entry->situacao_descricao === 'EMPENHO EMITIDO'){
            $url = "/empenho/minuta/{$entry->getKey()}/alteracao";
            $disabled = '';
        }
    @endphp
<div class="btn-group">
    <button type="button" title="Mais" class="btn btn-xs btn-default dropdown-toggle dropdown-toggle-split"
            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" title="Mais"><i class="fa fa-gears"></i>
        <span class="caret"></span>
        <span class="sr-only">Toggle Dropdown</span>
    </button>
        <ul class="dropdown-menu dropdown-menu-right">
            <li class="{{$disabled}}">
                <a href="{{$url}}" {{$disabled}}>&nbsp;&nbsp;&nbsp;
                    <i class="fa fa-angle-right"></i>Alterar Empenho</a>
            </li>
        </ul>

</div>
