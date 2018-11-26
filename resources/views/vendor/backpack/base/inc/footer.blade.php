@if (config('backpack.base.show_powered_by'))
    <div class="pull-right hidden-xs">
        versão {{ env('APP_VERSION') }} release {{ env('APP_RELEASE') }}
        {{--{{ trans('backpack::base.powered_by') }} <a target="_blank" href="http://backpackforlaravel.com?ref=panel_footer_link">Backpack for Laravel</a>--}}
    </div>
@endif
&nbsp;<strong>Copyright &copy; 2018 <a href="#">{{ env('APP_NAME') }}</a> - </strong> Todos direitos reservados.
{{--{{ trans('backpack::base.handcrafted_by') }} <a target="_blank" href="{{ config('backpack.base.developer_link') }}">{{ config('backpack.base.developer_name') }}</a>.--}}