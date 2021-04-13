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
                <div class="col-md-2 col-sm-3" id="credito">
                    R$ {{ number_format($credito,2,',','.') }}
                </div>
                <div class="col-md-8 col-sm-6" id="">
                    <button type="button" class="btn btn-primary btn-sm pull-left" id="atualiza_credito">
                        Atualizar Crédito Orçamentário <i class="fa fa-refresh"></i>
                    </button>
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
                <input type="hidden" id="tipo_item" name="tipo_item" value="{{$tipo_item}}">
                <input type="hidden" id="tipo_empenho_por" name="tipo_empenho_por" value="{{$tipo_empenho_por}}">
                <input type="hidden" id="minuta_id" name="minuta_id" value="{{$minuta_id}}">
                <input type="hidden" id="fornecedor_id" name="fornecedor_id" value="{{$fornecedor_id}}">
                <input type="hidden" id="credito" name="credito" value="{{$credito}}">
                <input type="hidden" id="saldo_id" name="saldo_id" value="{{$saldo_id}}">
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
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <script type="text/javascript">

        function BloqueiaValorTotal(tipo_alteracao, item_id) {
            let selected = $(tipo_alteracao).find(':selected').text();
            let minuta_por = $(tipo_alteracao).attr('id');
            let tipo_empenho_por = $('#tipo_empenho_por').val();
            $('#vrtotal' + item_id).val(0)
            $('#qtd' + item_id).val(0)
            calculaUtilizado(minuta_por);

            // se for Suprimento
            if (tipo_empenho_por === 'Suprimento') {

                if (selected === 'CANCELAMENTO' || selected === 'NENHUMA') {
                    $('#vrtotal' + item_id).prop('disabled', true)
                    $('#qtd' + item_id).prop('readonly', true)
                    return;
                }

                $('#vrtotal' + item_id).removeAttr('disabled');
                $('#qtd' + item_id).prop('readonly', true);

            }
            // se for contrato
            // else if(minuta_por.includes('contrato_item_id') && ($('#tipo_item').val() === 'Serviço')){
            else if(minuta_por.includes('contrato_item_id')){

                if (selected === 'CANCELAMENTO' || selected === 'NENHUMA') {
                    $('#vrtotal' + item_id).prop('disabled', true)
                    $('#qtd' + item_id).prop('readonly', true)
                    return;
                }

                $('#vrtotal' + item_id).removeAttr('disabled');
                $('#qtd' + item_id).prop('readonly', false);
            }
            else {

                if (selected === 'CANCELAMENTO' || selected === 'NENHUMA') {
                    $('#vrtotal' + item_id).prop('disabled', true)
                    $('#qtd' + item_id).prop('readonly', true)
                    return;
                }

                if ($('#sispp_servico').val() == false) {
                    $('#vrtotal' + item_id).prop('readonly', true)
                    $('#qtd' + item_id).prop('readonly', false)
                    return;
                }
                $('#vrtotal' + item_id).removeAttr('disabled')
                $('#vrtotal' + item_id).removeAttr('readonly')
                $('#qtd' + item_id).prop('readonly', true)
            }
        }

        function bloqueia(tipo) {
            $('input[type=checkbox]').each(function () {
                if (tipo != $(this).data('tipo')) {
                    this.checked = false;
                }
            });
        }

        function calculaValorTotal(obj) {

            var tipo_operacao = $(this).closest('tr').find('select').find(':selected').text();

            if (tipo_operacao === '') {
                tipo_operacao = $('#' + '{{$tipo}}_' + obj.dataset.{{$tipo}}).find(':selected').text();
            }

            if (tipo_operacao === 'ANULAÇÃO'){
                var {{$tipo}} = obj.dataset.{{$tipo}};
                var valor_total = obj.value * obj.dataset.vlr_unitario_item;
                valor_total = valor_total.toLocaleString('pt-br', {minimumFractionDigits: 2});
                $(".vrtotal" + {{$tipo}})
                    .val(valor_total)
                    .trigger("input");
            }

            if (tipo_operacao === 'REFORÇO'){
                var {{$tipo}} = obj.dataset.{{$tipo}};
                var valor_total = obj.value * obj.dataset.valor_unitario;
                valor_total = valor_total.toLocaleString('pt-br', {minimumFractionDigits: 2});
                $(".vrtotal" + {{$tipo}})
                    .val(valor_total)
                    .trigger("input");
            }

            calculaUtilizado('{{$tipo}}_' + obj.dataset.{{$tipo}});
        }

        function calculaQuantidade(obj) {

            var tipo_operacao = $(this).closest('tr').find('select').find(':selected').text();
            if (tipo_operacao === '') {
                tipo_operacao = $('#' + '{{$tipo}}_' + obj.dataset.{{$tipo}}).find(':selected').text();
            }

            if (tipo_operacao === 'ANULAÇÃO'){

                var {{$tipo}} = obj.dataset.{{$tipo}};
                var value = obj.value;
                value = ptToEn(value);

                if(obj.dataset.valor_unitario != 0){
                    var quantidade = value / obj.dataset.vlr_unitario_item;
                    $(".qtd" + {{$tipo}}).val(quantidade).trigger("input");
                }
            }

            if (tipo_operacao === 'REFORÇO'){
                var {{$tipo}} = obj.dataset.{{$tipo}};
                var value = obj.value;
                value = ptToEn(value);

                if(obj.dataset.valor_unitario != 0){
                    var quantidade = value / obj.dataset.valor_unitario;
                    $(".qtd" + {{$tipo}}).val(quantidade).trigger("input");
                }
            }

            calculaUtilizado('{{$tipo}}_' + obj.dataset.{{$tipo}});
        }


        $(document).ready(function () {

            $('body').on('click', '#atualiza_credito', function (event) {
                atualizaLinhadeSaldo(event);
            });

            $('body').on('change', '.valor_total', function (event) {
                calculaUtilizado('{{$tipo}}_' + this.dataset.{{$tipo}});
            });

            $('body').on('input', '.valor_total', function (event) {
                calculaUtilizado('{{$tipo}}_' + this.dataset.{{$tipo}});
            });

            $('body').on('input', '.qtd', function (event) {
                calculaUtilizado('{{$tipo}}_' + this.dataset.{{$tipo}});
            });

            $('.submeter').click(function (event) {

                $(".valor_total").each(function () {
                    $(this).removeAttr('disabled');
                    $(this).prop('readonly', true);
                });

                $(".qtd").each(function () {
                    $(this).prop('readonly', true);
                });

            });

        });

        function calculaUtilizado(tipo_operacao_id) {
            var soma = 0;
            var utilizado = 0;
            var saldo = {{$credito}};
            var valor_utilizado = {{$valor_utilizado}};
            var anulacao = outros = 0;
            $(".valor_total").each(function (index) {
                var valor = ptToEn($(this).val());

                var selected = $(this).closest('tr').find('select').find(':selected').text();
                if (selected === '') {
                    selected = $('#' + tipo_operacao_id).find(':selected').text();
                }

                if (!isNaN(parseFloat(valor))) {
                    if (selected === 'ANULAÇÃO') {
                        anulacao = parseFloat(anulacao) + parseFloat(valor);
                    } else {
                        outros = parseFloat(outros) + parseFloat(valor);
                    }
                }
            });
            soma = parseFloat(anulacao * -1) + parseFloat(outros);
            saldo = saldo - valor_utilizado - soma;

            var saldo_br = (saldo.toLocaleString('pt-br', {minimumFractionDigits: 2}));
            if (saldo_br == '-0,00'){
                saldo_br = '0,00'
            }

            utilizado = outros - anulacao;
            if (anulacao > outros) {
                utilizado = anulacao - outros;
                utilizado *= -1;
            }

            $("#utilizado").html("<b>R$ " + utilizado.toLocaleString('pt-br', {minimumFractionDigits: 2}) + "</b>");
            $("#saldo").html('R$ ' + saldo_br);
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


        number_format = function (number, decimals, dec_point, thousands_sep) {
            number = number.toFixed(decimals);

            var nstr = number.toString();
            nstr += '';
            x = nstr.split('.');
            x1 = x[0];
            x2 = x.length > 1 ? dec_point + x[1] : '';
            var rgx = /(\d+)(\d{3})/;

            while (rgx.test(x1))
                x1 = x1.replace(rgx, '$1' + thousands_sep + '$2');

            return x1 + x2;
        }

        function atualizaSaldos(credito) {
            var saldo = 0;
            var utilizado = parseFloat($('#utilizado').text().replace('R$', ''));

            if (utilizado < 0) {
                saldo = (parseFloat(credito + (utilizado * -1)));
            } else {
                saldo = (parseFloat(credito - utilizado));
            }

            $('#credito').text('R$ ' + number_format(credito, 2, ',', '.'));
            $('#saldo').text('R$ ' + number_format(saldo, 2, ',', '.'));

        }

        function atualizaLinhadeSaldo(event) {
            var saldo_id = {{$saldo_id}};
            var url = "{{route('atualiza.saldos.linha',':saldo_id')}}";
            url = url.replace(':saldo_id', saldo_id);

            axios.request(url)
                .then(response => {
                    dados = response.data
                    if (dados == true) {
                        atualizaCreditoOrcamentario(event)
                        Swal.fire({
                            position: 'top-end',
                            icon: 'success',
                            title: 'Crédito Orçamentário Atualizado com sucesso!',
                            showConfirmButton: false,
                            timer: 1500
                        })
                        var table = $('#dataTableBuilder').DataTable();
                        table.ajax.reload();
                    } else {
                        Swal.fire({
                            position: 'top-end',
                            icon: 'warning',
                            title: 'O saldo já está atualizado!',
                            showConfirmButton: false,
                            timer: 1500
                        })
                    }
                })
                .catch(error => {
                    alert(error);
                })
                .finally()
            event.preventDefault()
        }

        function atualizaCreditoOrcamentario(event) {
            var minuta_id = {{$minuta_id}}
            var url = "{{route('atualiza.credito.orcamentario',':minuta_id')}}";
            url = url.replace(':minuta_id', minuta_id);

            axios.request(url)
                .then(response => {
                    credito = response.data
                    atualizaSaldos(credito);
                })
                .catch(error => {
                    alert(error);
                })
                .finally()
            event.preventDefault()
        }

    </script>
@endpush

