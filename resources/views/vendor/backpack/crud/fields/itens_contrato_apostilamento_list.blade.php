<!-- field_type_name -->
@inject('compratrait', 'App\Http\Controllers\Empenho\CompraSiasgCrudController')
<div @include('crud::inc.field_wrapper_attributes') >
    <!-- Editable table -->
    <div class="card">
        <div class="card-body">
            <div>
                <div class="table-responsive">
                <br/>
                <table id="table" class="table table-bordered table-responsive-md table-striped text-center ">
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
                    buscarItens();
                });

                //quando altera o campo de valor unitario do item re-calcula os valores
                $('body').on('change','input[name="vl_unit[]"]',function(){
                    var tr = this.closest('tr');
                    atualizarValorTotal(tr);
                    calculaTotalGlobal();
                });

                //quando altera o campo de quantidade de parcela atualizar o valor da parcela no caso de apostilamento
                $('body').on('change','#novo_num_parcelas',function(){
                    atualizarValorParcelaApostilamento();
                });

                //quando altera o campo de periodicidade atualizar o valor global e valor de parcela
                $('body').on('change','#novo_valor_global',function(event){
                    atualizarValorParcelaApostilamento();
                });

                $('body').submit(function(){
                    $('input[name="qtd_item[]"]').prop('disabled', false);
                    $('input[name="vl_unit[]"]').prop('disabled', false);
                    $('input[name="vl_total[]"]').prop('disabled', false);
                    $('input[name="periodicidade[]"]').prop('disabled', false);
                    $('input[name="data_inicio[]"]').prop('disabled', false);
                });
            });

            //atualiza o valor da parcela do contrato para termo de apostilamento
            function atualizarValorParcelaApostilamento()
            {
                var valor_global = $('#novo_valor_global').val();
                var numero_parcelas = $('#novo_num_parcelas').val();
                var valor_parcela = valor_global / numero_parcelas;

                $('#novo_valor_parcela').val(parseFloat(valor_parcela.toLocaleString('en-US', {minimumFractionDigits: 4})));
            }

            function atualizarValorTotal(tr){
                var qtd_item = parseFloat($(tr).find('td').eq(3).find('input').val());
                var vl_unit = parseFloat($(tr).find('td').eq(4).find('input').val());

                var valor_total = qtd_item * vl_unit;

                $(tr).find('td').eq(5).find('input').val(parseFloat(valor_total.toLocaleString('en-US', {minimumFractionDigits: 4})));
                calculaTotalGlobal();
            }

            function adicionaLinhaItem(item){

                var newRow = $("<tr>");
                var cols = "";
                cols += '<td>'+item.descricao+'</td>';
                cols += '<td>'+item.numero+'</td>';
                cols += '<td>'+item.descricao_complementar+'</td>';
                cols += '<td><input class="form-control" type="number"  name="qtd_item[]"  step="0.0001" id="qtd_item" value="'+item.quantidade+'" disabled></td>';
                cols += '<td><input class="form-control" type="number"  name="vl_unit[]" id="vl_unit"  step="0.0001" value="'+item.valorunitario+'"></td>';
                cols += '<td><input class="form-control" type="number"  name="vl_total[]" id="vl_total"  step="0.0001" value="'+item.valortotal+'"disabled></td>';
                cols += '<td><input class="form-control" type="number" name="periodicidade[]" id="periodicidade" value="'+item.periodicidade+'" disabled></td>';
                cols += '<td><input class="form-control" type="date" name="data_inicio[]" id="data_inicio" value="'+ item.data_inicio +'" disabled>';

                cols += '<input type="hidden" name="numero_item_compra[]" id="numero_item_compra" value="'+item.numero+'">';
                cols += '<input type="hidden" name="catmatseritem_id[]" id="catmatseritem_id" value="'+item.catmatseritem_id+'">';
                cols += '<input type="hidden" name="tipo_item_id[]" id="tipo_item_id" value="'+item.tipo_item_id+'">';
                cols += '<input type="hidden" name="descricao_detalhada[]" id="descricao_detalhada" value="'+item.descricao_complementar+'">';
                cols += '<input type="hidden" name="item_id[]" id="item_id" value="'+item.id+'">';
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
                });
                $('#novo_valor_global').val(parseFloat(valor_total.toLocaleString('en-US', {minimumFractionDigits: 4})));

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
        </script>
    @endpush
@endif
