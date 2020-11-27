<!-- field_type_name -->
@inject('compratrait', 'App\Http\Controllers\Empenho\CompraSiasgCrudController')
<div @include('crud::inc.field_wrapper_attributes') >
    <label>{!! $field['label'] !!}</label>
    <input
        type="text"
        name="{{ $field['name'] }}"
        value="{{ old($field['name']) ? old($field['name']) : (isset($field['value']) ? $field['value'] : (isset($field['default']) ? $field['default'] : '' )) }}"
        @include('crud::inc.field_attributes')
    >
    <br>
    <!-- Editable table -->
    <div class="card">
        <div class="card-body">
            <div id="table" class="table-editable">
                <span class="table-up">
                            <button type="button" class="btn btn-primary" data-toggle="modal"
                                    data-target="#inserir_item">
                                Inserir Item <i class="fa fa-plus"></i>
                            </button>
                          </span>
                <table class="table table-bordered table-responsive-md table-striped text-center">
                    <thead>
                    <tr>
                        <th class="text-center">Tipo Item</th>
                        <th class="text-center">Item</th>
                        <th class="text-center">Quantidade</th>
                        <th class="text-center">Valor Unitário</th>
                        <th class="text-center">Valor Total</th>
                        <th class="text-center">Ações</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td class="pt-3-half" contenteditable="true">Aurelia Vega</td>
                        <td class="pt-3-half" contenteditable="true">30</td>
                        <td class="pt-3-half" contenteditable="true">Deepends</td>
                        <td class="pt-3-half" contenteditable="true">Spain</td>
                        <td class="pt-3-half" contenteditable="true">Madrid</td>
                        <td class="pt-3-half">
                          <span class="table-up">
                            <button type="button" class="btn btn-primary" data-toggle="modal"
                                    data-target="#inserir_item">
                                Editar <i class="fa fa-edit"></i>
                            </button>
                          </span>
                            <span class="table-remove">
                                <button type="button" class="btn btn-warning btn-rounded btn-sm my-0">Remover</button>
                            </span>
                        </td>
                    </tr>
                    <!-- This is our clonable table line -->
                    <tr>
                        <td class="pt-3-half" contenteditable="true">Guerra Cortez</td>
                        <td class="pt-3-half" contenteditable="true">45</td>
                        <td class="pt-3-half" contenteditable="true">Insectus</td>
                        <td class="pt-3-half" contenteditable="true">USA</td>
                        <td class="pt-3-half" contenteditable="true">San Francisco</td>
                        <td class="pt-3-half">
                          <span class="table-up">
                            <button type="button" class="btn btn-primary" data-toggle="modal"
                                    data-target="#inserir_item">
                                Editar <i class="fa fa-edit"></i>
                            </button>
                          </span>
                            <span class="table-remove">
                                <button type="button" class="btn btn-warning btn-rounded btn-sm my-0">Remover</button>
                            </span>
                        </td>
                    </tr>
                    <!-- This is our clonable table line -->
                    <tr>
                        <td class="pt-3-half" contenteditable="true">Guadalupe House</td>
                        <td class="pt-3-half" contenteditable="true">26</td>
                        <td class="pt-3-half" contenteditable="true">Isotronic</td>
                        <td class="pt-3-half" contenteditable="true">Germany</td>
                        <td class="pt-3-half" contenteditable="true">Frankfurt am Main</td>
                        <td class="pt-3-half">
                          <span class="table-up">
                            <button type="button" class="btn btn-primary" data-toggle="modal"
                                    data-target="#inserir_item">
                                Editar <i class="fa fa-edit"></i>
                            </button>
                          </span>
                            <span class="table-remove">
                                <button type="button" class="btn btn-warning btn-rounded btn-sm my-0">Remover</button>
                            </span>
                        </td>
                    </tr>
                    <!-- This is our clonable table line -->
                    <tr class="hide">
                        <td class="pt-3-half" contenteditable="true">Elisa Gallagher</td>
                        <td class="pt-3-half" contenteditable="true">31</td>
                        <td class="pt-3-half" contenteditable="true">Portica</td>
                        <td class="pt-3-half" contenteditable="true">United Kingdom</td>
                        <td class="pt-3-half" contenteditable="true">London</td>
                        <td class="pt-3-half">
                          <span class="table-up">
                            <button type="button" class="btn btn-primary" data-toggle="modal"
                                    data-target="#inserir_item">
                                Editar <i class="fa fa-edit"></i>
                            </button>
                          </span>
                            <span class="table-remove">
                                <button type="button" class="btn btn-warning btn-rounded btn-sm my-0">Remover</button>
                            </span>
                        </td>
                    </tr>
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
                    <fieldset class="form-group">
                        {!! form($compratrait->retonaFormModal(1,1)) !!}
                    </fieldset>
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
        <script type="text/javascript">
            const $tableID = $('#table');
            const $BTN = $('#export-btn');
            const $EXPORT = $('#export');

            $tableID.on('click', '.table-remove', function () {

                $(this).parents('tr').detach();
            });


            // A few jQuery helpers for exporting only
            jQuery.fn.pop = [].pop;
            jQuery.fn.shift = [].shift;

            $BTN.on('click', () => {

                const $rows = $tableID.find('tr:not(:hidden)');
                const headers = [];
                const data = [];

                // Get the headers (add special header logic here)
                $($rows.shift()).find('th:not(:empty)').each(function () {

                    headers.push($(this).text().toLowerCase());
                });

                // Turn all existing rows into a loopable array
                $rows.each(function () {
                    const $td = $(this).find('td');
                    const h = {};

                    // Use the headers from earlier to name our hash keys
                    headers.forEach((header, i) => {

                        h[header] = $td.eq(i).text();
                    });

                    data.push(h);
                });

                // Output the result
                $EXPORT.text(JSON.stringify(data));
            });
        </script>
    @endpush
@endif
