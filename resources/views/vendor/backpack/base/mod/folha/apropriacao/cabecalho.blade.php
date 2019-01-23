@php
    // Busca url da rota
    $request = Request();
    $url = $request->path();
    
    $partes = explode('/', $url);
    $proc = array_search('passo', $partes);
    
    // Define o passo atual
    $passo = (int) $partes[$proc +1];
    session(['apropriacao_passo' => $passo]);
    
    // Itens do cabeçalho
    $passos = array();
    
    $passos[1] = 'Importar DDP';
    $passos[2] = 'Identificar Situação';
    $passos[3] = 'Identificar Empenho';
    $passos[4] = 'Validar Saldo';
    $passos[5] = 'Informar Dados<br /> Complementares';
    $passos[6] = 'Persistir Dados';
    $passos[7] = 'Gerar XML';
@endphp

<div class="container-fluid spark-screen">
	<div class="row">
		<div class="box box-solid box-primary">
			<div class="box-header with-border">
				<h3 class="box-title"> Passos para Apropriação </h3>
			</div>
			<div class="box-body">
				<div class="row" align="center">
					
					@foreach($passos as $num => $descricao)
						@php $cor = ($passo >= $num) ? 'azul' : ''; @endphp
    					<div class="btn btn-app">
    						<span class="circulo {{$cor}}">{{$num}}</span>
    						{!! $descricao !!}
    					</div>
					@endforeach
					
				</div>
			</div>
		</div>
	</div>
</div>
<br />

@push('scripts')
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