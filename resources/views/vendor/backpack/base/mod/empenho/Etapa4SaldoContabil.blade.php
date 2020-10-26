@extends('backpack::layout')

@section('header')
    <section class="content-header">
        <h1>
            Saldo Contábil
            <small></small>
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
            <h3 class="box-title">Saldos Contábeis</h3>
        </div>

        <div class="box-body">
            <div class="box-tools" align="right">
                <div class="row">
                    <div class="col-md-3" align="left">
{{--                        {!! form($form) !!}--}}
                    </div>
                    <div class="col-md-3">
                        {!! Button::primary('Inserir célula orçamentária. <i class="fa fa-refresh"></i>')
                            ->asLinkTo(route('empenho.lista.minuta'))
                        !!}
                    </div>
                    <div class="col-md-3" align="right">

                    </div>
                    <div class="col-md-3" align="left">

                    </div>
                </div>
            </div>
            <br/>
            <form action="{{route('empenho.minuta.atualizar.saldo')}}" method="post">
                @csrf <!-- {{ csrf_field() }} -->
                <div class="col-sm-12">
                    <input type="hidden" id="etapa_id" name="etapa_id" value="{{$etapa_id}}">
                    <input type="hidden" id="minuta_id" name="minuta_id" value="{{$minuta_id}}">
                </div>
                {!! $html->table() !!}

            <div class="box-tools" align="right">
                <div class="row">
                    <div class="col-md-3" align="left">
                        {!! Button::primary('<i class="fa fa-arrow-left"></i> Voltar')
                            ->asLinkTo(route('empenho.lista.minuta'))
                        !!}
                    </div>
                    <div class="col-md-3">

                    </div>
                    <div class="col-md-3" align="right">
                        <button type="button" class="btn btn-primary" id="atualiza_saldo">
                            Atualizar todos os Saldos  <i class="fa fa-refresh"></i>
                        </button>
                    </div>
                    <div class="col-md-3" align="left">
                        <button type="submit" class="btn btn-primary">
                            Próxima Etapa  <i class="fa fa-arrow-right"></i>
                        </button>
                    </div>
                </div>
            </div>
            </form>
        </div>
    </div>

@endsection
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
@push('after_scripts')
    {!! $html->scripts() !!}
    <script type="text/javascript">

        $(document).ready(function(){
            $('body').on('click','#atualiza_saldo', function(event){
                atualizaSaldos(event);
            });
        });



        function atualizaTabeladeSaldos(event){
            var url = "{{route('atualiza.saldos.unidade','110161')}}";
            // Inicia requisição AJAX com o axios
            axios.request(url)
                .then(response => {
                    dados = response.data
                    if(dados.resultado){
                        console.log(dados.resultado);
                        // var table = $().DataTable();
                        // console.log(table.data());
                        // table.ajax.reload();
                       // alert("Saldos Atualizados com sucesso!");
                    }
                })
                .catch(error => {
                    alert(error);
                })
                .finally()

            event.preventDefault()

        }


        function atualizaSaldos(){
            var example_table = $().DataTable({
                'ajax': {
                    "type"   : "GET",
                    "url"    : '{{route('atualiza.saldos.unidade','110161')}}'
                    }
            });

            example_table.ajax.reload()
        }


        function atualizaLinhaSaldo(){
            var example_table = $().DataTable({
                'ajax': {
                    "type"   : "GET",
                    "url"    : '{{route('atualiza.saldos.unidade','110161')}}',
                    "data"   : function( d ) {
                        d.example_key1= $('#example_input1').val();
                        d.example_key2= $('#example_input2').val();
                        d.example_key3= $('#example_input3').val();
                    },
                    "dataSrc": ""
                },
                'columns': [
                    {"data" : "metric_name"},
                    {"data" : "metric_type"},
                    {"data" : "metric_timestamp"},
                    {"data" : "metric_duration"}
                ]
            });
            //To Reload The Ajax
            //See DataTables.net for more information about the reload method
            example_table.ajax.reload()
        }


    </script>
@endpush
