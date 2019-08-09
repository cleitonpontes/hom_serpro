@extends('backpack::layout')

@section('header')
    <section class="content-header">
        <h1>
            {{ $data['title'] }}
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ backpack_url() }}">{{ config('backpack.base.project_name') }}</a></li>
            <li class="active">{{ $data['title'] }}</li>
        </ol>
    </section>
@endsection


@section('content')
    <div class="row">
        <div class="col-md-12">

            <div class="row">
                <section class="col-lg-7 connectedSortable ui-sortable">
                    <div class="box box-solid">
                        <div class="box-header with-border">
                            <i class="fa fa-bar-chart"></i>
                            <h3 class="box-title">Execução por Empenho - Outras Despesas Corrente (330000)</h3>

                            <div class="box-tools pull-right">
                                {{--<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>--}}
                                {{--</button>--}}
                                {{--<button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>--}}
                            </div>
                        </div>
                        <div class="box-body">
                            {!! $graficoEmpenhosOutrasDespesasCorrentes->render() !!}
                            <br><br>
                            <div class="box-tools">
                                <div class="btn-group">
                                    {!! DropdownButton::normal('<i class="fa fa-gear"></i> Exportação')->withContents([
                                        ['url' => '/admin/downloadexecucaoporempenho/xlsx', 'label' => '<i class="fa fa-file-excel-o"></i> xlsx '],
                                        ['url' => '/admin/downloadexecucaoporempenho/xls', 'label' => '<i class="fa fa-file-excel-o"></i> xls '],
                                        ['url' => '/admin/downloadexecucaoporempenho/csv', 'label' => '<i class="fa fa-file-text-o"></i> csv ']
                                ])->split() !!}
                                </div>
                            </div>
                            <br>
                            <div class="col-sm-12">
                                {!! $dataTable->table() !!}
                            </div>
                        </div>
                        <!-- /.box-body -->
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
                                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i
                                        class="fa fa-minus"></i>
                                </button>
                                <button type="button" class="btn btn-box-tool" data-widget="remove"><i
                                        class="fa fa-times"></i></button>
                            </div>
                            <!-- /. tools -->
                        </div>
                        <!-- /.box-header -->
                        <div class="box-body no-padding">
                            <div class="col-sm-12">

                            </div>
                        </div>
                        <!-- /.box-body -->
                    </div>
                </section>
            </div>
        </div>
    </div>

@endsection
@push('after_scripts')

    {!! $dataTable->scripts() !!}

@endpush

