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
            <h3 class="box-title">Persistir Dados</h3>
        </div>
        
        <div class="box-body">
            <br />
            <table id="datatable" class="table table-striped table-bordered nowrap" cellspacing="0" width="100%">
                <thead class="bg-primary">
                    <tr>
                        <th>Dados a gravar</th>
                        <th class='text-right'>Status</th>
                    </tr>
                </thead>
                
                <tbody>
                    <tr>
                        <td><i>Preparação preliminar</i></td>
                        <td id='prp' class='text-right'>Não iniciado</td>
                    </tr>
                    <tr>
                        <td>Padrão</td>
                        <td id='pdr' class='text-right'>Não iniciado</td>
                    </tr>
                    <tr>
                        <td>Dados básicos</td>
                        <td id='bas' class='text-right'>Não iniciado</td>
                    </tr>
                    <tr>
                        <td>Documento de origem</td>
                        <td id='doc' class='text-right'>Não iniciado</td>
                    </tr>
                    <tr>
                        <td>PCO</td>
                        <td id='pco' class='text-right'>Não iniciado</td>
                    </tr>
                    <tr>
                        <td>PCO - Item</td>
                        <td id='pci' class='text-right'>Não iniciado</td>
                    </tr>
                    <tr>
                        <td>Despesa a anular</td>
                        <td id='dsp' class='text-right'>Não iniciado</td>
                    </tr>
                    <tr>
                        <td>Despesa a anular - Item</td>
                        <td id='dsi' class='text-right'>Não iniciado</td>
                    </tr>
                    <tr>
                        <td>Relacionamento entre itens PCO e Despesa a anular</td>
                        <td id='rdi' class='text-right'>Não iniciado</td>
                    </tr>
                </tbody>
            </table>
            <br />
            
            <div class="row">
            	<div class="col-md-12 text-center">
            		<button type="button"
                    		id="btnPersistirDados"
                    		class="btn btn-success">
                    		<i class="fa fa-play"></i> Persistir Dados
                	</button>
            		<button type="button"
                    		id="btnRelatorio"
                    		class="btn btn-success"
                    		disabled>
                    		<i class="fa fa-list-alt"></i> Relatório da Apropriação
                	</button>
            	</div>
            </div>
            
        </div>
        <br />
        
    </div>
    
    @include('backpack::mod.folha.apropriacao.botoes')
    
    <input type='hidden' id='apid' value='<?php echo $apid; ?>' />
@endsection

@push('after_scripts')
    <style type="text/css">
        .azul {
            color: blue;
        }
        
        .vermelho {
            color: red;
        }
    </style>
@endpush

@push('after_scripts')
    <script type="text/javascript">
		$('#btnPersistirDados').click(function() {
			$('#btnPersistirDados').attr('disabled', 'true');
			$('#btnProximo').attr('disabled', 'true');

        	var apid = $('#apid').val();
        	$('#prp').html("<strong>Prepração preliminar em andamento...</strong>");
        	preparacaoInicial(apid);
		});

		$('#btnRelatorio').click(function() {
			var apid = $('#apid').val();
			var url = '/folha/apropriacao/relatorio/' + apid;
			
			window.open(url, '_blank');
		});

		function preparacaoInicial(apid) {
		    $.ajax({
		    	type: 'PUT',
		    	dataType: 'text',
		    	headers: {
					// Passagem do token no header
			    	'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		    	},
		    	url: '/folha/apropriacao/persistir/' + apid + '/PreparacaoInicial',
		    	success: function(retorno) {
		    		$('#prp').html("Prepração preliminar concluída");
		    		$('#pdr').html("<strong>Prepração do registro 'SF - Padrão' em andamento...</strong>");
		    		
		    		atualizaPadrao(apid);
		    	},
		    	error: function(e) {
		    		$('#prp').html('<span class=vermelho>Erro na preparação preliminar</span>');
		    	}
	    	});
		}

		function atualizaPadrao(apid) {
		    $.ajax({
		    	type: 'PUT',
		    	dataType: 'text',
		    	headers: {
					// Passagem do token no header
			    	'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		    	},
		    	url: '/folha/apropriacao/persistir/' + apid + '/Padrao',
		    	success: function(retorno) {
		    		$('#pdr').html("Registro 'SF - Padrão' gravado");
		    		$('#bas').html("<strong>Prepração do registro 'SF - Dados básicos' em andamento...</strong>");
		    		
		    		atualizaDadosBasicos(apid);
		    	},
		    	error: function(e) {
		    		$('#pdr').html('<span class=vermelho>Erro na gravação de dados: Padrão</span>');
		    	}
	    	});
		}

		function atualizaDadosBasicos(apid) {
		    $.ajax({
		    	type: 'PUT',
		    	dataType: 'text',
		    	headers: {
					// Passagem do token no header
			    	'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		    	},
		    	url: '/folha/apropriacao/persistir/' + apid + '/DadosBasicos',
		    	success: function(retorno) {
		    		$('#bas').html("Registro 'SF - Dados básicos' gravado");
		    		$('#doc').html("<strong>Prepração do registro 'SF - Documento de origem' em andamento...</strong>");

		    		atualizaDocumentoOrigem(apid);
		    	},
		    	error: function(e) {
		    		$('#bas').html('<span class=vermelho>Erro na gravação de dados: Dados básicos</span>');
		    	}
	    	});
		}

		function atualizaDocumentoOrigem(apid) {
		    $.ajax({
		    	type: 'PUT',
		    	dataType: 'text',
		    	headers: {
					// Passagem do token no header
			    	'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		    	},
		    	url: '/folha/apropriacao/persistir/' + apid + '/DocumentoOrigem',
		    	success: function(retorno) {
		    		$('#doc').html("Registro 'SF - Documento de origem' gravado");
		    		$('#pco').html("<strong>Prepração do registro 'SF - PCO' em andamento...</strong>");

		    		atualizaPco(apid);
		    	},
		    	error: function(e) {
		    		$('#doc').html('<span class=vermelho>Erro na gravação de dados: Documento de origem</span>');
		    	}
	    	});
		}

		function atualizaPco(apid) {
		    $.ajax({
		    	type: 'PUT',
		    	dataType: 'text',
		    	headers: {
					// Passagem do token no header
			    	'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		    	},
		    	url: '/folha/apropriacao/persistir/' + apid + '/Pco',
		    	success: function(retorno) {
		    		$('#pco').html("Registros 'SF - PCO' gravados");
		    		$('#pci').html("<strong>Prepração do registro 'SF - PCO - Item' em andamento...</strong>");

		    		atualizaPcoItem(apid);
		    	},
		    	error: function(e) {
		    		$('#pco').html('<span class=vermelho>Erro na gravação de dados: PCO</span>');
		    	}
	    	});
		}

		function atualizaPcoItem(apid) {
		    $.ajax({
		    	type: 'PUT',
		    	dataType: 'text',
		    	headers: {
					// Passagem do token no header
			    	'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		    	},
		    	url: '/folha/apropriacao/persistir/' + apid + '/PcoItem',
		    	success: function(retorno) {
		    		$('#pci').html("Registros 'SF - PCO - Item' gravados");
		    		$('#dsp').html("<strong>Prepração do registro 'SF - Despesa a anular' em andamento...</strong>");

		    		atualizaDespesa(apid);
		    	},
		    	error: function(e) {
		    		$('#pci').html('<span class=vermelho>Erro na gravação de dados: PCO - Item</span>');
		    	}
	    	});
		}

		function atualizaDespesa(apid) {
		    $.ajax({
		    	type: 'PUT',
		    	dataType: 'text',
		    	headers: {
					// Passagem do token no header
			    	'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		    	},
		    	url: '/folha/apropriacao/persistir/' + apid + '/Despesa',
		    	success: function(retorno) {
		    		$('#dsp').html("Registros 'SF - Despesa a anular' gravados");
		    		$('#dsi').html("<strong>Prepração do registro 'SF - Despesa a anular - Item' em andamento...</strong>");

		    		atualizaDespesaItem(apid);
		    	},
		    	error: function(e) {
		    		$('#dsp').html('<span class=vermelho>Erro na gravação de dados: Despesa a anular</span>');
		    	}
	    	});
		}

		function atualizaDespesaItem(apid) {
		    $.ajax({
		    	type: 'PUT',
		    	dataType: 'text',
		    	headers: {
					// Passagem do token no header
			    	'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		    	},
		    	url: '/folha/apropriacao/persistir/' + apid + '/DespesaItem',
		    	success: function(retorno) {
		    		$('#dsi').html("Registros 'SF - Despesa a anular - Item' gravados");
		    		$('#rdi').html("<strong>Prepração do registro 'SF - Rel. Itens Desp. Anular e PCO' em andamento...</strong>");

		    		atualizaRelacionamentos(apid);
		    	},
		    	error: function(e) {
		    		$('#dsi').html('<span class=vermelho>Erro na gravação de dados: Despesa a anular - Item</span>');
		    	}
	    	});
		}
		
		function atualizaRelacionamentos(apid) {
		    $.ajax({
		    	type: 'PUT',
		    	dataType: 'text',
		    	headers: {
					// Passagem do token no header
			    	'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		    	},
		    	url: '/folha/apropriacao/persistir/' + apid + '/Relacionamentos',
		    	success: function(retorno) {
		    		$('#rdi').html("Registros 'SF - Rel. Itens Desp. Anular e PCO' gravados");
		    		
		    		$('#btnRelatorio').prop('disabled', false);
		    		$('#btnProximo').prop('disabled', false);
		    	},
		    	error: function(e) {
		    		$('#rdi').html('<span class=vermelho>Erro na gravação de dados: Rel. Itens Desp. Anular e PCO</span>');
		    	}
	    	});
		}
	</script>
@endpush
