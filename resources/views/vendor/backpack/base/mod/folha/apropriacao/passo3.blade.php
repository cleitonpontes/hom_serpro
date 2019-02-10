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
@php
    $apid = Request()->apid;
@endphp
@section('content')
	@include('backpack::mod.folha.apropriacao.cabecalho')
	
    <div class="box box-solid box-primary">
        <div class="box-header with-border">
            <h3 class="box-title">Listagem de Empenhos a Identificar</h3>
        </div>
        
        <div class="box-body">
            <br/>
            <div class="col-sm-12">
                {!! $html->table() !!}
            </div>
        </div>
    </div>
    
    @include('backpack::mod.folha.apropriacao.botoes')
@endsection

@push('after_scripts')
    {!! $html->scripts() !!}
    <script type="text/javascript">
		$('body').delegate('.valor', 'focusout', function() {
			var apid = $(this).data('apid');
			var id = $(this).data('id');
			var valor = $(this).val();

		    $.ajax({
		    	type: 'PUT',
		    	dataType: 'text',
		    	headers: {
					// Passagem do token no header
			    	'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		    	},
		    	url: '/folha/apropriacao/empenho/atualiza/' + id + '/' + valor,
		    	success: function(retorno) {
			    	// alert('Update...');
		    	},
		    	error: function(e) {
			    	// alert('Erro...');
		    	}
	    	});
		});
    </script>
@endpush
