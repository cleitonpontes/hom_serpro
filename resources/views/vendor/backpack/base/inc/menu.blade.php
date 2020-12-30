<div class="navbar-custom-menu pull-left">
    <ul class="nav navbar-nav">
        <!-- =================================================== -->
        <!-- ========== Top menu items (ordered left) ========== -->
        <!-- =================================================== -->

    @if (backpack_auth()->check())
        <!-- Topbar. Contains the left part -->
        @include('backpack::inc.topbar_left_content')
    @endif

    <!-- ========== End of top menu left items ========== -->
    </ul>
</div>


<div class="navbar-custom-menu pull-right">
    <ul class="nav navbar-nav">
        <!-- ========================================================= -->
        <!-- ========= Top menu right items (ordered right) ========== -->
        <!-- ========================================================= -->

        @if (config('backpack.base.setup_auth_routes'))
            @if (backpack_auth()->guest())
                <li>
                    <a href="{{ url(config('backpack.base.route_prefix', 'admin').'/login') }}">{{ trans('backpack::base.login') }}</a>
                </li>
                @if (config('backpack.base.registration_open'))
                    <li><a href="{{ route('backpack.auth.register') }}">{{ trans('backpack::base.register') }}</a></li>
                @endif
            @else
            <!-- Topbar. Contains the right part -->
                @if((!is_null(session('user_ug'))))
                    <li><a href="https://forms.gle/E5XFfEN3mJb7tmE66" target="_blank"><span><i class="fa fa-bug"></i> Informar Erro</a></li>
                    <li><a href="{{ url('/mudar-ug') }}"><span><i class="fa fa-exchange"></i> Mudar UG/UASG</a></li>
                @endif
                <li><a href="{{ route('inicio.meusdados') }}"><span><i class="fa fa-user-circle-o"></i> {{ trans('backpack::base.my_account') }}</span></a>
                </li>
                {{--<li class="dropdown">--}}
                    {{--<a href="#" data-toggle="control-sidebar" title="Configurações"><i class="fa fa-gears"></i></a>--}}
                    {{--<li class="dropdown">--}}
                    {{--<a href="#" class="dropdown-toggle" data-toggle="dropdown" title="Configurações"><i--}}
                            {{--class="fa fa-gears"></i> <span class="caret"></span></a>--}}
                    {{--<ul class="dropdown-menu" role="menu">--}}
                        {{--<li><a href="{{ route('inicio.meusdados') }}"><span><i class="fa fa-user-circle-o"></i> {{ trans('backpack::base.my_account') }}</span></a>--}}
                        {{--</li>--}}
                        {{--<li><a href="{{ url('/inicio/mudaug') }}"><span><i class="fa fa-exchange"></i> Mudar UG</a></li>--}}
                        {{--<li class="divider"></li>--}}
                        {{--<li><a href="{{ url('/logout') }}" id="logout" title="Sair da Aplicação"--}}
                        {{--onclick="event.preventDefault();--}}
                        {{--document.getElementById('logout-form').submit();">--}}
                        {{--Sair da Aplicação--}}
                        {{--</a></li>--}}
                    {{--</ul>--}}
                {{--</li>--}}
                @include('backpack::inc.topbar_right_content')
                <li><a href="{{ route('backpack.auth.logout') }}"><i
                            class="fa fa-btn fa-sign-out"></i> {{ trans('backpack::base.logout') }}</a></li>
        @endif
    @endif
    <!-- ========== End of top menu right items ========== -->
    </ul>
</div>
