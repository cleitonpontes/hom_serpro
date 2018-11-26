<div class="user-panel">
  <a class="pull-left image" href="{{ route('backpack.account.info') }}">
    <img src="{{ backpack_avatar_url(backpack_auth()->user()) }}" class="img-circle" alt="User Image">
  </a>
  <div class="pull-left info">
    {{--<p><a href="{{ route('backpack.account.info') }}">{{ backpack_auth()->user()->name }}</a></p>--}}
    <p style="overflow: hidden;text-overflow: ellipsis;max-width: 160px;" data-toggle="tooltip" title="{{ backpack_auth()->user()->name }}">{{ backpack_auth()->user()->name }}</p>
    {{--<small><small><a href="{{ route('backpack.account.info') }}"><span><i class="fa fa-user-circle-o"></i> {{ trans('backpack::base.my_account') }}</span></a> &nbsp;  &nbsp; <a href="{{ backpack_url('logout') }}"><i class="fa fa-sign-out"></i> <span>{{ trans('backpack::base.logout') }}</span></a></small></small>--}}
    <small><small>UG: <b>{{ (session()->get('user_ug')) ? session()->get('user_ug') : ' - ' }}</b></small></small>
  </div>
</div>