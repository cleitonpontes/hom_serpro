<div class="box box-solid box-primary">
    <div class="box-header ui-sortable-handle">
        <i class="fa fa-bar-chart"></i>

        <h3 class="box-title">Cronograma mensal - ({{ $anoref['inicio_cronograma'] }} - {{ $anoref['fim_cronograma'] }})</h3>
    </div>
    <div class="box-body">
        {!! $graficocontratoscronograma->render() !!}
    </div>
</div>
