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
        Mudar UG/UASG
    </h1>

    <ol class="breadcrumb">

        <li>
            <a href="{{ backpack_url() }}">{{ config('backpack.base.project_name') }}</a>
        </li>

        <li>
            <a href="{{ route('inicio.mudarug') }}">Mudar UG/UASG</a>
        </li>

    </ol>

</section>
@endsection

@section('content')
<div class="row">
    <div class="col-md-12">

            <div class="box padding-10">

                <div class="box-body backpack-profile-form">

                    <fieldset class="form-group">
                        {!! form($form->add('edit','submit',[
                            'attr' => [
                                'class' => 'btn btn-success'
                            ],
                            'label' => '<i class="fa fa-floppy-o"></i>&nbsp;&nbsp;Alterar'
                        ])) !!}
                    </fieldset>

                </div>
            </div>

    </div>
</div>
@endsection
