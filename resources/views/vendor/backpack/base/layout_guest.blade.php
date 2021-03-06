<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    @include('backpack::inc.head')
    <style>
        .button-ok {
            width: 100%;
            height: 40px;
            position:static;
            top:50%;
            left:50%;
        }

        .button-ok {
            background: #1351B4 0 0 no-repeat padding-box;
            box-shadow: 0 2px 3px #00000029;
            border-radius: 24px;
            font-size: 1em;
            font-weight: bold;
            color: #FFFFFF;
            cursor: pointer;
        }
    </style>
</head>
<body class="hold-transition {{ config('backpack.base.skin') }} fixed">
    <!-- Site wrapper -->
    <div class="wrapper">
      <!-- Content Wrapper. Contains page content -->
      <div class="content-wrapper no-margin no-padding">

        <!-- Content Header (Page header) -->
         @yield('header')

        <!-- Main content -->
        <section class="content">

          @yield('content')

        </section>
        <!-- /.content -->
      </div>
      <!-- /.content-wrapper -->

      <footer class="main-footer m-l-0 text-sm">
        @include('backpack::inc.footer')
      </footer>
    </div>
    <!-- ./wrapper -->


    @yield('before_scripts')
    @stack('before_scripts')

    @include('backpack::inc.scripts')
    @include('backpack::inc.alerts')

    @yield('after_scripts')
    @stack('after_scripts')

    <!-- JavaScripts -->
    {{-- <script src="{{ mix('js/app.js') }}"></script> --}}
</body>
</html>
