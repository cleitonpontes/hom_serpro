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
@if((!is_null(session('user_ug'))))
    <ol class="breadcrumb">

        <li>
            <a href="{{ backpack_url() }}">{{ config('backpack.base.project_name') }}</a>
        </li>

        <li>
            <a href="{{ route('inicio.meusdados') }}">{{ trans('backpack::base.my_account') }}</a>
        </li>

        <li class="active">
            {{ trans('backpack::base.update_account_info') }}
        </li>

    </ol>
@endif
</section>
@endsection

@section('content')
<div class="row">
    <div class="col-md-3">
        @include('backpack::auth.account.sidemenu')
    </div>
    <div class="col-md-6">

            <div class="box padding-10">

                <div class="box-body backpack-profile-form">

                    <fieldset class="form-group">
                        {!! form($form->add('edit','submit',[
                            'attr' => [
                                'class' => 'btn btn-success'
                            ],
                            'label' => '<i class="fa fa-floppy-o"></i>&nbsp;&nbsp;Salvar'
                        ])) !!}
                    </fieldset>

                </div>
            </div>

    </div>
</div>
@endsection
