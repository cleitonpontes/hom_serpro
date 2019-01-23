@extends('adminlte::layouts.app')

{{--@section('style')--}}
    {{--<link rel="stylesheet" href="{{asset('css/app.css')}}">--}}
{{--@endsection--}}

{{--@push('breadcrumb')--}}
    {{--{{ Breadcrumbs::render() }}--}}
{{--@endpush--}}

{{--@section('htmlheader_title')--}}
    {{--{{ trans('adminlte_lang::message.users') }}--}}
{{--@endsection--}}

@php
    $param = $apropriacao[0];
@endphp

@section('main-content')
    <div class="row">
        <div class="col-md-12">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">Relatório da Apropriação por Competência</h3>
                </div>

                <div class="box-body row">
                    <div class="col-lg-12">Orgão: Advocacia Geral da União</div>
                    <div class="col-lg-12 row">
                        <div class="col-lg-5">UG: 110062 - DGPE</div>
                        <div class="col-lg-5">Competência: {{$param['competencia']}}</div>
                    </div>
                    <div class="col-lg-12">Processo: 00000.0000/0000-00</div>
                    <div class="col-lg-12 row">
                        <div class="col-lg-5">Emissão: DD/MM/AAAA</div>
                        <div class="col-lg-5">Ateste: DD/MM/AAAA</div>
                    </div>
                    <div class="col-lg-12">Documento de origem: FOPAG - MM/AAAA</div>
                </div>

                <div class="box-body">
                    <table id="pdc" border="1" class="col-lg-12">
                        <caption>PCO</caption>
                        <thead>
                            <tr>
                                <th>Situção</th>
                                <th>Nome situção</th>
                                <th>VPD</th>
                                <th>NE</th>
                                <th>Sub item</th>
                                <th>Fonte</th>
                                <th>Valor</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($apropriacao as $categoria)
                            @php
                            $dataCriacao = Carbon\Carbon::parse($categoria['created_at'])->format('d-m-Y');
                            $dataAlteracao = Carbon\Carbon::parse($categoria['updated_at'])->format('d-m-Y');
                            @endphp
                            <tr>
                                <td>DFL001</td>
                                <td>Despesa com Remuneração e pessoal ativo</td>
                                <td>3.1111.1.00.00</td>
                                <td>2018NE0000001</td>
                                <td>34</td>
                                <td>01000000000000000</td>
                                <td>131.179,14</td>
                            </tr>
                            @endforeach
                            <tr style="background-color: grey; color: black;">
                                <td colspan="6"><b>&nbsp;Total</b></td>
                                <td colspan="1"><b>170.233.440,55</b></td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="box-body">
                    <table id="despesasAnular" border="1" class="col-lg-12">
                        <caption>Despesas a Anular</caption>
                        <thead>
                            <tr>
                                <th>Situção</th>
                                <th>Nome situção</th>
                                <th>VPD</th>
                                <th>NE</th>
                                <th>Sub item</th>
                                <th>Fonte</th>
                                <th>Valor</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($apropriacao as $categoria)
                            @php
                            $dataCriacao = Carbon\Carbon::parse($categoria['created_at'])->format('d-m-Y');
                            $dataAlteracao = Carbon\Carbon::parse($categoria['updated_at'])->format('d-m-Y');
                            @endphp
                            <tr>
                                <td>DFL001</td>
                                <td>Despesa com Remuneração e pessoal ativo</td>
                                <td>3.1111.1.00.00</td>
                                <td>2018NE0000001</td>
                                <td>34</td>
                                <td>01000000000000000</td>
                                <td>131.179,14</td>
                            </tr>
                            @endforeach
                            <tr style="background-color: grey; color: black;">
                                <td colspan="6"><b>&nbsp;Total</b></td>
                                <td colspan="1"><b>170.233.440,55</b></td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="box-body">
                    <div class="col-lg-12" style="background-color: grey; color: black;"><b>RESUMO</b></div>
                    <div class="col-lg-12 row">
                        <div class="col-lg-9" style="text-align: left">Valor Bruto:</div>
                        <div class="col-lg-3" style="text-align: right">{{$param['valor_bruto']}}</div>
                    </div>
                    <div class="col-lg-12 row">
                        <div class="col-lg-9" style="text-align: left">Descontos:</div>
                        <div class="col-lg-3" style="text-align: right">{{($param['valor_bruto'] - $param['valor_liquido'])}}</div>
                    </div>
                    <div class="col-lg-12 row">
                        <div class="col-lg-9" style="text-align: left">Líquido:</div>
                        <div class="col-lg-3" style="text-align: right">{{$param['valor_liquido']}}</div>
                    </div>
                </div>

                <br>
            </div>
        </div>
    </div>
@endsection

