@php
    $minuta_id = (int) Route::current()->parameter('minuta_id')
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
            @csrf <!-- {{ csrf_field() }} -->
                {!! $html->table() !!}
                <div class="col-sm-12">

                </div>
                <div class="box-tools">
                    <button type="submit" class="btn btn-success">
                        <span class="fa fa-save" role="presentation" aria-hidden="true"></span> &nbsp;
                    </button>
                    {!! Button::success('<i class="fa fa-arrow-left"></i> Voltar')
                        ->asLinkTo(route('empenho.lista.minuta'))
                    !!}
                    {!! Button::primary(' Próxima Etapa <i class="fa fa-arrow-right"></i>')
                        ->asLinkTo(route('empenho.lista.minuta'))
                    !!}
                </div>
            </form>
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
