
<!-- field_type_name -->
@inject('compratrait', 'App\Http\Controllers\Empenho\CompraSiasgCrudController')
<div @include('crud::inc.field_wrapper_attributes') >
    <!-- Editable table -->
    <div class="card">
        <div class="card-body">
            <div>

                <table id="tb_conta_corrente" class="table table-bordered table-striped m-b-0">
                    <thead>

                    <tr>
                        <th style="font-weight: 600!important;">
                            Número Conta Corrente
                        </th>
                        <th style="font-weight: 600!important;">
                            Valor
                        </th>
                        <th class="text-center ">

                        </th>
                    </tr>
                    </thead>
                    <tbody id="table-itens"></tbody>
                </table>
                <div class="array-controls btn-group m-t-10">
                    <button type="button" id="inserir_item" class="btn btn-sm btn-default"
                    ><i class="fa fa-plus"></i> {{trans('backpack::crud.add')}}
                    </button>
                </div>
            </div>
        </div>
    </div>
    <!-- Editable table -->

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
        {{--        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>--}}
        <script type="text/javascript">

            $(document).ready(function () {

                $('body').on('click', '#inserir_item', function (event) {
                    adicionaLinhaItem();
                });

                $('body').on('click', '#remove_item', function (event) {
                    removeLinha(this);
                });
            });

            function adicionaLinhaItem() {


                var newRow = $("<tr>");
                var cols = "";
                cols += '<td><input type="text" class="form-control"   name="numConta[]" value="{{$crud->params['conta_corrente_padrao']}}" ></td>';
                cols += '<td><input type="number" class="form-control"  name="valor[]" value="{{$crud->params['valor_total']}}" step="any"></td>';
                cols += '<td>';
                cols += '<button type="button" class="btn btn-danger" title="Excluir Item" id="remove_item">' +
                    '<i class="fa fa-trash"></i>' +
                    '</button>';
                cols += '</td>';

                newRow.append(cols);
                $("#table-itens").append(newRow);
            }

            function removeLinha(elemento) {
                var tr = $(elemento).closest('tr');
                tr.remove();
            }

        </script>
    @endpush
@endif
