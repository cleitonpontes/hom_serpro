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

        $etapa = session('empenho_etapa');
        $minuta_id = Route::current()->parameter('minuta_id') ?? session('minuta_id') ?? Route::current()->parameter('minutum');
        $fornecedor_id = session('fornecedor_compra') ?? Route::current()->parameter('fornecedor_id') ?? '';
        $conta_id = session('conta_id') ?? Route::current()->parameter('conta_id') ?? '';
        $situacao = session('situacao');

        // Itens do cabeçalho

        $passos[1] = 'Compra';
        $passos[2] = 'Fornecedor';
        $passos[3] = 'Itens';
        $passos[4] = 'Crédito disponível';
        $passos[5] = 'Subelemento';
        $passos[6] = 'Dados Empenho';
        $passos[7] = 'Passivo Anterior';
        $passos[8] = 'Finalizar';

        $rotas = [1 => '#', 2 => '#', 3 => '#', 4 => '#', 5 => '#', 6 => '#', 7 => '#', 8 => '#'];

        if ($situacao === 'EM ANDAMENTO' || $situacao === "ERRO"){
            $rotas[1] = '#';
            $rotas[2] = route('empenho.minuta.etapa.fornecedor', ['minuta_id' => $minuta_id]);
            $rotas[3] = route('empenho.minuta.etapa.item', ['minuta_id' => $minuta_id, 'fornecedor_id' => $fornecedor_id]);
            $rotas[4] = route('empenho.minuta.etapa.saldocontabil', ['minuta_id' => $minuta_id]);
            $rotas[5] = route('empenho.minuta.etapa.subelemento', ['minuta_id' => $minuta_id]);
            $rotas[6] = route('empenho.crud./minuta.edit', ['minutum' => $minuta_id]);
            $rotas[7] = route('empenho.minuta.etapa.passivo-anterior', ['passivo_anterior' => $minuta_id]);

            if ($conta_id){
                $rotas[7] = route('empenho.crud.passivo-anterior.edit', ['minuta_id' => $conta_id]);
            }

            $rotas[8] = route('empenho.crud./minuta.show', ['minutum' => $minuta_id]);

        }

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
                            $cor = '';
                            if ($rotas[$num] == url()->current()){
                                $cor = 'verde';
                            } elseif ($etapa >= $num) {
                                $cor = 'azul';
                            }

                        @endphp
                        <div class="btn btn-app" style="width: 108px;">
                            @if($cor=="azul") <a href="{{ $rotas[$num]  }}"> @endif
                                <span class="circulo {{$cor}}">{{$num}}</span>
                                {!! $descricao !!}
                            @if($cor=="azul") </a> @endif
                        </div>
                    @endforeach

                </div>
            </div>

        </div>
    </div>
</div>

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

        .verde {
            background-color: #00a65a;
        }
    </style>
@endpush
