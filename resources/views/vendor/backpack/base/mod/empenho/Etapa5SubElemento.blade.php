@php
    $minuta_id = Route::current()->parameter('minuta_id');
    $fornecedor_id = Route::current()->parameter('fornecedor_id')
@endphp
@extends('backpack::layout')

@section('header')
    <section class="content-header">
        <h1>
            Itens
            <small>da Compra</small>
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
            <h3 class="box-title">Itens da Compra</h3>
        </div>
        <div class="box-body">
            <div class="row">
                <div class="col-md-2">
                    Crédito orçamentário:
                </div>
                <div class="col-md-10" id="">
                    {{$bla}}
                </div>
            </div>
            <div class="row">
                <div class="col-md-2">
                    Utilizado:
                </div>
                <div class="col-md-10" id="utilizado">

                </div>
            </div>
            <div class="row">
                <div class="col-md-2">
                    Saldo:
                </div>
                <div class="col-md-10" id="test3">

                </div>
            </div>
        </div>
    </div>

    <div class="box box-solid box-primary">
        <div class="box-header with-border">
            <h3 class="box-title">Itens da Compra</h3>
        </div>

        <div class="box-body">
            <br/>
            <form action="/empenho/subelemento" method="POST">
                <input type="hidden" id="minuta_id" name="minuta_id" value="{{$minuta_id}}">
                <input type="hidden" id="fornecedor_id" name="fornecedor_id" value="{{$fornecedor_id}}">
            @csrf <!-- {{ csrf_field() }} -->


                {{--                <p id="test1">This is a paragraph.</p>--}}
                {{--                <p id="test2">This is another paragraph.</p>--}}

                {{--                <p>Input field: <input type="text" id="test3" value="ttteste"></p>--}}

                {!! $html->table() !!}
                <div class="col-sm-12">

                </div>
                <div class="box-tools">
                    {!! Button::success('<i class="fa fa-arrow-left"></i> Voltar')
                        ->asLinkTo(route('empenho.lista.minuta'))
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
        // alert(123);

        // $('#subitem').select2();

        function bloqueia(tipo) {
            $('input[type=checkbox]').each(function () {
                if (tipo != $(this).data('tipo')) {
                    this.checked = false;
                }
            });
        }

        function calculaValorTotal(obj) {

            // $("#test1").text("Hello world!");
            // $("#test2").html("<b>Hello world!</b>");
            // $("#test3").val("Dolly Duck");
            // $("#vrtotal_75").val("9");
            // $(".vrtotal75").val("654");
            // $("#vrtotal75").val("654");

            // var tables = $('#dataTableBuilder').DataTable();
            //
            // tables.ajax.reload();


            // alert(1212);


            // console.log(obj.value)
            // console.log(obj.dataset.valor_unitario)
            // console.log(obj.dataset.compra_item_id)
            var compra_item_id = obj.dataset.compra_item_id;
            var valor_total = obj.value * obj.dataset.valor_unitario;
            $(".vrtotal" + compra_item_id)
                .val(valor_total)
                .trigger("change")
            // console.log($('.vrtotal75').val());

            // $(".myTextBox").val("New value").trigger("change");


            // $('#vrtotal_75').val('123');
            // console.log($('#vrtotal_75'))
            // alert($('#vrtotal_75').val());
            // $('#vrtotal_75').attr('value', '456');
            // alert($('#vrtotal_75').val());


            // console.log($('#vrtotal_75'))
            // console.log($('#vrtotal_75').val());
            // console.log(obj)

        }

        function calculaQuantidade(obj) {

            $("#test1").text("Hello world!");
            // $("#test2").html("<b>Hello world!</b>");
            // $("#test3").val("Dolly Duck");
            // $("#vrtotal_75").val("9");
            // $(".vrtotal75").val("654");
            // $("#vrtotal75").val("654");

            // var tables = $('#dataTableBuilder').DataTable();
            //
            // tables.ajax.reload();


            // alert(1212);
            //
            //
            // console.log(obj.value)
            // console.log(obj.dataset.valor_unitario)
            // console.log(obj.dataset.compra_item_id)

            var compra_item_id = obj.dataset.compra_item_id;
            var quantidade = obj.value / obj.dataset.valor_unitario;
            // alert(quantidade);
            $(".qtd" + compra_item_id).val(quantidade)
            // console.log($('.vrtotal75').val());


            // $('#vrtotal_75').val('123');
            // console.log($('#vrtotal_75'))
            // alert($('#vrtotal_75').val());
            // $('#vrtotal_75').attr('value', '456');
            // alert($('#vrtotal_75').val());


            // console.log($('#vrtotal_75'))
            // console.log($('#vrtotal_75').val());
            // console.log(obj)

        }

        // $( document ).ready(function() {
        //     $('.valor_total').change(function () {
        //         alert(2);
        //         // (this).each(
        //         //     alert(this.value)
        //         // )
        //
        //     })
        // });

        $(document).ready(function(){
            $('body').on('change','.valor_total', function(event){
                var soma = 0;
                $( ".valor_total" ).each(function( index ) {
                    if (!isNaN(parseFloat($( this ).val()))){
                        soma = parseFloat($( this ).val()) + parseFloat(soma);
                    }
                });
                $("#utilizado").html("<b>"+ soma +"</b>");

            });
        });


    </script>
@endpush
