<!-- select2 from ajax multiple -->
@php
    $connected_entity = new $field['model'];
    $connected_entity_key_name = $connected_entity->getKeyName();
    $old_value = old(square_brackets_to_dots($field['name'])) ?? $field['value'] ?? $field['default'] ?? false;
@endphp

<div @include('crud::inc.field_wrapper_attributes') >
    <label>{!! $field['label'] !!}</label>
    @include('crud::inc.field_translatable_icon')
    <select
        name="{{ $field['name'] }}[]"
        style="width: 100%"
        id="select2_ajax_multiple_{{ $field['name'] }}"
        @include('crud::inc.field_attributes', ['default_class' =>  'form-control'])
        multiple>

        @if ($old_value)
            @foreach ($old_value as $item)
                @if (!is_object($item))
                    @php
                        $item = $connected_entity->find($item);
                    @endphp
                @endif
                @if(isset($field['attribute2']))
                    <option value="{{ $item->getKey() }}" selected>
                        {{ $item->{$field['attribute']} .' - '. $item->{$field['attribute2']} }}
                    </option>
                @else
                    <option value="{{ $item->getKey() }}" selected>
                        {{ $item->{$field['attribute']} }}
                        {{ $connected_entity->retornaConsultaMultiSelect($item) }}
                    </option>
                @endif
            @endforeach
        @endif
    </select>

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
        <link href="{{ asset('vendor/adminlte/bower_components/select2/dist/css/select2.min.css') }}" rel="stylesheet"
              type="text/css"/>
        <link
            href="https://cdnjs.cloudflare.com/ajax/libs/select2-bootstrap-theme/0.1.0-beta.10/select2-bootstrap.min.css"
            rel="stylesheet" type="text/css"/>
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
        jQuery(document).ready(function ($) {
            // trigger select2 for each untriggered select2 box
            $("#select2_ajax_multiple_{{ $field['name'] }}").each(function (i, obj) {
                var form = $(obj).closest('form');

                if (!$(obj).hasClass("select2-hidden-accessible")) {
                    $(obj).select2({
                        theme: 'bootstrap',
                        language: 'pt-BR',
                        multiple: true,
                        placeholder: "{{ $field['placeholder'] }}",
                        minimumInputLength: "{{ $field['minimum_input_length'] }}",
                        ajax: {
                            url: "{{ $field['data_source'] }}",
                            type: '{{ $field['method'] ?? 'GET' }}',
                            dataType: 'json',
                            quietMillis: 250,
                            data: function (params) {
                                return {
                                    q: params.term, // search term
                                    page: params.page, // pagination
                                    form: cleanArray(form.serializeArray())
                                };
                            },
                            @if(isset($field['process_results_template']))
                                @include($field['process_results_template'])
                                @else
                            processResults: function (data, params) {
                                params.page = params.page || 1;

                                return {
                                    results: $.map(data.data, function (item) {
                                        return {
                                            text: item["{{ $field['attribute'] }}"],
                                            id: item["{{ $connected_entity_key_name }}"]
                                        }
                                    }),
                                    pagination: {
                                        more: data.current_page < data.last_page
                                    }
                                };
                            },
                            @endif
                            cache: true
                        },
                    });
                }
            });

            // pega todos inputs do formulario e filtra enviando somente os necessarios pois estava exedendo o rage da request
            function cleanArray(arrInput)
            {
                var retorno = [];
                arrInput.forEach(function(item, i) {
                    switch (item.name) {
                        case 'fornecedor_id':
                            retorno.push({'name':'fornecedor_id', 'value': item.value});
                            break;

                        case 'contrato_id':
                            retorno.push({'name':'contrato_id', 'value': item.value});
                            break;

                        case 'modalidade_id':
                            retorno.push({'name':'modalidade_id', 'value': item.value});
                            break;

                        default:
                            break;
                    }
                });
                return retorno;
            }


            @if (isset($field['dependencies']))
            @foreach (array_wrap($field['dependencies']) as $dependency)
            $('input[name={{ $dependency }}], select[name={{ $dependency }}], checkbox[name={{ $dependency }}], radio[name={{ $dependency }}], textarea[name={{ $dependency }}]').change(function () {
                $("#select2_ajax_multiple_{{ $field['name'] }}").val(null).trigger("change");
            });
            @endforeach
            @endif
        });
    </script>
@endpush
{{-- End of Extra CSS and JS --}}
{{-- ########################################## --}}
