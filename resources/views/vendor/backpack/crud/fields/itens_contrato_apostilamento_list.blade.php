<!-- field_type_name -->
@inject('compratrait', 'App\Http\Controllers\Empenho\CompraSiasgCrudController')
<div @include('crud::inc.field_wrapper_attributes') >
    <!-- Editable table -->
    <div class="row">
        <div class="col-md-3 col-md-offset-9 text-right">
            <div class="input-group">
                <div class="input-group-addon">Valor total do Contrato:</div>
                <input type="text" class="form-control" id="valorTotalItem" readonly value="0">
            </div>
        </div>
    </div>
    <br>
    <br>
    <div class="table-responsive">
        <table id="table" class="table table-bordered table-responsive-md table-striped text-center ">
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
            </tr>
            </thead>
            <tbody id="table-itens">

            </tbody>
        </table>
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

                buscarItens();

                var valueHidden = $('input[name=adicionaCampoRecuperaGridItens]').val();
                if (valueHidden !== '{' + '{' + 'old(' + '\'name\'' + ')}}') {
                    $('#table').html(valueHidden);
                    calculaTotalGlobal();
                }

                $tableID.on('click', '.table-remove', function () {
                    $(this).parents('tr').detach();
                });

                //quando altera o campo de valor unitario do item re-calcula os valores
                $('body').on('change','input[name="vl_unit[]"]',function(event){
                    var tr = this.closest('tr');
                    atualizarValorTotal(tr);
                });

                //quando altera o campo de quantidade de parcela atualizar o valor da parcela no caso de apostilamento
                $('body').on('change','#novo_num_parcelas',function(){
                    atualizarValorParcelaApostilamento();
                });

                //quando altera o campo de periodicidade atualizar o valor global e valor de parcela
                $('body').on('change','#novo_valor_global',function(event){
                    atualizarValorParcelaApostilamento();
                });
            });

            //atualiza o valor da parcela do contrato para termo de apostilamento
            function atualizarValorParcelaApostilamento()
            {
                var valor_global = $('#novo_valor_global').val();
                var numero_parcelas = $('#novo_num_parcelas').val();
                var valor_parcela = valor_global / numero_parcelas;

                $('#novo_valor_parcela').val(parseFloat(valor_parcela.toFixed(2)));
            }

            function atualizarValorTotal(tr){
                var qtd_item = parseFloat($(tr).find('td').eq(3).find('input').val());
                var vl_unit = parseFloat($(tr).find('td').eq(4).find('input').val());
                var periodicidade = parseInt($(tr).find('td').eq(5).find('input').val());
                var vltotal = qtd_item * vl_unit * periodicidade;
                $(tr).find('td').eq(6).find('input').val(parseFloat(vltotal.toFixed(4)));
                calculaTotalGlobal();
            }

            function adicionaLinhaItem(item){

                var newRow = $("<tr>");
                var cols = "";
                cols += '<td>'+item.descricao+'</td>';
                cols += '<td>'+item.numero+'</td>';
                cols += '<td>'+item.codigo_siasg + ' - ' +item.descricao_complementar+'</td>';
                cols += '<td><input class="form-control" type="number"  name="qtd_item[]"  step="0.0001" id="qtd_item" value="'+item.quantidade+'" disabled></td>';
                cols += '<td><input class="form-control" type="number"  name="vl_unit[]" id="vl_unit"  step="0.0001" value="'+item.valorunitario+'"></td>';
                cols += '<td><input class="form-control" type="number" name="periodicidade[]" id="periodicidade" value="'+item.periodicidade+'" disabled></td>';
                cols += '<td><input class="form-control" type="number"  name="vl_total[]" id="vl_total"  step="0.0001" value="'+item.valortotal+'"disabled></td>';
                cols += '<td><input class="form-control" type="date" name="data_inicio[]" id="data_inicio" value="'+ item.data_inicio +'" disabled>';

                cols += '<input type="hidden" name="numero_item_compra[]" id="numero_item_compra" value="'+item.numero+'">';
                cols += '<input type="hidden" name="catmatseritem_id[]" id="catmatseritem_id" value="'+item.catmatseritem_id+'">';
                cols += '<input type="hidden" name="tipo_item_id[]" id="tipo_item_id" value="'+item.tipo_item_id+'">';
                cols += '<input type="hidden" name="descricao_detalhada[]" id="descricao_detalhada" value="'+item.descricao_complementar+'">';
                cols += '<input type="hidden" name="item_id[]" id="item_id" value="'+item.id+'">';
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
                $('#novo_valor_global').val(parseFloat(totalItens.toFixed(2)));
                $('#valorTotalItem').val(totalItens.toFixed(2));

                atualizarValorParcelaApostilamento();
            }

            function buscarItens()
            {
                if($("[name=apostilamento_id]").val()){
                    buscarSaldoHistoricoItens();
                } else{
                    buscarContratoItens();
                }
            }

            function buscarSaldoHistoricoItens(){
                var apostilamento_id = $("[name=apostilamento_id]").val();
                var url = "{{route('saldo.historico.itens',':id')}}";
                url = url.replace(':id', apostilamento_id);
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
