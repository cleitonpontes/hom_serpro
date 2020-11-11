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
                    <div class="col-md-9" align="right">
                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#inserir_celular_orcamentaria">
                            Inserir Célula Orçamentária <i class="fa fa-plus"></i>
                        </button>
                    </div>
                </div>
            </div>
            <br/>

                {!! $html->table() !!}

            <div class="box-tools" align="right">
                <div class="row">

                    <div class="box-tools col-md-6" align="left">

                            {!! Button::danger('<i class="fa fa-arrow-left"></i> Voltar')
                                ->asLinkTo(route('empenho.minuta.etapa.item',['minuta_id' => $minuta_id,'fornecedor_id'=> $fornecedor_id]))
                            !!}

                            <button type="submit" class="btn btn-success">
                                Próxima Etapa <i class="fa fa-arrow-right"></i>
                            </button>

                    </div>
                    <div class="col-md-6" align="right">
                        <button type="button" class="btn btn-primary" id="atualiza_saldo">
                            Atualizar todos os Saldos  <i class="fa fa-refresh"></i>
                        </button>
                    </div>

                </div>
            </div>
            </form>
        </div>
    </div>

    <!-- Preloader -->
    <section>
        <div id="preloader">
            <div id="ctn-preloader" class="loaded">
                <div class="animation-preloader col-md-6">
                    <div class="spinner"></div>
                    <div class="txt-loading">
						<span data-text-preloader="C" class="letters-loading">
							C
						</span>

                        <span data-text-preloader="O" class="letters-loading">
							O
						</span>

                        <span data-text-preloader="M" class="letters-loading">
							M
						</span>

                        <span data-text-preloader="P" class="letters-loading">
							P
						</span>

                        <span data-text-preloader="R" class="letters-loading">
							R
						</span>

                        <span data-text-preloader="A" class="letters-loading">
							A
						</span>

                        <span data-text-preloader="S" class="letters-loading">
							S
						</span>
                    </div>
                </div>
                <div class="loader-section section-left"></div>
                <div class="loader-section section-right"></div>
            </div>
        </div>
    </section>

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
                    <button type="button" class="close" id="fechar_modal" data-dismiss="modal" aria-label="Fechar">
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
@section('after_styles')
    <style media="screen">
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            background: url('https://images.pexels.com/photos/1036857/pexels-photo-1036857.jpeg?auto=compress&cs=tinysrgb&dpr=2&h=750&w=1260') #3C8DBC center center / cover no-repeat fixed;
            /*background: url('http://localhost:8080/img/conta_nome.png?auto=compress&cs=tinysrgb&dpr=2&h=750&w=1260') #1c1c1c center center / cover no-repeat fixed;*/
            height: 200vh;
        }

        .no-scroll-y {
            overflow-y: hidden;
        }

        /* Preloader */
        .ctn-preloader {
            align-items: center;
            cursor: none;
            display: flex;
            height: 100%;
            justify-content: center;
            position: fixed;
            left: 0;
            top: 0;
            width: 100%;
            z-index: 900;
        }

        .ctn-preloader .animation-preloader {
            position: absolute;
            z-index: 100;
        }

        /* Spinner cargando */
        .ctn-preloader .animation-preloader .spinner {
            animation: spinner 1s infinite linear;
            border-radius: 50%;
            /*border: 3px solid rgba(204, 232, 243, 10.50);*/
            border: 3px solid #CCE8F3;
            border-top-color: #3C8DBC; /* No se identa por orden alfabetico para que no lo sobre-escriba */
            height: 9em;
            margin: 0 auto 3.5em auto;
            width: 9em;
        }

        /* Texto cargando */
        .ctn-preloader .animation-preloader .txt-loading {
            font: bold 5em 'Montserrat', sans-serif;
            text-align: center;
            user-select: none;
        }

        .ctn-preloader .animation-preloader .txt-loading .letters-loading:before {
            animation: letters-loading 4s infinite;
            color: #3C8DBC;
            content: attr(data-text-preloader);
            left: 0;
            opacity: 0;
            position: absolute;
            top: 0;
            transform: rotateY(-90deg);
        }

        .ctn-preloader .animation-preloader .txt-loading .letters-loading {
            color: #DAEAF2;
            /*color: rgba(0, 0, 0, 0.2);*/
            position: relative;
        }

        .ctn-preloader .animation-preloader .txt-loading .letters-loading:nth-child(2):before {
            animation-delay: 0.2s;
        }

        .ctn-preloader .animation-preloader .txt-loading .letters-loading:nth-child(3):before {
            animation-delay: 0.4s;
        }

        .ctn-preloader .animation-preloader .txt-loading .letters-loading:nth-child(4):before {
            animation-delay: 0.6s;
        }

        .ctn-preloader .animation-preloader .txt-loading .letters-loading:nth-child(5):before {
            animation-delay: 0.8s;
        }

        .ctn-preloader .animation-preloader .txt-loading .letters-loading:nth-child(6):before {
            animation-delay: 1s;
        }

        .ctn-preloader .animation-preloader .txt-loading .letters-loading:nth-child(7):before {
            animation-delay: 1.2s;
        }

        .ctn-preloader .loader-section {
            background-color: #ffffff;
            height: 100%;
            position: fixed;
            top: 0px;
            opacity: 0.5;
            width: calc(50% + 1px);
        }

        .ctn-preloader .loader-section.section-left {
            left: 0;
        }

        .ctn-preloader .loader-section.section-right {
            right: 0;
        }

        /* Efecto de fade en la animación de cargando */
        .loaded .animation-preloader {
            opacity: 0;
            transition: 0.3s ease-out;
        }

        /* Efecto de cortina */
        .loaded .loader-section.section-left {
            transform: translateX(-101%);
            transition: 0.7s 0.3s all cubic-bezier(0.1, 0.1, 0.1, 1.000);
        }

        .loaded .loader-section.section-right {
            transform: translateX(101%);
            transition: 0.7s 0.3s all cubic-bezier(0.1, 0.1, 0.1, 1.000);
        }

        /* Animación del preloader */
        @keyframes spinner {
            to {
                transform: rotateZ(360deg);
            }
        }

        /* Animación de las letras cargando del preloader */
        @keyframes letters-loading {
            0%,
            75%,
            100% {
                opacity: 0;
                transform: rotateY(-90deg);
            }

            25%,
            50% {
                opacity: 1;
                transform: rotateY(0deg);
            }
        }

        /* Tamaño de portatil hacia atras (portatil, tablet, celular) */
        @media screen and (max-width: 767px) {
            /* Preloader */
            /* Spinner cargando */
            .ctn-preloader .animation-preloader .spinner {
                height: 8em;
                width: 8em;
            }

            /* Texto cargando */
            .ctn-preloader .animation-preloader .txt-loading {
                font: bold 3.5em 'Montserrat', sans-serif;
            }
        }

        @media screen and (max-width: 500px) {
            /* Prelaoder */
            /* Spinner cargando */
            .ctn-preloader .animation-preloader .spinner {
                height: 7em;
                width: 7em;
            }

            /* Texto cargando */
            .ctn-preloader .animation-preloader .txt-loading {
                font: bold 2em 'Montserrat', sans-serif;
            }
        }
    </style>
@endsection
@push('after_scripts')
    {!! $html->scripts() !!}
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <script type="text/javascript">

        $(document).ready(function(){
            // $('body').addClass('no-scroll-y');
            var maxLength = '000.000.000.000.000,00'.length;

            $('body').on('click','#atualiza_saldo', function(event){
                $('#ctn-preloader').addClass('ctn-preloader');
                $('#ctn-preloader').removeClass('loaded');
                $('body').addClass('no-scroll-y');
                atualizaSaldosPorUnidade(event);
            });


            $('body').on('click','button[name^="atualiza_saldo_acao_"]',function (event){
                var saldo_id = this.id;
                atualizaLinhadeSaldo(event,saldo_id);
            });


            $('body').on('change','#cb_unidade', function(event){
                recarregaTabeladeSaldos(event);
            });

            $('body').on('click','#btn_inserir', function(event){
                if(valida_form()){
                    inserirCelulaOrcamentaria(event);
                    $('#fechar_modal').trigger('click');
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

        function atualizaLinhadeSaldo(event,saldo_id){

            var url = "{{route('atualiza.saldos.linha',':saldo_id')}}";
            url = url.replace(':saldo_id',saldo_id);
            axios.request(url)
                .then(response => {
                    dados = response.data
                    // alert(dados);
                    if(dados == true) {
                        Swal.fire({
                            position: 'top-end',
                            icon: 'success',
                            title: 'Célula Orçamentária Atualizada com sucesso!',
                            showConfirmButton: false,
                            timer: 1500
                        })
                        var table = $('#dataTableBuilder').DataTable();
                        table.ajax.reload();
                    }else{
                        Swal.fire({
                            position: 'top-end',
                            icon: 'warning',
                            title: 'O saldo está atualizado!',
                            showConfirmButton: false,
                            timer: 1500
                        })
                    }
                })
                .catch(error => {
                    alert(error);
                })
                .finally()
            event.preventDefault()
        }


        function preloader(){
            setTimeout(function() {
                $('#ctn-preloader').addClass('loaded');
                // Una vez haya terminado el preloader aparezca el scroll
                $('body').removeClass('no-scroll-y');

                if ($('#ctn-preloader').hasClass('loaded')) {
                    // Es para que una vez que se haya ido el preloader se elimine toda la seccion preloader
                    $('#preloader').delay(1000).queue(function() {
                        $(this).remove();
                    });
                }
            }, 3000);
        }

        function atualizaSaldosPorUnidade(event){

            var unidade = $('#cb_unidade :selected').text().substring(0,6);

            var url = "{{route('atualiza.saldos.unidade',':cod_unidade')}}";
            url = url.replace(':cod_unidade',unidade);
            axios.request(url)
                .then(response => {
                    dados = response.data
                    if(dados == true) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Atualização dos saldos concluída com sucesso!',
                            showConfirmButton: true
                        })
                        preloader();
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

        function inserirCelulaOrcamentaria(event){

            var unidade = $('#cb_unidade :selected').text().substring(0,6);
            var contacorrente = $('#esfera').val();
            contacorrente += $('#ptrs').val();
            contacorrente += $('#fonte').val();
            contacorrente += $('#natureza_despesa').val();
            if(!$('#urg').val()){
                contacorrente += '        '
            }else{
                contacorrente += $('#urg').val();
            }
            contacorrente += $('#plano_interno').val();

            var url = "{{route('saldo.inserir.modal',[':cod_unidade',':contacorrente'])}}";
            var url2 = url.replace(':cod_unidade',unidade);
            url = url2.replace(':contacorrente',contacorrente);

            axios.request(url)
                .then(response => {
                    dados = response.data
                    console.log(dados.resultado);
                    if(dados.resultado == true) {
                        Swal.fire({
                            position: 'top-end',
                            icon: 'success',
                            title: 'Célula Orçamentária incluída com sucesso!',
                            showConfirmButton: false,
                            timer: 1500
                        })
                        var table = $('#dataTableBuilder').DataTable();
                        table.ajax.reload();
                    }else if(dados.resultado == null){
                        Swal.fire({
                            icon: 'warning',
                            title: 'Célula Orçamentária não encontrada!',
                            showConfirmButton: true,
                            footer: '<b>Verifique os dados enviados!</b>'
                        })
                    }else if(dados.resultado == false){
                        Swal.fire({
                            icon: 'warning',
                            title: 'Célula Orçamentária já existe!',
                            showConfirmButton: true,
                            footer: '<b>Insira outra Célula Orçamentário!</b>'
                        })
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

            var url = "{{route('carrega.saldos.unidade',':cod_unidade')}}";
            url = url.replace(':cod_unidade',unidade);
            axios.request(url)
                .then(response => {
                    dados = response.data
                    if(dados == true) {
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
