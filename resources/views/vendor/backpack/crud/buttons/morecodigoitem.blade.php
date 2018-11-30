<div class="btn-group">
    <button type="button" class="btn btn-xs btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true"
            aria-expanded="false">
        <span class="caret"></span>
        <span class="sr-only">Toggle Dropdown</span>
    </button>
    <ul class="dropdown-menu dropdown-menu-right">
        <li class="dropdown-header">{{ trans('backpack::crud.edit_translations') }}:</li>
            <li><a href="#">Teste {{$entry->getKey()}}</a></li>
            <li><a href="#">Teste {{$entry->getKey()}}</a></li>
            <li><a href="#">Teste {{$entry->getKey()}}</a></li>
    </ul>
</div>