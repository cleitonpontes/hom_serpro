{{--{{ dd(get_defined_vars()['__data']) }}--}}

@php
    $minuta_id = Route::current()->parameter('minuta_id');
    //$fornecedor_id = Route::current()->parameter('fornecedor_id')
@endphp
@extends('backpack::layout')

@section('header')
    <section class="content-header">
        <h1>
            Subelemento
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
                <div class="col-md-2 col-sm-3">
                    Crédito orçamentário:
                </div>
                <div class="col-md-10 col-sm-9" id="">
                    R$ {{ number_format($credito,2,',','.') }}
                </div>
            </div>
            <div class="row text-red">
                <div class="col-md-2 col-sm-3">
                    Utilizado:
                </div>
                <div class="col-md-10 col-sm-9" id="utilizado">
                    <b>R$ {{ number_format($valor_utilizado,2,',','.') }}</b>
                </div>
            </div>
            <div class="row">
                <div class="col-md-2 col-sm-3">
                    Saldo:
                </div>
                <div class="col-md-10 col-sm-9" id="saldo">
                    R$ {{ number_format($saldo,2,',','.') }}
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
{{--                <input type="hidden" id="fornecedor_id" name="fornecedor_id" value="{{$fornecedor_id}}">--}}
                <input type="hidden" id="credito" name="credito" value="{{$credito}}">
                <input type="hidden" id="valor_utilizado" name="valor_utilizado" value="{{$valor_utilizado}}">
                @csrf <!-- {{ csrf_field() }} -->
                @if($update)
                    {!! method_field('PUT') !!}
                @endif

                {!! $html->table() !!}
                <div class="col-sm-12">

                </div>

                <div class="box-tools">
                    @include('backpack::mod.empenho.botoes',['rota' => route('empenho.minuta.etapa.saldocontabil', ['minuta_id' => $minuta_id])])
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

            var {{$tipo}} = obj.dataset.{{$tipo}};
            var valor_total = obj.value * obj.dataset.valor_unitario;
            valor_total = valor_total.toLocaleString('pt-br', {minimumFractionDigits: 2});
            $(".vrtotal" + {{$tipo}})
                .val(valor_total)
                .trigger("change")
        }

        function calculaQuantidade(obj) {

            var {{$tipo}} = obj.dataset.{{$tipo}};
            var value = obj.value;

            value = ptToEn(value);

            var quantidade = value / obj.dataset.valor_unitario;

            $(".qtd" + {{$tipo}}).val(quantidade)

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
                //allowZero: true,
                affixesStay: false
            }).attr('maxlength', maxLength).trigger('mask.maskMoney');
        }

        function ptToEn(value) {

            value = value.replaceAll('.', '');
            return value.replaceAll(',', '.');
        }

    </script>
@endpush

