@php
    $idContrato = $entry->getKey();
    $value = \App\Models\Contrato::select (
        DB::raw("CONCAT(planointerno.codigo,' - ',planointerno.descricao) AS planointerno"),
        DB::raw("CONCAT(naturezadespesa.codigo,' - ',naturezadespesa.descricao) AS naturezadespesa"),
        'empenhos.numero as numeroEmpenho',
        'empenhos.empenhado as saldoEmpenhado',
        'empenhos.aliquidar as saldoALiquidar', 'empenhos.liquidado as saldoLiquidado',
        'empenhos.pago as saldoPago', 'empenhos.rpinscrito as saldoRPInscrito', 'empenhos.rpaliquidar as saldoRPALiquidar',
        'empenhos.rpliquidado as saldoRPLiquidado', 'empenhos.rppago as saldoRPPago')
    ->where('contratos.id', $idContrato)
    ->join('fornecedores', 'contratos.fornecedor_id', '=', 'fornecedores.id')
    ->join('unidades', 'unidades.id', '=', 'contratos.unidade_id')
    ->leftJoin('contratoempenhos', 'contratoempenhos.contrato_id', '=', 'contratos.id')
    ->leftJoin('empenhos', 'contratoempenhos.empenho_id', '=', 'empenhos.id')
    ->leftJoin('planointerno', 'planointerno.id', '=', 'empenhos.planointerno_id')
    ->leftJoin('naturezadespesa', 'naturezadespesa.id', '=', 'empenhos.naturezadespesa_id')
    ->get();
    $columns = [
            'numeroEmpenho' => 'Número',
            'planointerno' => 'PI',
            'naturezadespesa' => 'ND',
            'saldoEmpenhado' => 'Emp.',
            'saldoALiquidar' => 'A liq.',
            'saldoLiquidado' => 'Liquid.',
            'saldoPago' => 'Pg',
            'saldoRPInscrito' => 'RP Inscr.',
            'saldoRPALiquidar' => 'RP A Liq.',
            'saldoRPLiquidado' => 'RP Liq.',
            'saldoRPPago' => 'RP Pg'
        ];
@endphp
<span>
	@if ($value && count($columns))
        <table class="table table-bordered table-condensed table-striped m-b-0">
		<thead>
			<tr>
				@foreach($columns as $tableColumnKey => $tableColumnLabel)
                    <th>{{ $tableColumnLabel }}</th>
                @endforeach
			</tr>
		</thead>
		<tbody>
			@foreach ($value as $tableRow)
                <tr>
				@foreach($columns as $tableColumnKey => $tableColumnLabel)
                        <td>
                            @if($tableColumnKey == 'tipo')
                                {{ $tableRow->tipo->descricao }}
                            @else
                                @if($tableColumnKey == 'saldoEmpenhado' || $tableColumnKey == 'saldoALiquidar' || $tableColumnKey == 'saldoLiquidado' || $tableColumnKey == 'saldoPago' || $tableColumnKey == 'saldoRPInscrito' || $tableColumnKey == 'saldoRPALiquidar' || $tableColumnKey == 'saldoRPLiquidado' || $tableColumnKey == 'saldoRPPago')
                                    {{ number_format($tableRow->{$tableColumnKey}, 2, ',', '.') }}

                                @else
                                    {{ $tableRow->{$tableColumnKey} }}

                                @endif

                            @endif
					</td>
                    @endforeach
			</tr>
            @endforeach
		</tbody>
	</table>
    @endif
</span>
