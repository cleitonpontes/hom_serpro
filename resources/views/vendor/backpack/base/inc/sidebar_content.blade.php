<!-- This file is used to store sidebar items, starting with Backpack\Base 0.9.0 -->
<li><a href="{{ backpack_url('dashboard') }}"><i class="fa fa-dashboard"></i> <span>{{ trans('backpack::base.dashboard') }}</span></a></li>

{{--@can('administracao_inicio_acesso')--}}
    <li class="treeview">
        <a href="#"><i class="fa fa-gears"></i> <span>Administração</span> <i
                    class="fa fa-angle-left pull-right"></i></a>
        <ul class="treeview-menu">
            <li class="treeview">
                <a href="#"><i class='fa fa-bank'></i> <span>Estrutura</span> <i
                            class="fa fa-angle-left pull-right"></i></a>
                <ul class="treeview-menu">
                    @can('administracao_orgaosuperior_acesso')
                        <li><a href="{{ backpack_url('orgaosuperior') }}"><i class="fa fa-building"></i> <span>Órgão Superior</span></a>
                        </li>
                    @endcan
                    <li><a href="{{ backpack_url('orgao') }}"><i class="fa fa-building"></i> <span>Órgão</span></a></li>
                    <li><a href="{{ backpack_url('unidade') }}"><i class="fa fa-building"></i> <span>Unidade</span></a>
                    </li>
                </ul>
            </li>
            <li class="treeview">
                <a href="#"><i class='fa fa-lock'></i> <span>Acesso</span> <i
                            class="fa fa-angle-left pull-right"></i></a>
                <ul class="treeview-menu">
                    <li><a href='{{ backpack_url('/admin/usuario') }}'><i class='fa fa-user'></i> <span>Usuários</span></a></li>
                    <li><a href="{{ backpack_url('/admin/role') }}"><i class="fa fa-group"></i> <span>Grupos</span></a></li>
                    <li><a href="{{ backpack_url('/admin/permission') }}"><i class="fa fa-key"></i> <span>Permissões</span></a>
                    </li>
                </ul>
            </li>
            <li class="treeview">
                <a href="#"><i class='fa fa-terminal'></i> <span>Logs</span> <i class="fa fa-angle-left pull-right"></i></a>
                <ul class="treeview-menu">
                    <li><a href='{{ url(config('backpack.base.route_prefix', 'admin').'/log') }}'><i class='fa fa-terminal'></i> <span>Logs</span></a></li>
                    <li><a href='{{ backpack_url('/activitylog') }}'><i class='fa fa-terminal'></i>
                            <span>Logs Banco</span></a></li>
                </ul>
            </li>
        </ul>
    </li>
{{--@endcan--}}