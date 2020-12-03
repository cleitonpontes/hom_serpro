<div id="saveActions" class="form-group">

    <input type="hidden" name="save_action" value="{{ $saveAction['active']['value'] }}">
    <input type="hidden" name="catmatseritem_id[]" id="compra_itens_id[]">

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

        valor_global = 0;
        retornoAjax = 0;
        minutas_id = [];

        var maxLength = '000.000.000.000.000,0000'.length;

        // $('[name="vl_unit[]"]').maskMoney({
        //     allowNegative: false,
        //     thousands: '.',
        //     decimal: ',',
        //     affixesStay: false
        // }).attr('maxlength', maxLength).trigger('mask.maskMoney');
        //
        // $('[name="vl_total[]"]').maskMoney({
        //     allowNegative: false,
        //     thousands: '.',
        //     decimal: ',',
        //     affixesStay: false
        // }).attr('maxlength', maxLength).trigger('mask.maskMoney');

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

            $('#select2_ajax_multiple_minutasempenho').val(null).trigger('change');

        });

        $('body').on('click','#caracteristicasdocontrato', function(event){
            $('#botoes_contrato').hide();
            $('#cancelar').hide();
            $('#prev_aba').show();
            $('#next_aba').show();
        });

        $('body').on('click','#itensdocontrato', function(event){
            $('#botoes_contrato').hide();
            $('#cancelar').hide();
            $('#prev_aba').show();
            $('#next_aba').show();
            carregaitens(event, minutas_id);
            carregaDataInicio();
        });

        $('body').on('click','#vigenciavalores', function(event){
            $('#botoes_contrato').show();
            $('#cancelar').show();
            $('#prev_aba').show();
            $('#next_aba').hide();
            calculaTotalGlobal();
        });

        $('body').on('change','.itens', function(event){
            calculaTotalGlobal();
        });

        $("[name='minutasempenho[]']").on('change',function(event){
            minutas_id = [];
            minutas_id = retornaMinutaIds();
        });

        $('body').on('keyup','[name="vl_unit[]"]',function(event){
            var maxLength = '000.000.000.000.000,0000'.length;
            var tr = this.closest('tr');
                atualizarValores(tr);
            console.log($(tr).find('td').eq(3).find('input').val());
        });

        $('body').on('keyup','[name="vl_total[]"]',function(event){
            var tr = this.closest('tr');
            atualizarValores(tr);
        });


        $('body').on('click','#remove_item', function(event){
            removeLinha(this);
        });

    });


    function atualizarValores(tr){

        var qtd_item = parseFloat($(tr).find('td').eq(2).find('input').val());
        console.log(qtd_item);
        var vl_unit = parseFloat($(tr).find('td').eq(3).find('input').val());
        console.log(vl_unit);
        var total_iten = (qtd_item * vl_unit)
        console.log(total_iten);

        $('[name="vl_total[]"]').val(total_iten);
        // $('[name="vl_total[]"]').val(total_iten.toLocaleString('pt-br', {minimumFractionDigits: 2}));

    }

    function carregaDataInicio(){
        console.log($("[name='data_inicio[]']"));
    }

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

    function retornaMinutaIds(){
        var selected = $("[name='minutasempenho[]']").find(':selected');
        var array_minutas_id = [];
        selected.each(function (index,option){
            // console.log(option.value);
            array_minutas_id[index] = option.value;

        })
        // console.log(array_minutas_id);

        return array_minutas_id;
    }

    function adicionaLinhaItem(item){

        var compra_itens_id = $("[name='compra_itens_id[]']");
        compra_itens_id.push(item.id);
        var vl_unit = item.valor_unitario.toLocaleString('pt-br', {minimumFractionDigits: 2});
        var vl_total = item.valor_total.toLocaleString('pt-br', {minimumFractionDigits: 2});

        valor_global = (parseFloat(valor_global) + parseFloat(item.valor_total));

        var newRow = $("<tr>");
        var cols = "";
        cols += '<td>'+item.tipo_item+'</td>';
        cols += '<td>'+item.descricaodetalhada+'</td>';
        cols += '<td><input  class="form-control itens" name="qtd_item[]" id="qtd" type="number" max="'+item.quantidade_autorizada+'" min="'+item.quantidade+'" value="'+item.quantidade.toLocaleString('pt-br', {minimumFractionDigits: 2})+'"></td>';
        cols += '<td><input  class="form-control" name="vl_unit[]" id="vl_unit" type="text" value="'+vl_unit+'"></td>';
        cols += '<td><input  class="form-control" name="vl_total[]" id="vl_total" type="text" value="'+vl_total+'"></td>';
        cols += '<td><input type="number" name="periodicidade[]" id="periodicidade[]" value="1"></td>';
        cols += '<td><input type="date" name="data_inicio[]" id="data_inicio[]"></td>';
        cols += '<td>';
        cols += '<button type="button" class="btn btn-danger" title="Excluir Item" id="remove_item">'+
                    '<i class="fa fa-trash"></i>'+
                '</button>';
        cols += '<input type="hidden" name="catmatseritem_id[]" id="catmatseritem_id[]" value="'+item.catmatseritem_id+'">';
        cols += '<input type="hidden" name="tipo_item_id[]" id="tipo_item_id[]" value="'+item.tipo_item_id+'">';
        cols += '<input type="hidden" name="compra_item_unidade_id[]" id="compra_item_unidade_id[]" value="'+item.compra_item_unidade_id+'">';
        cols += '<input type="hidden" name="descricao_detalhada[]" id="descricao_detalhada[]" value="'+item.descricaodetalhada+'">';
        cols += '</td>';

        newRow.append(cols);
        $("#table-itens").append(newRow);
    }

    function removeLinha(elemento){
        var tr = $(elemento).closest('tr');
        tr.remove();
        calculaTotalGlobal()
    }

    function calculaTotalGlobal(){
        var valor_total = 0;
        $("#table-itens").find('tr').each(function(){
               var qtd_item = parseInt($(this).find('td').eq(2).find('input').val());
               var vl_unit = parseFloat($(this).find('td').eq(3).find('input').val());
               var total_iten = (qtd_item * vl_unit)
               valor_total += total_iten;
               $(this).find('td').eq(3).find('input').val(vl_unit)
               // $(this).find('td').eq(3).find('input').val(vl_unit.toLocaleString('pt-br', {minimumFractionDigits: 2}))
               $(this).find('td').eq(4).find('input').val(total_iten)
               // $(this).find('td').eq(4).find('input').val(total_iten.toLocaleString('pt-br', {minimumFractionDigits: 2}))
           // }
        });
         $('#valor_global').val(valor_total);
        $('#valor_global').maskMoney({
            allowNegative: false,
            thousands: '.',
            decimal: ',',
            affixesStay: false
        }).attr('maxlength', maxLength).trigger('mask.maskMoney');
         valor_global = valor_total;

    }

    function carregaitens(event,minutas_id) {

        console.log(minutas_id);
        $("#table-itens tr").remove();
        if(minutas_id.length > 0) {
            var url = "{{route('buscar.itens.modal',':minutas_id')}}";

            url = url.replace(':minutas_id', minutas_id);

            axios.request(url)
                .then(response => {
                    var itens = response.data;
                    var qtd_itens = itens.length;
                    itens.forEach(function (item) {
                        var linhas = $("#table-itens tr").length;
                        if(qtd_itens > linhas){
                            adicionaLinhaItem(item);
                        }
                    });
                    minutas_id = [];
                })
                .catch(error => {
                    alert(error);
                })
                .finally()
            event.preventDefault()
        }
    }


    function carregaitensmodal(tipo) {

        var tipo_id = tipo.value;

            var url = "{{route('busca.catmatseritens')}}";

            axios.request(url)
                .then(response => {
                    var itens = response.data;
                    console.log(itens);
                })
                .catch(error => {
                    alert(error);
                })
                .finally()

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
            case 'itensdocontrato':

                $('#botoes_contrato').show();
                $('#cancelar').show();
                $('#prev_aba').show();
                $('#next_aba').hide();
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
                $('#itensdocontrato').click();
                break;
            case 'itensdocontrato':
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
            case 'itensdocontrato':
                $('#caracteristicasdocontrato').click();
                break;
            case 'vigenciavalores':
                $('#itensdocontrato').click();
                break;
        }
    }

</script>
@endpush
