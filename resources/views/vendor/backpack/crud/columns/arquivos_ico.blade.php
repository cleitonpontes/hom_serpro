@php
    function retornaArquivoNome($arquivo)
    {
        $partes = explode('/', $arquivo);
        $nome = array_pop($partes);

        return $nome;
    }

    function retornaArquivoCaminho($arquivo)
    {
        $caminho = asset(\Storage::disk('local')->url($arquivo));

        return $caminho;
    }

    $arquivos = $entry->{$column['name']};
@endphp

<span>
    @if(!is_array($arquivos))
        - <!-- Não há arquivos -->
    @else
        @foreach($arquivos as $arquivo)
            <span>
                <a target='_blank'
                   href='{{ retornaArquivoCaminho($arquivo) }}'
                   alt='{{ retornaArquivoNome($arquivo) }}'
                   title='{{ retornaArquivoNome($arquivo) }}'
                >
                    <i class='fa fa-3x fa-file-pdf-o'></i> &nbsp;
                </a>
            </span>
        @endforeach
    @endif
</span>
