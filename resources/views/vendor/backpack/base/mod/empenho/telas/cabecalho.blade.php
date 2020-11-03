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

        // Itens do cabeçalho

        $passos[1] = 'Compra';
        $passos[2] = 'Fornecedor';
        $passos[3] = 'Itens';
        $passos[4] = 'Crédito disponível';
        $passos[5] = 'Subelemento';
        $passos[6] = 'Dados Empenho';
        $passos[7] = 'Passivo Anterior';
        $passos[8] = 'Finalizar';

        $rotas[1] = ['name' => 'empenho.minuta.etapa.compra', 'params'=>[]];
        $rotas[2] = [
            'name' => 'empenho.minuta.etapa.fornecedor',
            'params'=> ['minuta_id' => $minuta_id]
        ];

        $rotas[3] = [
            'name' => 'empenho.minuta.etapa.item',
            'params' => ['minuta_id' => $minuta_id,
                            'fornecedor_id' => $fornecedor_id
                        ]
        ];
        $rotas[4] = [
            'name' => 'empenho.minuta.etapa.saldocontabil',
            'params' => ['minuta_id' => $minuta_id]
        ];
        $rotas[5] = [
            'name' => 'empenho.minuta.etapa.subelemento',
            'params' => ['minuta_id' => $minuta_id]
        ];
        $rotas[6] = [
            'name' => 'empenho.crud./minuta.edit',
            'params' => ['minutum' => $minuta_id]
        ];
        if ($conta_id){
            $rotas[7] = [
                'name' => 'empenho.crud.passivo-anterior.edit',
                'params' => ['minuta_id' => $conta_id]
            ];

        } else {
            $rotas[7] = [
                'name' => 'empenho.minuta.etapa.passivo-anterior',
                'params' => ['passivo_anterior' => $minuta_id]
            ];
        }


        $rotas[8] = [
            'name' => 'empenho.crud./minuta.show',
            'params' => ['minutum' => $minuta_id]
        ]


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

                        @php $cor = ($etapa >= $num) ? 'azul' : '' @endphp
                        <div class="btn btn-app" style="width: 108px;">
                            @if($cor=="azul")
                                <a href="{{ route($rotas[$num]['name'], $rotas[$num]['params'])  }}">
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
