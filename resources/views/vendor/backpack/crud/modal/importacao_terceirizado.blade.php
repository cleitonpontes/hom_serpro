<div class="modal fade" tabindex="-1" role="dialog" id="modal-importacao-terceirizado">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form
            action="{{ $routeAction }}"
            enctype="multipart/form-data"
            method="post"
            >
                {!! csrf_field() !!}
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Importação de terceirizados</h4>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label class="input-required">Delimitador</label>
                        <input type="text" name="delimitador" value="" class="form-control">
                    </div>
                    <div class="form-group">
                        <label class="input-required">Arquivos</label>
                        <input name="arquivos[]" type="hidden" value="">
                        <input
                            type="file"
                            id="arquivos_file_input"
                            name="arquivos[]"
                            value=""
                            class="form-control"
                            multiple=""
                            accept=".txt"
                        >
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal"><span class="fa fa-ban"></span>
                        &nbsp;Cancelar
                    </button>
                    <button type="submit" class="btn btn-success">
                        <span class="fa fa-save" role="presentation" aria-hidden="true"></span> &nbsp;
                        <span>Salvar</span>
                    </button>
                </div>
            </form>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

@push('after_scripts')
    <style type="text/css">
        .input-required::after {
            content: ' *';
            color: #ff0000;
        }
    </style>
@endpush





