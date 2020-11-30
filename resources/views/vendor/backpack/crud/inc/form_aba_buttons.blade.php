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

        const $array_minutas = $("[name='minutasempenho[]']");
        valor_global = 0;
        retornoAjax = 0;

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
            // $('.select2-selection__choice').remove();

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
            if(retornoAjax == 0) {
                carregaitens(event, $array_minutas);
            }
            // calculaTotalGlobal()

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

        $('body').on('change','#select2_ajax_multiple_minutasempenho', function(event){
            retornoAjax = 0;
            console.log('onchange minutas '+retornoAjax);
        });

        $('body').on('click','#remove_item', function(event){
            removeLinha(this);
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

    function retornaMinutaIds($array_minutas){
            var minutas_id = [];
            $array_minutas.each(function (index,option) {
                     minutas_id[index] = option[index].value;
            });

            return minutas_id;
    }

    function adicionaLinhaItem(item){

        var compra_itens_id = $("[name='compra_itens_id[]']");
        compra_itens_id.push(item.id);
        var vl_unit = item.valor_unitario.toLocaleString('pt-br', {minimumFractionDigits: 2});
        var vl_total = item.valor_total.toLocaleString('pt-br', {minimumFractionDigits: 2});

        valor_global = (parseFloat(valor_global) + parseFloat(item.valor_total));
        console.log('valor global itens: '+valor_global);
        var newRow = $("<tr>");
        var cols = "";
        cols += '<td>'+item.tipo_item+'</td>';
        cols += '<td>'+item.descricaodetalhada+'</td>';
        cols += '<td><input  class="form-control itens" name="qtd_item[]" id="qtd" type="number" max="'+item.quantidade_autorizada+'" min="'+item.quantidade+'" value="'+item.quantidade+'"></td>';
        cols += '<td><input  class="form-control" name="vl_unit[]" id="vl_unit" type="text" value="'+vl_unit+'"readonly></td>';
        cols += '<td><input  class="form-control" name="vl_total[]" id="vl_total" type="text" value="'+vl_total+'"readonly></td>';
        cols += '<td>';
        cols += '<button type="button" class="btn btn-danger" title="Excluir Item" id="remove_item">'+
                    '<i class="fa fa-trash"></i>'+
                '</button>';
        cols += '<input type="hidden" name="catmatseritem_id[]" id="catmatseritem_id[]" value="'+item.catmatseritem_id+'">';
        cols += '<input type="hidden" name="tipo_item_id[]" id="tipo_item_id[]" value="'+item.tipo_item_id+'">';
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
               $(this).find('td').eq(3).find('input').val(vl_unit.toLocaleString('pt-br', {minimumFractionDigits: 2}))
               $(this).find('td').eq(4).find('input').val(total_iten.toLocaleString('pt-br', {minimumFractionDigits: 2}))
           // }
        });
         $('#valor_global').val(valor_total.toLocaleString('pt-br', {minimumFractionDigits: 2}));
         valor_global = valor_total.toLocaleString('pt-br', {minimumFractionDigits: 2})

    }

    function carregaitens(event,$array_minutas) {

        var minutas_id = retornaMinutaIds($array_minutas);

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
                    retornoAjax = 1;
                    console.log('Retorno API '+retornoAjax);
                })
                .catch(error => {
                    alert(error);
                })
                .finally()
            event.preventDefault()
        }else{
            $("#table-itens tr").remove();
        }
    }


    function carregaitensmodal(tipo) {

        var tipo_id = tipo.value;

            var url = "{{route('busca.catmatseritens')}}";

            // url = url.replace(':id', tipo_id);

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
