<!-- field_type_name -->
@inject('compratrait', 'App\Http\Controllers\Empenho\CompraSiasgCrudController')
<div @include('crud::inc.field_wrapper_attributes') >
    <!-- Editable table -->
    <div class="col-xs-6" id="div_inserir_item">
        {{--        <button type="button" class="btn btn-primary" data-toggle="modal"--}}
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
            <th class="text-center">Ações</th>
        </tr>
        </thead>
        <tbody id="table-itens">

        </tbody>
    </table>
    <div id="itens-para-excluir">

    </div>

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
                            <option value="{{$field['material']}}">Material</option>
                            <option value="{{$field['servico']}}">Serviço</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="qtd_item" class="control-label">Item</label>
                        <select class="form-control" id="item">
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="qtd_item" class="control-label">Número<span class="campo-obrigatorio">*</span></label>
                        <input class="form-control" id="numero_item" name="numero_item" type="text">
                    </div>
                    <div class="form-group">
                        <label for="qtd_item" class="control-label">Quantidade<span class="campo-obrigatorio">*</span></label>
                        <input class="form-control" id="quantidade_item" maxlength="10" name="quantidade_item"
                               type="number">
                    </div>
                    <div class="form-group">
                        <label for="vl_unit" class="control-label">Valor Unitário<span class="campo-obrigatorio">*</span></label>
                        <input class="form-control" id="valor_unit" name="valor_unit" type="number">
                    </div>
                    <div class="form-group">
                        <label for="data_inicio" class="control-label">Data Início</label>
                        <input class="form-control" id="dt_inicio" name="dt_inicio" type="date">
                    </div>
                    <button class="btn btn-danger" type="submit" data-dismiss="modal"><i class="fa fa-reply"></i>
                        Cancelar
                    </button>
                    <button class="btn btn-success" type="button" id="btn_inserir_item"><i
                            class="fa fa-save"></i> Incluir
                    </button>
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
                parcela = $('#num_parcelas').val();

                const $tableID = $('#table');

                $('#numero_item').mask('99999');

                buscarItenContrato();

                // ao carregar o documento verifica se possui minuta cadastrada
                var arrMinutaEmpenho = $('[name="minutasempenho[]"]').val();
                if (arrMinutaEmpenho.length === 0) {
                    var button = "";
                    button += '<button type="button" class="btn btn-primary" data-toggle="modal"';
                    button += 'data-target="#inserir_item" hidden>';
                    button += 'Inserir Item <i class="fa fa-plus"></i></button>';
                    $("#div_inserir_item").append(button);
                }

                var valueHidden = $('input[name=adicionaCampoRecuperaGridItens]').val();
                if (valueHidden !== '{' + '{' + 'old(' + '\'name\'' + ')}}') {
                    $('#table').html(valueHidden);
                    calculaTotalGlobal();
                }

                $tableID.on('click', '.table-remove', function () {
                    $(this).parents('tr').detach();
                });

                $('body').on('change', '#tipo_item', function (event) {
                    $('#item').val('');
                    atualizarSelectItem();
                });

                $('body').on('change', '.itens', function (event) {
                    calculaTotalGlobal();
                });

                //quando altera o campo de quantidade do item re-calcula os valores
                $('body').on('change', '[name="qtd_item[]"]', function (event) {
                    var tr = this.closest('tr');
                    atualizarValorTotal(tr);
                });

                //quando altera o campo de valor unitario do item re-calcula os valores
                $('body').on('change', 'input[name="vl_unit[]"]', function (event) {
                    var tr = this.closest('tr');
                    atualizarValorTotal(tr);
                });

                //quando altera o campo de valor total do item re-calcula a quantidade
                $('body').on('change', '[name="vl_total[]"]', function (event) {
                    var tr = this.closest('tr');
                    atualizarQuantidade(tr);
                });

                //quando altera o campo de quantidade de parcela atualizar o valor da parcela
                $('body').on('change', '#num_parcelas', function (event) {
                    atualizarParcela();
                });

                //quando altera o campo de periodicidade atualizar o valor global e valor de parcela
                $('body').on('change', 'input[name="periodicidade[]"]', function (event) {
                    var tr = this.closest('tr');
                    atualizarValorTotal(tr);
                    atualizarValorParcela(parcela);
                });

                //quando altera o campo de periodicidade atualizar o valor global e valor de parcela
                $('body').on('change', '#valor_global', function (event) {
                    atualizarValorParcela(parcela);
                });

                $('body').on('click', '#remove_item', function () {
                    removeLinha(this);
                });

                // se possuir minuta de empenho preenchido não é permitido inserir item
                $('body').on('change', '[name="minutasempenho[]"]', function (event) {

                    $("#div_inserir_item button").remove();

                    var arrMinutaEmpenho = $('[name="minutasempenho[]"]').val();
                    if (arrMinutaEmpenho.length === 0) {
                        var button = "";
                        button += '<button type="button" class="btn btn-primary" data-toggle="modal"';
                        button += 'data-target="#inserir_item" hidden>';
                        button += 'Inserir Item <i class="fa fa-plus"></i></button>';
                        $("#div_inserir_item").append(button);
                    }
                });

                $('body').on('click', '#btn_inserir_item', function (event) {
                    if(!validarCamposItemModal() && $('#item').val()){
                        buscarItem($('#item').val());
                        $('#inserir_item').modal('hide');
                    }
                });

                function validarCamposItemModal() {
                    let arrCamposModal = [
                        {
                            name: 'numero_item',
                            value: $('#numero_item').val(),
                        },
                        {
                            name: 'quantidade_item',
                            value: $('#quantidade_item').val(),
                        },
                        {
                            name: 'valor_unit',
                            value: $('#valor_unit').val(),
                        },
                        {
                            name: 'tipo_item',
                            value: $('#tipo_item').val(),
                        },
                        {
                            name: 'item',
                            value: $('#item').val(),
                        }
                    ]
                    return verificarCampoModalItem(arrCamposModal);
                }

                function verificarCampoModalItem(arrCamposModal){
                    var hasError = false;
                    arrCamposModal.forEach(function(campo){
                        $(`#${campo.name}`).closest('.form-group').removeClass('has-error');
                        if(!campo.value){
                            $(`#${campo.name}`).closest('.form-group').addClass('has-error');
                            hasError = true;
                        }
                    });
                    return hasError;
                }
            });

            //atualiza o valor da parcela do contrato
            function atualizarValorParcela(parcela) {
                var valor_global = $('#valor_global').val();
                var valor_parcela = valor_global / parcela;

                $('#valor_parcela').val(parseFloat(valor_parcela.toFixed(4)));
            }

            //atualiza o valor da parcela do contrato
            function atualizarParcela() {
                var valor_global = $('#valor_global').val();
                var num_parcelas = $('#num_parcelas').val();
                var valor = valor_global / num_parcelas;

                $('#valor_parcela').val(parseFloat(valor.toFixed(4)));
            }

            function atualizarValorTotal(tr) {
                var qtd_item = parseFloat($(tr).find('td').eq(3).find('input').val());
                var vl_unit = parseFloat($(tr).find('td').eq(4).find('input').val());
                var periodicidade = parseInt($(tr).find('td').eq(5).find('input').val());
                var vltotal = qtd_item * vl_unit * periodicidade;
                $(tr).find('td').eq(6).find('input').val(parseFloat(vltotal.toFixed(4)));
                calculaTotalGlobal();
            }

            function atualizarQuantidade(tr) {
                var vl_unit = $(tr).find('td').eq(4).find('input').val();
                var valor_total_item = $(tr).find('td').eq(6).find('input').val();

                var quantidade = valor_total_item / vl_unit;

                $(tr).find('td').eq(3).find('input').val(parseFloat(quantidade.toFixed(4)));
                calculaTotalGlobal();
            }

            function atualizarDataInicioItens() {
                $("#table-itens").find('tr').each(function () {
                    if ($(this).find('td').eq(7).find('input').val() === "") {
                        $(this).find('td').eq(7).find('input').val($('input[name=data_assinatura]').val());
                    }
                });
            }

            function adicionaLinhaItem(item) {

                if (!item.periodicidade) {
                    item.periodicidade = 1;
                }

                let valorTotal = item.quantidade * parseFloat(item.valorunitario) * item.periodicidade;

                var newRow = $("<tr>");
                var cols = "";
                cols += '<td>' + item.descricao;
                cols += '<input type="hidden" name="numero_item_compra[]" value="' + item.numero + '">';
                cols += '<input type="hidden" name="catmatseritem_id[]" value="' + item.catmatseritem_id + '">';
                cols += '<input type="hidden" name="tipo_item_id[]" value="' + item.tipo_item_id + '">';
                cols += '<input type="hidden" name="descricao_detalhada[]" value="' + item.descricao_complementar + '">';
                cols += '<input type="hidden" name="saldo_historico_id[]" value="' + item.id + '">';
                cols += '</td>';
                cols += '<td>' + item.numero + '</td>';
                cols += '<td>' + item.codigo_siasg + ' - ' + item.descricao_complementar + '</td>';
                cols += '<td><input class="form-control" type="number"  name="qtd_item[]" step="0.0001" value="' + item.quantidade + '"></td>';
                cols += '<td><input class="form-control" type="number" readonly name="vl_unit[]" step="0.0001" value="' + item.valorunitario + '"></td>';
                cols += '<td><input class="form-control" type="number" name="periodicidade[]" value="' + item.periodicidade + '"></td>';
                cols += '<td><input class="form-control" type="number" readonly name="vl_total[]" step="0.0001" value="' + valorTotal + '"></td>';
                cols += '<td><input class="form-control" type="date" name="data_inicio[]" value="' + item.data_inicio + '"></td>';
                cols += '<td><button type="button" class="btn btn-danger" title="Excluir Item" id="remove_item">' +
                    '<i class="fa fa-trash"></i>' +
                    '</button>';
                cols += '</td>';

                newRow.append(cols);
                $("#table-itens").append(newRow);
                calculaTotalGlobal();
            }

            function calculaTotalGlobal() {
                let totalItens = 0;
                $('[name="vl_total[]"]').each(function (index, elementInput) {
                    totalItens = parseFloat(totalItens) + parseFloat(elementInput.value);
                })
                $('#valor_global').val(totalItens.toFixed(2));
                $('#valorTotalItem').val(totalItens.toFixed(2));
                $("#table-itens").find('tr').each(function () {
                    var periodicidade = parseInt($(this).find('td').eq(5).find('input').val());
                    //seta num_parcelas
                    if (periodicidade > parcela) {
                        parcela = periodicidade;
                        $('#num_parcelas').val(parcela);
                    }
                });
                atualizarValorParcela(parcela);
            }

            function buscarItenContrato() {
                var instrumentoinicial_id = $("[name=instrumentoinicial_id]").val();
                var url = "{{route('saldo.historico.itens',':id')}}";
                url = url.replace(':id', instrumentoinicial_id);

                axios.request(url)
                    .then(response => {
                        var itens = response.data;
                        var qtd_itens = itens.length;
                        itens.forEach(function (item) {
                            var linhas = $("#table-itens tr").length;
                            if (qtd_itens > linhas) {
                                adicionaLinhaItem(item);
                            }
                        });
                    })
                    .catch(error => {
                        alert(error);
                    })
                    .finally()
            }

            function atualizarSelectItem() {
                $('#item').select2({
                    ajax: {
                        url: urlItens(),
                        dataType: 'json',
                        delay: 250,
                        processResults: function (data) {
                            return {
                                results: $.map(data.data, function (item) {
                                    return {
                                        text: item.codigo_siasg + ' - ' + item.descricao,
                                        id: item.id
                                    }
                                })
                            };
                        },
                        cache: true
                    }
                });
                $('.selection .select2-selection').css("height", "34px").css('border-color', '#d2d6de');
            }

            function urlItens() {
                let url = '{{route('busca.catmatseritens.portipo',':tipo_id')}}';
                url = url.replace(':tipo_id', $('#tipo_item').val());
                return url;
            }

            function buscarItem(id) {
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

            function prepararItemParaIncluirGrid(item) {
                item = {
                    'descricao': $('#tipo_item :selected').text(),
                    'descricao_complementar': item.descricao,
                    'quantidade': $('#quantidade_item').val(),
                    'valorunitario': $('#valor_unit').val(),
                    'numero': $('#numero_item').val(),
                    'periodicidade': $('#periodicidade_item').val(),
                    'data_inicio': $('#dt_inicio').val(),
                    'catmatseritem_id': item.id,
                    'tipo_item_id': $('#tipo_item').val(),
                    'codigo_siasg': item.codigo_siasg,
                }

                adicionaLinhaItem(item);
                resetarCamposFormulario();
            }

            function resetarCamposFormulario() {
                $('#tipo_item').val('').change();
                $('#item').val('').change();
                $('#quantidade_item').val('');
                $('#numero_item').val('');
                $('#valor_unit').val('');
                $('#periodicidade_item').val('');
                $('#dt_inicio').val('');
            }

            function removeLinha(elemento) {
                var tr = $(elemento).closest('tr');
                var historicoSaldoItemId = $(tr).find('td').eq(8).find('#saldo_historico_id').val();
                if (historicoSaldoItemId === 'undefined') {
                    tr.remove();
                    calculaTotalGlobal()
                } else {
                    removerSaldoHistoricoItem(historicoSaldoItemId);
                    tr.remove();
                    calculaTotalGlobal()
                }
            }

            function removerSaldoHistoricoItem(historicoSaldoItemId) {
                var newItem = $("#itens-para-excluir");
                var cols = "";
                cols += '<input type="hidden" name="excluir_item[]" value="' + historicoSaldoItemId + '">';
                newItem.append(cols);
            }

            /**
             * atualiza o value do atributo no html
             * necessario para recuperar a tabela de itens com os ultimos dados inseridos nos inputs
             * @param event
             */
            function atualizaValueHTMLCamposAbaItem() {
                $('[name="qtd_item[]"]').each(function (index, elementInput) {
                    elementInput.setAttribute('value', elementInput.value);
                })
                $('[name="vl_unit[]"]').each(function (index, elementInput) {
                    elementInput.setAttribute('value', elementInput.value);
                })
                $('[name="vl_total[]"]').each(function (index, elementInput) {
                    elementInput.setAttribute('value', elementInput.value);
                })
                $('[name="periodicidade[]"]').each(function (index, elementInput) {
                    elementInput.setAttribute('value', elementInput.value);
                })
                $('[name="data_inicio[]"]').each(function (index, elementInput) {
                    elementInput.setAttribute('value', elementInput.value);
                })
            }
        </script>
    @endpush
@endif
