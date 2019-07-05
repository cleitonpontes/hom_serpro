@extends('backpack::layout')

@section('header')
    <section class="content-header">
        <h1>
            Mensagens
            {{--<small>Sistema Conta</small>--}}
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ backpack_url() }}">{{ config('backpack.base.project_name') }}</a></li>
            <li class="active">Mensagens</li>
        </ol>
    </section>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="box box-solid">
                <div class="box-header with-border">
                    <i class="fa fa-envelope"></i>
                    <h3 class="box-title">Mensagens Recebidas</h3>
                    <div class="box-tools pull-right">
                        <a href="/inicio" class="btn btn-box-tool" title="Voltar">
                            <i class="fa fa-times"></i>
                        </a>
                    </div>

                </div>
                <div class="box-body">
                    <table class="table table-striped table-hover table-bordered table-responsive">
                        <thead>
                        <tr>
                            <td align="center"></td>
                            <td align="center" width="15%"><b>Data</b></td>
                            <td align="center" width="25%"><b>Assunto</b></td>
                            <td align="center" width="60%"><b>Mensagem</b></td>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($mensagens as $mensage)
                            @php
                                $texto = new \Html2Text\Html2Text($mensage->data['mensagem']);
                            @endphp
                            @if($mensage->read_at)
                                <tr>
                                    <td><a href="/mensagem/{{$mensage->id}}"><i class="fa fa-envelope-open-o"></i> </a></td>
                                    <td><a href="/mensagem/{{$mensage->id}}">{{date_format($mensage->created_at, 'd/m/Y H:i:s')}}</a></td>
                                    <td><a href="/mensagem/{{$mensage->id}}">{{$mensage->data['assunto']}}</a></td>
                                    <td><a href="/mensagem/{{$mensage->id}}">{!! trim(substr(ucfirst(mb_convert_encoding($texto->getText(),'HTML-ENTITIES','UTF-8')),0,100)).'...' !!}</a></td>
                                </tr>
                            @else
                                <tr>
                                    <td><a href="/mensagem/{{$mensage->id}}"><i class="fa fa-envelope-o"></i> </a></td>
                                    <td><a href="/mensagem/{{$mensage->id}}"><b>{{date_format($mensage->created_at, 'd/m/Y H:i:s')}}</b></a></td>
                                    <td><a href="/mensagem/{{$mensage->id}}"><b>{{$mensage->data['assunto']}}</b></a></td>
                                    <td><a href="/mensagem/{{$mensage->id}}"><b>{!! trim(substr(ucfirst(mb_convert_encoding($texto->getText(),'HTML-ENTITIES','UTF-8')),0,100)).'...' !!}</b></a></td>
                                </tr>
                            @endif

                        @endforeach
                        </tbody>
                    </table>
                    {!! $mensagens->links() !!}
                </div>
            </div>
        </div>
    </div>
@endsection
