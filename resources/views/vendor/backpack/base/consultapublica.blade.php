@extends('backpack::layout')

@section('header')
    <section class="content-header">
        <h1>
            Transparência
            <small>Comprasnet Contratos</small>
        </h1>
@if((!is_null(session('user_ug'))))
        <ol class="breadcrumb">
            <li><a href="{{ backpack_url() }}">{{ config('backpack.base.project_name') }}</a></li>
            <li class="active">Transparência</li>
        </ol>
@endif
    </section>
@endsection

@section('content')
    {{--  Filtro Dashboard  --}}
    <div class="row">
        <section class="col-lg-12 connectedSortable ui-sortable">
            <div class="box box-solid box-primary">
                <div class="box-header with-border">
                    <i class="fa fa-search"></i>
                    <h3 class="box-title">Filtro</h3>

                    <div class="box-tools pull-right">
                        <a href="/transparencia" class="btn btn-box-tool" title="Voltar">
                            <i class="fa fa-times"></i>
                        </a>
                    </div>
                </div>
                <div class="box-body">
                    <fieldset class="form-group">
                        {{--                                {!! form($form) !!}--}}
                        {!! form_start($form) !!}
                        <div class="col-md-6">
                            {!! form_row($form->orgao) !!}
                        </div>
                        <div class="col-md-6">
                            {!! form_row($form->unidade) !!}
                        </div>
                        <div class="col-md-6">
                            {!! form_row($form->fornecedor) !!}
                        </div>
                        <div class="col-md-6">
                            {!! form_row($form->contrato) !!}
                        </div>
                        <div class="col-md-12">
                            {!! form_end($form) !!}
                        </div>
                    </fieldset>
                </div>

            </div>
        </section>
    </div>
    <div class="row">
        <div class="col-md-3">
            @include('backpack::inc.quantitativos_contratos')
        </div>
        <div class="col-md-6">
            @include('backpack::inc.valor_contratado')
            @include('backpack::inc.grafico_categoria_contratos')
        </div>
        <div class="col-md-3">
            @include('backpack::inc.grafico_contratos_por_ano')
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            @include('backpack::inc.grafico_cronograma_contratos')
        </div>
    </div>
@endsection

@push('after_scripts')
    <script type="text/javascript">
        $(document).ready(function () {
                @if(isset($data['fields']))
                @foreach($data['fields'] as $field_key => $field_value)
                @foreach($field_value as $key => $value)
            var newOption = new Option('{{$value}}', '{{$key}}', false, false);
            $('#{{$field_key}}').append(newOption).trigger('change');
            @endforeach
            @endforeach
            @endif
            $("#orgao").each(function (i, obj) {
                var form = $(obj).closest('form');

                if (!$(obj).hasClass("select2-hidden-accessible")) {
                    $(obj).select2({
                        theme: 'bootstrap',
                        language: 'pt-BR',
                        // multiple: true,
                        placeholder: "Selecione",
                        allowClear: true,
                        // minimumInputLength: "2",
                        ajax: {
                            url: "/api/transparenciaorgaos",
                            dataType: 'json',
                            quietMillis: 250,
                            data: function (params) {
                                return {
                                    q: params.term, // search term
                                };
                            },
                            processResults: function (data) {
                                return {
                                    results: $.map(data.data, function (orgao) {
                                        return {
                                            text: orgao.codigo + ' - ' + orgao.nome,
                                            id: orgao.codigo
                                        }
                                    }),
                                };
                            },
                            cache: true
                        },
                    });
                }
            });

            $("#unidade").each(function (i, obj) {
                var form = $(obj).closest('form');

                if (!$(obj).hasClass("select2-hidden-accessible")) {
                    $(obj).select2({
                        theme: 'bootstrap',
                        language: 'pt-BR',
                        // multiple: true,
                        placeholder: "Selecione",
                        allowClear: true,
                        // minimumInputLength: "2",
                        ajax: {
                            url: "/api/transparenciaunidades",
                            dataType: 'json',
                            quietMillis: 250,
                            data: function (params) {
                                return {
                                    q: params.term, // search term
                                    orgao: $('#orgao').val(), // search term
                                };
                            },
                            processResults: function (data) {
                                return {
                                    results: $.map(data.data, function (unidade) {
                                        return {
                                            text: unidade.codigo + ' - ' + unidade.nomeresumido,
                                            id: unidade.codigo
                                        }
                                    }),
                                };
                            },
                            cache: true
                        },
                    });
                }
            });


            $("#fornecedor").each(function (i, obj) {
                var form = $(obj).closest('form');

                if (!$(obj).hasClass("select2-hidden-accessible")) {
                    $(obj).select2({
                        theme: 'bootstrap',
                        language: 'pt-BR',
                        // multiple: true,
                        placeholder: "Selecione",
                        allowClear: true,
                        // minimumInputLength: "2",
                        ajax: {
                            url: "/api/transparenciafornecedores",
                            dataType: 'json',
                            quietMillis: 250,
                            data: function (params) {
                                return {
                                    q: params.term, // search term
                                    unidade: $('#unidade').val(), // search term
                                };
                            },
                            processResults: function (data) {
                                return {
                                    results: $.map(data.data, function (fornecedor) {
                                        return {
                                            text: fornecedor.cpf_cnpj_idgener + ' - ' + fornecedor.nome,
                                            id: fornecedor.cpf_cnpj_idgener
                                        }
                                    }),
                                };
                            },
                            cache: true
                        }
                    });
                }
            });

            $("#contrato").each(function (i, obj) {
                var form = $(obj).closest('form');

                if (!$(obj).hasClass("select2-hidden-accessible")) {
                    $(obj).select2({
                        theme: 'bootstrap',
                        language: 'pt-BR',
                        // multiple: true,
                        placeholder: "Selecione",
                        allowClear: true,
                        // minimumInputLength: "2",
                        ajax: {
                            url: "/api/transparenciacontratos",
                            dataType: 'json',
                            quietMillis: 250,
                            data: function (params) {
                                return {
                                    q: params.term, // search term
                                    fornecedor: $('#fornecedor').val(), // search term
                                };
                            },
                            processResults: function (data) {
                                return {
                                    results: $.map(data.data, function (contrato) {
                                        return {
                                            text: contrato.numero,
                                            id: contrato.numero
                                        }
                                    }),
                                };
                            },
                            cache: true
                        },
                    });
                }
            });
        });
    </script>
@endpush
