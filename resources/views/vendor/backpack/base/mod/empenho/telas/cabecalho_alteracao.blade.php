{{--{{ dd(get_defined_vars()['__data']) }}--}}
{{--{{dd( Request::url() )}}--}}

@php

    //dd(session('empenho_etapa'));
    //dd(session()->all());
        // Busca url da rota

        $request = Request();
        $url = $request->path();
        //dd($url);
        //dd(session('passivo_anterior'));
        $partes = explode('/', $url);
        $proc = array_search('tela', $partes);

        $etapa = session('empenho_etapa');
        $minuta_id = Route::current()->parameter('minuta_id') ?? session('minuta_id') ?? Route::current()->parameter('minutum');

        $remessa_id = Route::current()->parameter('remessa') ?? session('remessa_id') ;

        $situacao = session('situacao');

        // Itens do cabeçalho

        $passos[1] = 'Subelemento';
        $passos[2] = 'Passivo Anterior';
        $passos[3] = 'Finalizar';

        $rotas = [1 => '#', 2 => '#', 3 => '#'];

        //dump($situacao);
        if ($situacao === 'EM ANDAMENTO' || $situacao === 'ERRO'){
            $rotas[1] = route('empenho.crud.alteracao.create', ['minuta_id' => $minuta_id]);
            $rotas[2] = route('empenho.crud.alteracao.passivo-anterior',
             ['minuta_id' => $minuta_id, 'remessa' => $remessa_id]);

            if ($etapa >= 2 ){

                $rotas[1] = route('empenho.crud.alteracao.edit',[
                'minuta_id' => $minuta_id,
                'remessa' => $remessa_id,
                'minuta' => $minuta_id
                ]);
            }
            if (session('passivo_anterior')){
                $rotas[2] = route('empenho.crud.alteracao.passivo-anterior.edit',
                ['minuta_id' => $minuta_id, 'remessa' => $remessa_id]);
            }else{
                $rotas[2] = '#';
            }

            $rotas[3] = route('empenho.crud.alteracao.show', [
                'minuta_id' => $minuta_id,
                'remessa' => $remessa_id,
                'minuta' => $minuta_id
                ]);
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
//dd($rotas[$num] , url()->current());
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
