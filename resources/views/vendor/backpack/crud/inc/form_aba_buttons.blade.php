<div id="saveActions" class="form-group">

    <input type="hidden" name="save_action" value="{{ $saveAction['active']['value'] }}">

    <div class="btn-group" id="botoes_contrato">

        <button type="submit" class="btn btn-success">
            <span class="fa fa-save" role="presentation" aria-hidden="true"></span> &nbsp;
            <span data-value="{{ $saveAction['active']['value'] }}">{{ $saveAction['active']['label'] }}</span>
        </button>

        <button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aira-expanded="false">
            <span class="caret"></span>
            <span class="sr-only">&#x25BC;</span>
        </button>

        <ul class="dropdown-menu">
            @foreach( $saveAction['options'] as $value => $label)
            <li><a href="javascript:void(0);" data-value="{{ $value }}">{{ $label }}</a></li>
            @endforeach
        </ul>

    </div>

    <a href="" class="btn btn-info" id="prev_aba"><span class="fa fa-arrow-circle-left"></span> &nbsp;Aba Anterior</a>
    <a href="" class="btn btn-info" id="next_aba">Próxima Aba <span class="fa fa-arrow-circle-right"></span></a>
    <a href="{{ $crud->hasAccess('list') ? url($crud->route) : url()->previous() }}" class="btn btn-default" id="cancelar"><span class="fa fa-ban"></span> &nbsp;{{ trans('backpack::crud.cancel') }}</a>
</div>
@push('after_scripts')
<script type="text/javascript">
    $(document).ready(function() {
        $('#botoes_contrato').hide();
        $('#cancelar').hide();
        $('#prev_aba').hide();

        habilitaDesabilitaBotoes();

        $('body').on('click','#prev_aba', function(event){
                abaAnterior(event);
        });

        $('body').on('click','#next_aba', function(event){
                proximaAba(event);
        });

        $('body').on('click','#dadosdocontrato', function(event){
            $('#botoes_contrato').hide();
            $('#cancelar').hide();
            $('#prev_aba').hide();
            $('#next_aba').show();
        });

        $('body').on('click','#caracteristicasdocontrato', function(event){
            $('#botoes_contrato').hide();
            $('#cancelar').hide();
            $('#prev_aba').show();
            $('#next_aba').show();
        });

        $('body').on('click','#vigenciavalores', function(event){
            $('#botoes_contrato').show();
            $('#cancelar').show();
            $('#prev_aba').show();
            $('#next_aba').hide();
        });
    });

    function verificaAbaAtiva() {
        var divTabs = $('#form_tabs');
        var ul = divTabs.find('ul');
        var a = ul.find('li').find('a');
        let nomeAba;
        let li;

        a.each(function () {
            aba = $(this).parent();
            if (aba.attr('class') == 'active') {
                nomeAba = aba.find('a');
            }
        });
        return nomeAba;
    }

    function habilitaDesabilitaBotoes(){

        nomeAba = verificaAbaAtiva();

        switch (nomeAba.attr('id')) {
            case 'dadosdocontrato':

                $('#botoes_contrato').hide();
                $('#cancelar').hide();
                $('#prev_aba').hide();
                $('#next_aba').show();
                break;
            case 'caracteristicasdocontrato':

                $('#botoes_contrato').hide();
                $('#cancelar').hide();
                $('#prev_aba').show();
                $('#next_aba').show();
                break;
            case 'vigenciavalores':

                $('#botoes_contrato').show();
                $('#cancelar').show();
                $('#prev_aba').show();
                $('#next_aba').hide();
                break;
        }
    }

    function proximaAba(event){
        event.preventDefault();
        nomeAba = verificaAbaAtiva();

        switch (nomeAba.attr('id')) {
            case 'dadosdocontrato':
                $('#caracteristicasdocontrato').click();
                break;
            case 'caracteristicasdocontrato':
                $('#vigenciavalores').click();
                break;
            case 'vigenciavalores':
                break;
        }
    }

    function abaAnterior(event){
        event.preventDefault();
        nomeAba = verificaAbaAtiva();

        switch (nomeAba.attr('id')) {
            case 'dadosdocontrato':
                break;
            case 'caracteristicasdocontrato':
                $('#dadosdocontrato').click();
                break;
            case 'vigenciavalores':
                $('#caracteristicasdocontrato').click();
                break;
        }
    }

</script>
@endpush
