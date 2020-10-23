@extends('backpack::layout')

@section('header')
    <section class="content-header">
        <h1>
            Saldo Contábil
            <small></small>
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
            <h3 class="box-title">Saldos Contábeis</h3>
        </div>

        <div class="box-body">
            <div class="box-tools" align="right">
                <div class="row">
                    <div class="col-md-3" align="left">
{{--                        {!! form($form) !!}--}}
                    </div>
                    <div class="col-md-3">
                        {!! Button::primary('Inserir célula orçamentária. <i class="fa fa-refresh"></i>')
                            ->asLinkTo(route('empenho.lista.minuta'))
                        !!}
                    </div>
                    <div class="col-md-3" align="right">

                    </div>
                    <div class="col-md-3" align="left">

                    </div>
                </div>
            </div>
            <br/>
            <form action="{{route('empenho.lista.minuta')}}" method="POST">
                {!! $html->table() !!}
            <div class="col-sm-12">

            </div>
            <div class="box-tools" align="right">
                <div class="row">
                    <div class="col-md-3" align="left">
                        {!! Button::primary('<i class="fa fa-arrow-left"></i> Voltar')
                            ->asLinkTo(route('empenho.lista.minuta'))
                        !!}
                    </div>
                    <div class="col-md-3">

                    </div>
                    <div class="col-md-3" align="right">
                        {!! Button::primary('Atualizar todos os Saldos <i class="fa fa-refresh"></i>')
                            ->asLinkTo(route('empenho.lista.minuta'))
                        !!}
                    </div>
                    <div class="col-md-3" align="left">
                        {!! Button::primary(' Próxima Etapa <i class="fa fa-arrow-right"></i>')
                            ->asLinkTo(route('empenho.lista.minuta'))
                        !!}
                    </div>
                </div>
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
