@extends('backpack::layout')

@section('header')
	<section class="content-header">
	  <h1>
        <span class="text-capitalize">{!! $crud->getHeading() ?? $crud->entity_name_plural !!}</span>
        <small>{!! $crud->getSubheading() ?? trans('backpack::crud.add').' '.$crud->entity_name !!}.</small>
	  </h1>
	  <ol class="breadcrumb">
	    <li><a href="{{ url(config('backpack.base.route_prefix'), 'dashboard') }}">{{ trans('backpack::crud.admin') }}</a></li>
	    <li><a href="{{ url($crud->route) }}" class="text-capitalize">{{ $crud->entity_name_plural }}</a></li>
	    <li class="active">{{ trans('backpack::crud.add') }}</li>
	  </ol>
	</section>
@endsection

@section('content')

@if ($crud->hasAccess('list'))
	<a href="{{ starts_with(URL::previous(), url($crud->urlVoltar)) ? URL::previous() : url($crud->urlVoltar) }}" class="hidden-print"><i class="fa fa-angle-double-left"></i> {{ trans('backpack::crud.back_to_all') }} <span>{{ $crud->entity_name_plural }}</span></a>
@endif
<div class="flash-message">
    @foreach (['danger', 'warning', 'success', 'info'] as $msg)
        @if(Session::has('alert-' . $msg))
            <p class="alert alert-{{ $msg }}">{{ Session::get('alert-' . $msg) }}</p>
        @endif
    @endforeach
</div>
<div class="row m-t-20">
	<div class="{{ $crud->getCreateContentClass() }}">
    @include('backpack::mod.empenho.telas.cabecalho')
		<!-- Default box -->

		@include('crud::inc.grouped_errors')

		  <form method="post"
		  		action="{{ url($crud->route) }}"
				@if ($crud->hasUploadFields('create'))
				enctype="multipart/form-data"
				@endif
		  		>
		  {!! csrf_field() !!}
              @if(isset($crud->update_form) && $crud->update_form !== false)
                  {!! method_field('PUT') !!}
              @endif
		  <div class="col-md-12">

		    <div class="row display-flex-wrap">
		      <!-- load the view from the application if it exists, otherwise load the one in the package -->
		      @if(view()->exists('vendor.backpack.crud.form_content'))
                    <div class="box box-solid box-primary">
                        @if(strpos(URL::current(),'buscacompra') !== false )
                        <div class="box-header">
                            <h3 class="box-title label-title">Tipo: </h3>
                            <input type="radio" value="1" checked name="tipoEmpenho" id="opc_contrato">
                            <label for="opc_contrato" class="margin-right-10"><h3 class="box-title">Contrato</h3></label>
                            <input type="radio" value="2" name="tipoEmpenho" id="opc_compra">
                            <label for="opc_compra" class="margin-right-10"><h3 class="box-title">Compra</h3></label>
                            <input type="radio" value="3" name="tipoEmpenho" id="opc_suprimento">
                            <label for="opc_suprimento" class="margin-right-10"><h3 class="box-title">Suprimento</h3></label>
                        </div>
                        @endif
                        <div>
                            @include('crud::form_content', [ 'fields' => $crud->getFields('create'), 'action' => 'create' ])
                        </div>
                    </div>
		      @endif
		    </div><!-- /.box-body -->
              <div class="">
                  @include('backpack::mod.empenho.botoes',['rota' => $crud->urlVoltar])
              </div>

		  </div><!-- /.box -->
		  </form>
	</div>
</div>
@endsection
@push('after_scripts')
    <style type="text/css">
        .margin-right-10{
            margin-right: 10px;
            cursor: pointer
        }
        .label-title{
            margin-right: 10px !important;
        }
    </style>

    <script type="text/javascript">
        $(document).ready(function() {
            toogleRadioTipoEmpenho();
        });

        function toogleRadioTipoEmpenho() {
            $('input[type=radio][name=tipoEmpenho]').change(function() {
                switch (this.value){
                    case '1': habilitarFormParaContrato(); break;
                    case '2': habilitarFormParaCompra(); break;
                    case '3': habilitarFormParaSuprimento(); break;
                }
            });
        }

        function habilitarFormParaContrato() {
            $('.opc_compra').prop("disabled", true);
            $('.opc_contrato').prop("disabled", false);
            $('.opc_suprimento').prop("disabled", true);
            limpaForm();
        }

        function habilitarFormParaCompra() {
            $('.opc_compra').prop("disabled", false);
            $('.opc_contrato').prop("disabled", true);
            $('.opc_suprimento').prop("disabled", true);
            limpaForm();
        }

        function habilitarFormParaSuprimento() {
            $('.opc_compra').prop("disabled", true);
            $('.opc_contrato').prop("disabled", true);
            $('.opc_suprimento').prop("disabled", false);
            limpaForm();
        }

        function limpaForm() {
            $('.opc_contrato option').remove();
            $('#select2_ajax_unidade_origem_id option').remove();
            $('#numero_ano').val('');
            limpaSelect2FromArray();
            limpaSuprido();
        }

        function limpaSelect2FromArray(){
            $('#select2-opc_compra_modalidade-container').attr('title', 'Selecione...');
            $('#select2-opc_compra_modalidade-container').text('Selecione...');
        }

        function limpaSuprido(){
            $('#select2-select2_ajax_fornecedor_empenho_id-container').attr('title', 'Selecione o suprido');
            $('#select2-select2_ajax_fornecedor_empenho_id-container').text('Selecione o suprido');
        }
    </script>
@endpush
