{{--{{ dd(get_defined_vars()['__data']) }}--}}
{{--{{dd($entry)}}--}}
{{--{{dd(get_defined_vars()['__data'])}}--}}
@php

    $fornecedor_id = $entry->fornecedor_empenho_id ?? '';
    //$conta_id = $entry->passivo_anterior ??  '';
    $conta_id = session('conta_id') ?? Route::current()->parameter('conta_id') ?? '';

    $rotas[2] = route('empenho.minuta.etapa.fornecedor', ['minuta_id' => $entry->getKey()]);
    $rotas[3] = route('empenho.minuta.etapa.item', ['minuta_id' => $entry->getKey(), 'fornecedor_id' => $fornecedor_id]);
    $rotas[4] = route('empenho.minuta.etapa.saldocontabil', ['minuta_id' => $entry->getKey()]);
    $rotas[5] = route('empenho.minuta.etapa.subelemento', ['minuta_id' => $entry->getKey()]);
    $rotas[6] = route('empenho.crud./minuta.edit', ['minutum' => $entry->getKey()]);
    $rotas[7] = route('empenho.minuta.etapa.passivo-anterior', ['passivo_anterior' => $entry->getKey()]);
    $rotas[8] = route('empenho.crud./minuta.show', ['minutum' => $entry->getKey()]);

    if ($conta_id){
        $rotas[7] = route('empenho.crud.passivo-anterior.edit', ['minuta_id' => $conta_id]);
    }
@endphp
@if ($crud->hasAccess('update'))
    @if (!$crud->model->translationEnabled())

        <!-- Single edit button -->
        <a href="{{ $rotas[$entry->etapa] }}" class="btn btn-xs btn-default"
           title="{{ trans('backpack::crud.edit') }}"><i class="fa fa-edit"></i></a>

    @else

        <!-- Edit button group -->
        <div class="btn-group">
            <a href="{{ $rotas[$entry->etapa] }}" class="btn btn-xs btn-default"><i
                    class="fa fa-edit"></i> {{ trans('backpack::crud.edit') }}</a>
            <button type="button" class="btn btn-xs btn-default dropdown-toggle" data-toggle="dropdown"
                    aria-haspopup="true" aria-expanded="false">
                <span class="caret"></span>
                <span class="sr-only">Toggle Dropdown</span>
            </button>
            <ul class="dropdown-menu dropdown-menu-right">
                <li class="dropdown-header">{{ trans('backpack::crud.edit_translations') }}:</li>
                @foreach ($crud->model->getAvailableLocales() as $key => $locale)
                    <li>
                        <a href="{{ url($crud->route.'/'.$entry->getKey().'/edit') }}?locale={{ $key }}">{{ $locale }}</a>
                    </li>
                @endforeach
            </ul>
        </div>

    @endif
@endif
