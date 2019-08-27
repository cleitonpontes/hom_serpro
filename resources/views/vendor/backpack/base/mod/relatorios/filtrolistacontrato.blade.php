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
                    <div class="box box-solid box-warning">
                        <div class="box-header with-border">
                            <i class="fa fa-search"></i>
                            <h3 class="box-title">Filtro - {{ $data['title'] }}</h3>

                            <div class="box-tools pull-right">
                                {{--<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>--}}
                                {{--</button>--}}
                                {{--<button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>--}}
                            </div>
                        </div>
                        <div class="box-body">
                            <fieldset class="form-group">
                                <div class="col-md-6">
                                    {!! form_row($form->tipo_contrato) !!}
                                </div>
                                <div class="col-md-6">
                                    {!! form_row($form->numero) !!}
                                </div>
                                <div class="col-md-12">
                                    {!! form_end($form) !!}
                                </div>
                            </fieldset>
                        </div>

                    </div>
                </section>
            </div>
        </div>
    </div>

@endsection
@push('after_scripts')

    <script type="text/javascript">
        $(window).on('load', function () {
            var value = $("#tipo_contrato option:selected").text();

            if (value == 'Empenho') {
                mascaraEmpenho('#numero');
            } else {
                mascaraContrato('#numero');
            }

        });

        $(document).on('change', '#tipo_contrato', function () {

            var value = $("#tipo_contrato option:selected").text();

            if (value == 'Empenho') {
                mascaraEmpenho('#numero');
            } else {
                mascaraContrato('#numero');
            }

        });

    </script>
@endpush

