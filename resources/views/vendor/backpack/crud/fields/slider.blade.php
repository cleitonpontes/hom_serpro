<!-- ion-rangeslider 2.3.1 slider -->

@php
    $old = old(square_brackets_to_dots($field['name']));

    $from = 0;
    $to = 100;

    if ($old){
        $from = substr($old,0,strpos($old,';'));
        $to = substr($old,strpos($old,';')+1);
    } elseif  (isset($entry)) {
        $from = ($entry->from);
        $to = ($entry->to);
    }
@endphp
<div @include('crud::inc.field_wrapper_attributes') >
    <label>{!! $field['label'] !!}</label>
    @include('crud::inc.field_translatable_icon')
    <input type="text" class="js-range-slider" name="{{ $field['name'] }}" value=""
           data-type="double"
           data-min="{{ $field['min'] }}"
           data-max="{{ $field['max'] }}"
           data-step="{{ $field['step'] }}"
           data-from="{{ $from }}"
           data-to="{{ $to }}"
           data-grid="{{ $field['grid'] }}"
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
        <link rel="stylesheet"
              href="https://cdnjs.cloudflare.com/ajax/libs/ion-rangeslider/2.3.1/css/ion.rangeSlider.min.css"/>

        <!--jQuery-->
{{--        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>--}}

        <!--Plugin JavaScript file-->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/ion-rangeslider/2.3.1/js/ion.rangeSlider.min.js"></script>

        <script src="{{ asset('vendor/adminlte/bower_components/select2/dist/js/select2.min.js') }}"></script>
        <script>
            $(".js-range-slider").ionRangeSlider();
        </script>
    @endpush

@endif
