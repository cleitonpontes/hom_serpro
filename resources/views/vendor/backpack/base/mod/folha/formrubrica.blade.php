@extends('adminlte::layouts.app')

@push('breadcrumb')
    {{ Breadcrumbs::render() }}
@endpush

@section('htmlheader_title')
    {{ trans('adminlte_lang::message.users') }}
@endsection


@section('main-content')
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">Formulário Rubrica</h3>
                    <div class="box-tools pull-right">
                        <a href="/folha/rubrica" class="btn btn-box-tool"  title="Voltar">
                            <i class="fa fa-times"></i>
                        </a>
                    </div>

                </div>
                <div class="box-body">
                    <fieldset class="form-group">
                        {!! form($form->add('insert','submit',[
                        'attr' => [
                            'class' => 'btn btn-primary'
                        ],
                        'label' => Icon::create('floppy-disk').'&nbsp;&nbsp;Inserir'
                    ])) !!}
                    </fieldset>
                </div>
                <!-- /.box-body -->
            </div>
            <!-- /.box -->
        </div>
    </div>

    @push('scripts')
        <script type="text/javascript">
            $('#criacao').mask('99/9999');
            $('#codigo').mask('99999');
        </script>
        <script>
            $(document).ready(function() {
                $('#tipo').select2()
                $('#situacao').select2()
            } );
        </script>
    @endpush
    <!-- /.box -->
    {{--@include('adminlte::newuserform')--}}
    {{--@include('adminlte::mod.admin.deletemodal')--}}
@endsection