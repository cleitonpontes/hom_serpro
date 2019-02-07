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
	
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">
                    	Gerar XML
                    </h3>
                    <div class="box-tools pull-right">
                        <a href="/folha/apropriacao" class="btn btn-box-tool" title="Voltar">
                            <i class="fa fa-times"></i>
                        </a>
                    </div>
                </div>
                
                <div class="box-body">
                    <br />
                    
                    <div class="row">
                    	<div class="col-md-12 text-center">
                        	{!! Button::success('<i class="fa fa-file"></i> Apropriar SIAFI')
                    			->asLinkTo(route('folha.apropriacao.passo.1'))
                			!!}
                    	</div>
                	</div>
                	<br />
                </div>
            </div>
        </div>
    </div>
    
    @include('backpack::mod.folha.apropriacao.botoes')
@endsection
