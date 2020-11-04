@php
    $minuta_id = Route::current()->parameter('minuta_id');
    $fornecedor_id = Route::current()->parameter('fornecedor_id')
@endphp
@extends('backpack::layout')

@section('header')
    <section class="content-header">
        <h1>
            Crédito Orçamentário
        </h1>
    </section>
@endsection

@section('content')
    @include('backpack::mod.empenho.telas.cabecalho')
    <div class="flash-message">
        @foreach (['danger', 'warning', 'success', 'info'] as $msg)
            @if(Session::has('alert-' . $msg))
                <p class="alert alert-{{ $msg }}">{{ Session::get('alert-' . $msg) }}</p>
            @endif
        @endforeach
    </div>
    <div class="box box-solid box-primary">
        <div class="box-header with-border">
            <h3 class="box-title">Saldo do Crédito Orçamentário</h3>
        </div>
        <div class="box-body">
            <div class="row">
                <div class="col-md-2 col-sm-2">
                    Crédito orçamentário:
                </div>
                <div class="col-md-10 col-sm-10" id="">
                    R$ {{ number_format($credito,2,',','.') }}
                </div>
            </div>
            <div class="row text-red">
                <div class="col-md-2 col-sm-2">
                    Utilizado:
                </div>
                <div class="col-md-10 col-sm-10" id="utilizado">

                </div>
            </div>
            <div class="row">
                <div class="col-md-2 col-sm-10">
                    Saldo:
                </div>
                <div class="col-md-10 col-sm-10" id="saldo">

                </div>
            </div>
        </div>
    </div>

    <div class="box box-solid box-primary">
        <div class="box-header with-border">
            <h3 class="box-title">Subelemento</h3>
        </div>

        <div class="box-body">
            <br/>
            <form action="/empenho/subelemento" method="POST">
                <input type="hidden" id="minuta_id" name="minuta_id" value="{{$minuta_id}}">
                <input type="hidden" id="fornecedor_id" name="fornecedor_id" value="{{$fornecedor_id}}">
                <input type="hidden" id="credito" name="credito" value="{{$credito}}">
                <input type="hidden" id="valor_utilizado" name="valor_utilizado" value="">
            @csrf <!-- {{ csrf_field() }} -->

                {!! $html->table() !!}
                <div class="col-sm-12">

                </div>
                <div class="box-tools">
                    {!! Button::success('<i class="fa fa-arrow-left"></i> Voltar')
                        ->asLinkTo(route('empenho.crud./minuta.index'))
                    !!}
                    <button type="submit" class="btn btn-primary">
                        Próxima Etapa <i class="fa fa-arrow-right"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>

@endsection
@push('after_scripts')
    {!! $html->scripts() !!}
    <script type="text/javascript">

        function bloqueia(tipo) {
            $('input[type=checkbox]').each(function () {
                if (tipo != $(this).data('tipo')) {
                    this.checked = false;
                }
            });
        }

        function calculaValorTotal(obj) {

            var compra_item_id = obj.dataset.compra_item_id;
            var valor_total = obj.value * obj.dataset.valor_unitario;
            valor_total = valor_total.toLocaleString('pt-br', {minimumFractionDigits: 2});
            $(".vrtotal" + compra_item_id)
                .val(valor_total)
                .trigger("change")

        }

        function calculaQuantidade(obj) {

            var compra_item_id = obj.dataset.compra_item_id;
            var value = obj.value;

            value = ptToEn(value);

            var quantidade = value / obj.dataset.valor_unitario;

            $(".qtd" + compra_item_id).val(quantidade)

        }

        $(document).ready(function () {
            $('body').on('change', '.valor_total', function (event) {
                var soma = 0;
                var saldo = {{$credito}};
                $(".valor_total").each(function (index) {
                    var valor = ptToEn($(this).val());

                    if (!isNaN(parseFloat(valor))) {
                        soma = parseFloat(valor) + parseFloat(soma);
                    }
                });
                saldo = saldo - soma;
                $("#utilizado").html("<b>R$ " + soma.toLocaleString('pt-br', {minimumFractionDigits: 2}) + "</b>");
                $("#saldo").html('R$ ' + saldo.toLocaleString('pt-br', {minimumFractionDigits: 2}));
                $("#valor_utilizado").val(soma);
            });
        });

        function atualizaMascara() {
            var maxLength = '000.000.000.000.000,00'.length;
            $('.valor_total').maskMoney({
                allowNegative: false,
                thousands: '.',
                decimal: ',',
                affixesStay: false
            }).attr('maxlength', maxLength).trigger('mask.maskMoney');
        }

        function ptToEn(value) {

            value = value.replaceAll('.', '');
            return value.replaceAll(',', '.');
        }

    </script>
@endpush

