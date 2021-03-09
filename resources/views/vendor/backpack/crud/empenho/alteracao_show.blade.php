{{--@php dd($crud->columns) @endphp--}}
{{--@php dd($entry) @endphp--}}
{{--@php dd($entry->situacao_descricao, $entry->etapa) @endphp--}}
{{--{{ dd(get_defined_vars()['__data']) }}--}}
@extends('backpack::layout')

@section('header')
    <section class="content-header">
        <h1>
            <span class="text-capitalize">{!! $crud->getHeading() ?? $crud->entity_name_plural !!}</span>
            <small>{!! $crud->getSubheading() ?? mb_ucfirst(trans('backpack::crud.preview')).' '.$crud->entity_name !!}
                .</small>
        </h1>
        <ol class="breadcrumb">
            <li>
                <a href="{{ url(config('backpack.base.route_prefix'), 'dashboard') }}">{{ trans('backpack::crud.admin') }}</a>
            </li>
            <li><a href="{{ url($crud->route) }}" class="text-capitalize">{{ $crud->entity_name_plural }}</a></li>
            <li class="active">{{ trans('backpack::crud.preview') }}</li>
        </ol>
    </section>
@endsection

@section('content')
    @if ($crud->hasAccess('list'))
        <a href="{{ starts_with(URL::previous(), url($crud->route)) ? URL::previous() : url($crud->route) }}"
           class="hidden-print"><i class="fa fa-angle-double-left"></i> {{ trans('backpack::crud.back_to_all') }}
            <span>{{ $crud->entity_name_plural }}</span></a>

        <a href="javascript: window.print();" class="pull-right hidden-print"><i class="fa fa-print"></i></a>
    @endif
    <div class="row">
        <div class="{{ $crud->getShowContentClass() }}">
        @include('backpack::mod.empenho.telas.cabecalho_alteracao')

        <!-- Resumo da Minuta de Empenho -->
            <div class="m-t-0">
                @if ($crud->model->translationEnabled())
                    <div class="row">
                        <div class="col-md-12 m-b-10">
                            <!-- Change translation button group -->
                            <div class="btn-group pull-right">
                                <button type="button" class="btn btn-sm btn-primary dropdown-toggle"
                                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    {{trans('backpack::crud.language')}}
                                    : {{ $crud->model->getAvailableLocales()[$crud->request->input('locale')?$crud->request->input('locale'):App::getLocale()] }}
                                    &nbsp; <span class="caret"></span>
                                </button>
                                <ul class="dropdown-menu">
                                    @foreach ($crud->model->getAvailableLocales() as $key => $locale)
                                        <li>
                                            <a href="{{ url($crud->route.'/'.$entry->getKey()) }}?locale={{ $key }}">{{ $locale }}</a>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                @else
                @endif
                <div class="box box-solid box-primary ">
                    <div class="box-header with-border" data-widget="collapse" data-toggle="tooltip" title="Collapse">
                        <h3 class="box-title">Resumo da Minuta de Empenho</h3>
                    </div>

                    <form action="/empenho/subelemento" method="POST">
                        <div class="box-body">
                                <input type="hidden" id="minuta_id" name="minuta_id" value="{{$crud->getCurrentEntryId()}}">
                                <input type="hidden" id="remessa_id" name="remessa_id" value="{{Route::current()->parameter('remessa')}}">
                            @csrf <!-- {{ csrf_field() }} -->

                                <div class="box-body">
                                    <table class="table table-striped">
                                        <tbody>
                                        @foreach ($crud->columns as $column)
                                            @if( !isset($column['box']) || ($column['box'] === 'resumo') )
                                                <tr>
                                                    <td>
                                                        <strong>{{ $column['label'] }}</strong>
                                                    </td>
                                                    <td>
                                                        @if (!isset($column['type']))
                                                            @include('crud::columns.text')
                                                        @else
                                                            @if(view()->exists('vendor.backpack.crud.columns.'.$column['type']))
                                                                @include('vendor.backpack.crud.columns.'.$column['type'])
                                                            @else
                                                                @if(view()->exists('crud::columns.'.$column['type']))
                                                                    @include('crud::columns.'.$column['type'])
                                                                @else
                                                                    @include('crud::columns.text')
                                                                @endif
                                                            @endif
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endif()
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                        </div><!-- /.box-body -->
                        <div class="col-sm-12"></div>

                    </form>
                </div>
            </div>

            {{-- Crédito Orçamentário  --}}
            @foreach($crud->columns['saldo']['values'] as $itens )
                <div class="m-t-20">
                    <div class="box box-solid box-primary collapsed-box">
                        <div class="box-header with-border" data-widget="collapse" data-toggle="tooltip"
                             title="Collapse">
                            <h3 class="box-title">Crédito Orçamentário</h3>
                        </div>
                        <div class="box-body">
                        @csrf <!-- {{ csrf_field() }} -->
                            <div class="box-body">
                                <table class="table table-striped">
                                    <tbody>
                                    @foreach ($itens as $key => $value)
                                        <tr>
                                            <td>
                                                <strong>{{ $key }}</strong>
                                            </td>
                                            <td>
                                                <span>{{ $value }}</span>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div><!-- /.box-body -->
                        <div class="col-sm-12"></div>
                    </div>
                </div>
            @endforeach

        <!-- Resumo da Compra -->
            <div class="m-t-20">
                <div class="box box-solid box-primary collapsed-box">
                    <div class="box-header with-border" data-widget="collapse" data-toggle="tooltip" title="Collapse">
                        <h3 class="box-title">Resumo da Compra</h3>
                    </div>

                    <form action="/empenho/subelemento" method="POST">
                        <div class="box-body">
                            @csrf <!-- {{ csrf_field() }} -->

                                <div class="box-body">
                                    <table class="table table-striped">
                                        <tbody>
                                        @foreach ($crud->columns as $column)
                                            @if( isset($column['box']) && ($column['box'] === 'compra') )
                                                <tr>
                                                    <td>
                                                        <strong>{{ $column['label'] }}</strong>
                                                    </td>
                                                    <td>
                                                        @if (!isset($column['type']))
                                                            @include('crud::columns.text')
                                                        @else
                                                            @if(view()->exists('vendor.backpack.crud.columns.'.$column['type']))
                                                                @include('vendor.backpack.crud.columns.'.$column['type'])
                                                            @else
                                                                @if(view()->exists('crud::columns.'.$column['type']))
                                                                    @include('crud::columns.'.$column['type'])
                                                                @else
                                                                    @include('crud::columns.text')
                                                                @endif
                                                            @endif
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endif
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                        </div><!-- /.box-body -->
                        <div class="col-sm-12"></div>
                    </form>
                </div>
            </div>

            {{-- ITENS DA COMPRA  --}}
            @foreach($crud->columns['itens']['values'] as $itens )
                <div class="m-t-20">
                    <div class="box box-solid box-primary collapsed-box">
                        <div class="box-header with-border" data-widget="collapse" data-toggle="tooltip"
                             title="Collapse">
                            <h3 class="box-title">Item da Compra</h3>
                        </div>
                        <form action="/empenho/subelemento" method="POST">
                            <div class="box-body">
                                @csrf <!-- {{ csrf_field() }} -->
                                    <div class="box-body">
                                        <table class="table table-striped">
                                            <tbody>
                                            @foreach ($itens as $key => $value)
                                                <tr>
                                                    <td>
                                                        <strong>{{ $key }}</strong>
                                                    </td>
                                                    <td>
                                                        <span>{{ $value }}</span>
                                                    </td>
                                                </tr>
                                            @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                            </div><!-- /.box-body -->
                            <div class="col-sm-12"></div>
                        </form>
                    </div>
                </div>
            @endforeach

            <div class="box-tools">
                <div class="row">
                    <div class="col-md-4">
                        @if(session('passivo_anterior'))
                            <button type="button" class="btn btn-primary" id="voltar_passivo"
                                @if (session('situacao') !== 'EMPENHO EMITIDO' && session('situacao') !== 'EM PROCESSAMENTO')
                                    id="voltar_passivo"
                                @else
                                    disabled
                                @endif
                            >
                                <i class="fa fa-arrow-left"></i> Voltar
                            </button>
                        @else
                            <button type="button" class="btn btn-primary" id="voltar"
                                    @if (session('situacao') !== 'EMPENHO EMITIDO' && session('situacao') !== 'EM PROCESSAMENTO')
                                    id="voltar"
                                    @else
                                    disabled
                                @endif
                            >
                                <i class="fa fa-arrow-left"></i> Voltar
                            </button>
                        @endif
                    </div>
                    <div class="col-md-4" align="center">
                        <button type="button" class="btn btn-primary" id="emitir_empenho_siafi"
                                @if (session('situacao') === 'EM ANDAMENTO' || session('situacao') === 'ERRO')
                                id="emitir_empenho_siafi"
                                @else
                                disabled
                            @endif
                        >
                            <i class="fa fa-save"></i> Emitir Empenho SIAFI
                        </button>

                    </div>
                    <div class="col-md-4" align="right">
                        <button type="button" class="btn btn-primary" id="finalizar"
                            {{(session('situacao') !== 'EMPENHO EMITIDO') ? 'disabled' : ''}}
                        >
                            <i class="fa fa-check-circle"></i> Finalizar
                        </button>
                    </div>

                </div>
            </div>

        </div>
    </div>
@endsection


@section('after_styles')
    <link rel="stylesheet" href="{{ asset('vendor/backpack/crud/css/crud.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/backpack/crud/css/show.css') }}">
@endsection

@section('after_scripts')
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <script src="{{ asset('vendor/backpack/crud/js/crud.js') }}"></script>
    <script src="{{ asset('vendor/backpack/crud/js/show.js') }}"></script>
    <script type="text/javascript">

        $(document).ready(function () {
            $('body').on('click', '#emitir_empenho_siafi', function (event) {
                $('#emitir_empenho_siafi').attr('disabled', true);
                salvarTabelasSiafi(event);
            });


            $('body').on('click', '#finalizar', function (event) {
                var minuta_id = $('#minuta_id').val();
                var url ="{{route('empenho.crud.alteracao.index', ['minuta_id' => ':minuta_id'])}}";
                    url = url.replace(':minuta_id', minuta_id);
                window.location.href = url;
            });


            $('body').on('click', '#voltar', function (event) {
                var minuta_id = $('#minuta_id').val();
                var minuta = $('#minuta_id').val();
                var remessa_id = $('#remessa_id').val();
                var url ="{{route('empenho.crud.alteracao.edit', ['minuta_id' => ':minuta_id','remessa_id' => ':remessa_id','minuta' => ':minuta'])}}";
                url = url.replace(':minuta_id', minuta_id);
                url = url.replace(':remessa_id', remessa_id);
                url = url.replace(':minuta', minuta);
                window.location.href = url;
            });

            $('body').on('click', '#voltar_passivo', function (event) {

                var remessa_id = $('#remessa_id').val();
                var minuta_id = $('#minuta_id').val();
                var url ="{{route('empenho.crud.alteracao.passivo-anterior.edit',['minuta_id' => ':minuta_id','remessa_id' => ':remessa_id'])}}";
                url = url.replace(':minuta_id', minuta_id);
                url = url.replace(':remessa_id', remessa_id);
                window.location.href = url;
            });


        });

        function retirarLinks() {
            $('#rowCabecalho').find('a').each(function () {
                this.href = '#';
            })
        }

        function salvarTabelasSiafi(event) {
            var minuta_id = $('#minuta_id').val();
            var remessa_id = $('#remessa_id').val();

            var url = "{{route('popula.tabelas.siafi.alt',['minuta_id'=>':minuta_id', 'remessa'=>':remessa_id'])}}";
            url = url.replace(':minuta_id', minuta_id);
            url = url.replace(':remessa_id', remessa_id);

            event.preventDefault()

            axios.request(url)
                .then(response => {
                    dados = response.data
                    if (dados.resultado == true) {
                        Swal.fire(
                            'Sucesso!',
                            'Empenho salvo com sucesso!',
                            'success'
                        )
                        retirarLinks();
                        $('#voltar').attr('disabled', true);
                        $('#finalizar').removeAttr('disabled');
                        location.reload();
                    } else {
                        Swal.fire(
                            'Alerta!',
                            'Houve um problema ao tentar salvar os dados.',
                            'warning'
                        )
                    }
                })
                .catch(error => {
                    $('#emitir_empenho_siafi').removeAttr('disabled');
                    alert(error);
                })
                .finally()
        }

        function empenharOutroFornecedor(event) {

            var minuta_id = $('#minuta_id').val();
            var url = "{{route('novo.empenho.compra',':minuta_id')}}";
            url = url.replace(':minuta_id', minuta_id);
            axios.request(url)
                .then(response => {
                    var nova_minuta_id = response.data
                    var url = "{{route('empenho.minuta.etapa.fornecedor',':minuta_id')}}";
                    url = url.replace(':minuta_id', nova_minuta_id);
                    console.log(url);
                    window.location.href = url;
                })
                .catch(error => {
                    alert(error);
                })
                .finally()
            event.preventDefault()
        }

    </script>
@endsection
