<!-- text input -->
<div @include('crud::inc.field_wrapper_attributes') >
    <label>{!! $field['label'] !!}</label>
    @include('crud::inc.field_translatable_icon')

    @if(isset($field['prefix']) || isset($field['suffix'])) <div class="input-group"> @endif
        @if(isset($field['prefix'])) <div class="input-group-addon">{!! $field['prefix'] !!}</div> @endif
        <input
                type="text"
                name="{{ $field['name'] }}"
                id="{{ $field['name'] }}"
                value="{{ old($field['name']) ?? $field['value'] ?? $field['default'] ?? '' }}"
                @include('crud::inc.field_attributes')
        >
        @if(isset($field['suffix'])) <div class="input-group-addon">{!! $field['suffix'] !!}</div> @endif
        @if(isset($field['prefix']) || isset($field['suffix'])) </div> @endif

    {{-- HINT --}}
    @if (isset($field['hint']))
        <p class="help-block">{!! $field['hint'] !!}</p>
    @endif
</div>

@push('crud_fields_scripts')
    <script type="text/javascript">
        $(window).on('load', function() {
            var value = $('#tipo_fonecedor').val();

            if (value == 'JURIDICA') {
                mascaraCNPJ('#{{ $field['name'] }}');
            }

            if (value == 'FISICA') {
                mascaraCPF('#{{ $field['name'] }}');
            }

            if (value == 'UG') {
                mascaraUG('#{{ $field['name'] }}');
            }

            if (value == 'IDGENERICO') {
                mascaraIDGener('#{{ $field['name'] }}');
            }
        });

        $(document).on('change','#tipo_fonecedor',function(){

            var value = $(this).val();

            if (value == 'JURIDICA') {
                mascaraCNPJ('#{{ $field['name'] }}');
            }

            if (value == 'FISICA') {
                mascaraCPF('#{{ $field['name'] }}');
            }

            if (value == 'UG') {
                mascaraUG('#{{ $field['name'] }}');
            }

            if (value == 'IDGENERICO') {
                mascaraIDGener('#{{ $field['name'] }}');
            }
        });

        function mascaraCNPJ(element) {
            $(element).mask("99.999.999/9999-99");
        }

        function mascaraCPF(element) {
            $(element).mask("999.999.999-99");
        }

        function mascaraUG(element) {
            $(element).mask("999999");
        }

        function mascaraIDGener(element) {
            $(element).mask("*********");
        }
    </script>
@endpush



