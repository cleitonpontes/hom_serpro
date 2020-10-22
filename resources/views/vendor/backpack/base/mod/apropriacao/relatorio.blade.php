<?php
$ateste = \Illuminate\Support\Carbon::createFromFormat('Y-m-d', $identificacao['ateste'])->format('d/m/Y');
$valorBruto = retornaValorFormatado($identificacao['valor_bruto']);
$valorDesconto = retornaValorFormatado($identificacao['valor_desconto']);
$valorLiquido = retornaValorFormatado($identificacao['valor_liquido']);

$ordemPco = 1;
$totalPco = 0;
?>

@extends('backpack::layout')

@section('header')
    <section class="content-header">
        <h1>
            Apropriação
            <small>Fatura</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ backpack_url() }}">{{ config('backpack.base.project_name') }}</a></li>
            <li>Apropriação</li>
            <li class="active">Fatura</li>
        </ol>
    </section>
@endsection

@section('content')
    <div class="box box-solid box-primary">
        <div class="box-header with-border">
            <h3 class="box-title">
                Relatório da Apropriação da fatura
            </h3>
            <div class="box-tools pull-right">
                <a href="/apropriacao/fatura" class="btn btn-box-tool" title="Voltar">
                    <i class="fa fa-times"></i>
                </a>
            </div>
        </div>

        <div class="box-body">
            <div class="row">
                <div class="col-md-12 text-left">
                    <h3 class="bg-primary" style="padding: 5px;"> Identificação </h3>

                    <div class="row">
                        <div class="col-lg-2">
                    		<strong>
                    			Órgão:
                    		</strong>
                        </div>
                        <div class="col-lg-10">
                    		{{ $identificacao['orgao'] }}
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-2">
                    		<strong>
                    			Unidade gestora:
                    		</strong>
                        </div>
                        <div class="col-lg-10">
                    		{{ $identificacao['unidade'] }}
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-2">
                        	<strong>
                        		Processo:
                        	</strong>
                        </div>
                        <div class="col-lg-10">
                            {{ $identificacao['processo'] }}
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-2">
                        	<strong>
                        		Data de ateste:
                        	</strong>
                        </div>
                        <div class="col-lg-10">
                            {{ $ateste }}
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-2">
                        	<strong>
                        		Documento origem:
                        	</strong>
                        </div>
                        <div class="col-lg-10">
                            {{ $identificacao['doc_origem'] }}
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-2">
                        	<strong>
                        		Observações:
                        	</strong>
                        </div>
                        <div class="col-lg-10">
                            {{ $identificacao['observacoes'] }}
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-2">
                            <strong>
                                Centro de Custo:
                            </strong>
                        </div>
                        <div class="col-lg-10">
                            {{ $identificacao['centro_custo'] }}
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-2">
                        	<strong>
                        		Valor bruto:
                        	</strong>
                        </div>
                        <div class="col-lg-10">
                            {{ $valorBruto }}
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-2">
                        	<strong>
                        		Valor desconto:
                        	</strong>
                        </div>
                        <div class="col-lg-10">
                            {{ $valorDesconto }}
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-2">
                        	<strong>
                        		Valor líquido:
                        	</strong>
                        </div>
                        <div class="col-lg-10">
                            {{ $valorLiquido }}
                        </div>
                    </div>

                </div>
            </div>

            <div class="row">
                <div class="col-md-12 text-left">
                    <h3 class="bg-primary" style="padding: 5px;"> PCO </h3>

                    <table id="tbPco" class="col-lg-12 table-striped table-bordered nowrap">
                        <thead>
                            <tr>
                                <th nowrap> # &nbsp; </th>
                                <th nowrap> Situação &nbsp; </th>
                                <th nowrap> Descrição situação </th>
                                <th> Empenho </th>
                                <th nowrap class="text-right"> &nbsp; Sub item </th>
                                <th nowrap class="text-right"> &nbsp; Fonte </th>
                                <th class="text-right"> VPD </th>
                                <th class="text-right"> Valor </th>
                            </tr>
                        </thead>

                        <tbody>
                        	@foreach($pcos as $pco)
                        	@php $totalPco += $pco['valor'] @endphp
                            <tr>
                                <td> {{ $ordemPco++ }} </td>
                                <td> {{ $pco['situacao'] }} </td>
                                <td> {{ ucwords(mb_strtolower($pco['descricao'])) }} </td>
                                <td> {{ $pco['empenho'] }} </td>
                                <td class="text-right"> {{ $pco['subitem'] }} </td>
                                <td class="text-right"> {{ $pco['fonte'] }} </td>
                                <td class="text-right"> {{ $pco['vpd'] }} </td>
                                <td class="text-right"> {{ retornaValorFormatado($pco['valor']) }} </td>
                            </tr>
                            @endforeach
                        </tbody>

                        <tfoot>
                            <tr style="background-color: #eeeeee; ">
                                <th colspan="7"> Total </th>
                                <th class="text-right"> {!! '&nbsp;' . retornaValorFormatado($totalPco) !!} </th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
            <br />

            {{--
            <div class="row">
                <div class="col-md-12 text-left">
                    <h3 class="bg-primary" style="padding: 5px;"> Despesa a anular </h3>

                    <table id="tbDespesa" class="col-lg-12 table-striped table-bordered nowrap">
                        <thead>
                            <tr>
                                <th nowrap> # &nbsp; </th>
                                <th nowrap> Situação &nbsp; </th>
                                <th nowrap> Descrição situação </th>
                                <th> Empenho </th>
                                <th nowrap class="text-right"> &nbsp; Sub item </th>
                                <th nowrap class="text-right"> &nbsp; Fonte </th>
                                <th class="text-right"> VPD </th>
                                <th class="text-right"> Valor </th>
                            </tr>
                        </thead>

                        <tbody>
                        	@foreach($despesas as $despesa)
                        	@php $totalDespesa += $despesa['valor'] @endphp
                            <tr>
                                <td> {{ $ordemDespesa++ }} </td>
                                <td> {{ $despesa['situacao'] }} </td>
                                <td> {{ ucwords(mb_strtolower($despesa['descricao'])) }} </td>
                                <td> {{ $despesa['empenho'] }} </td>
                                <td class="text-right"> {{ $despesa['subitem'] }} </td>
                                <td class="text-right"> {{ $despesa['fonte'] }} </td>
                                <td class="text-right"> {{ $despesa['vpd'] }} </td>
                                <td class="text-right"> {{ retornaValorFormatado($despesa['valor']) }} </td>
                            </tr>
                            @endforeach
                        </tbody>

                        <tfoot>
                            <tr style="background-color: #eeeeee; ">
                                <th colspan="7"> Total </th>
                                <th class="text-right"> {!! '&nbsp;' . retornaValorFormatado($totalDespesa) !!} </th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
            <br />
        </div>
        --}}
    </div>
@endsection

<?php
/**
 * Formata número
 *
 * @param number $valor
 * @return number
 */
function retornaValorFormatado($valor)
{
    if (! is_numeric($valor)) {
        return $valor;
    }

    return number_format(floatval($valor), 2, ',', '.');
}
?>
