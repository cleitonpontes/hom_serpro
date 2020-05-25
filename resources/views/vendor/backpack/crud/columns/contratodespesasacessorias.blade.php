@php
    $id = $entry->getKey();
    $value = \App\Models\Contratodespesaacessoria::where('contrato_id',$id)
            ->get();
    $columns = [
            'tipo_id' => 'Data Assinatura',
            'numero' => 'Número',
            "tipo" => 'Tipo',
            'observacao' => 'Observação',
            'vigencia_inicio' => 'Data Início',
            'vigencia_fim' => 'Data Fim',
            'valor_global' => 'Vlr. Global',
            'num_parcelas' => 'Parcelas',
            'valor_parcela' => 'Vlr. Parcela'
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
                                {{ $tableRow->{$tableColumnKey} }}
                            @endif
					</td>
                    @endforeach
			</tr>
            @endforeach
		</tbody>
	</table>
    @endif
</span>
