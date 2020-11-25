@php
    $minuta_id = Route::current()->parameter('minuta_id');
    $fornecedor_id = Route::current()->parameter('fornecedor_id')
@endphp
@extends('backpack::layout')

@section('header')
    <section class="content-header">
        <h1>
            Itens
            <small>da Compra</small>
        </h1>
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
    <div class="box box-solid box-primary">
        <div class="box-header with-border">
            <h3 class="box-title">Itens da Compra</h3>
        </div>

        <div class="box-body">
            <br/>
            <form action="/empenho/item" method="POST">
                <input type="hidden" id="minuta_id" name="minuta_id" value="{{$minuta_id}}">
                <input type="hidden" id="fornecedor_id" name="fornecedor_id" value="{{$fornecedor_id}}">
            @csrf <!-- {{ csrf_field() }} -->
                @if($update)
                    {!! method_field('PUT') !!}
                @endif
                {!! $html->table() !!}
                <div class="col-sm-12">

                </div>
                <div class="box-tools">
                    {!! Button::danger('<i class="fa fa-arrow-left"></i> Voltar')
                        ->asLinkTo(route('empenho.minuta.etapa.fornecedor', ['minuta_id' => $minuta_id]))
                    !!}
                    <button type="submit" class="btn btn-success">
                        Próxima Etapa <i class="fa fa-arrow-right"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>

@endsection

@push('after_scripts')
    {!! $html->scripts() !!}
    <script type="text/javascript">
        $(document).ready(function () {

            $('#selectAll').click(function () {
                var checkedStatus = this.checked;
                $('input[type=checkbox]').each(function () {
                    $(this).prop('checked', checkedStatus);
                });
            });
        });

        function bloqueia(tipo) {
            $('input[type=checkbox]').each(function () {
                if (tipo != $(this).data('tipo')) {
                    this.checked = false;
                }
            });
        }


    </script>
@endpush
