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
                <div class="table-responsive">
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
                    </tr>
                    </thead>
                    <tbody id="table-itens">

                    </tbody>
                </table>
                </div>
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
                const $tableID = $('#table');

                $('#numero_item').mask('99999');

                $tableID.on('click', '.table-remove', function () {
                    $(this).parents('tr').detach();
                });

                $('body').on('click','#itensdocontrato', function(event){
                    buscarItenContrato();
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
                    'descricao' : $('#tipo_item :selected').text(),
                    'descricao_complementar': item.descricao,
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

            //atualiza o valor da parcela do contrato
            function atualizarValorParcela()
            {

                valor_global = $('#valor_global').val();
                numero_parcelas = $('#num_parcelas').val();

                $('#valor_parcela').val(valor_global / numero_parcelas);
            }

            function atualizarValorTotal(tr){
                var qtd_item = parseFloat($(tr).find('td').eq(3).find('input').val());
                var vl_unit = parseFloat($(tr).find('td').eq(4).find('input').val());

                parseFloat($(tr).find('td').eq(5).find('input').val(qtd_item * vl_unit));
            }

            function atualizarQuantidade(tr){
                var vl_unit = parseFloat($(tr).find('td').eq(4).find('input').val());
                var valor_total_item = parseFloat($(tr).find('td').eq(5).find('input').val());

                parseFloat($(tr).find('td').eq(3).find('input').val(valor_total_item / vl_unit));
            }

            function atualizarDataInicioItens(){
                $("#table-itens").find('tr').each(function(){
                    if ($(this).find('td').eq(7).find('input').val() === "") {
                        $(this).find('td').eq(7).find('input').val($('input[name=data_assinatura]').val());
                    }
                });
            }

            function adicionaLinhaItem(item){
                // var compra_itens_id = $("[name='compra_itens_id[]']");
                // compra_itens_id.push(item.id);

                var newRow = $("<tr>");
                var cols = "";
                cols += '<td>'+item.descricao+'</td>';
                cols += '<td>'+item.numero+'</td>';
                cols += '<td>'+item.descricao_complementar+'</td>';
                cols += '<td><input class="form-control" type="number"  name="qtd_item[]" id="qtd" value="'+item.quantidade+'"></td>';
                cols += '<td><input class="form-control" type="number"  name="vl_unit[]" id="vl_unit" value="'+item.valorunitario+'"></td>';
                cols += '<td><input class="form-control" type="number"  name="vl_total[]" id="vl_total"value="'+item.valortotal+'"></td>';
                cols += '<td><input class="form-control" type="number" name="periodicidade[]" id="periodicidade" value="'+item.periodicidade+'"></td>';
                cols += '<td><input class="form-control" type="date" name="data_inicio[]" id="data_inicio" value="'+ item.data_inicio +'">';

                cols += '<input type="hidden" name="numero_item_compra[]" id="numero_item_compra" value="'+item.numero+'">';
                cols += '<input type="hidden" name="catmatseritem_id[]" id="catmatseritem_id" value="'+item.catmatseritem_id+'">';
                cols += '<input type="hidden" name="tipo_item_id[]" id="tipo_item_id" value="'+item.tipo_item_id+'">';
                cols += '<input type="hidden" name="descricao_detalhada[]" id="descricao_detalhada" value="'+item.descricao_complementar+'">';
                cols += '<input type="hidden" name="saldo_historico_item_id[]" id="saldo_historico_item_id" value="'+item.saldo_historico_item_id+'">';
                cols += '</td>';

                newRow.append(cols);
                $("#table-itens").append(newRow);
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

            function buscarItenContrato()
            {
                var contrato_id = $("[name=contrato_id]").val();
                var url = "{{route('saldo.historico.item.contrato',':contrato_id')}}";
                url = url.replace(':contrato_id', contrato_id);

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
                    })
                    .catch(error => {
                        alert(error);
                    })
                    .finally()
            }
        </script>
    @endpush
@endif
