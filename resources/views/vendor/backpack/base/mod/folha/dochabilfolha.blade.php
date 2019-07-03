@extends('backpack::layout')

@section('header')
    <section class="content-header">
        <h1>
            Folha
            <small>Documento Hábil</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ backpack_url() }}">{{ config('backpack.base.project_name') }}</a></li>
            <li><a href="/folha/apropriacao">Folha</a></li>
            <li class="active">DocHábil</li>
        </ol>
    </section>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="box box-solid box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">Documento Hábil - Folha de Pagamento</h3>
                    <div class="box-tools pull-right">
                        <a href="/folha/apropriacao" class="btn btn-box-tool" title="Voltar">
                            <i class="fa fa-times"></i>
                        </a>
                    </div>

                </div>
                <div class="box-body">
                    <table class="table table-bordered">
                        <tbody>
                        <tr>
                            <th scope="row">Id Apropriação</th>
                            <td width="70%">{{$dado->fk}}</td>
                        </tr>
                        <tr>
                            <th scope="row">Data Emissão</th>
                            <td>
                                @php
                                    $data = implode('/',array_reverse(explode('-',$dado->dtemis)));
                                @endphp
                                {{$data}}
                            </td>
                        </tr>
                        <tr>
                            <th scope="row">Número Doc. Hábil</th>
                            <td>{{$dado->anodh.$dado->codtipodh.str_pad($dado->numdh , 6 , '0' , STR_PAD_LEFT)}}</td>

                        </tr>
                        <tr>
                            <th scope="row">Mensagem retorno</th>
                            <td>{{$dado->msgretorno}}</td>
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
