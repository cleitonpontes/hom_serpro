<!-- field_type_name -->
@inject('compratrait', 'App\Http\Controllers\Empenho\CompraSiasgCrudController')
<div @include('crud::inc.field_wrapper_attributes') >
    <!-- Editable table -->
    <div class="card">
        <div class="card-body">
            <div>
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
                    </tr>
                    </thead>
                    <tbody id="table-itens">

                    </tbody>
                </table>
            </div>
            <div id="itens-para-excluir">

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
                    atualizarParcela();
                });

                //quando altera o campo de periodicidade atualizar o valor global e valor de parcela
                $('body').on('change','input[name="periodicidade[]"]',function(event){
                    calculaTotalGlobal();
                    atualizarValorParcela(parcela);
                });

                //quando altera o campo de periodicidade atualizar o valor global e valor de parcela
                $('body').on('change','#valor_global',function(event){
                    atualizarValorParcela(parcela);
                });
            });

            //atualiza o valor da parcela do contrato
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

                var valor_total = qtd_item * vl_unit;

                $(tr).find('td').eq(5).find('input').val(parseFloat(valor_total.toFixed(4)));
                calculaTotalGlobal();
            }

            function atualizarQuantidade(tr){
                var vl_unit = $(tr).find('td').eq(4).find('input').val();
                var valor_total_item = $(tr).find('td').eq(5).find('input').val();

                var quantidade = valor_total_item / vl_unit;

                $(tr).find('td').eq(3).find('input').val(parseFloat(quantidade.toFixed(4)));
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

                var newRow = $("<tr>");
                var cols = "";
                cols += '<td>'+item.descricao+'</td>';
                cols += '<td>'+item.numero+'</td>';
                cols += '<td>'+item.descricao_complementar+'</td>';
                cols += '<td><input class="form-control" type="number"  name="qtd_item[]" id="qtd"  step="0.0001" value="'+item.quantidade+'"></td>';
                cols += '<td><input class="form-control" type="number"  name="vl_unit[]" id="vl_unit"  step="0.0001" value="'+item.valorunitario+'"></td>';
                cols += '<td><input class="form-control" type="number"  name="vl_total[]" id="vl_total" step="0.0001" value="'+item.valortotal+'"></td>';
                cols += '<td><input class="form-control" type="number" name="periodicidade[]" id="periodicidade" value="'+item.periodicidade+'"></td>';
                cols += '<td><input class="form-control" type="date" name="data_inicio[]" id="data_inicio" value="'+ item.data_inicio +'">';

                cols += '<input type="hidden" name="numero_item_compra[]" id="numero_item_compra" value="'+item.numero+'">';
                cols += '<input type="hidden" name="catmatseritem_id[]" id="catmatseritem_id" value="'+item.catmatseritem_id+'">';
                cols += '<input type="hidden" name="tipo_item_id[]" id="tipo_item_id" value="'+item.tipo_item_id+'">';
                cols += '<input type="hidden" name="descricao_detalhada[]" id="descricao_detalhada" value="'+item.descricao_complementar+'">';
                cols += '<input type="hidden" name="saldo_historico_id[]" id="saldo_historico_id" value="'+item.id+'">';
                cols += '</td>';

                newRow.append(cols);
                $("#table-itens").append(newRow);
            }

            function calculaTotalGlobal(){
                var valor_total = 0;
                $("#table-itens").find('tr').each(function(){
                    var total_item = parseFloat($(this).find('td').eq(5).find('input').val());
                    var periodicidade = parseInt($(this).find('td').eq(6).find('input').val());
                    var total_iten = (total_item * periodicidade);
                    valor_total += total_iten;
                    if(periodicidade > parcela){
                        parcela = periodicidade;
                        $('#num_parcelas').val(parcela);
                    }
                });
                $('#valor_global').val(parseFloat(valor_total.toFixed(4)));
                atualizarValorParcela(parcela);
            }

            function buscarItenContrato()
            {
                var instrumentoinicial_id = $("[name=instrumentoinicial_id]").val();
                var url = "{{route('saldo.historico.itens',':id')}}";
                url = url.replace(':id', instrumentoinicial_id);

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
