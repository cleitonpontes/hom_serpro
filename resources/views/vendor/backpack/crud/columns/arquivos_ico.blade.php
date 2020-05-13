@php
    $arquivos = $entry->{$column['name']};
@endphp

<span>
    @if(!is_array($arquivos))
        - <!-- Não há arquivos -->
    @else
        @foreach($arquivos as $arquivo)
            @php
                $nome = array_pop(explode('/', $arquivo));
                $caminho = asset(\Storage::disk('local')->url($arquivo));
            @endphp

            <span>
                <a target='_blank'
                   href='{{ $caminho }}'
                   alt='{{ $nome }}'
                   title='{{ $nome }}'
                >
                    <i class='fa fa-3x fa-file-pdf-o'></i> &nbsp;
                </a>
            </span>
        @endforeach
    @endif
</span>
