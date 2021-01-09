<!-- field_type_name -->
@inject('compratrait', 'App\Http\Controllers\Empenho\CompraSiasgCrudController')
<div @include('crud::inc.field_wrapper_attributes') >
    <!-- Editable table -->
    <div class="col-xs-6">
{{--        <button type="button" disabled class="btn btn-primary" id="btn-inserir-item" data-toggle="modal"--}}
{{--                data-target="#inserir_item">--}}
{{--            Inserir Item <i class="fa fa-plus"></i>--}}
{{--        </button>--}}
    </div>
    <div class="col-xs-6 col-md-3 col-md-offset-3 text-right">
        <div class="input-group">
            <div class="input-group-addon">Valor total do Contrato:</div>
            <input type="text" class="form-control" id="valorTotalItem" readonly value="0">
        </div>
    </div>
    <br>
    <br>

    <div class="table-responsive">
        <table id="table" class="table table-bordered table-responsive-md table-striped text-center">
            <thead>
            <tr>
                <th class="text-center">Tipo Item</th>
                <th class="text-center">Número</th>
                <th class="text-center">Item</th>
                <th class="text-center">Quantidade</th>
                <th class="text-center">Valor Unitário</th>
                <th class="text-center">Qtd. parcelas</th>
                <th class="text-center">Valor Total</th>
                <th class="text-center">Data Início</th>
{{--                <th class="text-center">Ações</th>--}}
            </tr>
            </thead>
            <tbody id="table-itens">

            </tbody>
        </table>
    </div>
    <!-- Editable table -->

    <!-- Janela modal para inserção de registros -->
    <div id="inserir_item" tabindex="-1" class="modal fade"
         role="dialog"
         aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title">
                        Novo Item
                    </h3>
                    <button type="button" class="close" id="fechar_modal" data-dismiss="modal" aria-label="Fechar">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" id="textoModal">
                    <div class="form-group">
                        <label for="tipo_item" class="control-label">Tipo Item</label>
                        <select class="form-control" style="width:100%;" id="tipo_item">
                            <option value="">Selecione</option>
                            <option value="149">Material</option>
                            <option value="150">Serviço</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="item" class="control-label">Item</label>
                        <select class="form-control" style="width:100%;height: 34px;border-color: #d2d6de" id="item">
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="numero_item" class="control-label">Número</label>
                        <input class="form-control" id="numero_item"  name="numero_item" type="text">
                    </div>
                    <div class="form-group">
                        <label for="quantidade_item" class="control-label">Quantidade</label>
                        <input class="form-control" id="quantidade_item" maxlength="10" name="quantidade_item" type="number">
                    </div>
                    <div class="form-group">
                        <label for="vl_unit" class="control-label">Valor Unitário</label>
                        <input class="form-control" id="valor_unit" name="valor_unit" type="number">
                    </div>
                    <div class="form-group">
                        <label for="vl_total" class="control-label">Valor Total</label>
                        <input class="form-control" id="valor_total" name="valor_total" type="number">
                    </div>
                    <div class="form-group">
                        <label for="periodicidade" class="control-label">Periodicidade</label>
                        <input class="form-control" id="periodicidade_item" maxlength="10" name="periodicidade_item" type="number">
                    </div>
                    <div class="form-group">
                        <label for="data_inicio" class="control-label">Data Início</label>
                        <input class="form-control" id="dt_inicio" name="dt_inicio" type="date">
                    </div>
                    <button class="btn btn-danger" type="submit" data-dismiss="modal"><i class="fa fa-reply"></i> Cancelar</button>
                    <button class="btn btn-success" type="button" data-dismiss="modal" id="btn_inserir_item"><i class="fa fa-save"></i> Incluir</button>
                </div>
                <div class="modal-footer">
                </div>
            </div>
        </div>
    </div>

    {{-- HINT --}}
    @if (isset($field['hint']))
        <p class="help-block">{!! $field['hint'] !!}</p>
    @endif
</div>

@if ($crud->checkIfFieldIsFirstOfItsType($field))
    {{-- FIELD EXTRA CSS  --}}
    {{-- push things in the after_styles section --}}

    @push('crud_fields_styles')
        <style media="screen">
            .pt-3-half {
                padding-top: 1.4rem;
            }
        </style>
    @endpush

    {{-- FIELD EXTRA JS --}}
    {{-- push things in the after_scripts section --}}

    @push('crud_fields_scripts')
        <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
        <script type="text/javascript">

            $(document).ready(function () {

                valor_global = 0;
                parcela = 1;

                // buscar os itens do contrato/aditivo assim que a pagina for carregada
                buscarItens();

                const $tableID = $('#table');

                $('#numero_item').mask('99999');

                var valueHidden = $('input[name=adicionaCampoRecuperaGridItens]').val();
                if (valueHidden !== '{' + '{' + 'old(' + '\'name\'' + ')}}') {
                    $('#table').html(valueHidden);
                    calculaTotalGlobal();
                }

                $tableID.on('click', '.table-remove', function () {
                    $(this).parents('tr').detach();
                });

                $('body').on('change','#tipo_item', function(event){
                    $('#item').val('');
                    atualizarSelectItem();
                });

                $('body').on('click','#btn_inserir_item', function(event){
                    if(!$('#item').val()){
                        alert('Não foi encontrado nenhum item para incluir à lista.');
                    }else{
                        buscarItem($('#item').val());
                    }
                });

                $('body').on('change','.itens', function(event){
                    calculaTotalGlobal();
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
                    calculaTotalGlobal();
                });

                //quando altera o campo de quantidade de parcela atualizar o valor da parcela no caso de aditivo
                $('body').on('change','#num_parcelas',function(){
                    atualizarParcela();
                });

                //quando altera o campo de periodicidade atualizar o valor global e valor de parcela
                $('body').on('change','#periodicidade',function(event){
                    var tr = this.closest('tr');
                    atualizarValorTotal(tr);
                });

                //quando altera o campo de periodicidade atualizar o valor global e valor de parcela
                $('body').on('change','#valor_global',function(event){
                    atualizarParcela();
                });

                $('body').on('click','#remove_item',function(event){
                    removeLinha(this);
                });

                function atualizarSelectItem(){
                    $('#item').select2({
                        ajax: {
                            url: urlItens(),
                            dataType: 'json',
                            delay: 250,
                            processResults: function (data) {
                                return {
                                    results:  $.map(data.data, function (item) {
                                        return {
                                            text: item.codigo_siasg +' - '+ item.descricao,
                                            id: item.id
                                        }
                                    })
                                };
                            },
                            cache: true
                        }
                    });
                    $('.selection .select2-selection').css("height","34px").css('border-color','#d2d6de');
                }

                function urlItens(){
                    var url = '{{route('busca.catmatseritens.portipo',':tipo_id')}}';
                    url = url.replace(':tipo_id', $('#tipo_item').val());
                    return url;
                }
                initSelectQualificacao();
                onChangeSelectQualificacao();
            });

            function addOption(valor) {
                var option = new Option(valor, valor);
                var select = document.getElementById("tipo_item");
                select.add(option);
            }

            function buscarItem(id){
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
                    'descricao' : $('#tipo_item :selected').text(),
                    'descricao_complementar': item.descricao,
                    'codigo_siasg': item.codigo_siasg,
                    'quantidade' : $('#quantidade_item').val(),
                    'valorunitario': $('#valor_unit').val(),
                    'numero':$('#numero_item').val(),
                    'valortotal': $('#valor_total').val(),
                    'periodicidade': $('#periodicidade_item').val(),
                    'data_inicio': $('#dt_inicio').val(),
                    'catmatseritem_id' : item.id,
                    'tipo_item_id' : $('#tipo_item').val(),
                }

                adicionaLinhaItem(item);
                resetarCamposFormulario();
            }

            function resetarCamposFormulario(){
                $('#tipo_item').val('');
                $('#item').val('').change();
                $('#quantidade_item').val('');
                $('#numero_item').val('');
                $('#valor_unit').val('');
                $('#valor_total').val('');
                $('#periodicidade_item').val('');
                $('#dt_inicio').val('');
            }

            //atualiza o valor da parcela do contrato para termo aditivo
            function atualizarValorParcela(parcela)
            {
                var valor_global = $('#valor_global').val();
                var valor_parcela = valor_global / parcela;

                $('#valor_parcela').val(parseFloat(valor_parcela.toFixed(4)));
            }

            //atualiza o valor da parcela do contrato
            function atualizarParcela()
            {
                var valor_global = $('#valor_global').val();
                var num_parcelas = $('#num_parcelas').val();
                var valor = valor_global / num_parcelas;

                $('#valor_parcela').val(parseFloat(valor.toFixed(4)));
            }

            function atualizarValorTotal(tr){
                var qtd_item = parseFloat($(tr).find('td').eq(3).find('input').val());
                var vl_unit = parseFloat($(tr).find('td').eq(4).find('input').val());
                var periodicidade = parseInt($(tr).find('td').eq(5).find('input').val());
                var vltotal = qtd_item * vl_unit * periodicidade;
                $(tr).find('td').eq(6).find('input').val(parseFloat(vltotal.toFixed(4)));
                calculaTotalGlobal();
            }

            function atualizarQuantidade(tr){
                var vl_unit = $(tr).find('td').eq(4).find('input').val();
                var valor_total_item = $(tr).find('td').eq(6).find('input').val();

                var quantidade = valor_total_item / vl_unit;

                $(tr).find('td').eq(3).find('input').val(parseFloat(quantidade.toLocaleString('en-US', {minimumFractionDigits: 4})));
                calculaTotalGlobal();
            }

            function atualizarDataInicioItens(){
                $("#table-itens").find('tr').each(function(){
                    if ($(this).find('td').eq(7).find('input').val() === "") {
                        $(this).find('td').eq(7).find('input').val($('input[name=data_assinatura]').val());
                    }
                });
            }

            function adicionaLinhaItem(item){

                var newRow = $("<tr>"),
                    cols = "",
                    propReadOnly = $.inArray('ACRÉSCIMO / SUPRESSÃO', tratarArrayItemQualificacao()[0]) !== -1 ? '' : 'readOnly',
                    propReadOnlyReajuste = $.inArray('REAJUSTE', tratarArrayItemQualificacao()[0]) !== -1 ? '' : 'readOnly',
                    vl_total = parseInt(item.quantidade) * parseFloat(item.valorunitario) * item.periodicidade;

                cols += '<td>'+item.descricao+'</td>';
                cols += '<td>'+item.numero+'</td>';
                cols += '<td>'+item.codigo_siasg+' - '+item.descricao_complementar+'</td>';
                cols += '<td><input class="form-control input-item input-item-acrescimo" '+ propReadOnly +' type="number"  name="qtd_item[]" step="0.0001" id="qtd" value="'+item.quantidade+'"></td>';
                cols += '<td><input class="form-control input-item input-item-vl-unitario" '+ propReadOnlyReajuste +' type="number"  name="vl_unit[]" step="0.0001" id="vl_unit" value="'+item.valorunitario+'"></td>';
                cols += '<td><input class="form-control input-item input-item-acrescimo" '+ propReadOnly +' type="number" name="periodicidade[]" id="periodicidade" value="'+item.periodicidade+'"></td>';
                cols += '<td><input class="form-control input-item" readonly type="number"  name="vl_total[]" step="0.0001" id="vl_total" value="'+vl_total+'"></td>';
                cols += '<td><input class="form-control input-item" readonly type="date" name="data_inicio[]" id="data_inicio" value="'+ item.data_inicio +'">';
                // cols += '<td><button type="button" class="btn btn-danger" title="Excluir Item" id="remove_item">'+
                //     '<i class="fa fa-trash"></i>'+
                //     '</button>';
                cols += '<input type="hidden" name="numero_item_compra[]" id="numero_item_compra" value="'+item.numero+'">';
                cols += '<input type="hidden" name="catmatseritem_id[]" id="catmatseritem_id" value="'+item.catmatseritem_id+'">';
                cols += '<input type="hidden" name="tipo_item_id[]" id="tipo_item_id" value="'+item.tipo_item_id+'">';
                cols += '<input type="hidden" name="descricao_detalhada[]" id="descricao_detalhada" value="'+item.descricao_complementar+'">';
                cols += '<input type="hidden" name="aditivo_item_id[]" id="aditivo_item_id" value="'+item.id+'">';
                cols += '</td>';

                newRow.append(cols);
                $("#table-itens").append(newRow);
                calculaTotalGlobal();
            }

            function calculaTotalGlobal(){
                let totalItens = 0;
                $('[name="vl_total[]"]').each(function(index, elementInput){
                    totalItens = parseFloat(totalItens) + parseFloat(elementInput.value);
                })
                $('#valor_global').val(totalItens.toFixed(2));
                $('#valorTotalItem').val(totalItens.toFixed(2));

                $("#table-itens").find('tr').each(function(){
                    var periodicidade = parseInt($(this).find('td').eq(5).find('input').val());
                    //seta num_parcelas
                    if (periodicidade > parcela) {
                        parcela = periodicidade;
                        $('#num_parcelas').val(parcela);
                    }
                });
                atualizarValorParcela(parcela);
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

            function buscarItens()
            {
                if($("[name=aditivo_id]").val()){
                    buscarSaldoHistoricoItens();
                } else{
                    buscarContratoItens();
                }
            }

            function buscarSaldoHistoricoItens(){
                var aditivo_id = $("[name=aditivo_id]").val();
                var url = "{{route('saldo.historico.itens',':id')}}";
                url = url.replace(':id', aditivo_id);
                carregarItens(url);
            }

            function buscarContratoItens(){
                var contrato_id = $("[name=contrato_id]").val();
                var url = "{{route('contrato.item',':contrato_id')}}";
                url = url.replace(':contrato_id', contrato_id);
                carregarItens(url);
            }

            function carregarItens(url){
                axios.get(url)
                    .then(response => {
                        var itens = response.data;
                        var qtd_itens = itens.length;
                        itens.forEach(function (item) {
                            var linhas = $("#table-itens tr").length;
                            if(qtd_itens > linhas){
                                adicionaLinhaItem(item);
                            }
                        });
                    })
                    .catch(error => {
                        alert(error);
                    })
                    .finally()
            }

            function removeLinha(elemento){
                var tr = $(elemento).closest('tr');
                var historicoSaldoItemId = $(tr).find('td').eq(8).find('#id').val();
                if (historicoSaldoItemId === 'undefined'){
                    tr.remove();
                    calculaTotalGlobal()
                } else {
                    alert('Esse item não pode ser exluído. Só é permitido alterar.')
                }
            }
            /*---------------------------HABILITA-E-DESABILITA-CAMPOS---------------------------*/
            function initSelectQualificacao(){
                var arrayNameCamposHabilitarDesabilitar = recuperarArrObjCampos(tratarArrayItemQualificacao()[1]);
                habilitarDesabilitarCampos(tratarArrayItemQualificacao()[0], arrayNameCamposHabilitarDesabilitar);
                habilitarDesabilitarCamposItens();
            }

            function onChangeSelectQualificacao() {
                $('#select2_ajax_multiple_qualificacoes').change(function () {
                    var arrayNameCamposHabilitarDesabilitar = recuperarArrObjCampos(tratarArrayItemQualificacao()[1]);
                    habilitarDesabilitarCampos(tratarArrayItemQualificacao()[0], arrayNameCamposHabilitarDesabilitar);
                    habilitarDesabilitarCamposItens();
                });
            }

            function habilitarDesabilitarCamposItens() {
                let booAcrescimoSelecionado = $.inArray('ACRÉSCIMO / SUPRESSÃO', tratarArrayItemQualificacao()[0]) !== -1;
                let booReajusteSelecionado = $.inArray('REAJUSTE', tratarArrayItemQualificacao()[0]) !== -1;
                $('#btn-inserir-item').prop('disabled', !booAcrescimoSelecionado);
                if ($('.input-item').length) {
                    $('.input-item-acrescimo').prop("readonly", !booAcrescimoSelecionado);
                    $('.input-item-vl-unitario').prop("readonly", !booReajusteSelecionado);
                    if (!booAcrescimoSelecionado || !booReajusteSelecionado) {
                        $('#table-itens').empty();
                        buscarItens();
                    }
                }
            }

            function tratarArrayItemQualificacao(){
                var array_selected = [],
                    arrayItemQualificacao = [];

                var selected = $("[name='qualificacoes[]']").find(':selected');
                arrayItemQualificacao = JSON.parse($("input[name=options_qualificacao]").val());

                selected.each(function (index, option) {
                    array_selected[index] = option.text;
                })
                return[array_selected, arrayItemQualificacao];
            }

            function habilitarDesabilitarCampos(array_selected, arrayNameCamposHabilitarDesabilitar) {
                arrayNameCamposHabilitarDesabilitar.forEach(function (objCampo) {
                    var booSelected = $.inArray(objCampo.id, array_selected) !== -1;
                    objCampo.arrInput.forEach(function (inputElement) {
                        if (
                            inputElement.name !== 'fornecedor_id' &&
                            inputElement.name !== 'retroativo_mesref_de' &&
                            inputElement.name !== 'retroativo' &&
                            inputElement.name !== 'retroativo_soma_subtrai' &&
                            inputElement.name !== 'num_parcelas'
                        ) {
                            $('[name=' + inputElement.name + ']').prop("readonly", !booSelected);
                            $('[name=' + inputElement.name + ']').val(function (index, currentValue) {
                                return !booSelected ? inputElement.oldValue : currentValue;
                            });
                        }
                        if (inputElement.name === 'fornecedor_id') {
                            $('[name=' + inputElement.name + ']').prop("disabled", !booSelected);
                            $('[name=' + inputElement.name + ']').append(function (index, currentHtml) {
                                return !booSelected ? inputElement.oldValue : currentHtml;
                            })
                        }

                        if (
                            inputElement.name === 'retroativo_mesref_de' ||
                            inputElement.name === 'retroativo_anoref_de' ||
                            inputElement.name === 'retroativo_mesref_ate' ||
                            inputElement.name === 'retroativo_anoref_ate'
                        ) {
                            $('[name=' + inputElement.name + ']').prop("disabled", !booSelected);
                        }

                        if (inputElement.name === 'retroativo') {
                            $('[name=' + inputElement.name + ']').prop("disabled", !booSelected);
                        }

                        if (inputElement.name === 'retroativo_soma_subtrai') {
                            $('[name=' + inputElement.name + ']').prop("disabled", !booSelected);
                        }
                        if (inputElement.name === 'num_parcelas') {
                            var booAcrescimoSelected = $.inArray('ACRÉSCIMO / SUPRESSÃO', array_selected) !== -1,
                                booVigenciaSelected = $.inArray('VIGÊNCIA', array_selected) !== -1;
                            $('[name=' + inputElement.name + ']').prop("readonly", !(booAcrescimoSelected || booVigenciaSelected));
                        }
                    });
                });
            }

            function getOldValue(name) {
                switch (name) {
                    case 'fornecedor_id':
                        $('#select2_ajax_fornecedor_id option:first').remove(); //remove opcao Selecione...
                        return $('#select2_ajax_fornecedor_id').html();
                        break;

                        case 'retroativo_mesref_de':

                        break;
                    default:
                        return $('[name=' + name + ']').data('val', $('[name=' + name + ']').val())[0].defaultValue;


                }
            }

            function recuperarArrObjCampos(arrayItemQualificacao) {
                let arrObjCampos = [];
                arrayItemQualificacao.forEach(function (qualificacaoItem, index) {

                    switch (qualificacaoItem.descricao) {
                        case 'VIGÊNCIA':
                            arrObjCampos.push({
                                id: qualificacaoItem.descricao,
                                arrInput: [
                                    {name: 'vigencia_inicio', oldValue: getOldValue('vigencia_inicio')},
                                    {name: 'vigencia_fim', oldValue: getOldValue('vigencia_fim')},
                                    {name: 'num_parcelas', oldValue: getOldValue('num_parcelas')}
                                ]
                            })
                            break;
                        case 'ACRÉSCIMO / SUPRESSÃO':
                            arrObjCampos.push({
                                id: qualificacaoItem.descricao,
                                arrInput: [
                                    {name: 'num_parcelas', oldValue: getOldValue('num_parcelas')}
                                ]
                            })
                            break;
                        case 'FORNECEDOR':
                            arrObjCampos.push({
                                id: qualificacaoItem.descricao,
                                arrInput: [
                                    {name: 'fornecedor_id', oldValue: getOldValue('fornecedor_id')}
                                ]
                            })
                            break;
                        case 'REAJUSTE':
                            arrObjCampos.push({
                                id: qualificacaoItem.descricao,
                                arrInput: [
                                    {name: 'retroativo_mesref_de', oldValue: getOldValue('retroativo_mesref_de')},
                                    {name: 'retroativo_anoref_de', oldValue: getOldValue('retroativo_anoref_de')},
                                    {name: 'retroativo_mesref_ate', oldValue: getOldValue('retroativo_mesref_ate')},
                                    {name: 'retroativo_anoref_ate', oldValue: getOldValue('retroativo_anoref_ate')},
                                    {name: 'retroativo_vencimento', oldValue: getOldValue('retroativo_vencimento')},
                                    {name: 'retroativo_valor', oldValue: getOldValue('retroativo_valor')},
                                    {name: 'retroativo', oldValue: getOldValue('retroativo')},
                                    {name: 'retroativo_soma_subtrai', oldValue: getOldValue('retroativo')},
                                ]
                            },)
                            break;
                    }
                });
                return arrObjCampos;
            }

            /**
             * atualiza o value do atributo no html
             * necessario para recuperar a tabela de itens com os ultimos dados inseridos nos inputs
             * @param event
             */
            function atualizaValueHTMLCamposAbaItem() {
                $('[name="qtd_item[]"]').each(function(index, elementInput){
                    elementInput.setAttribute('value', elementInput.value);
                })
                $('[name="vl_unit[]"]').each(function(index, elementInput){
                    elementInput.setAttribute('value', elementInput.value);
                })
                $('[name="vl_total[]"]').each(function(index, elementInput){
                    elementInput.setAttribute('value', elementInput.value);
                })
                $('[name="periodicidade[]"]').each(function(index, elementInput){
                    elementInput.setAttribute('value', elementInput.value);
                })
                $('[name="data_inicio[]"]').each(function(index, elementInput){
                    elementInput.setAttribute('value', elementInput.value);
                })
            }
        </script>
    @endpush
@endif
