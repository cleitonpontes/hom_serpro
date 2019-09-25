@extends('backpack::layout')

@section('header')
    <section class="content-header">
        <h1>
            {{ $data['title'] }}
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
                            <i class="fa fa-search"></i>
                            <h3 class="box-title">Filtro</h3>

                            <div class="box-tools pull-right">
                                <a href="{{route($data['relatorio_route'])}}" class="btn btn-box-tool" title="Voltar">
                                    <i class="fa fa-times"></i>
                                </a>
                            </div>
                        </div>
                        <div class="box-body">
                            <fieldset class="form-group">
                                {!! form($form) !!}
{{--                                {!! form_start($form) !!}--}}
{{--                                <div class="col-md-6">--}}
{{--                                    {!! form_row($form->tipo_contrato) !!}--}}
{{--                                </div>--}}
{{--                                <div class="col-md-6">--}}
{{--                                    {!! form_row($form->numero) !!}--}}
{{--                                </div>--}}
{{--                                <div class="col-md-12">--}}
{{--                                    {!! form_end($form) !!}--}}
{{--                                </div>--}}
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

