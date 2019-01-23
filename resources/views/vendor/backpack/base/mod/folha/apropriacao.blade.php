@extends('backpack::layout')

@section('header')
    <section class="content-header">
        <h1>
            Folha
            <small>Apropriação</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ backpack_url() }}">{{ config('backpack.base.project_name') }}</a></li>
            <li>Folha</li>
            <li class="active">Apropriação</li>
        </ol>
    </section>
@endsection

@section('content')
    <div class="box box-solid box-primary">
        <div class="box-header with-border">
            <h3 class="box-title">Listagem de Apropriações</h3>
        </div>
        
        <div class="box-body">
            <div class="box-tools">
                {!! Button::primary('<i class="fa fa-plus"></i> Nova apropriação')
                    ->asLinkTo(route('folha.apropriacao.passo.1'))
                !!}
                <div class="btn-group">
                    {!! DropdownButton::normal('<i class="fa fa-gear"></i> Exportação')->withContents([
                        ['url' => '/administracao/downloadapropriacao/xlsx', 'label' => '<i class="fa fa-file-excel-o"></i> xlsx '],
                        ['url' => '/administracao/downloadapropriacao/xls', 'label' => '<i class="fa fa-file-excel-o"></i> xls '],
                        ['url' => '/administracao/downloadapropriacao/csv', 'label' => '<i class="fa fa-file-text-o"></i> csv ']
                ])->split() !!}
                </div>
            </div>

            <br/>
            <div class="col-sm-12">
                {!! $html->table() !!}
            </div>
        </div>
    </div>
    
    <!-- Janela modal para exclusão de registros -->
    <div id="confirmaExclusaoApropriacao" tabindex="-1" class="modal fade" 
        role="dialog" 
        aria-labelledby="confirmaExclusaoApropriacaoTitle" 
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title">
                        Confirmação de exclusão
                    </h3>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" id="textoModal">
                    Deseja excluir esta apropriação?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                        Fechar
                    </button>
                    <a href="#" class="btn btn-danger" id="btnExcluir">
                        Excluir apropriação
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('after_scripts')
    {!! $html->scripts() !!}
    <script type="text/javascript">
        $('#confirmaExclusaoApropriacao').on('show.bs.modal', function(event) {
            var botao = $(event.relatedTarget);
            var link = botao.data('link');

            $('#btnExcluir').attr('href', link);
        });
    </script>
@endpush
