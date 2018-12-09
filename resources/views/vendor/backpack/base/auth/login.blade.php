@extends('backpack::layout_guest')

@section('content')
    <div class="row m-t-40">
        <div class="col-md-4 col-md-offset-4">
            {{--<h3 class="text-center m-b-20">{{ trans('backpack::base.login') }}</h3>--}}
            <div class="login-logo">
                <a href="{{ url('/inicio') }}">
                    <img src="data:image/png;base64,<?php echo base64_encode(file_get_contents("../public/img/logo.png")) ?>" width="150px"
                         alt="{!! env('APP_NAME') !!}"><br><h5>{{ trans('backpack::base.login') }}</h5></a>
            </div>
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
    @push('after_scripts')
        <script type="text/javascript">
            $('#{{ $username }}').mask('999.999.999-99');
        </script>
    @endpush
@endsection
