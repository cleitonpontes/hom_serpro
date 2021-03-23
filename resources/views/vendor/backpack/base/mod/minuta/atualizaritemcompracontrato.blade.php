@extends('backpack::layout')
@section('header')
    <section class="content-header">
        <h1>
            Itens de Minuta
            <small>Editar Itens de Minuta</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ backpack_url() }}">{{ config('backpack.base.project_name') }}</a></li>
            <li><a href="{{ backpack_url('/admin/ajusteminuta') }}">Ajuste de Minutas</a></li>
            <li class="active">Editar Itens de Minuta</li>
        </ol>
    </section>
@endsection
@section('content')
    <form method="post"
          action="{{ url($route) }}"
    >
        {!! csrf_field() !!}
        <div class="box col-md-12 padding-10 p-t-20">
            <div class="box-body">
                {!! $html->table(['class' => 'box table table-striped table-hover display responsive nowrap m-t-0 dataTable dtr-inline collapsed has-hidden-columns'], true) !!}
                <input type='hidden' name='tipo_minuta' value='{{$tipo_minuta}}'/>
                <input type='hidden' name='id_minuta' value='{{$id_minuta}}'/>
                <input type='hidden' name='id_remessa' value='{{$id_remessa}}'/>
            </div>
        </div>
        <button type="submit" class="btn btn-success" style="margin: 10px">
            <i class="fa fa-save"></i> Salvar
        </button>
        <a href="{{ backpack_url('/admin/ajusteminuta') }}" class="btn btn-default"><span class="fa fa-angle-double-left"></span> &nbsp;Voltar</a>
    </form>
@endsection

@push('after_scripts')
    {!! $html->scripts() !!}
@endpush
