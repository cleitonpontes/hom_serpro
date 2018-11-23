<div class="navbar-custom-menu pull-left">
    <ul class="nav navbar-nav">
        <!-- =================================================== -->
        <!-- ========== Top menu items (ordered left) ========== -->
        <!-- =================================================== -->

    <!-- <li><a href="{{ url('/') }}"><i class="fa fa-home"></i> <span>Home</span></a></li> -->

        <!-- ========== End of top menu left items ========== -->
    </ul>
</div>


<div class="navbar-custom-menu">
    <ul class="nav navbar-nav">
        <!-- ========================================================= -->
        <!-- ========== Top menu right items (ordered left) ========== -->
        <!-- ========================================================= -->

    <!-- <li><a href="{{ url('/') }}"><i class="fa fa-home"></i> <span>Home</span></a></li> -->
        @if (config('backpack.base.setup_auth_routes'))
            @if (backpack_auth()->guest())
                <li>
                    <a href="{{ url(config('backpack.base.route_prefix', 'admin').'/login') }}">{{ trans('backpack::base.login') }}</a>
                </li>
                @if (config('backpack.base.registration_open'))
                    <li><a href="{{ route('backpack.auth.register') }}">{{ trans('backpack::base.register') }}</a></li>
            @endif
        @else
            <!-- Notifications: style can be found in dropdown.less -->
                <li class="dropdown notifications-menu">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <i class="fa fa-bell-o"></i>
                        <span class="label label-warning">{{\App\Helpers\AdminHelper::unreadNotificationsCount()}}</span>
                    </a>
                    <ul class="dropdown-menu">
                        <li class="header">Você possui {{\App\Helpers\AdminHelper::unreadNotificationsCount()}} notificações</li>
                        @if(\App\Helpers\AdminHelper::unreadNotificationsCount() > 0)
                            <li>
                                <!-- inner menu: contains the actual data -->
                                <ul class="menu">
                                    @foreach (\App\Helpers\AdminHelper::unreadNotifications() as $notification)
                                        <li>
                                            <a href="{{$notification->markAsRead()}}">
                                                <i class="fa {{$notification['data']['icon']}} text-aqua"></i> {{$notification['data']['message']}}
                                            </a>
                                        </li>
                                    @endforeach
                                </ul>
                            </li>
                            <li class="footer"><a href="#">Ver todas</a></li>
                        @endif
                    </ul>
                </li>
            {{--@php--}}
                {{--$user = \backpack_auth();--}}
                {{----}}
                {{--$totalmsg = $user->unreadNotifications()->count();;--}}
            {{--@endphp--}}
            {{--<!-- Notifications Menu -->--}}
                {{--<li class="dropdown notifications-menu">--}}
                    {{--<!-- Menu toggle button -->--}}
                    {{--<a href="#" class="dropdown-toggle" data-toggle="dropdown" title="Mensagens">--}}
                        {{--<i class="fa fa-envelope-o"></i>--}}
                        {{--<span class="label label-warning">{{$totalmsg}}</span>--}}
                    {{--</a>--}}
                    {{--<ul class="dropdown-menu">--}}
                        {{--<li class="header">{{ str_replace('$', $totalmsg, 'Você possui $ mensagens') }}</li>--}}
                        {{--<li>--}}
                            {{--<!-- Inner Menu: contains the notifications -->--}}
                            {{--@if($totalmsg > 0)--}}
                                {{--<ul class="menu">--}}
                                    {{--@foreach($user->unreadNotifications as $notification)--}}
                                        {{--@php--}}
                                            {{--$texto = new \Html2Text\Html2Text($notification->data['texto']);--}}
                                        {{--@endphp--}}
                                        {{--<li><!-- start notification -->--}}
                                            {{--<a href="/inicio/mensagem/{{$notification->id}}">--}}
                                                {{--<i class="fa fa-envelope-o text-aqua"></i> {{$notification->data['assunto']}}--}}
                                                {{--<br> {!! substr(ucfirst(strtolower(mb_convert_encoding($texto->getText(),'HTML-ENTITIES','UTF-8'))),0,40).' ...' !!}--}}
                                            {{--</a>--}}
                                        {{--</li><!-- end notification -->--}}
                                    {{--@endforeach--}}
                                {{--</ul>--}}
                            {{--@endif--}}
                        {{--</li>--}}
                        {{--@if($totalmsg > 0)--}}
                            {{--<li class="footer"><a--}}
                                        {{--href="/inicio/mensagens/lertodas/{{$user->id}}">{{ trans('adminlte_lang::message.readall') }}</a>--}}
                            {{--</li>--}}
                        {{--@else--}}
                            {{--<li class="footer">--}}
                                {{--<div align="center"> Nada pendente</div>--}}
                            {{--</li>--}}
                        {{--@endif--}}
                    {{--</ul>--}}
                {{--</li>--}}
                <li class="dropdown">
                    {{--<a href="#" data-toggle="control-sidebar" title="Configurações"><i class="fa fa-gears"></i></a>--}}
                    {{--<li class="dropdown">--}}
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" title="Configurações"><i
                                class="fa fa-gears"></i> <span class="caret"></span></a>
                    <ul class="dropdown-menu" role="menu">
                        <li><a href="{{ route('backpack.account.info') }}"><span><i class="fa fa-user-circle-o"></i> {{ trans('backpack::base.my_account') }}</span></a></li>
                        <li><a href="{{ url('/inicio/mudaug') }}"><span><i class="fa fa-exchange"></i> Mudar UG</a></li>
                        {{--<li class="divider"></li>--}}
                        {{--<li><a href="{{ url('/logout') }}" id="logout" title="Sair da Aplicação"--}}
                        {{--onclick="event.preventDefault();--}}
                        {{--document.getElementById('logout-form').submit();">--}}
                        {{--Sair da Aplicação--}}
                        {{--</a></li>--}}
                    </ul>
                </li>
                <li><a href="{{ route('backpack.auth.logout') }}"><i
                                class="fa fa-btn fa-sign-out"></i> {{ trans('backpack::base.logout') }}</a></li>

        @endif
    @endif
    <!-- ========== End of top menu right items ========== -->
    </ul>
</div>
