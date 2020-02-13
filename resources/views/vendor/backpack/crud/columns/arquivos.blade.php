<span>
    @php
        $id = $entry->getKey();
        $arquivos = \App\Models\Contratoarquivo::where('contrato_id',$id)->get();
    @endphp
    @if ($arquivos && count($arquivos))
        @foreach ($arquivos as $arquivo)
            @foreach($arquivo->arquivos as $file_path)
                {{$arquivo->codigoItem->descricao}} - <a target="_blank" href="{{ isset($column['disk'])?asset(\Storage::disk($column['disk'])->url($file_path)):asset($file_path) }}">{{ $file_path }}</a><br>
            @endforeach
        @endforeach
    @else
        -
    @endif
</span>
