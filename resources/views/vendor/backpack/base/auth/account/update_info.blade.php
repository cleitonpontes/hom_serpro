@extends('backpack::layout')

@section('after_styles')
<style media="screen">
    .backpack-profile-form .required::after {
        content: ' *';
        color: red;
    }
</style>
@endsection

@section('header')
<section class="content-header">

    <h1>
        {{ trans('backpack::base.my_account') }}
    </h1>

    <ol class="breadcrumb">

        <li>
            <a href="{{ backpack_url() }}">{{ config('backpack.base.project_name') }}</a>
        </li>

        <li>
            <a href="{{ route('backpack.account.info') }}">{{ trans('backpack::base.my_account') }}</a>
        </li>

        <li class="active">
            {{ trans('backpack::base.update_account_info') }}
        </li>

    </ol>

</section>
@endsection

@section('content')
<div class="row">
    <div class="col-md-3">
        @include('backpack::auth.account.sidemenu')
    </div>
    <div class="col-md-6">

        <form class="form" action="{{ route('backpack.account.info') }}" method="post">

            {!! csrf_field() !!}

            <div class="box padding-10">

                <div class="box-body backpack-profile-form">

                    @if (session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if ($errors->count())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $e)
                                <li>{{ $e }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <div class="form-group">
                        @php
                            $label = 'CPF';
                            $field = 'cpf';
                        @endphp
                        <label>{{ $label }}</label>
                        <input class="form-control" disabled="disabled" type="text" name="{{ $field }}" value="{{ old($field) ? old($field) : $user->$field }}">
                    </div>
                    <div class="form-group">
                        @php
                            $label = trans('backpack::base.name');
                            $field = 'name';
                        @endphp
                        <label class="required">{{ $label }}</label>
                        <input required class="form-control" type="text" name="{{ $field }}" value="{{ old($field) ? old($field) : $user->$field }}">
                    </div>
                    <div class="form-group">
                        @php
                            $label = 'E-mail';
                            $field = 'email';
                        @endphp
                        <label class="required">{{ $label }}</label>
                        <input required class="form-control" type="text" name="{{ $field }}" value="{{ old($field) ? old($field) : $user->$field }}">
                    </div>
                    <div class="form-group">
                        @php
                            $label = 'UG Primária';
                            $field = 'ugprimaria';
                            $ug = \Illuminate\Support\Facades\DB::table('unidades')->find($user->$field);
                        @endphp
                        <label>{{ $label }}</label>
                        <select name="{{ $field }}" class="form-control" disabled="disabled">
                            <option value="{{$ug->codigo ? $ug->codigo : ''}}">{{$ug->codigo ? $ug->codigo : ''}}</option>
                        </select>
                        {{--<input required class="form-control" disabled="disabled" type="text" name="{{ $field }}" value="{{ old($field) ? old($field) : $ug->codigo }}">--}}
                    </div>
                    <div class="form-group">
                        @php
                            $label = 'Senha Siafi';
                            $field = 'senhasiafi';
                        @endphp
                        <label>{{ $label }}</label>
                        <input class="form-control" type="password" name="{{ $field }}" value="{{ old($field) ? old($field) : $user->$field }}">
                    </div>
                    {{--<div class="form-group">--}}
                        {{--@php--}}
                            {{--$label = config('backpack.base.authentication_column_name');--}}
                            {{--$field = backpack_authentication_column();--}}
                        {{--@endphp--}}
                        {{--<label class="required">{{ $label }}</label>--}}
                        {{--<input required class="form-control" type="{{ backpack_authentication_column()=='email'?'email':'text' }}" name="{{ $field }}" value="{{ old($field) ? old($field) : $user->$field }}">--}}
                    {{--</div>--}}

                    <div class="form-group m-b-0">
                        <button type="submit" class="btn btn-success"><span class="ladda-label"><i class="fa fa-save"></i> {{ trans('backpack::base.save') }}</span></button>
                        <a href="{{ backpack_url() }}" class="btn btn-default"><span class="ladda-label">{{ trans('backpack::base.cancel') }}</span></a>
                    </div>

                </div>
            </div>

        </form>

    </div>
</div>
@endsection
