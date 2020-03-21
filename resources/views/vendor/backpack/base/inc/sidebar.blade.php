@if (backpack_auth()->check())
    <!-- Left side column. contains the sidebar -->
    <aside class="main-sidebar">
        <!-- sidebar: style can be found in sidebar.less -->
        <section class="sidebar">
            <!-- Sidebar user panel -->
        @include('backpack::inc.sidebar_user_panel')

        <!-- sidebar menu: : style can be found in sidebar.less -->
            <ul class="sidebar-menu" data-widget="tree">
            {{-- <li class="header">{{ trans('backpack::base.administration') }}</li> --}}
            <!-- ================================================ -->
                <!-- ==== Recommended place for admin menu items ==== -->
                <!-- ================================================ -->

            @if(substr(request()->getPathInfo(),0,14)=='/transparencia')
                @include('backpack::inc.sidebar_content_public')
            @else
                @include('backpack::inc.sidebar_content')
            @endif

            <!-- ======================================= -->
                {{-- <li class="header">Other menus</li> --}}
            </ul>
        </section>
        <!-- /.sidebar -->
    </aside>
@else
    <aside class="main-sidebar">
        <!-- sidebar: style can be found in sidebar.less -->
        <section class="sidebar">
            <!-- Sidebar user panel -->
            <!-- sidebar menu: : style can be found in sidebar.less -->
            <ul class="sidebar-menu" data-widget="tree">
            {{-- <li class="header">{{ trans('backpack::base.administration') }}</li> --}}
            <!-- ================================================ -->
                <!-- ==== Recommended place for admin menu items ==== -->
                <!-- ================================================ -->

            @include('backpack::inc.sidebar_content_public')

            <!-- ======================================= -->
                {{-- <li class="header">Other menus</li> --}}
            </ul>
        </section>
        <!-- /.sidebar -->
    </aside>
@endif
