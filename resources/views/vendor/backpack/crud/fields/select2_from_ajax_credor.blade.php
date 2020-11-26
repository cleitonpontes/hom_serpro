<!-- select2 from ajax -->
{{--{{ dd(get_defined_vars()['__data']) }}--}}

@php
    $connected_entity = new $field['model'];
    $connected_entity_key_name = $connected_entity->getKeyName();
    $old_value = old(square_brackets_to_dots($field['name'])) ?? $field['value'] ?? $field['default'] ?? false
@endphp

<div @include('crud::inc.field_wrapper_attributes') >
    <label>{!! $field['label'] !!}</label>
    <button type="button" class="btn btn-primary btn-xs pull-right" data-toggle="modal"
            data-target="#inserir_novo_credor">
        Novo <i class="fa fa-plus"></i>
    </button>


    <?php $entity_model = $crud->model; ?>

    <select
        name="{{ $field['name'] }}"
        style="width: 100%"
        id="select2_ajax_{{ $field['name'] }}"
        @include('crud::inc.field_attributes', ['default_class' =>  'form-control'])
    >

        @if ($old_value)
            @php
                $item = $connected_entity->find($old_value)
            @endphp
            @if ($item)



                {{-- allow clear --}}
                @if ($entity_model::isColumnNullable($field['name']))
                    @if(isset($field['attribute']) && strpos($item->{$field['attribute']},'ESTRANGEIRO') )
                        <option value="" selected>
                            {{ $field['placeholder'] }}
                        </option>
                    @endif

                @endif
                @if(isset($field['attribute2']))
                    <option value="{{ $item->getKey() }}" selected>
                        {{ $item->{$field['attribute']} .' - '. $item->{$field['attribute2']} }}
                    </option>
                @else
                    <option value="{{ $item->getKey() }}" selected>
                        {{ $item->{$field['attribute']} }}
                    </option>
                @endif
            @endif
        @endif
    </select>


    {{-- HINT --}}
    @if (isset($field['hint']))
        <p class="help-block">{!! $field['hint'] !!}</p>
    @endif
</div>

@push('modal_novo_credor')
    <div id="inserir_novo_credor" tabindex="-1" class="modal fade"
         role="dialog"
         aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title">
                        Inserir Novo Credor
                    </h3>
                    <button type="button" class="close" id="fechar_modal" data-dismiss="modal" aria-label="Fechar">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" id="textoModal">
                    <fieldset class="form-group">
                        {!! form($field['form']) !!}
                    </fieldset>
                </div>
                <div class="modal-footer">
                </div>
            </div>
        </div>
    </div>
@endpush


{{-- ########################################## --}}
{{-- Extra CSS and JS for this particular field --}}
{{-- If a field type is shown multiple times on a form, the CSS and JS will only be loaded once --}}
@if ($crud->checkIfFieldIsFirstOfItsType($field))

    {{-- FIELD CSS - will be loaded in the after_styles section --}}
    @push('crud_fields_styles')
        <!-- include select2 css-->
        <link href="{{ asset('vendor/adminlte/bower_components/select2/dist/css/select2.min.css') }}" rel="stylesheet"
              type="text/css"/>
        <link
            href="https://cdnjs.cloudflare.com/ajax/libs/select2-bootstrap-theme/0.1.0-beta.10/select2-bootstrap.min.css"
            rel="stylesheet" type="text/css"/>
        {{-- allow clear --}}
        @if ($entity_model::isColumnNullable($field['name']))
            <style type="text/css">
                .select2-selection__clear::after {
                    content: ' {{ trans('backpack::crud.clear') }}';
                }
            </style>
        @endif
    @endpush

    {{-- FIELD JS - will be loaded in the after_scripts section --}}
    @push('crud_fields_scripts')
        <!-- include select2 js-->
        <script src="{{ asset('vendor/adminlte/bower_components/select2/dist/js/select2.min.js') }}"></script>
        <script src="{{ asset('vendor/adminlte/bower_components/select2/dist/js/i18n/pt-BR.js') }}"></script>
        <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    @endpush

@endif

<!-- include field specific select2 js-->
@push('crud_fields_scripts')
    <script>
        jQuery(document).ready(function ($) {
            // trigger select2 for each untriggered select2 box
            $("#select2_ajax_{{ $field['name'] }}").each(function (i, obj) {
                var form = $(obj).closest('form');

                if (!$(obj).hasClass("select2-hidden-accessible")) {
                    $(obj).select2({
                        theme: 'bootstrap',
                        language: 'pt-BR',
                        multiple: false,
                        placeholder: "{{ $field['placeholder'] }}",
                        minimumInputLength: "{{ $field['minimum_input_length'] }}",

                        {{-- allow clear --}}
                            @if ($entity_model::isColumnNullable($field['name']))
                        allowClear: true,
                        @endif
                            @if ( strpos($item->{$field['attribute']},'ESTRANGEIRO') === false)
                        disabled: disabled,
                        @endif

                        ajax: {
                            url: "{{ $field['data_source'] }}",
                            type: '{{ $field['method'] ?? 'GET' }}',
                            dataType: 'json',
                            quietMillis: 250,
                            data: function (params) {
                                return {
                                    q: params.term, // search term
                                    page: params.page, // pagination
                                    form: form.serializeArray()  // all other form inputs
                                };
                            },
                            @if(isset($field['process_results_template']))
                                @include($field['process_results_template'])
                                @else
                            processResults: function (data, params) {
                                params.page = params.page || 1;

                                var result = {
                                    results: $.map(data.data, function (item) {
                                        textField = "{{$field['attribute']}}";
                                        return {
                                            text: item[textField],
                                            id: item["{{ $connected_entity_key_name }}"]
                                        }
                                    }),
                                    pagination: {
                                        more: data.current_page < data.last_page
                                    }
                                };

                                return result;
                            },
                            @endif
                            cache: true
                        },
                    })
                    {{-- allow clear --}}
                    @if ($entity_model::isColumnNullable($field['name']))
                        .on('select2:unselecting', function (e) {
                            $(this).val('').trigger('change');
                            // console.log('cleared! '+$(this).val());
                            e.preventDefault();
                        })
                    @endif
                    ;

                }
            });

            @if (isset($field['dependencies']))
            @foreach (array_wrap($field['dependencies']) as $dependency)
            $('input[name={{ $dependency }}], select[name={{ $dependency }}], checkbox[name={{ $dependency }}], radio[name={{ $dependency }}], textarea[name={{ $dependency }}]').change(function () {
                $("#select2_ajax_{{ $field['name'] }}").val(null).trigger("change");
            });
            @endforeach
            @endif

            $('#inserir_novo_credor').on('show.bs.modal', function (event) {
                var unidade_id = $('#cb_unidade :selected').val();
                $('#unidade_id').val(unidade_id);

                var botao = $(event.relatedTarget);
                var link = botao.data('link');

                $('#btnExcluir').attr('href', link);
            });

            $('body').on('click', '#btn_inserir', function (event) {
                if (valida_form()) {
                    inserirFornecedor(event);
                    $('#fechar_modal').trigger('click');
                }
                event.preventDefault();
            });
        });

        $(window).on('load', function () {
            var value = $('#tipo_fornecedor').val();

            if (value == 'JURIDICA') {
                mascaraCNPJ('#cpf_cnpj_idgener');
            }

            if (value == 'FISICA') {
                mascaraCPF('#cpf_cnpj_idgener');
            }

            if (value == 'UG') {
                mascaraUG('#cpf_cnpj_idgener');
            }

            if (value == 'IDGENERICO') {
                mascaraIDGener('#cpf_cnpj_idgener');
            }
        });

        $(document).on('change', '#tipo_fornecedor', function () {

            var value = $(this).val();

            if (value == 'JURIDICA') {
                mascaraCNPJ('#cpf_cnpj_idgener');
            }

            if (value == 'FISICA') {
                mascaraCPF('#cpf_cnpj_idgener');
            }

            if (value == 'UG') {
                mascaraUG('#cpf_cnpj_idgener');
            }

            if (value == 'IDGENERICO') {
                mascaraIDGener('#cpf_cnpj_idgener');
            }
        });

        function valida_form(event) {
            // return true;

            var vazio1 = null_or_empty("#tipo_fornecedor");
            var vazio2 = null_or_empty("#cpf_cnpj_idgener");
            var vazio3 = null_or_empty("#nome");

            if (!vazio1) {
                Swal.fire('Alerta!', 'O campo Tipo Fornecedor é obrigatório!', 'warning');
                return false;
            }
            if (!vazio2) {
                Swal.fire('Alerta!', 'O campo CPF/CNPJ/UG/ID Genérico é obrigatório!', 'warning');
                return false;
            }
            if (!vazio3) {
                Swal.fire('Alerta!', 'O campo Nome é obrigatório!', 'warning');
                return false;
            }
            return true;
        }

        function null_or_empty(str) {
            var v = $(str).val();
            if (v == null || v == "") {
                return false;
            }
            return true;
        }

        function inserirFornecedor(event) {

            var fornecedor = $('#tipo_fornecedor :selected').val();
            var cpf_cnpj_idgener = $('#cpf_cnpj_idgener').val();
            var nome = $('#nome').val();


            var url = "{{route('empenho.minuta.inserir.fornecedor')}}";

            params = {
                fornecedor: fornecedor,
                cpf_cnpj_idgener: cpf_cnpj_idgener,
                nome: nome
            }

            axios.post(url, params)
                .then(response => {
                    dados = response.data
                    $('#select2_ajax_{{ $field['name'] }}').append(
                        `<option value="${dados.id}" selected="selected">
                             ${dados.cpf_cnpj_idgener} - ${dados.nome}
                        </option>`
                    );

                    $("#select2_ajax_{{ $field['name'] }}").select2();

                    Swal.fire({
                        position: 'top-end',
                        icon: 'success',
                        title: 'Credor incluído com sucesso!',
                        showConfirmButton: false,
                        timer: 2000
                    })
                    // var table = $('#dataTableBuilder').DataTable();
                    // table.ajax.reload();

                    // if (dados.resultado == true) {
                    //
                    // } else if (dados.resultado == null) {
                    //     Swal.fire({
                    //         icon: 'warning',
                    //         title: 'Célula Orçamentária não encontrada!',
                    //         showConfirmButton: true,
                    //         footer: '<b>Verifique os dados enviados!</b>'
                    //     })
                    // } else if (dados.resultado == false) {
                    //     Swal.fire({
                    //         icon: 'warning',
                    //         title: 'Célula Orçamentária já existe!',
                    //         showConfirmButton: true,
                    //         footer: '<b>Insira outra Célula Orçamentário!</b>'
                    //     })
                    // }
                })
                .catch(error => {
                    alert(error);
                })
                .finally()
            event.preventDefault()
        }
    </script>
@endpush
{{-- End of Extra CSS and JS --}}
{{-- ########################################## --}}
