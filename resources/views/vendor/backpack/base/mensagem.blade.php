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

@php
    $mensagens = backpack_user()->notifications;
@endphp
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="box box-solid">
                <div class="box-header with-border">
                    <h3 class="box-title">Ler Mensagem</h3>
                    <div class="box-tools pull-right">
                        <a href="/mensagens" class="btn btn-box-tool" title="Voltar">
                            <i class="fa fa-times"></i>
                        </a>
                    </div>

                </div>
                <div class="box-body">
                    <table class="table table-bordered">
                        <tbody>
                        <tr>
                            <th scope="row">Assunto</th>
                            <td>{{$notificacao->data['assunto']}}</a></td>
                        </tr>
                        <tr>
                            <th scope="row">Mensagem</th>
                            <td>{!! $notificacao->data['mensagem'] !!}</td>
                        </tr>
                        <tr>
                            <th scope="row">Anexos</th>
                            <td>
                                @if($notificacao->data['anexos'])
                                    @foreach($notificacao->data['anexos'] as $anexo)
                                        <a href="{!! url('storage/'.$anexo) !!}" target="_blank">{!! $anexo !!}</a><br>
                                    @endforeach
                                @endif
                            </td>

                        </tr>
                        <tr>
                            <th scope="row">Criação</th>
                            <td>{{date_format($notificacao->created_at, 'd/m/Y H:i:s')}}</td>
                        </tr>
                        </tbody>
                    </table>

                </div>
                <!-- /.box-body -->
            </div>
            <!-- /.box -->
        </div>
    </div>
@endsection
