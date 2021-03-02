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
        $(window).on('load', function () {
            var value = $("select[name=tipo_id] option:selected").text();

            if (value == 'Empenho') {
                mascaraEmpenho('#{{ $field['name'] }}');
            }else{
                mascaraContrato('#{{ $field['name'] }}');
            }

        });

        $(document).on('change', '#tipo_contrato', function () {

            var value = $("select[name=tipo_id] option:selected").text();

            if (value == 'Empenho') {
                mascaraEmpenho('#{{ $field['name'] }}');
            }else{
                mascaraContrato('#{{ $field['name'] }}');
            }

        });

    </script>
@endpush



