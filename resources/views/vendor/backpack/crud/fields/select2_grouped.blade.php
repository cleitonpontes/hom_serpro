<!-- select2 -->
@php
    $current_value = old($field['name']) ? old($field['name']) : (isset($field['value']) ? $field['value'] : (isset($field['default']) ? $field['default'] : '' ));
@endphp

<div @include('crud::inc.field_wrapper_attributes') >
    <label>{!! $field['label'] !!}</label>
    @include('crud::inc.field_translatable_icon')
    @php
        $entity_model = $crud->getRelationModel($field['entity'],  - 1);
        $group_by_model = (new $entity_model)->{$field['group_by']}()->getRelated();
        $categories = $group_by_model::has($field['group_by_relationship_back'])->get();

        if (isset($field['model'])) {
            $categorylessEntries = $field['model']::has($field['group_by'], '=', 0)->get();
        }
    @endphp
    <select
        name="{{ $field['name'] }}"
        style="width: 100%"
        @include('crud::inc.field_attributes', ['default_class' =>  'form-control select2_field'])
        >

            @if ($entity_model::isColumnNullable($field['name']))
                <option value="">Selecione...</option>
            @endif

            @if (isset($field['model']) && isset($field['group_by']))
                @foreach ($categories as $category)
                    <optgroup label="{{ $category->{$field['group_by_attribute']} }}">
                        @foreach ($category->{$field['group_by_relationship_back']} as $subEntry)
                            <option value="{{ $subEntry->getKey() }}"
                                @if ( ( old($field['name']) && old($field['name']) == $subEntry->getKey() ) || (isset($field['value']) && $subEntry->getKey()==$field['value']))
                                     selected
                                @endif
                            >{{ $subEntry->{$field['attribute']} }}</option>
                        @endforeach
                    </optgroup>
                @endforeach

                @if ($categorylessEntries->count())
                    <optgroup label="-">
                        @foreach ($categorylessEntries as $subEntry)

                            @if($current_value == $subEntry->getKey())
                                <option value="{{ $subEntry->getKey() }}" selected>{{ $subEntry->{$field['attribute']} }}</option>
                            @else
                                <option value="{{ $subEntry->getKey() }}">{{ $subEntry->{$field['attribute']} }}</option>
                            @endif
                        @endforeach
                    </optgroup>
                @endif
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
@if ($crud->checkIfFieldIsFirstOfItsType($field, $fields))

    {{-- FIELD CSS - will be loaded in the after_styles section --}}
    @push('crud_fields_styles')
        <!-- include select2 css-->
        <link href="{{ asset('vendor/adminlte/bower_components/select2/dist/css/select2.min.css') }}" rel="stylesheet" type="text/css" />
        <link href="https://cdnjs.cloudflare.com/ajax/libs/select2-bootstrap-theme/0.1.0-beta.10/select2-bootstrap.min.css" rel="stylesheet" type="text/css" />
    @endpush

    {{-- FIELD JS - will be loaded in the after_scripts section --}}
    @push('crud_fields_scripts')
        <!-- include select2 js-->
        <script src="{{ asset('vendor/adminlte/bower_components/select2/dist/js/select2.min.js') }}"></script>
        <script>
            jQuery(document).ready(function($) {
                // trigger select2 for each untriggered select2 box
                $('.select2_field').each(function (i, obj) {
                    if (!$(obj).hasClass("select2-hidden-accessible"))
                    {
                        $(obj).select2({
                            theme: "bootstrap"
                        });
                    }
                });
            });
        </script>
    @endpush

@endif
{{-- End of Extra CSS and JS --}}
{{-- ########################################## --}}