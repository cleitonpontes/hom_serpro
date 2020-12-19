@extends('backpack::layout')

@section('header')
    <section class="content-header">
        <h1>
            Minuta
            <small>Empenho</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ backpack_url() }}">{{ config('backpack.base.project_name') }}</a></li>
            <li>Minuta</li>
            <li class="active">Empenho</li>
        </ol>
    </section>
@endsection

@section('content')
	@include('backpack::mod.empenho.telas.cabecalho')
    <div class="flash-message">
        @foreach (['danger', 'warning', 'success', 'info'] as $msg)
            @if(Session::has('alert-' . $msg))
                <p class="alert alert-{{ $msg }}">{{ Session::get('alert-' . $msg) }}</p>
            @endif
        @endforeach
    </div>
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">
                    	Consultar Compra
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

            </div>
        </div>
    </div>
@endsection
