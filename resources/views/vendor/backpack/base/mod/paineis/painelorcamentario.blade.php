@extends('backpack::layout')

@section('header')
    <section class="content-header">
        <h1>
            {{ $data['title'] }}
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ backpack_url() }}">{{ config('backpack.base.project_name') }}</a></li>
            <li class="active">{{ $data['title'] }}</li>
        </ol>
    </section>
@endsection


@section('content')
    <div class="row">
        <div class="col-md-12">

            <div class="row">
                <section class="col-lg-7 connectedSortable ui-sortable">
                    <div class="box box-solid">
                        <div class="box-header with-border">
                            <i class="fa fa-bar-chart"></i>
                            <h3 class="box-title">Execução por Empenho - Outras Despesas Corrente (330000)</h3>

                            <div class="box-tools pull-right">
                                {{--<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>--}}
                                {{--</button>--}}
                                {{--<button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>--}}
                            </div>
                        </div>
                        <div class="box-body">
                            {!! $graficoEmpenhosOutrasDespesasCorrentes->render() !!}
                            <br><br>
                            <table class="table table-striped table-hover table-bordered table-responsive">
                                <thead>
                                <tr>
                                    <td align="center"><b>Unidade Gestora</b></td>
                                    <td align="center"><b>Empenhado</b></td>
                                    <td align="center"><b>A Liquidar</b></td>
                                    <td align="center"><b>Liquidado</b></td>
                                    <td align="center"><b>Pago</b></td>
                                </tr>
                                </thead>
                                <tbody>
                                @php
                                    $tfootdadosEmpenhosOutrasDespesasCorrentes['empenhado'] = 0;
                                    $tfootdadosEmpenhosOutrasDespesasCorrentes['aliquidar'] = 0;
                                    $tfootdadosEmpenhosOutrasDespesasCorrentes['liquidado'] = 0;
                                    $tfootdadosEmpenhosOutrasDespesasCorrentes['pago'] = 0;
                                @endphp
                                @foreach($dadosEmpenhosOutrasDespesasCorrentes as $dadoEmpenhoOutrasDespesasCorrentes)
                                    <tr>
                                        <td>{{$dadoEmpenhoOutrasDespesasCorrentes['nome'] }}</td>
                                        <td align="right">R$ {{number_format($dadoEmpenhoOutrasDespesasCorrentes['empenhado'],2,',','.')}}</td>
                                        <td align="right">R$ {{number_format($dadoEmpenhoOutrasDespesasCorrentes['aliquidar'],2,',','.')}}</td>
                                        <td align="right">R$ {{number_format($dadoEmpenhoOutrasDespesasCorrentes['liquidado'],2,',','.')}}</td>
                                        <td align="right">R$ {{number_format($dadoEmpenhoOutrasDespesasCorrentes['pago'],2,',','.')}}</td>
                                    </tr>
                                @php
                                    $tfootdadosEmpenhosOutrasDespesasCorrentes['empenhado'] += $dadoEmpenhoOutrasDespesasCorrentes['empenhado'];
                                    $tfootdadosEmpenhosOutrasDespesasCorrentes['aliquidar'] += $dadoEmpenhoOutrasDespesasCorrentes['aliquidar'];
                                    $tfootdadosEmpenhosOutrasDespesasCorrentes['liquidado'] += $dadoEmpenhoOutrasDespesasCorrentes['liquidado'];
                                    $tfootdadosEmpenhosOutrasDespesasCorrentes['pago'] += $dadoEmpenhoOutrasDespesasCorrentes['pago'];
                                @endphp
                                @endforeach
                                <tfoot>
                                <tr>
                                    <td align="center"><b>Total</b></td>
                                    <td align="right"><b>R$ {{number_format($tfootdadosEmpenhosOutrasDespesasCorrentes['empenhado'],2,',','.')}}</b></td>
                                    <td align="right"><b>R$ {{number_format($tfootdadosEmpenhosOutrasDespesasCorrentes['aliquidar'],2,',','.')}}</b></td>
                                    <td align="right"><b>R$ {{number_format($tfootdadosEmpenhosOutrasDespesasCorrentes['liquidado'],2,',','.')}}</b></td>
                                    <td align="right"><b>R$ {{number_format($tfootdadosEmpenhosOutrasDespesasCorrentes['pago'],2,',','.')}}</b></td>
                                </tr>
                                </tfoot>
                            </table>
                        </div>
                        <!-- /.box-body -->
                    </div>
                </section>
                <section class="col-lg-5 connectedSortable ui-sortable">
                    <div class="box box-solid">
                        <div class="box-header ui-sortable-handle with-border" style="cursor: move;">
                            <i class="fa fa-calendar"></i>

                            <h3 class="box-title">Calendário</h3>
                            <!-- tools box -->
                            <div class="pull-right box-tools">
                                <!-- button with a dropdown -->
                                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i
                                        class="fa fa-minus"></i>
                                </button>
                                <button type="button" class="btn btn-box-tool" data-widget="remove"><i
                                        class="fa fa-times"></i></button>
                            </div>
                            <!-- /. tools -->
                        </div>
                        <!-- /.box-header -->
                        <div class="box-body no-padding">
                        </div>
                        <!-- /.box-body -->
                    </div>
                </section>
            </div>
        </div>
    </div>
@endsection
