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

        <!-- Resumo da Minuta de Empenho -->
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
                <div class="box box-solid box-primary ">
                    <div class="box-header with-border" data-widget="collapse" data-toggle="tooltip" title="Collapse">
                        <h3 class="box-title">Resumo da unidade descentralizada</h3>
                    </div>
                    <div class="box-body">
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
                    </div>
                </div>
            </div>

            {{-- VALORES TOTAIS POR ANO  --}}
            @foreach($crud->columns['valores']['values']  as $valor )
                <div class="m-t-20">
                    <div class="box box-solid box-primary">
                        <div class="box-header with-border" data-widget="collapse" data-toggle="tooltip"
                             title="Collapse">
                            <h3 class="box-title">Total empenhado em {{$valor['ano']}}</h3>
                        </div>
                        <div class="box-body">
                            <div class="box-body">
                                <table class="table table-striped">
                                    <tbody>
                                        <tr>
                                            <td>
                                                <span>{{ $valor['valor'] }}</span>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="col-sm-12"></div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endsection


@section('after_styles')
    <link rel="stylesheet" href="{{ asset('vendor/backpack/crud/css/crud.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/backpack/crud/css/show.css') }}">
@endsection
