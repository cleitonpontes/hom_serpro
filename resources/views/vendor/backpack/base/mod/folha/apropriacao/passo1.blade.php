@extends('adminlte::layouts.app')

@push('breadcrumb')
    {{ Breadcrumbs::render() }}
@endpush

@section('htmlheader_title')
    {{ trans('adminlte_lang::message.users') }}
@endsection

@section('main-content')
	@include('adminlte::mod.folha.apropriacao.cabecalho')
	
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">
                    	Importar Arquivos DDP
                    </h3>
                    <div class="box-tools pull-right">
                        <a href="/folha/apropriacao" class="btn btn-box-tool" title="Voltar">
                            <i class="fa fa-times"></i>
                        </a>
                    </div>
                </div>
                
                <div class="box-body">
                    <fieldset class="form-group">
                        {!! form($form) !!}
                    </fieldset>
                </div>
                
                @include('adminlte::mod.folha.apropriacao.validacaopendencias')
                
                @include('adminlte::mod.folha.apropriacao.sucessoimportacao')
                
            </div>
        </div>
    </div>
@endsection
