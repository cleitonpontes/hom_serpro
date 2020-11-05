<!-- text input -->

<div @include('crud::inc.field_wrapper_attributes') >
    <label>{!! $field['label'] !!}</label>
    <?php //$entity_model = $crud->model; ?>

    @include('crud::inc.field_translatable_icon')

    @if(isset($field['prefix']) || isset($field['suffix'])) <div class="input-group"> @endif
        @if(isset($field['prefix'])) <div class="input-group-addon">{!! $field['prefix'] !!}</div> @endif
        <input
            type="text"
            name="{{ $field['name'] }}"
            value="{{ old(square_brackets_to_dots($field['name'])) ?? $field['value'] ?? $field['default'] ?? '' }}"
            @include('crud::inc.field_attributes')
        >
        @if(isset($field['suffix'])) <div class="input-group-addon">{!! $field['suffix'] !!}</div> @endif
    @if(isset($field['prefix']) || isset($field['suffix'])) </div> @endif

    {{-- HINT --}}
    @if (isset($field['hint']))
        <p class="help-block">{!! $field['hint'] !!}</p>
    @endif
</div>

<!-- include field specific select2 js-->
@push('crud_fields_scripts')
    @if($action === 'create')
        <script>
            jQuery(document).ready(function($) {
                 // alert( $("#passivo").val());
                if ( $("#passivo").val() == 1) {
                    $('#contabil').attr('disabled', true);
                    $('#btn_add').attr('disabled', true);
                }

                $("#passivo").click(function() {
                    if ($(this).is(':checked')) {
                        $('#contabil').removeAttr('disabled');
                        $('#btn_add').removeAttr('disabled');
                    } else {
                        $('#contabil').attr('disabled', true);
                        $('#btn_add').attr('disabled', true);
                    }
                });

            });
        </script>
    @endif

@endpush
{{-- End of Extra CSS and JS --}}
{{-- ########################################## --}}



{{-- FIELD EXTRA CSS  --}}
{{-- push things in the after_styles section --}}

    {{-- @push('crud_fields_styles')
        <!-- no styles -->
    @endpush --}}


{{-- FIELD EXTRA JS --}}
{{-- push things in the after_scripts section --}}

    {{-- @push('crud_fields_scripts')
        <!-- no scripts -->
    @endpush --}}


{{-- Note: you can use @if ($crud->checkIfFieldIsFirstOfItsType($field, $fields)) to only load some CSS/JS once, even though there are multiple instances of it --}}

