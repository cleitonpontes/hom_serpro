{{--{{ dd(get_defined_vars()['__data']) }}--}}
{{--{{dd($entry)}}--}}
{{--{{dd(get_defined_vars()['__data'])}}--}}
@php

    $minuta_id = $entry->id;
    $remessa_id = $entry->minutaempenhos_remessa_id;


    $rotas[1] = route('empenho.crud.alteracao.edit',[
                'minuta_id' => $minuta_id,
                'remessa' => $remessa_id,
                'minuta' => $minuta_id
                ]);
    $rotas[2] = route('empenho.crud.alteracao.passivo-anterior',
        ['minuta_id' => $minuta_id, 'remessa' => $remessa_id]);

    if ($entry->passivo_anterior && !is_null($entry->conta_corrente)){
        $rotas[2] = route('empenho.crud.alteracao.passivo-anterior.edit',
                ['minuta_id' => $minuta_id, 'remessa' => $remessa_id]);
    }

    $rotas[3] = route('empenho.crud.alteracao.show', [
                'minuta_id' => $minuta_id,
                'remessa' => $remessa_id,
                'minuta' => $minuta_id
                ]);

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
