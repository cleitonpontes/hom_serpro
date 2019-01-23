@extends('adminlte::layouts.app')

@php
$apid = Request()->apid;
@endphp

@push('breadcrumb')
    {{ Breadcrumbs::render() }}
@endpush

@section('htmlheader_title')
    {{ trans('adminlte_lang::message.users') }}
@endsection

@section('main-content')
	@include('adminlte::mod.folha.apropriacao.cabecalho')

    <div class="box box-solid box-primary">
        <div class="box-header with-border">
            <h3 class="box-title">Listagem de Saldos a Validar</h3>
        </div>
        <div class="box-body">
            <br/>
            <div class="col-sm-12">
                {!! $html->table() !!}
            </div>
        </div>
    </div>

    @include('adminlte::mod.folha.apropriacao.botoes')
    
@endsection

@push('scripts')
    <script>
        // var oTable = $('#datatable').DataTable({
        //     retrieve: true,
        //     'fnRowCallback': function(row, data, index){
        //         if(data[5]){
        //             $(row).find('td:eq(3)').css('color', 'red');
        //         }
        //     }
        // });

        // table.fnRowCallback.function(nRow, aData, iDisplayIndex, iDisplayIndexFull)
        // {
        //     console.log(aData[1]);
        // }

        // $(document).ready(function() {
        //     $('#datatable').DataTable( {
        //         retrieve: true,
        //         "createdRow": function ( row, data, index ) {
        //             if (data[1]) {
        //                 console.log('asdf');
        //             }
        //             // if ( data[4].replace(/[\$,]/g, '') * 1 > 150000 ) {
        //                 $('td', row).eq(5).addClass('highlight');
        //                 $('td', row).eq(5).addClass('highlight');
        //             // }
        //         }
        //     } );
        // } );

        // $(document).ready(function() {
        //     $('#datatable').dataTable( {
        //         retrieve: true,
        //         "fnRowCallback": function( nRow, aData, iDisplayIndex ) {
        //             /* Append the grade to the default row class name */
        //
        //             if ( aData[1] )
        //             {
        //                 console.log('caiu ?');
        //                 $('td:eq(4)', nRow).html( '<b>A</b>' );
        //             }
        //         },
        //         "aoColumnDefs": [ {
        //             "sClass": "center",
        //             "aTargets": [ -1, -2 ]
        //         } ]
        //     } );
        // } );


            // $('#datatable').DataTable({
            //     retrieve: true,
            //     paging: false,
            //     "fnRowCallback": function( nRow, aData, iDisplayIndex, iDisplayIndexFull ) {
            //         console.log(aData);
            //         // if (data[5]) {
            //         // //     console.log(data[5]);
            //         //     $('td', nRow).css('background-color', 'Red');
            //         // }
            //
            //     }
            //
            // });
    </script>
    {!! $html->scripts() !!}
@endpush
