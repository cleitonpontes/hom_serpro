<div id="saveActions" class="form-group">

    <input type="hidden" name="save_action" value="{{ $saveAction['active']['value'] }}">
    <div class="btn-group" id="botoes_instrumentoinicial">

        <button type="button" class="btn btn-success" id="btn-submit-itens-contrato">
            <span class="fa fa-save" role="presentation" aria-hidden="true"></span> &nbsp;
            <span data-value="{{ $saveAction['active']['value'] }}">{{ $saveAction['active']['label'] }}</span>
        </button>

        <button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aira-expanded="false">
            <span class="caret"></span>
            <span class="sr-only">&#x25BC;</span>
        </button>

        <ul class="dropdown-menu">
            @foreach( $saveAction['options'] as $value => $label)
                <li><a href="javascript:void(0);" data-value="{{ $value }}">{{ $label }}</a></li>
            @endforeach
        </ul>

    </div>

    <a href="" class="btn btn-info" id="prev_aba"><span class="fa fa-arrow-circle-left"></span> &nbsp;Aba Anterior</a>
    <a href="" class="btn btn-info" id="next_aba">Próxima Aba <span class="fa fa-arrow-circle-right"></span></a>
    <a href="{{ $crud->hasAccess('list') ? url($crud->route) : url()->previous() }}" class="btn btn-default" id="cancelar"><span class="fa fa-ban"></span> &nbsp;{{ trans('backpack::crud.cancel') }}</a>
</div>
@push('after_scripts')
    <script type="text/javascript">
        $(document).ready(function() {

            valor_global = 0;
            retornoAjax = 0;
            minutas_id = [];

            var maxLength = '000.000.000.000.000,0000'.length;

            $('#botoes_instrumentoinicial').hide();
            $('#cancelar').hide();
            $('#prev_aba').hide();

            habilitaDesabilitaBotoes();

            $('body').on('click','#prev_aba', function(event){
                abaAnterior(event);
            });

            $('body').on('click','#next_aba', function(event){
                proximaAba(event);
            });

            $('body').on('click','#dadoscontrato', function(event){
                $('#botoes_instrumentoinicial').hide();
                $('#cancelar').hide();
                $('#prev_aba').hide();
                $('#next_aba').show();
            });

            $('body').on('click','#caracteristicasdocontrato', function(event){
                $('#botoes_instrumentoinicial').hide();
                $('#cancelar').hide();
                $('#prev_aba').show();
                $('#next_aba').show();
            });

            $('body').on('click','#itensdocontrato', function(event){
                $('#botoes_instrumentoinicial').hide();
                $('#cancelar').hide();
                $('#prev_aba').show();
                $('#next_aba').show();
            });

            $('body').on('click','#vigenciavalores', function(event){
                $('#botoes_instrumentoinicial').show();
                $('#cancelar').show();
                $('#prev_aba').show();
                $('#next_aba').hide();
            });

            $('body').on('change','#select2_ajax_multiple_minutasempenho', function(event){
                carregaitens(event, minutas_id);
            });

            $('body').on('focusout','input[name=data_assinatura]', function(event){
                atualizarDataInicioItens();
            });

            $("[name='minutasempenho[]']").on('change',function(event){
                minutas_id = [];
                minutas_id = retornaMinutaIds();
            });

            $(document).on('change', '#select2_ajax_multiple_minutasempenho', function () {
                if (!null_or_empty("#select2_ajax_multiple_minutasempenho")) {
                    buscarCamposAutoPreenchimento();
                }

                if (null_or_empty("#select2_ajax_multiple_minutasempenho")) {
                    // resetar os campos
                    $('select[name=unidadecompra_id]').val('').change();
                    $('select[name=modalidade_id]').val('').change();
                    $('#select2_ajax_multiple_amparoslegais').val('').change();
                    $('#licitacao_numero').val('');
                }
            });
        });

        //atualiza o valor da parcela do contrato
        function atualizarValorParcela()
        {

            valor_global = $('#valor_global').val();
            numero_parcelas = $('#num_parcelas').val();

            $('#valor_parcela').val(valor_global / numero_parcelas);
        }

        // verifica se o array esta nulo ou vazio
        function null_or_empty(str)
        {
            var v = $(str).val();
            if (v === null || v.length == 0) {
                return true;
            }
            return false;
        }

        //busca a modalidade de acordo com a primeira minuta de empenho selecionada para popular os campos
        function buscarCamposAutoPreenchimento()
        {
            var arrayMinutas = $("#select2_ajax_multiple_minutasempenho").val();

            var url = "{{route('buscar.campos.contrato.empenho',':id')}}";
            url = url.replace(':id', arrayMinutas[0]);
            axios.request(url)
                .then(response => {
                    var dadosCampos = response.data;

                    if(dadosCampos){
                        // altera campo unidade de compra
                        $('select[name=unidadecompra_id]').append(`<option value="${dadosCampos.unidade_id}">${dadosCampos.codigo} -  ${dadosCampos.nomeresumido}</option>`);
                        $('select[name=unidadecompra_id]').val(dadosCampos.unidade_id).change();

                        //altera campo de modalidade da licitacao
                        $("select[name=modalidade_id]").val(dadosCampos.modalidade_id).change();
                        $('#select2-select2_ajax_unidadecompra_id-container .select2-selection__placeholder').remove();

                        // altera campo de amparos legais
                        $('#select2_ajax_multiple_amparoslegais option').remove();
                        $('#select2_ajax_multiple_amparoslegais').append(`<option value="${dadosCampos.amparo_legal_id}">${dadosCampos.ato_normativo} - Artigo: ${dadosCampos.artigo}</option>`);
                        $('#select2_ajax_multiple_amparoslegais').val(dadosCampos.amparo_legal_id).change();

                        // altera campo de numero/ano da licitacao
                        $("#licitacao_numero").val(dadosCampos.compra_numero_ano);
                    }
                })
                .catch(error => {
                    alert(error);
                })
                .finally()
            event.preventDefault()
        }

        function atualizarDataInicioItens(){
            $("#table-itens").find('tr').each(function(){
                if ($(this).find('td').eq(7).find('input').val() === "") {
                    $(this).find('td').eq(7).find('input').val($('input[name=data_assinatura]').val());
                }
            });
        }

        function verificaAbaAtiva() {
            var divTabs = $('#form_tabs');
            var ul = divTabs.find('ul');
            var a = ul.find('li').find('a');
            let nomeAba;
            let li;

            a.each(function () {
                aba = $(this).parent();
                if (aba.attr('class') == 'active') {
                    nomeAba = aba.find('a');
                }
            });
            return nomeAba;
        }

        function retornaMinutaIds(){
            var selected = $("[name='minutasempenho[]']").find(':selected');
            var array_minutas_id = [];
            selected.each(function (index,option){
                array_minutas_id[index] = option.value;
            })
            return array_minutas_id;
        }

        function carregaitens(event,minutas_id) {

            $("#table-itens tr").remove();
            if(minutas_id.length > 0) {
                var url = "{{route('buscar.itens.instrumentoinicial',':minutas_id')}}";

                url = url.replace(':minutas_id', minutas_id);

                axios.request(url)
                    .then(response => {
                        var itens = response.data;
                        var qtd_itens = itens.length;
                        itens.forEach(function (item) {
                            var linhas = $("#table-itens tr").length;
                            if(qtd_itens > linhas){
                                adicionaLinhaItem(item);
                            }
                        });
                        minutas_id = [];
                    })
                    .catch(error => {
                        alert(error);
                    })
                    .finally()
                event.preventDefault()
            }
            atualizarValorTotal();
        }

        function habilitaDesabilitaBotoes(){

            nomeAba = verificaAbaAtiva();

            switch (nomeAba.attr('id')) {
                case 'dadoscontrato':
                    $('#botoes_instrumentoinicial').hide();
                    $('#cancelar').hide();
                    $('#prev_aba').hide();
                    $('#next_aba').show();
                    break;
                case 'caracteristicasdocontrato':
                    $('#botoes_instrumentoinicial').hide();
                    $('#cancelar').hide();
                    $('#prev_aba').show();
                    $('#next_aba').show();
                    break;
                case 'itensdocontrato':
                    $('#botoes_instrumentoinicial').hide();
                    $('#cancelar').hide();
                    $('#prev_aba').show();
                    $('#next_aba').show();
                    break;
                case 'vigenciavalores':
                    $('#botoes_instrumentoinicial').show();
                    $('#cancelar').show();
                    $('#prev_aba').show();
                    $('#next_aba').hide();
                    break;
            }
        }

        function proximaAba(event){
            event.preventDefault();
            nomeAba = verificaAbaAtiva();

            switch (nomeAba.attr('id')) {
                case 'dadoscontrato':
                    $('#caracteristicasdocontrato').click();
                    break;
                case 'caracteristicasdocontrato':
                    $('#itensdocontrato').click();
                    break;
                case 'itensdocontrato':
                    $('#vigenciavalores').click();
                    break;
                case 'vigenciavalores':
                    break;
            }
        }

        function abaAnterior(event){
            event.preventDefault();
            nomeAba = verificaAbaAtiva();

            switch (nomeAba.attr('id')) {
                case 'dadoscontrato':
                    break;
                case 'caracteristicasdocontrato':
                    $('#dadoscontrato').click();
                    break;
                case 'itensdocontrato':
                    $('#caracteristicasdocontrato').click();
                    break;
                case 'vigenciavalores':
                    $('#itensdocontrato').click();
                    break;
            }
        }

        /**
         * retira a propriedade disabled para os campos serem submetidos
         * guarda html da grid de itens em campo hidden
         */
        function configurarFormParaSubmit(){
            atualizaValueHTMLCamposAbaItem();
            var htmlGridItem = $('#table').html();
            $('input[name=adicionaCampoRecuperaGridItens]').val(htmlGridItem);
        }
    </script>
    <script src="{{ asset('js/mensagem/confirmacaoPublicacao.js')}}"></script>
@endpush
