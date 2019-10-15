@if (config('backpack.base.show_powered_by'))
    @php
        $version = \App\Models\AppVersion::all()->last();
    @endphp
    <div class="pull-right hidden-xs">
        v. {{ $version->major . '.' . $version->minor . '.' . str_pad($version->patch, 3, "0", STR_PAD_LEFT) }}
        {{--{{ trans('backpack::base.powered_by') }} <a target="_blank" href="http://backpackforlaravel.com?ref=panel_footer_link">Backpack for Laravel</a>--}}
    </div>
@endif
&nbsp;Copyright &copy; {{ date('Y') }} <strong><a href="#">{{ env('APP_NAME') }}</a> </strong>- Todos direitos reservados. Software Livre (GPL).
{{--{{ trans('backpack::base.handcrafted_by') }} <a target="_blank" href="{{ config('backpack.base.developer_link') }}">{{ config('backpack.base.developer_name') }}</a>.--}}
