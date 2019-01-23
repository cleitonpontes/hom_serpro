@extends('adminlte::layouts.app')

@php
$apid = Request()->apid;
@endphp

@push('breadcrumb')
    {{ Breadcrumbs::render() }}
@endpush

@section('htmlheader_title')
    {{ trans('adminlte_lang::message.users') }}
@endsection

@section('main-content')
	@include('adminlte::mod.folha.apropriacao.cabecalho')
	
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
    
    @include('adminlte::mod.folha.apropriacao.botoes')
    
@endsection

@push('scripts')
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
		    	url: '/folha/apropriacao/ne/' + id + '/' + valor,
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
