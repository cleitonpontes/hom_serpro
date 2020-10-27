@extends('backpack::layout')

@section('header')
    <section class="content-header">
        <h1>
            Apropriação
            <small>Fatura</small>
        </h1>
        <ol class="breadcrumb">
            <li>
                <a href="{{ backpack_url() }}">
                    {{ config('backpack.base.project_name') }}
                </a>
            </li>
            <li>
                Apropriacao
            </li>
            <li>
                <a href="{{ route('apropriacao.faturas') }}">
                    Fatura
                </a>
            </li>
            <li class="active">
                DocHábil
            </li>
        </ol>
    </section>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="box box-solid box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">
                        Documento Hábil - Faturas de Contratos
                    </h3>
                    <div class="box-tools pull-right">
                        <a href="{{ route('apropriacao.faturas') }}" class="btn btn-box-tool" title="Voltar">
                            <i class="fa fa-times"></i>
                        </a>
                    </div>
                </div>

                <div class="box-body">
                    <table class="table table-striped table-hover table-bordered table-responsive">
                        <thead>
                            <tr>
                                <td align="center" width="2%"><b>#</b></td>
                                <td align="center" width="8%"><b>Tipo</b></td>
                                <td align="center"><b>Emissão</b></td>
                                <td align="center"><b>Num. DH</b></td>
                                <td align="center"><b>Situação</b></td>
                                <td align="center"><b>Empenho</b></td>
                                <td align="center"><b>SubItem</b></td>
                                <td align="center"><b>Valor (R$)</b></td>
                                <td align="center"><b>Msg. Retorno</b></td>
                            </tr>
                        </thead>

                        <tbody>
                            @php
                                $i = 1;
                            @endphp

                            @foreach($dados as $dado)
                                <tr>
                                    <td align="center">{{$i}}</td>
                                    <td>{{$dado[0]}}</td>
                                    <td>{{$dado[1]}}</td>
                                    <td>{{$dado[2]}}</td>
                                    <td>{{$dado[3]}}</td>
                                    <td>{{$dado[4]}}</td>
                                    <td>{{$dado[5]}}</td>
                                    <td align="right">{{$dado[6]}}</td>
                                    <td>
                                        @if($dado[7] == 'Em Andamento')
                                            <i class="fa fa-spinner"></i> {{$dado[7]}}
                                        @else
                                            {{$dado[7]}}
                                        @endif
                                    </td>
                                </tr>

                                @php
                                    $i++;
                                @endphp
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
