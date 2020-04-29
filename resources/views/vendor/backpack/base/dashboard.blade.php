@extends('backpack::layout')

@section('header')
    <section class="content-header">
        <h1>
            Início
            <small>Comprasnet Contratos</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ backpack_url() }}">{{ config('backpack.base.project_name') }}</a></li>
            <li class="active">Início</li>
        </ol>
    </section>
@endsection

@section('content')
    <!-- Linha 1 -->
    <div class="row">
        <div class="col-lg-3 col-xs-6">
            <!-- small box -->
            <div class="small-box bg-aqua">
                <div class="inner">
                    <h3>{{$html['novos']}}</h3>

                    <p>Novos Contratos inseridos</p>
                </div>
                <div class="icon">
                    <i class="ion ion-ios-cloud-download"></i>
                </div>
                <a href="/gescon/contrato" class="small-box-footer">Ver contratos <i
                        class="fa fa-arrow-circle-right"></i></a>
            </div>
        </div>
        <div class="col-lg-3 col-xs-6">
            <!-- small box -->
            <div class="small-box bg-green">
                <div class="inner">
                    <h3>{{$html['atualizados']}}</h3>

                    <p>Contratos Atualizados</p>
                </div>
                <div class="icon">
                    <i class="ion ion-ios-refresh"></i>
                </div>
                <a href="/gescon/contrato" class="small-box-footer">Ver contratos <i
                        class="fa fa-arrow-circle-right"></i></a>
            </div>
        </div>
        <div class="col-lg-3 col-xs-6">
            <!-- small box -->
            <div class="small-box bg-red">
                <div class="inner">
                    <h3>{{$html['vencidos']}}</h3>

                    <p>Contratos vencidos</p>
                </div>
                <div class="icon">
                    <i class="ion ion-ios-close"></i>
                </div>
                <a href="/gescon/contrato" class="small-box-footer">Ver contratos <i
                        class="fa fa-arrow-circle-right"></i></a>
            </div>
        </div>
        @php
            $totalmsg = backpack_user()->unreadNotifications()->count() ?? 0;
        @endphp
        <div class="col-lg-3 col-xs-6">
            <!-- small box -->
            <div class="small-box bg-yellow">
                <div class="inner">
                    <h3>{{$totalmsg}}</h3>
                    <p>Mensagens pendentes</p>
                </div>
                <div class="icon">
                    <i class="ion ion-ios-email"></i>
                </div>
                <a href="/mensagens" class="small-box-footer">Ler agora <i class="fa fa-arrow-circle-right"></i></a>
            </div>
        </div>
    </div>

    <!-- Linha 2 -->
    <div class="row">
        <section class="col-lg-7 connectedSortable ui-sortable">
            <div class="box box-solid">
                <div class="box-header with-border">
                    <i class="fa fa-bar-chart"></i>
                    <h3 class="box-title">Empenhos sem Contrato: {{ $ug }}</h3>

                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse">
                            <i class="fa fa-minus"></i>
                        </button>
                        <button type="button" class="btn btn-box-tool" data-widget="remove">
                            <i class="fa fa-times"></i>
                        </button>
                    </div>
                </div>

                <div class="box-body">
                    <div class="overflow-hidden">
                        {!! $gridEmpenhos->table() !!}
                    </div>
                </div>
            </div>
        </section>

        <section class="col-lg-5 connectedSortable ui-sortable">
            <div class="box box-solid">
                <div class="box-header ui-sortable-handle with-border" style="cursor: move;">
                    <i class="fa fa-calendar"></i>

                    <h3 class="box-title">Calendário</h3>
                    <!-- tools box -->
                    <div class="pull-right box-tools">
                        <!-- button with a dropdown -->
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                        </button>
                        <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                    </div>
                    <!-- /. tools -->
                </div>
                <!-- /.box-header -->
                <div class="box-body no-padding">
                    <!--The calendar -->
                    {!! $calendar->calendar() !!}
                    @push('after_scripts')
                        {!! $calendar->script() !!}
                    @endpush
                </div>
                <!-- /.box-body -->
            </div>
        </section>
    </div>

    <!-- Linha 3 -->
    <div class="row">
        <div class="col-md-12">
            <div class="row">
                <section class="col-lg-7 connectedSortable ui-sortable">
                </section>
                <section class="col-lg-5 connectedSortable ui-sortable">
                    <div class="box box-solid">
                        <div class="box-header with-border">
                            <i class="fa fa-bar-chart"></i>
                            <h3 class="box-title">Contratos por Categoria</h3>

                            <div class="box-tools pull-right">
                                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                                </button>
                                <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                            </div>
                        </div>
                        <div class="box-body">
                            {!! $chartjs->render() !!}
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </div>

    <!-- #Modal -->
    <div class="modal fade contratoNaoInformado" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <h4 class="modal-title ">
                        Empenhos sem Contrato: {{ $ug }}
                    </h4>
                </div>
                <div class="modal-body">
                    <p>
                        Favor selecionar um contrato.
                    </p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default alert-warning" data-dismiss="modal">
                        Fechar
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('after_scripts')
    {!! $gridEmpenhos->scripts() !!}

    <script type="text/javascript">
        $('body').delegate('.contrato', 'click', function() {
            var empenho = $(this).data('ne');
            var fornecedor = $(this).data('fornecedor');
            var contrato = $('#' + empenho).val();
            var url = '/api/empenho/sem/contrato/' + empenho + '/' + fornecedor + '/' + contrato;
            var linha = '#linha_' + empenho;

            if (contrato == '') {
                $('.contratoNaoInformado').modal('show');
                return false;
            }

            $.ajax({
                type: 'PUT',
                dataType: 'text',
                headers: {
                    // Passagem do token no header
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: '/api/empenho/sem/contrato/e/' + empenho + '/f/' + fornecedor + '/c/' + contrato,
                success: function(retorno) {
                    $(linha).remove();
                },
                error: function(e) {
                    // alert('Erro...');
                }
            });
        });
    </script>
@endpush
