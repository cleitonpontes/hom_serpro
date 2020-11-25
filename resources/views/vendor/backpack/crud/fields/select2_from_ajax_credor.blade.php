<!-- select2 from ajax -->
{{--{{ dd(get_defined_vars()['__data']) }}--}}
@php
    $connected_entity = new $field['model'];
    $connected_entity_key_name = $connected_entity->getKeyName();
    $old_value = old(square_brackets_to_dots($field['name'])) ?? $field['value'] ?? $field['default'] ?? false;
@endphp

<div @include('crud::inc.field_wrapper_attributes') >
    <label>{!! $field['label'] !!}</label>
    <button type="button" class="btn btn-primary btn-xs pull-right" data-toggle="modal"
            data-target="#inserir_celular_orcamentaria">
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
                $item = $connected_entity->find($old_value);
            @endphp
            @if ($item)

            {{-- allow clear --}}
                @if ($entity_model::isColumnNullable($field['name']))
                    @if(isset($field['attribute']) && $item->{$field['attribute']} == "ESTRANGEIRO")
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

    <div id="inserir_celular_orcamentaria" tabindex="-1" class="modal fade"
         role="dialog"
         aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title">
                        Inserir Célula Orçamentária
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


    {{-- HINT --}}
    @if (isset($field['hint']))
        <p class="help-block">{!! $field['hint'] !!}</p>
    @endif
</div>

{{-- ########################################## --}}
{{-- Extra CSS and JS for this particular field --}}
{{-- If a field type is shown multiple times on a form, the CSS and JS will only be loaded once --}}
@if ($crud->checkIfFieldIsFirstOfItsType($field))

    {{-- FIELD CSS - will be loaded in the after_styles section --}}
    @push('crud_fields_styles')
    <!-- include select2 css-->
    <link href="{{ asset('vendor/adminlte/bower_components/select2/dist/css/select2.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2-bootstrap-theme/0.1.0-beta.10/select2-bootstrap.min.css" rel="stylesheet" type="text/css" />
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
    @endpush

@endif

<!-- include field specific select2 js-->
@push('crud_fields_scripts')
<script>
    jQuery(document).ready(function($) {
        // trigger select2 for each untriggered select2 box
        $("#select2_ajax_{{ $field['name'] }}").each(function (i, obj) {
            var form = $(obj).closest('form');

            if (!$(obj).hasClass("select2-hidden-accessible"))
            {
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

                    @if ($item->{$field['attribute']} != "ESTRANGEIRO")
                        disabled:  disabled,
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
                .on('select2:unselecting', function(e) {
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

            $('#inserir_celular_orcamentaria').on('show.bs.modal', function (event) {
                var unidade_id = $('#cb_unidade :selected').val();
                $('#unidade_id').val(unidade_id);

                var botao = $(event.relatedTarget);
                var link = botao.data('link');

                $('#btnExcluir').attr('href', link);
            });
    });
</script>
@endpush
{{-- End of Extra CSS and JS --}}
{{-- ########################################## --}}
