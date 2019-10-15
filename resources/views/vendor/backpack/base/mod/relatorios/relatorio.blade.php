@extends('backpack::layout')

@section('header')
    <section class="content-header">
        <h1>
            Relatório - {{ $data['title'] }}
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ backpack_url() }}">{{ config('backpack.base.project_name') }}</a></li>
            <li>Relatórios</li>
            <li class="active">{{ $data['title'] }}</li>
        </ol>
    </section>
@endsection


@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="row">
                <section class="col-lg-12 connectedSortable ui-sortable">
                    <div class="box box-solid box-primary">
                        <div class="box-header with-border">
                            <i class="fa fa-table"></i>
                            <h3 class="box-title">Dados</h3>
                            <div class="box-tools pull-right">
                                {{--<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>--}}
                                {{--</button>--}}
                                {{--<button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>--}}
                            </div>
                        </div>
                        <div class="box-body">
                            <div class="box-tools">
{{--                                {!!--}}
{{--                                Button::primary('<i class="fa fa-search"></i> Filtrar')--}}
{{--                                        ->asLinkTo(route($data['filtro_route']))--}}
{{--                                !!}--}}
                                <div class="btn-group">
                                    {!! DropdownButton::normal('<i class="fa fa-gear"></i> Exportação')->withContents([
                                        ['url' => route($data['download_route'],['type' => 'xlsx']), 'label' => '<i class="fa fa-file-excel-o"></i> xlsx '],
                                        ['url' => route($data['download_route'],['type' => 'xls']), 'label' => '<i class="fa fa-file-excel-o"></i> xls '],
                                        ['url' => route($data['download_route'],['type' => 'csv']), 'label' => '<i class="fa fa-file-text-o"></i> csv ']
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
            </div>
        </div>
    </div>

@endsection
@push('after_scripts')

    {!! $dataTable->scripts() !!}

@endpush

