<div class="modal fade" tabindex="-1" role="dialog" id="modal-importacao-terceirizado">
    <div class="modal-dialog" role="document">
        <div class="modal-content" id="modal-content">
            <form
            action="{{ $routeAction }}"
            enctype="multipart/form-data"
            method="post"
            id="form_importacao_terceirizado"
            >
                {!! csrf_field() !!}
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
                    <span>
                        <h4 class="modal-title" style="float: left">Importação de terceirizados</h4>
                        <a href="#" style="float: right;margin-right: 15px;">
                            <span class="fa fa-question" title="Ajuda"/>
                        </a>
                    </span>
                </div>
                <div class="modal-body">
                    <div class="form-group input-form">
                        <label class="input-required">Delimitador</label>
                        <input
                            type="text"
                            name="delimitador"
                            value=""
                            class="form-control"
                            id="input-delimitador"
                        >
                    </div>
                    <div class="form-group input-form">
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
                        <span id="mensagem-validacao" style="color: red; font-weight: bold"></span>
                    </div>
                    <div id="progressbar">

                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal" id="botao-cancelar"><span class="fa fa-ban"></span>
                        &nbsp;Cancelar
                    </button>
                    <button type="button" class="btn btn-success" id="botao-salvar">
                        <span class="fa fa-save" role="presentation" aria-hidden="true"></span> &nbsp;
                        <span>Salvar</span>
                    </button>
                </div>
            </form>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

@push('after_scripts')
    <script type="text/javascript">
        $(document).ready(function(){
            //valida a extensao do arquivo
            $("#arquivos_file_input").change(function () {
                var fileInput = $(this);
                var extPermitidas = ['txt'];

                if(typeof extPermitidas.find(function(ext){ return fileInput.val().split('.').pop() == ext; }) == 'undefined') {
                    $('#mensagem-validacao').text('A única extensão permitida é (.txt)');
                    $('#mensagem-validacao').show();
                    $('#botao-salvar').attr('disabled', 'disabled');
                } else {
                    $('#mensagem-validacao').hide();
                    $('#botao-salvar').removeAttr('disabled');
                }
            });

            $(function(){
                /***************ATRIBUI EVENTOS DE VALIDAÇÃO PARA O INPUT DE DELIMITADOR***************************/
                var inputDelimitador = document.querySelector('#input-delimitador');
                var funcMaxCharacter = maxCharacater(1);
                inputDelimitador.addEventListener('keyup', funcMaxCharacter);
                inputDelimitador.addEventListener('blur', funcMaxCharacter);
                /***********************************************************************************************/

                //funcao para limitar o input de delimitador para um character
                function maxCharacater(length)
                {
                    var running = false;

                    return function () {
                        //Para evitar conflito entre o blur e o keyup
                        if (running) return;
                        //
                        running = true;
                        //Se o input for maior que length seta o input com o primeiro character digitado
                        if (this.value.length > length) {
                            this.value = this.value.charAt(0);
                        }
                        //Habilita novamente as chamadas do blur e keyup
                        running = false;
                    };
                }
            });

            $('#botao-salvar').on('click', function (){
                if(!verificarCampoModalItem()){
                    $('#botao-salvar').attr('disabled', 'disabled');
                    $('#botao-cancelar').attr('disabled', 'disabled');
                    $('#progressbar').html(
                        "{!! ProgressBar::normal(100)->animated() !!}" +
                        "<div><span>Carregando...</span></div>"
                    );
                    $('.input-form').hide();
                    this.closest('form').submit();
                }
            });

            function verificarCampoModalItem(){
                var hasError = false;

                    $('#input-delimitador').closest('.form-group').removeClass('has-error');
                    $('#arquivos_file_input').closest('.form-group').removeClass('has-error');

                    if(!$('#input-delimitador').val()){
                        $('#input-delimitador').closest('.form-group').addClass('has-error');
                        hasError = true;
                    }
                    if(!$('#arquivos_file_input').val()){
                        $('#arquivos_file_input').closest('.form-group').addClass('has-error');
                        hasError = true;
                    }

                return hasError;
            }
        });
    </script>
    <style type="text/css">
        .input-required::after {
            content: ' *';
            color: #ff0000;
        }
    </style>
@endpush





