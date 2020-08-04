@extends('backpack::layout_guest')

@section('content')

    <div class="login-logo">
        <a href="{{ url('/inicio') }}">
            <img src="data:image/png;base64,<?php echo base64_encode(file_get_contents("../public/img/logo.png")) ?>" width="200px"
                 alt="{!! env('APP_NAME') !!}"></a>
    </div>
    <nav class="nav_tabs">
        <ul>
            <li>
                <input type="radio" id="tab1" class="rd_tab" name="tabs" checked>
                <label for="tab1" class="tab_label">Login</label>
                <div class="tab-content">

                    <div class="row">
                        <div class="col-md-8 col-md-offset-2">
                            {{--<h3 class="text-center m-b-20">{{ trans('backpack::base.login') }}</h3>--}}

                            <div class="box">
                                <div class="box-body">
                                    <form class="col-md-12 p-t-10" role="form" method="POST"
                                          action="{{ route('backpack.auth.login') }}">
                                        {!! csrf_field() !!}

                                        <div class="form-group{{ $errors->has($username) ? ' has-error' : '' }}">
                                            <label class="control-label">{{ config('backpack.base.authentication_column_name') }}</label>

                                            <div>
                                                <input type="text" class="form-control" id="{{ $username }}" name="{{ $username }}"
                                                       value="{{ old($username) }}">

                                                @if ($errors->has($username))
                                                    <span class="help-block">
                                        <strong>{{ $errors->first($username) }}</strong>
                                    </span>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                                            <label class="control-label">{{ trans('backpack::base.password') }}</label>

                                            <div>
                                                <input type="password" class="form-control" name="password">

                                                @if ($errors->has('password'))
                                                    <span class="help-block">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <div>
                                                <div class="checkbox">
                                                    <label>
                                                        <input type="checkbox"
                                                               name="remember"> {{ trans('backpack::base.remember_me') }}
                                                    </label>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <div>
                                                <button type="submit" class="btn btn-block btn-primary">
                                                    {{ trans('backpack::base.login') }}
                                                </button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            @if (backpack_users_have_email())
                                <div class="text-center m-t-10"><a
                                        href="{{ route('backpack.auth.password.reset') }}">{{ trans('backpack::base.forgot_your_password') }}</a>
                                </div>
                            @endif

                            @if (config('backpack.base.registration_open'))
                                <div class="text-center m-t-10"><a
                                        href="{{ route('backpack.auth.register') }}">{{ trans('backpack::base.register') }}</a>
                                </div>
                            @endif
                        </div>
                    </div>

                </div>
            </li>
            <li>
                <input type="radio" name="tabs" class="rd_tab" id="tab2">
                <label for="tab2" class="tab_label">Acesso Gov</label>
                <div class="tab-content">
                    <div class="box">
                        <div class="box-body">
                            <div class="text-center m-t-10">
                                <a href="{{ url('/acessogov/autorizacao') }}">
                                    <img src="data:image/png;base64,<?php echo base64_encode(file_get_contents("../public/img/govbr.png")) ?>" width="150px"
                                         alt="Entrar via Acesso Gov"></a>
                            <div>
                                <a href="{{ url('/acessogov/autorizacao') }}" class="btn btn-primary" id="prev_aba"><span class="fa fa-sign-in"></span> &nbsp;Entrar via Acesso Gov</a>
                            </div>
                            </div>
                        </div>
                    </div>

                </div>
            </li>
            <li>
                <input type="radio" name="tabs" class="rd_tab" id="tab3">
                <label for="tab3" class="tab_label">Transparência</label>
                <div class="tab-content">
                    <div class="box">
                        <div class="box-body">
                            <div class="text-center m-t-10">
                                <div class="transparencia">
                                    <a href="/transparencia" class="btn btn-primary" id="prev_aba"><span class="fa fa-indent"></span> Acessar Transparência</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </li>
        </ul>
    </nav>
    @push('after_scripts')
        <script type="text/javascript">
            $('#{{ $username }}').mask('999.999.999-99');
        </script>
    @endpush
    <style>
        *{
            margin: 0;
            padding: 0;
        }

        body{
            background-color: #ddd;
        }

        .nav_tabs{
            width: 600px;
            height: 400px;
            margin: 20px auto;
            background-color: #fff;
            position: relative;
        }

        .nav_tabs ul{
            list-style: none;
        }

        .nav_tabs ul li{
            float: left;
        }

        .tab_label{
            display: block;
            width: 200px;
            background-color: #646869;
            padding: 25px;
            font-size: 20px;
            color:#fff;
            cursor: pointer;
            text-align: center;
        }


        .nav_tabs .rd_tab {
            display:none;
            position: absolute;
        }

        .nav_tabs .rd_tab:checked ~ label {
            background-color: #3894d1;
            color:#fff;}

        .tab-content{
            border-top: solid 5px #3894d1;
            background-color: #fff;
            display: none;
            position: absolute;
            height: 320px;
            width: 600px;
            left: 0;
        }

        .rd_tab:checked ~ .tab-content{
            display: block;
        }
        .tab-content h2{
            padding: 10px;
            color: #87d3b7;
        }
        .tab-content article{
            padding: 10px;
            color: #555;
        }

        .box-body img{
            position: relative;
            padding: 20px;
        }


        .transparencia a{
            margin-top: 50px;
        }
    </style>
@endsection
