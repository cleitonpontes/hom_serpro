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
            <h3 class="box-title">Listagem de Apropriações de Faturas</h3>
        </div>

        <div class="box-body">
            <div class="box-tools">
                <div class="btn-group">
                    {!! DropdownButton::normal('<i class="fa fa-gear"></i> Exportar')->withContents([
                            ['url' => '/admin/downloadapropriacao/xlsx', 'label' => '<i class="fa fa-file-excel-o"></i> xlsx '],
                            ['url' => '/admin/downloadapropriacao/xls', 'label' => '<i class="fa fa-file-excel-o"></i> xls '],
                            ['url' => '/admin/downloadapropriacao/csv', 'label' => '<i class="fa fa-file-text-o"></i> csv ']
                        ])->split()
                    !!}
                </div>
            </div>

            <br/>
            <div class="col-sm-12">
                {!! $html->table() !!}
            </div>
        </div>
    </div>

    <!-- Janela modal para exclusão de registros -->
    <div id="confirmaExclusaoApropriacaoFatura" tabindex="-1" class="modal fade"
        role="dialog"
        aria-labelledby="confirmaExclusaoApropriacaoFatura"
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
                    Deseja excluir a apropriação desta fatura?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                        Fechar
                    </button>
                    <a href=""
                       id="btnExcluir"
                       data-id="0"
                       class="btn btn-danger text-center"
                    >
                        Excluir apropriação
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- #Modal -->
    <div class="modal fade exclusaoErro" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <h4 class="modal-title ">
                        Apropriação de fatura
                    </h4>
                </div>
                <div class="modal-body">
                    <p>
                        Erro ao excluir apropriação de fatura.
                    </p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default alert-error" data-dismiss="modal">
                        Fechar
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('after_scripts')
    {!! $html->scripts() !!}
    <script type="text/javascript">
        $('#confirmaExclusaoApropriacaoFatura').on('show.bs.modal', function(event) {
            var modal = $(event.relatedTarget);
            var id = modal.data('id');

            $('#btnExcluir').attr('data-id', id);
        });

        $('#btnExcluir').click(function() {
            var id = $(this).data('id');
            var registro = '#registro_id_' + id;

            $.ajax({
                type: 'DELETE',
                dataType: 'text',
                headers: {
                    // Passagem do token no header
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: '/apropriacao/fatura/' + id,
                success: function(retorno) {
                    $(registro).remove();
                },
                error: function(xhr, ajaxOptions, thrownError) {
                    $('.exclusaoErro').modal('show');
                }
            });
        });
    </script>
@endpush
