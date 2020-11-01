{{--{{ dd(get_defined_vars()['__data']) }}--}}
{{--{{dd( Request::url() )}}--}}

@php

    //dd(session('empenho_etapa'));
    //dd(session()->all());
        // Busca url da rota
        $request = Request();
        $url = $request->path();

        $partes = explode('/', $url);
        $proc = array_search('tela', $partes);

        $passo = session('empenho_etapa');
        //dd($passo);
        $minuta_id = (int) Route::current()->parameter('minuta_id');
        $minuta = ($minuta_id ?: '' );

        // Itens do cabeçalho
        $passos = array();

        $passos[1] = 'Compra';
        $passos[2] = 'Fornecedor';
        $passos[3] = 'Itens';
        $passos[4] = 'Crédito disponível';
        $passos[5] = 'Subelemento';
        $passos[6] = 'Dados Empenho';
        $passos[7] = 'Passivo Anterior';
        $passos[8] = 'Finalizar';

        $rotas[1] = 'buscacompra';
        $rotas[2] = 'fornecedor';
        $rotas[3] = 'item';
        $rotas[4] = '';
        $rotas[5] = '';
        $rotas[6] = '';
        $rotas[7] = '';
        $rotas[8] = '';

@endphp

<div class="container-fluid spark-screen">
    <div class="row">
        <div class="box box-solid box-primary">
            <div class="box-header with-border">
                <h3 class="box-title"> Fluxo de Empenho </h3>
            </div>
            <div class="box-body">
                <div class="row" align="center">

                    @foreach($passos as $num => $descricao)
                        @php
                            $fornecedor = ''
                        @endphp
                        @if($num === 3 )
                            @php $fornecedor = Route::current()->parameter('fornecedor_id') @endphp
                        @endif
                        @php $cor = ($passo >= $num) ? 'azul' : '' @endphp
                        <div class="btn btn-app" style="width: 108px;">
                            @if($cor=="azul")
                                <a href="{{ backpack_url("/empenho/$rotas[$num]/$num/$minuta$fornecedor") }}">
                                    {{--                                <a href="{{route('busca.compra', ['tela_id'=> '1'])}}">--}}
                                    @endif
                                    <span class="circulo {{$cor}}">{{$num}}</span>
                                    {!! $descricao !!}
                                    @if($cor=="azul")
                                </a>
                            @endif
                        </div>
                    @endforeach

                </div>
            </div>

        </div>
    </div>
</div>
<br/>

@push('after_scripts')
    <style type="text/css">
        .circulo {
            display: block;
            border-radius: 50px;

            width: 30px;
            height: 30px;
            line-height: 30px;

            margin-left: 30%;
            margin-top: -10px;

            font-weight: bold;
            color: white;
            background-color: gray;
        }

        .azul {
            background-color: #3C8DBC;
        }
    </style>
@endpush
