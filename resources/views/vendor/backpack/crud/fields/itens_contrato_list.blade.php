<!-- field_type_name -->
@inject('compratrait', 'App\Http\Controllers\Empenho\CompraSiasgCrudController')
<div @include('crud::inc.field_wrapper_attributes') >
    <!-- Editable table -->
    <div class="card">
        <div class="card-body">
            <div>
                <span class="table-up">
                            <button type="button" class="btn btn-primary" data-toggle="modal"
                                    data-target="#inserir_item">
                                Inserir Item <i class="fa fa-plus"></i>
                            </button>
                </span>
                <br>
                <br>
                <table id="table" class="table table-bordered table-responsive-md table-striped text-center">
                    <thead>
                    <tr>
                        <th class="text-center">Tipo Item</th>
                        <th class="text-center">Número</th>
                        <th class="text-center">Item</th>
                        <th class="text-center">Quantidade</th>
                        <th class="text-center">Valor Unitário</th>
                        <th class="text-center">Valor Total</th>
                        <th class="text-center">Periodicidade</th>
                        <th class="text-center">Data Início</th>
                        <th class="text-center">Ações</th>
                    </tr>
                    </thead>
                    <tbody id="table-itens">

                    </tbody>
                </table>
            </div>
        </div>
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
                        <label for="qtd_item" class="control-label">Tipo Item</label>
                        <select class="form-control" style="width:100%;" id="tipo_item">
                            <option value="">Selecione</option>
                            <option value="149">Material</option>
                            <option value="150">Serviço</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="qtd_item" class="control-label">Item</label>
                        <select class="form-control" style="width:100%;height: 34px;border-color: #d2d6de" id="item">
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="qtd_item" class="control-label">Número</label>
                        <input class="form-control" id="numero_item"  name="numero_item" type="text">
                    </div>
                    <div class="form-group">
                        <label for="qtd_item" class="control-label">Quantidade</label>
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

                const $tableID = $('#table');

                 $('#numero_item').mask('99999');

                var valueHidden = $('input[name=adicionaCampoRecuperaGridItens]').val();
                if(valueHidden !== '{'+'{'+'old(' +'\'name\''+ ')}}'){
                    $('#table').html(valueHidden);
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
                    atualizarCurrentAtribute(event);
                });

                //quando altera o campo de valor unitario do item re-calcula os valores
                $('body').on('change','input[name="vl_unit[]"]',function(event){
                    var tr = this.closest('tr');
                    atualizarValorTotal(tr);
                    atualizarCurrentAtribute(event);
                });

                //quando altera o campo de valor total do item re-calcula a quantidade
                $('body').on('change','[name="vl_total[]"]',function(event){
                    var tr = this.closest('tr');
                    atualizarQuantidade(tr);
                    atualizarCurrentAtribute(event);
                });

                //quando altera o campo de quantidade de parcela atualizar o valor da parcela
                $('body').on('change','#num_parcelas',function(event){
                    atualizarValorParcela(parcela);
                });

                //quando altera o campo de periodicidade atualizar o valor global e valor de parcela
                $('body').on('change','input[name="periodicidade[]"]',function(event){
                    calculaTotalGlobal();
                    atualizarValorParcela(parcela);
                    atualizarCurrentAtribute(event);
                });

                $('body').on('change','input[name="data_inicio[]"]',function(event){
                    atualizarCurrentAtribute(event);
                });

                //quando altera o campo de periodicidade atualizar o valor global e valor de parcela
                $('body').on('change','#valor_global',function(event){
                    atualizarValorParcela(parcela);
                });

                $('body').on('click','#remove_item', function(event){
                    removeLinha(this);
                });

                $("form").submit(function (event) {
                    var y = $('#table').html();
                    $('input[name=adicionaCampoRecuperaGridItens]').val(y);
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
                                            text: item.descricao,
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
            });

            function addOption(valor) {
                var option = new Option(valor, valor);
                var select = document.getElementById("tipo_item");
                select.add(option);
            }

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
                    'numero':$('#numero_item').val(),
                    'quantidade' : parseFloat(($('#quantidade_item').val()).toFixed(2)),
                    'valor_unitario': parseFloat(($('#valor_unit').val()).toFixed(2)),
                    'valor_total': parseFloat(($('#valor_total').val()).toFixed(2)),
                    'periodicidade': $('#periodicidade_item').val(),
                    'data_inicio': $('#dt_inicio').val()
                }
                adicionaLinhaItem(item);
                resetarCamposFormulario();
            }

            function resetarCamposFormulario(){
                $('#tipo_item').val('');
                $('#item').val('').change();
                $('#numero_item').val('');
                $('#quantidade_item').val('');
                $('#valor_unit').val('');
                $('#valor_total').val('');
                $('#periodicidade_item').val('');
                $('#dt_inicio').val('');
            }

            //atualiza o valor da parcela do contrato
            function atualizarValorParcela(parcela)
            {
                var valor_global = $('#valor_global').val();
                var numero_parcelas = $('#num_parcelas').val();
                var valor_parcela = valor_global / numero_parcelas;

                $('#valor_parcela').val(parseFloat(valor_parcela.toLocaleString('en-US', {minimumFractionDigits: 4})));
            }

            function atualizarValorTotal(tr){

                var qtd_item = parseFloat($(tr).find('td').eq(3).find('input').val());
                var vl_unit = parseFloat($(tr).find('td').eq(4).find('input').val());
                var vltotal = qtd_item * vl_unit;
                $(tr).find('td').eq(5).find('input').val(parseFloat(vltotal.toFixed(2)));
                calculaTotalGlobal();
            }

            function atualizarQuantidade(tr){
                var vl_unit = parseFloat($(tr).find('td').eq(4).find('input').val());
                var valor_total_item = parseFloat($(tr).find('td').eq(5).find('input').val());
                var quantidade = valor_total_item / vl_unit;
                $(tr).find('td').eq(3).find('input').val(parseFloat(quantidade.toFixed(2)));
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

                var compra_itens_id = $("[name='compra_itens_id[]']");
                compra_itens_id.push(item.id);
                var qtd = item.quantidade;
                var vl_unit = item.valor_unitario;
                var vl_total = item.valor_total;

                // se vier data dos dados do contrato preencher com a data default
                var data_inicio = $('input[name=data_assinatura]').val();
                if ($('input[name=dt_inicio]').val()) {
                    data_inicio = $('input[name=dt_inicio]').val();
                }

                var periodicidade = 1;
                if ($('#periodicidade_item').val()) {
                    periodicidade = $('#periodicidade_item').val();
                }

                var newRow = $("<tr>");
                var cols = "";
                cols += '<td>'+item.tipo_item+'</td>';
                cols += '<td>'+item.numero+'</td>';
                cols += '<td>'+item.descricaodetalhada+'</td>';
                cols += '<td><input class="form-control" type="number"  name="qtd_item[]" id="qtd" max="'+item.quantidade_autorizada+'" min="'+qtd+'" value="'+qtd+'"></td>';
                cols += '<td><input class="form-control" type="number"  name="vl_unit[]" id="vl_unit" value="'+vl_unit+'"></td>';
                cols += '<td><input class="form-control" type="number"  name="vl_total[]" id="vl_total"value="'+vl_total+'"></td>';
                cols += `<td><input class="form-control" type="number" name="periodicidade[]" id="periodicidade" value="${periodicidade}"></td>`;
                cols += `<td><input class="form-control" type="date" name="data_inicio[]" id="data_inicio" value="${data_inicio}"></td>`;
                cols += '<td>';
                cols += '<button type="button" class="btn btn-danger" title="Excluir Item" id="remove_item">'+
                    '<i class="fa fa-trash"></i>'+
                    '</button>';
                cols += '<input type="hidden" name="numero_item_compra[]" id="numero_item_compra" value="'+item.numero+'">';
                cols += '<input type="hidden" name="catmatseritem_id[]" id="catmatseritem_id" value="'+item.catmatseritem_id+'">';
                cols += '<input type="hidden" name="tipo_item_id[]" id="tipo_item_id" value="'+item.tipo_item_id+'">';
                cols += '<input type="hidden" name="compra_item_unidade_id[]" id="compra_item_unidade_id" value="'+item.compra_item_unidade_id+'">';
                cols += '<input type="hidden" name="descricao_detalhada[]" id="descricao_detalhada" value="'+item.descricaodetalhada+'">';
                cols += '</td>';

                newRow.append(cols);
                $("#table-itens").append(newRow);
                calculaTotalGlobal();

            }

            function removeLinha(elemento){
                var tr = $(elemento).closest('tr');
                tr.remove();
                calculaTotalGlobal()
            }

            function calculaTotalGlobal(){
                var valor_total = 0;
                $("#table-itens").find('tr').each(function(){
                    var total_item = parseFloat($(this).find('td').eq(5).find('input').val());
                    //console.log('Valor total do item: '+total_item);
                    var periodicidade = parseInt($(this).find('td').eq(6).find('input').val());
                    // console.log('Periodicidade: '+periodicidade);
                    var total_iten = (total_item * periodicidade);
                    // console.log('ValorTotal * Periodicidade = '+total_item);
                    valor_total += total_iten;
                    // console.log('Valor Global: '+valor_total);
                    if(periodicidade > parcela){
                        parcela = periodicidade;
                    }
                });
                console.log(parcela);
                $('#valor_global').val(parseFloat(valor_total.toFixed(2)));
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

        </script>
    @endpush
@endif

