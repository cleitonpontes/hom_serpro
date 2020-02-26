<div class="btn-group">
    <button type="button" class="btn btn-default dropdown-toggle"
            data-toggle="dropdown" title="Carregar Itens"><i class="fa fa-download"></i>
        <span class="caret"></span>
        <span class="sr-only">Toggle Dropdown</span>
    </button>
    <ul class="dropdown-menu dropdown-menu-right">
        <li>
            <a href="/gescon/saldohistoricoitens/carregaritens/atual/{{session()->get('saldohistoricoitens_contratohistorico_id')}}">&nbsp;&nbsp;&nbsp;<i
                    class="fa fa-angle-right"></i>Valor Atual</a></li>
        <li>
            <a href="/gescon/saldohistoricoitens/carregaritens/inicial/{{session()->get('saldohistoricoitens_contratohistorico_id')}}">&nbsp;&nbsp;&nbsp;<i
                    class="fa fa-angle-right"></i>Valor Inicial</a></li>
    </ul>
</div>
