<?php
$seq = 0;

$saldoNegativo = '<span style="color: red">Saldo insuficiente</span>';
$saldoPositivo = '<span style="color: blue">Saldo suficiente</span>';
?>

@extends('backpack::layout')

@section('header')
    <section class="content-header">
        <h1>
            Folha
            <small>Apropriação</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ backpack_url() }}">{{ config('backpack.base.project_name') }}</a></li>
            <li>Folha</li>
            <li class="active">Apropriação</li>
        </ol>
    </section>
@endsection

@section('content')
    @include('backpack::mod.folha.apropriacao.cabecalho')

    <div class="box box-solid box-primary">
        <div class="box-header with-border">
            <h3 class="box-title">Listagem de Saldos a Validar</h3>
        </div>
        <div class="box-body">
        	<div class="text-right">
        		<button type="button"
        				id="btnAtualizaTodosSaldos"
        				class="btn btn-danger">
        			<i class="fa fa-repeat"></i>
        			Atualizar todos os Saldos
        		</button>
        		
        	</div>
            <br/>
            <table id="datatable" class="table table-striped table-bordered nowrap" cellspacing="0" width="100%">
                <thead>
                    <tr>
                        <th> # </th>
                        <th> Empenho </th>
                        <th> Sub Item </th>
                        <th> Fonte </th>
                        <th class="text-right"> Saldo Necessário </th>
                        <th class="text-right"> Saldo Atual </th>
                        <th class="text-right"> Status </th>
                        <th class="text-right"> Ação </th>
                    </tr>
                </thead>
                
                <tbody>
                	@foreach($empenhos as $registro)
                	<?php
                	$seq++;
                	
                    // Campos para exibição
                    $empenho = $registro['empenho'];
                    $subitem = $registro['subitem'];
                    $fonte = $registro['fonte'];
                    $saldoNecessario = $registro['saldo_necessario'];
                    $saldoAtual = $registro['saldo_atual'];
                    
                    // Validação para utilização
                    $bSaldo = $saldoAtual >= $saldoNecessario;
                    
                    // Outras variáveis/campos auxiliares
                    $saldoNecessarioFormatado = retornaValorFormatado($saldoNecessario);
                    $saldoAtualFormatado = retornaValorFormatado($saldoAtual);
                    $status = $bSaldo ? $saldoPositivo : $saldoNegativo;
                    
                    $neId = $empenho . '_' . $subitem;
                    $registroId = $neId . '_' . $seq;
                    $btnClasse = $bSaldo ? 'btn-light' : 'btn-danger';
                    $habilita = $bSaldo ? 'disabled' : '';
                    
                    // Botão para atualização do saldo
                    $ug = $registro['ug'];
                    $ano = $registro['ano'];
                    $mes = $registro['mes'];
                    
                    ?>
                    
                    <tr>
                        <td> {{ $seq }} </td>
                        <td> {{ $empenho }} </td>
                        <td> {{ $subitem }} </td>
                        <td> {{ $fonte }} </td>
                        <td class="text-right"
                        	id="NEC_{{ $registroId }}"
                        	data-saldonecessario="{{ $saldoNecessario }}">
                        	{{ $saldoNecessarioFormatado }}
                        </td>
                        <td class="text-right SALDO_{{ $neId }}">
                        	{{ $saldoAtualFormatado }}
                        </td>
                        <td class="text-right STATUS_{{ $neId }}" data-seq="{{ $seq }}">
                        <!-- <td class="text-right STATUS_{{ $neId }}" data-seq="{{ $seq }}"> -->
                        	{!! $status !!}
                        </td>
                        <td class="text-right">
                        	<button type="button"
                        			id="BTN_{{ $registroId }}"
                        			class="btn btnSaldo BTN_{{ $neId }} {{ $btnClasse }}"
                        			data-seq="{{ $seq }}"
                        			data-ug="{{ $ug }}"
                        			data-ano="{{ $ano }}"
                        			data-mes="{{ $mes }}"
                        			data-empenho="{{ $empenho }}"
                        			data-subitem="{{ $subitem }}"
                        			{{ $habilita }}>
                				<i class="fa fa-repeat"></i>
                			</button>
            			</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            <br />
        </div>
    </div>
    
    @include('backpack::mod.folha.apropriacao.botoes')
@endsection

@push('after_scripts')
    <script type="text/javascript">
    	$('.btnSaldo').click(function() {
        	// Consulta campos necessários para consulta e atualização dos dados
    		seq = $(this).data('seq');
    		ug = $(this).data('ug');
    		ano = $(this).data('ano');
    		mes = $(this).data('mes');
    		empenho = $(this).data('empenho');
    		subitem = $(this).data('subitem');

    		// Identifica que o saldo será consultado
    		$('.STATUS_' + empenho + '_' + subitem).html("Consultando saldo...");

    		// Marca o(s) botão(ões) para bloqueio
    		botao = $('.BTN_' + empenho + '_' + subitem);
    		bloqueiaBotaoDuranteOperacao(botao);

			// Consulta saldo atualizado
			valorAtualizadoFormatado = '0,00';

			$.ajax({
		    	type: 'PUT',
		    	dataType: 'text',
		    	headers: {
					// Passagem do token no header
			    	'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		    	},
		    	url: '/folha/apropriacao/empenho/saldo/' + ug + '/' + ano + '/' + mes + '/' + empenho + '/' + subitem,
		    	success: function(retorno) {
		    		atualizaDadosNaTela(retorno, seq, empenho, subitem);
		    	},
		    	error: function(e) {
		    		$('#NEC_' + neId + '_' + seq).data(valorAtualizado);
		    		$('.STATUS_' + empenho + '_' + subitem + '_').html("Erro na consulta do saldo!");

		    		formataBotaoSemSaldo(botao);
		    	}
	    	});
    	});

    	function formataValor(valor) {
        	valorOk = parseFloat(valor);
    		return valorOk.toLocaleString('pt-br', {minimumFractionDigits: 2});
    	}

    	function bloqueiaBotaoDuranteOperacao(botao) {
    		botao.removeClass('btn-danger');
        	botao.addClass('btn-info');
        	botao.attr('disabled', true);
    	}

    	function formataBotaoSemSaldo(botao) {
    		botao.removeClass('btn-info');
    		botao.addClass('btn-danger');
    		botao.attr('disabled', false);
    	}

    	function formataBotaoComSaldo(botao) {
    		botao.removeClass('btn-info');
    		botao.addClass('btn-light');
    	}

    	function atualizaDadosNaTela(retorno, seq, empenho, subitem) {
    		valorAtualizado = retorno;
    		valorAtualizadoFormatado = formataValor(valorAtualizado);

    		// Atualiza campo Saldo Atual
			campoSaldo = $('.SALDO_' + empenho + '_' + subitem);
			campoSaldo.html(valorAtualizadoFormatado);

			classe = '.STATUS_' + empenho + '_' + subitem;
    		$(classe).each(function(index) {
    			// Identifica registro
				neId = empenho + '_' + subitem;
				seq = $(this).data('seq');

				// Busca valor (numérico) do Saldo Necessário
				saldo = $('#NEC_' + neId + '_' + seq).data('saldonecessario');

				// Valida - dinamicamente - o saldo
				bSaldo = (valorAtualizado >= saldo);

				status = '<span style="color: red">Saldo insuficiente</span>';
				if (bSaldo == true) {
					status = '<span style="color: blue">Saldo suficiente</span>';
				}

				// Atualiza campo Status unitariamente
				$(this).html(status);

				// Atualiza aparência do botão
				botao = $('#BTN_' + empenho + '_' + subitem + '_' + seq);

				if (bSaldo == true) {
					formataBotaoComSaldo(botao);
				} else {
					formataBotaoSemSaldo(botao);
				}
    		});
    	}
	</script>
@endpush

<?php
/**
 * Formata número
 *
 * @param number $valor
 * @return number
 */
function retornaValorFormatado($valor)
{
    if (! is_numeric($valor)) {
        return $valor;
    }
    
    return number_format(floatval($valor), 2, ',', '.');
}
?>
