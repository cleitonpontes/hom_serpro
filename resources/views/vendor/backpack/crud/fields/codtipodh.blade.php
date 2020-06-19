<!-- text input -->
@include('layouts.textid')

@push('crud_fields_scripts')
    <script type="text/javascript">
        $('#{{ $field['name'] }}').keyup(function () {
            this.value = this.value.replace(/[^a-zA-Z]/g,'');
        });
    </script>
@endpush



