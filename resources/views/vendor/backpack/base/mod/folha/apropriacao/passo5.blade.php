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
	
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">
                    	Informar Dados Complementares
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
                
                @include('backpack::mod.folha.apropriacao.validacaopendencias')
                
                @include('backpack::mod.folha.apropriacao.sucessoimportacao')
                
            </div>
        </div>
    </div>
    
    @include('backpack::mod.folha.apropriacao.botoes')
@endsection

@push('after_scripts')
    <script type="text/javascript">
	    $('#nup').mask('99999.999999/9999-99');
    </script>
@endpush
