@extends('adminlte::layouts.app')

@push('breadcrumb')
    {{ Breadcrumbs::render() }}
@endpush

@section('htmlheader_title')
    {{ trans('adminlte_lang::message.users') }}
@endsection


@section('main-content')
    @if (count($errors) > 0)
        <div class="alert alert-warning">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
            <h4><i class="icon fa fa-ban"></i> Ops!</h4>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Default box -->
            <div class="box box-solid box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">Adicionar Situação a Rubrica</h3>
                    <div class="box-tools pull-right">
                        <a href="/folha/rubrica" class="btn btn-box-tool" title="Voltar">
                            <i class="fa fa-times"></i>
                        </a>
                    </div>

                </div>

                <div class="box-body">
                    {{Form::open(['name'=>'novo_rhsituacao','url' => "/folha/rhsituacaoxrhrubrica/$rubrica->id/adiciona", 'method'=>'post'])}}
                    {{Form::token()}}
                    <div class="form-group">
                        {{ $html->table() }}
                    </div>
                    <div class="form-group">
                        {{Form::button(Icon::create('floppy-disk').'&nbsp;&nbsp;Salvar', ['type' => 'submit', 'class'=>'btn btn-primary'])}}
                    </div>
                    {{Form::close()}}
                </div>
                <!-- /.box-body -->
            </div>
            <!-- /.box -->

@endsection
@push('scripts')
    {!! $html->scripts() !!}
@endpush