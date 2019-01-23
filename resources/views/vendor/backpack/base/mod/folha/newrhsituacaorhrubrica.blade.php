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
    <script type="text/javascript">

        document.novo_unidade.reset();

    </script>
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="box box-primary">
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
                        {{Form::label('Situações', null, ['class' => 'control-label'])}}
                        {{Form::select('situacao[]', $situacao ,$rubrica->rhsituacao, ['multiple'=>'multiple', 'class'=>'form-control', 'style'=>'height: 400px'])}}
                    </div>
                    <div class="form-group">
                        {{Form::button('Inserir', ['type' => 'submit', 'class'=>'btn btn-primary'])}}
                    </div>
                    {{Form::close()}}
                </div>
                <!-- /.box-body -->
            </div>
            <!-- /.box -->
        </div>
    </div>

@endsection