<!-- text input -->
@include('layouts.textid')

@push('crud_fields_scripts')
    <script type="text/javascript">
        $('#{{ $field['name'] }}').mask('9999');
    </script>
@endpush



