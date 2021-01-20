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
        @include('vendor.backpack.base.mod.empenho.telas.cabecalho_alteracao')
    @if ( $errors->any())
        <div class="callout callout-danger">
            <h4>{{ trans('backpack::crud.please_fix') }}</h4>
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

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
            <div class="row">
                <div class="col-md-2 col-sm-3">
                    Empenhado:
                </div>
                <div class="col-md-10 col-sm-9" >
                    R$ {{ number_format($valor_utilizado,2,',','.') }}
                </div>
            </div>
            <div class="row text-red">
                <div class="col-md-2 col-sm-3">
                    Utilizado:
                </div>
                <div class="col-md-10 col-sm-9" id="utilizado">
                    <b>R$ {{ number_format($empenhado,2,',','.') }}</b>
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
            <form action="{{$url_form}}" method="POST">
                <input type="hidden" id="sispp_servico" name="sispp_servico" value="{{$sispp_servico}}">
                <input type="hidden" id="minuta_id" name="minuta_id" value="{{$minuta_id}}">
                <input type="hidden" id="fornecedor_id" name="fornecedor_id" value="{{$fornecedor_id}}">
                <input type="hidden" id="credito" name="credito" value="{{$credito}}">
                <input type="hidden" id="valor_utilizado" name="valor_utilizado" value="{{$valor_utilizado}}">
            @csrf <!-- {{ csrf_field() }} -->
                                @if($update !== false)
                                    {!! method_field('PUT') !!}
                                @endif

                {!! $html->table() !!}
                <div class="col-sm-12">

                </div>

                <div class="box-tools">
                    @include('backpack::mod.empenho.botoes',['rota' => route('empenho.crud.alteracao.index', ['minuta_id' => $minuta_id])])
                </div>


            </form>
        </div>
    </div>

@endsection
@push('after_scripts')
    {!! $html->scripts() !!}
    <script type="text/javascript">

        function BloqueiaValorTotal(tipo_alteracao) {
            var selected = $(tipo_alteracao).find(':selected').text();

            $(tipo_alteracao).closest('tr').find('td').find('.valor_total').val(0)
            $(tipo_alteracao).closest('tr').find('td').find('.qtd').val(0)
            calculaUtilizado();

            if (selected == 'CANCELAMENTO' || selected == 'NENHUMA') {
                // $(tipo_alteracao).closest('tr').find('td').find('.valor_total').val(0)
                $(tipo_alteracao).closest('tr').find('td').find('.valor_total').prop('readonly', true)
                $(tipo_alteracao).closest('tr').find('td').find('.qtd').prop('readonly', true)
                return;
            }

            if($('#sispp_servico').val() == false){
                $(tipo_alteracao).closest('tr').find('td').find('.valor_total').prop('readonly', true)
                $(tipo_alteracao).closest('tr').find('td').find('.qtd').prop('readonly', false)
                return;
            }
            $(tipo_alteracao).closest('tr').find('td').find('.valor_total').prop('readonly', false)
            $(tipo_alteracao).closest('tr').find('td').find('.qtd').prop('readonly', true)

        }

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
                .trigger("input")
        }

        function calculaQuantidade(obj) {
            var {{$tipo}} = obj.dataset.{{$tipo}};
            var value = obj.value;

            value = ptToEn(value);

            var quantidade = value / obj.dataset.valor_unitario;
            $(".qtd" + {{$tipo}}).val(quantidade).trigger("input")

        }

        $(document).ready(function () {

            $('body').on('change', '.valor_total', function (event) {
                calculaUtilizado();
            });

            $('body').on('input', '.valor_total', function (event) {
                calculaUtilizado();
            });
            $('body').on('input', '.qtd', function (event) {
                calculaUtilizado();
            });
        });

        function calculaUtilizado(){
            var soma = 0;
            var utilizado = 0;
            var saldo = {{$credito}};
            var valor_utilizado = {{$valor_utilizado}};
            var anulacao = outros = 0;
            $(".valor_total").each(function (index) {
                var valor = ptToEn($(this).val());

                    var selected = $(this).closest('tr').find('select').find(':selected').text();

                    if (!isNaN(parseFloat(valor))) {
                        if (selected == 'ANULAÇÃO'){
                            anulacao = parseFloat(anulacao) + parseFloat(valor) ;
                        } else {
                            outros = parseFloat(outros) + parseFloat(valor);
                        }
                    }
                });
                soma = parseFloat(anulacao * -1) + parseFloat(outros);
                saldo = saldo - valor_utilizado - soma;

                utilizado = outros - anulacao;
                if (anulacao > outros){
                    utilizado = anulacao - outros;
                    utilizado *= -1;
                }

            $("#utilizado").html("<b>R$ " + utilizado.toLocaleString('pt-br', {minimumFractionDigits: 2}) + "</b>");
            $("#saldo").html('R$ ' + saldo.toLocaleString('pt-br', {minimumFractionDigits: 2}));
            $("#valor_utilizado").val(soma);

        }

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

