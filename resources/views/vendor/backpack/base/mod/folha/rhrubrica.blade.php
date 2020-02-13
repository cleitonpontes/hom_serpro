@extends('backpack::layout')

@section('header')
    <section class="content-header">
        <h1>
            Rubrica
            <small>Comprasnet Contratos</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ backpack_url() }}">{{ config('backpack.base.project_name') }}</a></li>
            <li class="active">Início</li>
        </ol>
    </section>
@endsection


@section('main-content')

    <!-- Default box -->
    <div class="box box-solid box-primary">
        <div class="box-header with-border">
            <h3 class="box-title">Listagem de Rubricas</h3>
        </div>
        <div class="box-body">
            <div class="box-tools">
                {!! Button::primary('<i class="fa fa-plus"></i> Nova rubrica')->asLinkTo(route('folha.rubrica.novo')) !!}
                <div class="btn-group">
                    {!! DropdownButton::normal('<i class="fa fa-gear"></i> Exportação')->withContents([
               ['url' => '#', 'label' => '<i class="fa fa-file-excel-o"></i> XLS'],
               ['url' => '#', 'label' => '<i class="fa fa-file-text-o"></i> CSV'],
               ['url' => '#', 'label' => '<i class="fa fa-file-pdf-o"></i> PDF']
           ])->split()  !!}
                </div>
            </div>

            <br/>
            <div class="col-sm-12">
                {!! $html->table() !!}
            </div>

        </div>


        <!-- /.box-body -->
    </div>
    <!-- /.box -->
    {{--@include('adminlte::newuserform')--}}
    {{--@include('adminlte::mod.admin.deletemodal')--}}
@endsection
@push('scripts')
    {!! $html->scripts() !!}
@endpush
