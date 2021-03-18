@php
    $minuta_id = Route::current()->parameter('minuta_id');
    $fornecedor_id = Route::current()->parameter('fornecedor_id')
@endphp
@extends('backpack::layout')

@section('header')
    <section class="content-header">
        <h1>
            Itens
            <small>da Compra / Contrato</small>
        </h1>
    </section>
@endsection

@section('content')
    @include('backpack::mod.empenho.telas.cabecalho')
    <div class="flash-message">
        <p class="alert alert-warning"><b>Atenção:</b> Não serão exibidos itens sem saldo, ou com data de vigência expirada.</p>
    </div>
    <div class="flash-message">
        @foreach (['danger', 'warning', 'success', 'info'] as $msg)
            @if(Session::has('alert-' . $msg))
                <p class="alert alert-{{ $msg }}">{{ Session::get('alert-' . $msg) }}</p>
            @endif
        @endforeach
    </div>
    <div class="box box-solid box-primary">
        <div class="box-header with-border">
            <h3 class="box-title">Itens da Compra / Contrato</h3>
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
                    @include('backpack::mod.empenho.botoes',['rota' => route('empenho.minuta.etapa.fornecedor', ['minuta_id' => $minuta_id])])
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
