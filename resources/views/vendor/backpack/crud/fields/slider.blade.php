<!-- html5 range input -->
<div @include('crud::inc.field_wrapper_attributes') >
    <label>{!! $field['label'] !!}</label>
    @include('crud::inc.field_translatable_icon')
{{--    <input--}}
{{--        type="range"--}}
{{--        name="{{ $field['name'] }}"--}}
{{--        value="{{ old(square_brackets_to_dots($field['name'])) ?? $field['value'] ?? $field['default'] ?? '' }}"--}}
{{--        @include('crud::inc.field_attributes')--}}
{{--        >--}}

    <input type="text" id="example_id" name="slider" value=""
{{--        @include('crud::inc.field_attributes')--}}
    />


    {{-- HINT --}}
    @if (isset($field['hint']))
        <p class="help-block">{!! $field['hint'] !!}</p>
    @endif
</div>

{{-- ########################################## --}}
{{-- Extra CSS and JS for this particular field --}}
{{-- If a field type is shown multiple times on a form, the CSS and JS will only be loaded once --}}
@if ($crud->checkIfFieldIsFirstOfItsType($field))

    {{-- FIELD JS - will be loaded in the after_scripts section --}}
    @push('crud_fields_scripts')
        <!--Plugin CSS file with desired skin-->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ion-rangeslider/2.3.1/css/ion.rangeSlider.min.css"/>

        <!--jQuery-->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>

        <!--Plugin JavaScript file-->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/ion-rangeslider/2.3.1/js/ion.rangeSlider.min.js"></script>



        <script src="{{ asset('vendor/adminlte/bower_components/select2/dist/js/select2.min.js') }}"></script>
        <script>
            jQuery(document).ready(function($) {
                $("#example_id").ionRangeSlider({
                    grid: true,
                    type: "double",
                    step: "0.1"
                });
            });
        </script>
    @endpush




@endif
