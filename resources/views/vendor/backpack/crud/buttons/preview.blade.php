{{-- This button is deprecated and will be removed in CRUD 3.5 --}}

@if ($crud->hasAccess('show'))
	<a href="{{ url($crud->route.'/'.$entry->getKey()) }}" class="btn btn-xs btn-default" title="{{ trans('backpack::crud.preview') }}"><i class="fa fa-eye"></i></a>
@endif
