<!-- jQuery 3.3.1 -->
<script src="{{ asset('vendor/adminlte') }}/bower_components/jquery/dist/jquery.min.js"></script>
<script src="{{ asset('vendor/adminlte') }}/bower_components/moment/min/moment.min.js"></script>
<script src="{{ asset('vendor/adminlte') }}/bower_components/fullcalendar/dist/fullcalendar.min.js"></script>
<script src="{{ asset('vendor/adminlte') }}/bower_components/fullcalendar/dist/locale/pt-br.js"></script>
<script src="{{ asset('vendor/adminlte') }}/bower_components/chart.js/Chart.js"></script>
<script src="{{ asset('vendor/adminlte') }}/bower_components/chart.js/Chart.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.1.4/Chart.bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/i18n/pt-BR.js"></script>

<script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.19/js/dataTables.bootstrap.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.2.3/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.2.3/js/responsive.bootstrap.min.js"></script>


{{-- <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
<script>window.jQuery || document.write('<script src="{{ asset('vendor/adminlte') }}/bower_components/jquery/dist/jquery.min.js"><\/script>')</script> --}}

<!-- Bootstrap 3.3.7 -->
<script src="{{ asset('vendor/adminlte') }}/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
<script src="{{ asset('vendor/adminlte') }}/plugins/pace/pace.min.js"></script>
<script src="{{ asset('vendor/adminlte') }}/bower_components/jquery-slimscroll/jquery.slimscroll.min.js"></script>
{{-- <script src="{{ asset('vendor/adminlte') }}/bower_components/fastclick/lib/fastclick.js"></script> --}}
<script src="{{ asset('vendor/adminlte') }}/dist/js/adminlte.js"></script>
<script src="{{asset('/js/jquery.maskedinput.js')}}" type="text/javascript"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-maskmoney/3.0.2/jquery.maskMoney.min.js"></script>

<!-- page script -->
<script type="text/javascript">
    // To make Pace works on Ajax calls
    $(document).ajaxStart(function() { Pace.restart(); });

    // Ajax calls should always have the CSRF token attached to them, otherwise they won't work
    $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

    {{-- Enable deep link to tab --}}
    var activeTab = $('[href="' + location.hash.replace("#", "#tab_") + '"]');
    location.hash && activeTab && activeTab.tab('show');
    $('.nav-tabs a').on('shown.bs.tab', function (e) {
        location.hash = e.target.hash.replace("#tab_", "#");
    });
</script>

<script type="text/javascript">
    function maiuscula(z){
        v = z.value.toUpperCase();
        z.value = v;
    }

    function minusculo(z){
        v = z.value.toLowerCase();
        z.value = v;
    }

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

    function mascaraEmpenho(element) {
        $(element).mask("9999NE999999");
    }

    function mascaraContrato(element) {
        $(element).mask("9999/9999");
    }
</script>


<script type="text/javascript">
    $.fn.dataTable.ext.errMode = 'throw';
</script>
