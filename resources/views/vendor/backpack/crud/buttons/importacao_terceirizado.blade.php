@php
//dd(Route::current()->parameter('contrato_id'))
@endphp

<button type="button" class="btn btn-primary ladda-button" data-toggle="modal"
        data-target="#modal-importacao-terceirizado" hidden>
    <span class="ladda-label"><i class="fa fa-file-text"></i> Importação de terceirizado</span>
</button>

{{--@include('vendor.backpack.crud.modal.importacao_terceirizado', ['routeAction' => $crud->route.'/importacao-terceirizados'])--}}
@include('vendor.backpack.crud.modal.importacao_terceirizado',
 ['routeAction' => '/admin/importacao/terceirizados/'. Route::current()->parameter('contrato_id')])
