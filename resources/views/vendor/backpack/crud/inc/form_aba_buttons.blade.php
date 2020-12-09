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

        valor_global = 0;
        retornoAjax = 0;
        minutas_id = [];

        var maxLength = '000.000.000.000.000,0000'.length;

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

        $('body').on('change','#select2_ajax_multiple_minutasempenho', function(event){
            carregaitens(event, minutas_id);
        });

        $('body').on('focusout','input[name=data_assinatura]', function(event){
            atualizarDataInicioItens();
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

        //quando altera o campo de quantidade do item re-calcula os valores
        $('body').on('change','[name="qtd_item[]"]',function(event){
            var tr = this.closest('tr');
            atualizarValorTotal(tr);
        });

        //quando altera o campo de valor unitario do item re-calcula os valores
        $('body').on('change','input[name="vl_unit[]"]',function(event){
            var tr = this.closest('tr');
            atualizarValorTotal(tr);
        });

        //quando altera o campo de valor total do item re-calcula a quantidade
        $('body').on('change','[name="vl_total[]"]',function(event){
            var tr = this.closest('tr');
            atualizarQuantidade(tr);
        });

        //quando altera o campo de quantidade de parcela atualizar o valor da parcela
        $('body').on('change','#num_parcelas',function(event){
            atualizarValorParcela();
        });

        //quando altera o campo de periodicidade atualizar o valor global e valor de parcela
        $('body').on('change','input[name="periodicidade"]',function(event){
            atualizarValorParcela();
        });

        //quando altera o campo de periodicidade atualizar o valor global e valor de parcela
        $('body').on('change','#valor_global',function(event){
            atualizarValorParcela();
        });

        $('body').on('click','#remove_item', function(event){
            removeLinha(this);
        });

        $('body').on('click','#btn_inserir_item', function(event){
            if(!$('#item').val()){
                alert('Não foi encontrado nenhum item para incluir à lista.');
            }else{
                buscarItem($('#item').val());
            }
        });

        $(document).on('change', '#select2_ajax_multiple_minutasempenho', function () {
            if (!null_or_empty("#select2_ajax_multiple_minutasempenho")) {
                $("select[name=modalidade_id]" ).removeAttr("disabled");
                buscarModalidade();
            }

            if (null_or_empty("#select2_ajax_multiple_minutasempenho")) {
                // resetar os campos
                $('select[name=unidadecompra_id]').val('').change();
                $("select[name=modalidade_id]").val(172).change();
                $('#select2_ajax_multiple_amparoslegais').val('').change();
                $("#licitacao_numero").val('');
                $("select[name=modalidade_id]" ).attr('disabled', 'disabled');
            }
        });
    });

    function buscarItem(id)
    {
        var url = "{{route('busca.catmatseritens.id',':id')}}";
        url = url.replace(':id', id);

        axios.request(url)
            .then(response => {
                prepararItemParaIncluirGrid(response.data);
            })
            .catch(error => {
                alert(error);
            })
            .finally()
    }

    function prepararItemParaIncluirGrid(item)
    {
        item = {
            'tipo_item' : $('#tipo_item :selected').text(),
            'tipo_item_id' : $('#tipo_item').val(),
            'catmatseritem_id' : item.id,
            'descricaodetalhada': item.descricao,
            'quantidade' : $('#quantidade_item').val(),
            'valor_unitario': $('#valor_unit').val(),
            'valor_total': $('#valor_total').val(),
            'periodicidade': $('#periodicidade_item').val(),
            'data_inicio': $('#dt_inicio').val()
        }
        adicionaLinhaItem(item);
        resetarCamposFormulario();
    }

    function resetarCamposFormulario(){
            $('#tipo_item').val('');
            $('#item').val('').change();
            $('#quantidade_item').val('');
            $('#valor_unit').val('');
            $('#valor_total').val('');
            $('#periodicidade_item').val('');
            $('#dt_inicio').val('');
    }

    //atualiza o valor da parcela do contrato
    function atualizarValorParcela()
    {

        valor_global = $('#valor_global').val();
        numero_parcelas = $('#num_parcelas').val();

        $('#valor_parcela').val(valor_global / numero_parcelas);
    }

    // verifica se o array esta nulo ou vazio
    function null_or_empty(str)
    {
        var v = $(str).val();
        if (v === null || v.length == 0) {
            return true;
        }
        return false;
    }

    //busca a modalidade de acordo com a primeira minuta de empenho selecionada para popular os campos
    function buscarModalidade()
    {
        var arrayMinutas = $("#select2_ajax_multiple_minutasempenho").val();

        var url = "{{route('buscar.campos.contrato.empenho',':id')}}";
        url = url.replace(':id', arrayMinutas[0]);
        axios.request(url)
            .then(response => {
                var dadosCampos = response.data;

                if(dadosCampos){
                    // altera campo unidade de compra
                    $('select[name=unidadecompra_id]').append(`<option value="${dadosCampos.unidade_id}">${dadosCampos.codigo} -  ${dadosCampos.nomeresumido}</option>`);
                    $('select[name=unidadecompra_id]').val(dadosCampos.unidade_id).change();

                    //altera campo de modalidade da licitacao
                    $("select[name=modalidade_id]").val(dadosCampos.modalidade_id).change();
                    $('#select2-select2_ajax_unidadecompra_id-container .select2-selection__placeholder').remove();

                    // altera campo de amparos legais
                    $('#select2_ajax_multiple_amparoslegais option').remove();
                    $('#select2_ajax_multiple_amparoslegais').append(`<option value="${dadosCampos.amparo_legal_id}">${dadosCampos.ato_normativo} - Artigo: ${dadosCampos.artigo}</option>`);
                    $('#select2_ajax_multiple_amparoslegais').val(dadosCampos.amparo_legal_id).change();

                    // altera campo de numero/ano da licitacao
                    $("#licitacao_numero").val(dadosCampos.compra_numero_ano);
                }
            })
            .catch(error => {
                alert(error);
            })
            .finally()
        event.preventDefault()
    }

    function atualizarValorTotal(tr){
        var qtd_item = parseFloat($(tr).find('td').eq(2).find('input').val());
        var vl_unit = parseFloat($(tr).find('td').eq(3).find('input').val());

        parseFloat($(tr).find('td').eq(4).find('input').val(qtd_item * vl_unit));
    }

    function atualizarQuantidade(tr){
        var vl_unit = parseFloat($(tr).find('td').eq(3).find('input').val());
        var valor_total_item = parseFloat($(tr).find('td').eq(4).find('input').val());

        parseFloat($(tr).find('td').eq(2).find('input').val(valor_total_item / vl_unit));
    }

    function atualizarDataInicioItens(){
        $("#table-itens").find('tr').each(function(){
            if ($(this).find('td').eq(6).find('input').val() === "") {
                $(this).find('td').eq(6).find('input').val($('input[name=data_assinatura]').val());
            }
        });
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
            array_minutas_id[index] = option.value;
        })
        return array_minutas_id;
    }

    function adicionaLinhaItem(item){

        var compra_itens_id = $("[name='compra_itens_id[]']");
        compra_itens_id.push(item.id);
        var vl_unit = item.valor_unitario.toLocaleString('pt-br', {minimumFractionDigits: 2});
        var vl_total = item.valor_total.toLocaleString('pt-br', {minimumFractionDigits: 2});

        // se vier data dos dados do contrato preencher com a data default
        var data_inicio = $('input[name=data_assinatura]').val();
        if ($('input[name=data_inicio]').val()) {
            data_inicio = $('input[name=data_inicio]').val();
        }

        var periodicidade = 1;
        if ($('#periodicidade_item').val()) {
            periodicidade = $('#periodicidade_item').val();
        }

        var newRow = $("<tr>");
        var cols = "";
        cols += '<td>'+item.tipo_item+'</td>';
        cols += '<td>'+item.descricaodetalhada+'</td>';
        cols += '<td><input class="form-control" type="number"  name="qtd_item[]" id="qtd" max="'+item.quantidade_autorizada+'" min="'+item.quantidade+'" value="'+item.quantidade.toLocaleString('pt-br', {minimumFractionDigits: 2})+'"></td>';
        cols += '<td><input class="form-control" type="number"  name="vl_unit[]" id="vl_unit" value="'+vl_unit+'"></td>';
        cols += '<td><input class="form-control" type="number"  name="vl_total[]" id="vl_total"value="'+vl_total+'"></td>';
        cols += `<td><input class="form-control" type="number" name="periodicidade[]" id="periodicidade" value="${periodicidade}"></td>`;
        cols += `<td><input class="form-control" type="date" name="data_inicio[]" id="data_inicio" value="${data_inicio}"></td>`;
        cols += '<td>';
        cols += '<button type="button" class="btn btn-danger" title="Excluir Item" id="remove_item">'+
                    '<i class="fa fa-trash"></i>'+
                '</button>';
        cols += '<input type="hidden" name="catmatseritem_id[]" id="catmatseritem_id" value="'+item.catmatseritem_id+'">';
        cols += '<input type="hidden" name="tipo_item_id[]" id="tipo_item_id" value="'+item.tipo_item_id+'">';
        cols += '<input type="hidden" name="compra_item_unidade_id[]" id="compra_item_unidade_id" value="'+item.compra_item_unidade_id+'">';
        cols += '<input type="hidden" name="descricao_detalhada[]" id="descricao_detalhada" value="'+item.descricaodetalhada+'">';
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
            var total_item = parseFloat($(this).find('td').eq(4).find('input').val());
            var periodicidade = parseInt($(this).find('td').eq(5).find('input').val());
            var total_iten = (total_item * periodicidade);
            valor_total += total_iten;
        });
        $('#valor_global').val(valor_total);
        atualizarValorParcela();
    }

    function carregaitens(event,minutas_id) {

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

        resetarSelect();

        if (tipo.value){
            var tipo_id = tipo.value;

            var url = "{{route('busca.catmatseritens.portipo',':tipo_id')}}";

            url = url.replace(':tipo_id', tipo_id);

            axios.request(url)
                .then(response => {
                    var itens = response.data.data;

                    itens.forEach(function (item) {
                        carregarOptionsSelect(item);
                    });
                })
                .catch(error => {
                    alert(error);
                })
                .finally()
        }
    }

    function resetarSelect(){
        $("#item option").remove();
        var newRow = '<option value="">Selecione...</option>';
        $("#item").append(newRow);
    }

    function carregarOptionsSelect(item)
    {
        var newRow = '<option value="'+ item.id+'">'+item.descricao+'</option>';
        $("#item").append(newRow);
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
