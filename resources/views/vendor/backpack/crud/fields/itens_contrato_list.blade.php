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
                const $tableID = $('#table');

                $tableID.on('click', '.table-remove', function () {
                    $(this).parents('tr').detach();
                });

                $('body').on('change','#tipo_item', function(event){
                    $('#item').val('');
                    atualizarSelectItem();
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

        </script>
    @endpush
@endif
