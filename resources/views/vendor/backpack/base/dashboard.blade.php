@extends('backpack::layout')

@section('header')
    <section class="content-header">
        <h1>
            Início
            <small>Sistema Conta</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ backpack_url() }}">{{ config('backpack.base.project_name') }}</a></li>
            <li class="active">{{ trans('backpack::base.dashboard') }}</li>
        </ol>
    </section>
@endsection


@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="box">
                <div class="box-header with-border">
                    <div class="box-title">Informações gerais</div>
                </div>

                <div class="box-body">
                    <div class="row">
                        <div class="col-lg-3 col-xs-6">
                            <!-- small box -->
                            <div class="small-box bg-aqua">
                                <div class="inner">
                                    <h3>7</h3>

                                    <p>Novos Contratos a serem baixados</p>
                                </div>
                                <div class="icon">
                                    <i class="ion ion-ios-cloud-download"></i>
                                </div>
                                <a href="#" class="small-box-footer">Inserir agora <i
                                            class="fa fa-arrow-circle-right"></i></a>
                            </div>
                        </div>
                        <!-- ./col -->
                        <div class="col-lg-3 col-xs-6">
                            <!-- small box -->
                            <div class="small-box bg-green">
                                <div class="inner">
                                    <h3>15</h3>

                                    <p>Contratos desatualizados</p>
                                </div>
                                <div class="icon">
                                    <i class="ion ion-ios-refresh"></i>
                                </div>
                                <a href="#" class="small-box-footer">Atualizar agora <i
                                            class="fa fa-arrow-circle-right"></i></a>
                            </div>
                        </div>
                        <!-- ./col -->
                        <div class="col-lg-3 col-xs-6">
                            <!-- small box -->
                            <div class="small-box bg-yellow">
                                <div class="inner">
                                    <h3>5</h3>

                                    <p>Mensagens pendentes</p>
                                </div>
                                <div class="icon">
                                    <i class="ion ion-ios-email"></i>
                                </div>
                                <a href="#" class="small-box-footer">Ler agora <i class="fa fa-arrow-circle-right"></i></a>
                            </div>
                        </div>
                        <!-- ./col -->
                        <div class="col-lg-3 col-xs-6">
                            <!-- small box -->
                            <div class="small-box bg-red">
                                <div class="inner">
                                    <h3>03</h3>

                                    <p>Contratos vencidos e ativos</p>
                                </div>
                                <div class="icon">
                                    <i class="ion ion-ios-close"></i>
                                </div>
                                <a href="#" class="small-box-footer">Solucionar <i class="fa fa-arrow-circle-right"></i></a>
                            </div>
                        </div>
                        <!-- ./col -->
                    </div>
                </div>
                <div class="box box-solid bg-green-gradient">
                    <div class="box-header ui-sortable-handle" style="cursor: move;">
                        <i class="fa fa-calendar"></i>

                        <h3 class="box-title">Calendário</h3>
                        <!-- tools box -->
                        <div class="pull-right box-tools">
                            <!-- button with a dropdown -->
                            <div class="btn-group">
                                <button type="button" class="btn btn-success btn-sm dropdown-toggle"
                                        data-toggle="dropdown">
                                    <i class="fa fa-bars"></i></button>
                                <ul class="dropdown-menu pull-right" role="menu">
                                    <li><a href="#">Add new event</a></li>
                                    <li><a href="#">Clear events</a></li>
                                    <li class="divider"></li>
                                    <li><a href="#">View calendar</a></li>
                                </ul>
                            </div>
                            <button type="button" class="btn btn-success btn-sm" data-widget="collapse"><i
                                        class="fa fa-minus"></i>
                            </button>
                            <button type="button" class="btn btn-success btn-sm" data-widget="remove"><i
                                        class="fa fa-times"></i>
                            </button>
                        </div>
                        <!-- /. tools -->
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body no-padding">
                        <!--The calendar -->
                        <div id="calendar" style="width: 100%">
                            <div class="datepicker datepicker-inline">
                                <div class="datepicker-days" style="">
                                    <table class="table-condensed">
                                        <thead>
                                        <tr>
                                            <th colspan="7" class="datepicker-title" style="display: none;"></th>
                                        </tr>
                                        <tr>
                                            <th class="prev">«</th>
                                            <th colspan="5" class="datepicker-switch">Novembro 2018</th>
                                            <th class="next">»</th>
                                        </tr>
                                        <tr>
                                            <th class="dow">Su</th>
                                            <th class="dow">Mo</th>
                                            <th class="dow">Tu</th>
                                            <th class="dow">We</th>
                                            <th class="dow">Th</th>
                                            <th class="dow">Fr</th>
                                            <th class="dow">Sa</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr>
                                            <td class="old day" data-date="1540684800000">28</td>
                                            <td class="old day" data-date="1540771200000">29</td>
                                            <td class="old day" data-date="1540857600000">30</td>
                                            <td class="old day" data-date="1540944000000">31</td>
                                            <td class="day" data-date="1541030400000">1</td>
                                            <td class="day" data-date="1541116800000">2</td>
                                            <td class="day" data-date="1541203200000">3</td>
                                        </tr>
                                        <tr>
                                            <td class="day" data-date="1541289600000">4</td>
                                            <td class="day" data-date="1541376000000">5</td>
                                            <td class="day" data-date="1541462400000">6</td>
                                            <td class="day" data-date="1541548800000">7</td>
                                            <td class="day" data-date="1541635200000">8</td>
                                            <td class="day" data-date="1541721600000">9</td>
                                            <td class="day" data-date="1541808000000">10</td>
                                        </tr>
                                        <tr>
                                            <td class="day" data-date="1541894400000">11</td>
                                            <td class="day" data-date="1541980800000">12</td>
                                            <td class="day" data-date="1542067200000">13</td>
                                            <td class="day" data-date="1542153600000">14</td>
                                            <td class="day" data-date="1542240000000">15</td>
                                            <td class="day" data-date="1542326400000">16</td>
                                            <td class="day" data-date="1542412800000">17</td>
                                        </tr>
                                        <tr>
                                            <td class="day" data-date="1542499200000">18</td>
                                            <td class="day" data-date="1542585600000">19</td>
                                            <td class="day" data-date="1542672000000">20</td>
                                            <td class="day" data-date="1542758400000">21</td>
                                            <td class="day" data-date="1542844800000">22</td>
                                            <td class="day" data-date="1542931200000">23</td>
                                            <td class="day" data-date="1543017600000">24</td>
                                        </tr>
                                        <tr>
                                            <td class="day" data-date="1543104000000">25</td>
                                            <td class="day" data-date="1543190400000">26</td>
                                            <td class="day" data-date="1543276800000">27</td>
                                            <td class="day" data-date="1543363200000">28</td>
                                            <td class="day" data-date="1543449600000">29</td>
                                            <td class="day" data-date="1543536000000">30</td>
                                            <td class="new day" data-date="1543622400000">1</td>
                                        </tr>
                                        <tr>
                                            <td class="new day" data-date="1543708800000">2</td>
                                            <td class="new day" data-date="1543795200000">3</td>
                                            <td class="new day" data-date="1543881600000">4</td>
                                            <td class="new day" data-date="1543968000000">5</td>
                                            <td class="new day" data-date="1544054400000">6</td>
                                            <td class="new day" data-date="1544140800000">7</td>
                                            <td class="new day" data-date="1544227200000">8</td>
                                        </tr>
                                        </tbody>
                                        <tfoot>
                                        <tr>
                                            <th colspan="7" class="today" style="display: none;">Today</th>
                                        </tr>
                                        <tr>
                                            <th colspan="7" class="clear" style="display: none;">Clear</th>
                                        </tr>
                                        </tfoot>
                                    </table>
                                </div>
                                <div class="datepicker-months" style="display: none;">
                                    <table class="table-condensed">
                                        <thead>
                                        <tr>
                                            <th colspan="7" class="datepicker-title" style="display: none;"></th>
                                        </tr>
                                        <tr>
                                            <th class="prev">«</th>
                                            <th colspan="5" class="datepicker-switch">2018</th>
                                            <th class="next">»</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr>
                                            <td colspan="7"><span class="month">Jan</span><span class="month">Feb</span><span
                                                        class="month">Mar</span><span class="month">Apr</span><span
                                                        class="month">May</span><span class="month">Jun</span><span
                                                        class="month">Jul</span><span class="month">Aug</span><span
                                                        class="month">Sep</span><span class="month">Oct</span><span
                                                        class="month focused">Nov</span><span class="month">Dec</span>
                                            </td>
                                        </tr>
                                        </tbody>
                                        <tfoot>
                                        <tr>
                                            <th colspan="7" class="today" style="display: none;">Today</th>
                                        </tr>
                                        <tr>
                                            <th colspan="7" class="clear" style="display: none;">Clear</th>
                                        </tr>
                                        </tfoot>
                                    </table>
                                </div>
                                <div class="datepicker-years" style="display: none;">
                                    <table class="table-condensed">
                                        <thead>
                                        <tr>
                                            <th colspan="7" class="datepicker-title" style="display: none;"></th>
                                        </tr>
                                        <tr>
                                            <th class="prev">«</th>
                                            <th colspan="5" class="datepicker-switch">2010-2019</th>
                                            <th class="next">»</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr>
                                            <td colspan="7"><span class="year old">2009</span><span
                                                        class="year">2010</span><span class="year">2011</span><span
                                                        class="year">2012</span><span class="year">2013</span><span
                                                        class="year">2014</span><span class="year">2015</span><span
                                                        class="year">2016</span><span class="year">2017</span><span
                                                        class="year focused">2018</span><span
                                                        class="year">2019</span><span class="year new">2020</span></td>
                                        </tr>
                                        </tbody>
                                        <tfoot>
                                        <tr>
                                            <th colspan="7" class="today" style="display: none;">Today</th>
                                        </tr>
                                        <tr>
                                            <th colspan="7" class="clear" style="display: none;">Clear</th>
                                        </tr>
                                        </tfoot>
                                    </table>
                                </div>
                                <div class="datepicker-decades" style="display: none;">
                                    <table class="table-condensed">
                                        <thead>
                                        <tr>
                                            <th colspan="7" class="datepicker-title" style="display: none;"></th>
                                        </tr>
                                        <tr>
                                            <th class="prev">«</th>
                                            <th colspan="5" class="datepicker-switch">2000-2090</th>
                                            <th class="next">»</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr>
                                            <td colspan="7"><span class="decade old">1990</span><span class="decade">2000</span><span
                                                        class="decade focused">2010</span><span
                                                        class="decade">2020</span><span class="decade">2030</span><span
                                                        class="decade">2040</span><span class="decade">2050</span><span
                                                        class="decade">2060</span><span class="decade">2070</span><span
                                                        class="decade">2080</span><span class="decade">2090</span><span
                                                        class="decade new">2100</span></td>
                                        </tr>
                                        </tbody>
                                        <tfoot>
                                        <tr>
                                            <th colspan="7" class="today" style="display: none;">Today</th>
                                        </tr>
                                        <tr>
                                            <th colspan="7" class="clear" style="display: none;">Clear</th>
                                        </tr>
                                        </tfoot>
                                    </table>
                                </div>
                                <div class="datepicker-centuries" style="display: none;">
                                    <table class="table-condensed">
                                        <thead>
                                        <tr>
                                            <th colspan="7" class="datepicker-title" style="display: none;"></th>
                                        </tr>
                                        <tr>
                                            <th class="prev">«</th>
                                            <th colspan="5" class="datepicker-switch">2000-2900</th>
                                            <th class="next">»</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr>
                                            <td colspan="7"><span class="century old">1900</span><span
                                                        class="century focused">2000</span><span
                                                        class="century">2100</span><span
                                                        class="century">2200</span><span
                                                        class="century">2300</span><span
                                                        class="century">2400</span><span
                                                        class="century">2500</span><span
                                                        class="century">2600</span><span
                                                        class="century">2700</span><span
                                                        class="century">2800</span><span
                                                        class="century">2900</span><span class="century new">3000</span>
                                            </td>
                                        </tr>
                                        </tbody>
                                        <tfoot>
                                        <tr>
                                            <th colspan="7" class="today" style="display: none;">Today</th>
                                        </tr>
                                        <tr>
                                            <th colspan="7" class="clear" style="display: none;">Clear</th>
                                        </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /.box-body -->
                    <div class="box-footer text-black">
                        <div class="row">
                            <div class="col-sm-6">
                                <!-- Progress bars -->
                                <div class="clearfix">
                                    <span class="pull-left">Task #1</span>
                                    <small class="pull-right">90%</small>
                                </div>
                                <div class="progress xs">
                                    <div class="progress-bar progress-bar-green" style="width: 90%;"></div>
                                </div>

                                <div class="clearfix">
                                    <span class="pull-left">Task #2</span>
                                    <small class="pull-right">70%</small>
                                </div>
                                <div class="progress xs">
                                    <div class="progress-bar progress-bar-green" style="width: 70%;"></div>
                                </div>
                            </div>
                            <!-- /.col -->
                            <div class="col-sm-6">
                                <div class="clearfix">
                                    <span class="pull-left">Task #3</span>
                                    <small class="pull-right">60%</small>
                                </div>
                                <div class="progress xs">
                                    <div class="progress-bar progress-bar-green" style="width: 60%;"></div>
                                </div>

                                <div class="clearfix">
                                    <span class="pull-left">Task #4</span>
                                    <small class="pull-right">40%</small>
                                </div>
                                <div class="progress xs">
                                    <div class="progress-bar progress-bar-green" style="width: 40%;"></div>
                                </div>
                            </div>
                            <!-- /.col -->
                        </div>
                        <!-- /.row -->
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
