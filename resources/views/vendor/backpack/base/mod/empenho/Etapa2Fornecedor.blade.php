@extends('backpack::layout')

@section('header')
    <section class="content-header">
        <h1>
            Minuta
            <small>Empenho</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ backpack_url() }}">{{ config('backpack.base.project_name') }}</a></li>
            <li>Minuta</li>
            <li class="active">Empenho</li>
        </ol>
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
    <div class="flash-message">
        @if(isset($uasg_inativa))
            <p class="alert alert-warning">{{$uasg_inativa}}</p>
        @endif
    </div>
    <div class="box box-solid box-primary">
        <div class="box-header with-border">
            <h3 class="box-title">Fornecedores da Compra</h3>
        </div>

        <div class="box-body">
            <div class="box-tools">
{{--                {!! Button::primary('<i class="fa fa-plus"></i> Novo Empenho')--}}
{{--                    ->asLinkTo(route('empenho'))--}}
{{--                !!}--}}
                <div class="btn-group">
{{--                    {!! DropdownButton::normal('<i class="fa fa-gear"></i> Exportação')->withContents([--}}
{{--                        ['url' => '/admin/downloadapropriacao/xlsx', 'label' => '<i class="fa fa-file-excel-o"></i> xlsx '],--}}
{{--                        ['url' => '/admin/downloadapropriacao/xls', 'label' => '<i class="fa fa-file-excel-o"></i> xls '],--}}
{{--                        ['url' => '/admin/downloadapropriacao/csv', 'label' => '<i class="fa fa-file-text-o"></i> csv ']--}}
{{--                ])->split() !!}--}}
                </div>
            </div>

            <br/>
            <div class="col-sm-12">
                {!! $html->table() !!}
            </div>
        </div>
    </div>

@endsection

@push('after_scripts')
    {!! $html->scripts() !!}
    <script type="text/javascript">

        $('#uasg_compra').select2({
            placeholder: "Choose tags...",
            minimumInputLength: 2,
            ajax: {
                url: '/tags/find',
                dataType: 'json',
                data: function (params) {
                    return {
                        q: $.trim(params.term)
                    };
                },
                processResults: function (data) {
                    return {
                        results: data
                    };
                },
                cache: true
            }
        });

    </script>
@endpush
