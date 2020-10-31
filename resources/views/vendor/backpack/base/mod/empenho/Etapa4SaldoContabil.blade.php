@extends('backpack::layout')

@section('header')
    <section class="content-header">
        <h1>
            Saldo Contábil
            <small></small>
        </h1>
    </section>
@endsection

@section('content')
    @include('backpack::mod.empenho.telas.cabecalho')
    <div class="flash-message">
        @foreach (['danger', 'warning', 'success', 'info'] as $msg)
            @if(Session::has('alert-' . $msg))
                <p class="alert alert-{{ $msg }}">{{ Session::get('alert-' . $msg) }}</p>
            @endif
        @endforeach
    </div>
    <div class="box box-solid box-primary">
        <div class="box-header with-border">
            <h3 class="box-title">Saldos Contábeis</h3>
        </div>

        <div class="box-body">
            <form action="{{route('empenho.minuta.atualizar.saldo')}}" method="post">
            @csrf <!-- {{ csrf_field() }} -->
                <div class="col-sm-12">
                    <input type="hidden" id="etapa_id" name="etapa_id" value="{{$etapa_id}}">
                    <input type="hidden" id="minuta_id" name="minuta_id" value="{{$minuta_id}}">
                </div>

            <div class="box-tools" align="right">
                <div class="row">
                    <div class="col-md-3" align="left">
                        <label for="cb_unidade"> UG Emitente </label>
                        <select name="cb_unidade" id="cb_unidade">
                            @foreach($unidades as $key => $unidade)
                                @if($key == $modUnidade->id)
                                    <option value="{{$key}}" selected>{{$unidade}}</option>
                                @else
                                    <option value="{{$key}}">{{$unidade}}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#inserir_celular_orcamentaria">
                            Inserir Célula Orçamentária <i class="fa fa-plus"></i>
                        </button>
                    </div>
                    <div class="col-md-3" align="right">

                    </div>
                    <div class="col-md-3" align="left">

                    </div>
                </div>
            </div>
            <br/>

                {!! $html->table() !!}

            <div class="box-tools" align="right">
                <div class="row">
                    <div class="col-md-3" align="left">
                        {!! Button::primary('<i class="fa fa-arrow-left"></i> Voltar')
                            ->asLinkTo(route('empenho.minuta.etapa.item',['etapa_id'=> ($etapa_id - 1),'minuta_id' => $minuta_id,'fornecedor_id'=> $fornecedor_id]))
                        !!}
                    </div>
                    <div class="col-md-3">

                    </div>
                    <div class="col-md-3" align="right">
                        <button type="button" class="btn btn-primary" id="atualiza_saldo">
                            Atualizar todos os Saldos  <i class="fa fa-refresh"></i>
                        </button>
                    </div>
                    <div class="col-md-3" align="left">
                        <button type="submit" class="btn btn-primary" id="salvar">
                            Próxima Etapa  <i class="fa fa-right"></i>
                        </button>
                    </div>
                </div>
            </div>
            </form>
        </div>
    </div>

    <!-- Janela modal para inserção de registros -->
    <div id="inserir_celular_orcamentaria" tabindex="-1" class="modal fade"
         role="dialog"
         aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title">
                        Inserir Célula Orçamentária
                    </h3>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" id="textoModal">
                    <fieldset class="form-group">
                        {!! form($form) !!}
                    </fieldset>
                </div>
                <div class="modal-footer">
                </div>
            </div>
        </div>
    </div>

@endsection

@push('after_scripts')
    {!! $html->scripts() !!}
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <script type="text/javascript">

        $(document).ready(function(){
            var maxLength = '000.000.000.000.000,00'.length;

            $('body').on('click','#atualiza_saldo', function(event){
                atualizaTabeladeSaldos(event);
            });

            $('body').on('click','#atualiza_saldo_acao', function(event){
                atualizaTabeladeSaldos(event);
            });

            $('body').on('change','#cb_unidade', function(event){
                atualizaTabeladeSaldos(event);
            });

            $('body').on('click','#btn_inserir', function(event){
                if(valida_form()){
                    $('#form_modal').submit();
                }
                event.preventDefault();
            });


            $('#inserir_celular_orcamentaria').on('show.bs.modal', function(event) {
                var unidade_id = $('#cb_unidade :selected').val();
                $('#unidade_id').val(unidade_id);

                var botao = $(event.relatedTarget);
                var link = botao.data('link');

                $('#btnExcluir').attr('href', link);
            });

            $('#valor').maskMoney({
                allowNegative: false,
                thousands: '.',
                decimal: ',',
                affixesStay: false
            }).attr('maxlength', maxLength).trigger('mask.maskMoney');

        });

        function atualizaTabeladeSaldos(event){

            var unidade = $('#cb_unidade :selected').text().substring(0,6);

            var url = "{{route('atualiza.saldos.unidade',':cod_unidade')}}";
            url = url.replace(':cod_unidade',unidade);
            axios.request(url)
                .then(response => {
                    dados = response.data
                    if(dados.resultado == true) {
                        var table = $('#dataTableBuilder').DataTable();
                        table.ajax.reload();
                    }
                })
                .catch(error => {
                    alert(error);
                })
                .finally()
            event.preventDefault()
        }


        function recarregaTabeladeSaldos(event){

            var unidade = $('#cb_unidade :selected').text().substring(0,6);

            var url = "{{route('atualiza.saldos.unidade',':cod_unidade')}}";
            url = url.replace(':cod_unidade',unidade);
            axios.request(url)
                .then(response => {
                    dados = response.data
                    if(dados.resultado == true) {
                        location.reload();
                    }
                })
                .catch(error => {
                    alert(error);
                })
                .finally()
            event.preventDefault()
        }

        function somenteNumeros(e) {
            var charCode = e.charCode ? e.charCode : e.keyCode;
            // charCode 8 = backspace
            // charCode 9 = tab
            if (charCode != 8 && charCode != 9) {
                // charCode 48 equivale a 0
                // charCode 57 equivale a 9
                if (charCode < 48 || charCode > 57) {
                    return false;
                }
            }
        }

        function handleInput(e) {
            var ss = e.target.selectionStart;
            var se = e.target.selectionEnd;
            e.target.value = e.target.value.toUpperCase();
            e.target.selectionStart = ss;
            e.target.selectionEnd = se;
        }


        function null_or_empty(str) {
            var v = $(str).val();
            if(v == null || v == ""){
                return false;
            }
            return true;
        }

        function valida_form(event) {

            var vazio1 = null_or_empty("#esfera");
            var vazio2 = null_or_empty("#ptrs");
            var vazio3 = null_or_empty("#fonte");
            var vazio4 = null_or_empty("#natureza_despesa");
            var vazio6 = null_or_empty("#plano_interno");

            if (!vazio1) {
                Swal.fire('Alerta!','O campo Esfera é obrigatório!','warning');
                return false;
            }
            if (!vazio2) {
                Swal.fire('Alerta!','O campo PTRS é obrigatório!','warning');
                return false;
            }
            if (!vazio3) {
                Swal.fire('Alerta!','O campo Fonte é obrigatório!','warning');
                return false;
            }
            if (!vazio4) {
                Swal.fire('Alerta!','O campo Natureza de Despesa é obrigatório!','warning');
                return false;
            }
            if (!vazio6) {
                Swal.fire('Alerta!','O campo Plano Interno é obrigatório!','warning');
                return false;
            }
            return true;
        }
    </script>
@endpush
