{{--@php dd($crud->columns['itens']['values']) @endphp--}}
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
        @include('backpack::mod.empenho.telas.cabecalho')

        <!-- Default box -->
            <div class="m-t-20">
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
                {{--	    <div class="box no-padding no-border">--}}
                <div class="box box-solid box-primary">
                    <div class="box-header with-border" data-widget="collapse" data-toggle="tooltip" title="Collapse">
                        <button class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse">
                            <i class="fa fa-minus"></i>
                        </button>
                        <h3 class="box-title">Resumo da Minuta de Empenho</h3>
                    </div>

                    <div class="box-body">
                        <form action="/empenho/subelemento" method="POST">
                            <input type="hidden" id="minuta_id" name="minuta_id" value="">
                            <input type="hidden" id="fornecedor_id" name="fornecedor_id" value="">
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
                                    @if ($crud->buttons->where('stack', 'line')->count())
                                        <tr>
                                            <td><strong>{{ trans('backpack::crud.actions') }}</strong></td>
                                            <td>
                                                @include('crud::inc.button_stack', ['stack' => 'line'])
                                            </td>
                                        </tr>
                                    @endif
                                    </tbody>
                                </table>
                            </div>
                    </div><!-- /.box-body -->
                    <div class="col-sm-12"></div>

                    </form>
                </div>
            </div>

            <div class="m-t-20">

                {{--	    <div class="box no-padding no-border">--}}
                <div class="box box-solid box-primary">
                    <div class="box-header with-border" data-widget="collapse" data-toggle="tooltip" title="Collapse">
                        <button class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse">
                            <i class="fa fa-minus"></i>
                        </button>
                        <h3 class="box-title">Resumo da Compra</h3>
                    </div>

                    <div class="box-body">
                        <form action="/empenho/subelemento" method="POST">
                            <input type="hidden" id="minuta_id" name="minuta_id" value="">
                            <input type="hidden" id="fornecedor_id" name="fornecedor_id" value="">
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
                                    @if ($crud->buttons->where('stack', 'line')->count())
                                        <tr>
                                            <td><strong>{{ trans('backpack::crud.actions') }}</strong></td>
                                            <td>
                                                @include('crud::inc.button_stack', ['stack' => 'line'])
                                            </td>
                                        </tr>
                                    @endif
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

                    {{--	    <div class="box no-padding no-border">--}}
                    <div class="box box-solid box-primary">
                        <div class="box-header with-border" data-widget="collapse" data-toggle="tooltip"
                             title="Collapse">
                            <button class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip"
                                    title="Collapse">
                                <i class="fa fa-minus"></i>
                            </button>
                            <h3 class="box-title">Item da Compra</h3>
                        </div>
                        <div class="box-body">
                            <form action="/empenho/subelemento" method="POST">
                                <input type="hidden" id="minuta_id" name="minuta_id" value="">
                                <input type="hidden" id="fornecedor_id" name="fornecedor_id" value="">
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
                {!! Button::success('<i class="fa fa-arrow-left"></i> Voltar')
                    ->asLinkTo(route('empenho.crud./minuta.index'))
                !!}
                <button type="submit" class="btn btn-primary">
                    Próxima Etapa <i class="fa fa-arrow-right"></i>
                </button>
            </div>

        </div>
    </div>
@endsection


@section('after_styles')
    <link rel="stylesheet" href="{{ asset('vendor/backpack/crud/css/crud.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/backpack/crud/css/show.css') }}">
@endsection

@section('after_scripts')
    <script src="{{ asset('vendor/backpack/crud/js/crud.js') }}"></script>
    <script src="{{ asset('vendor/backpack/crud/js/show.js') }}"></script>
@endsection
