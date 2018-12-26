<!-- This file is used to store sidebar items, starting with Backpack\Base 0.9.0 -->
<li><a href="{{ backpack_url('inicio') }}"><i class="fa fa-home"></i> <span>Início</span></a></li>

<li class="treeview">
    <a href="#"><i class="fa fa-file-text-o"></i> <span>Gestão de Contratos</span> <i
            class="fa fa-angle-left pull-right"></i></a>
    <ul class="treeview-menu">

        <li class="treeview">
            <a href="#"><i class='fa fa-edit'></i> <span>Cadastro</span> <i
                    class="fa fa-angle-left pull-right"></i></a>
            <ul class="treeview-menu">
                <li><a href="{{ backpack_url('/gescon/contrato') }}"><i class="fa fa-file-text-o"></i>
                        <span>Contratos</span></a></li>
                <li><a href="{{ backpack_url('/gescon/fornecedor') }}"><i class="fa fa-users"></i>
                        <span>Fornecedores</span></a>
                </li>
            </ul>
        </li>
        <li><a href="{{ backpack_url('/gescon/meus-contratos') }}"><i class="fa fa-file-text-o"></i> <span>Meus Contratos</span></a>
        </li>
    </ul>
</li>

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
                <li><a href="{{ backpack_url('/admin/orgaosuperior') }}"><i class="fa fa-building"></i> <span>Órgão Superior</span></a>
                </li>
                <li><a href="{{ backpack_url('/admin/orgao') }}"><i class="fa fa-building"></i> <span>Órgão</span></a>
                </li>
                <li><a href="{{ backpack_url('/admin/unidade') }}"><i class="fa fa-building"></i>
                        <span>Unidade</span></a>
                </li>
            </ul>
        </li>
        {{--<li class="treeview">--}}
        {{--<a href="#"><i class="fa fa-group"></i> <span>Users, Roles, Permissions</span> <i class="fa fa-angle-left pull-right"></i></a>--}}
        {{--<ul class="treeview-menu">--}}
        {{--<li><a href="{{ backpack_url('user') }}"><i class="fa fa-user"></i> <span>Users</span></a></li>--}}
        {{--<li><a href="{{ backpack_url('role') }}"><i class="fa fa-group"></i> <span>Roles</span></a></li>--}}
        {{--<li><a href="{{ backpack_url('permission') }}"><i class="fa fa-key"></i> <span>Permissions</span></a></li>--}}
        {{--</ul>--}}
        {{--</li>--}}
        <li class="treeview">
            <a href="#"><i class='fa fa-lock'></i> <span>Acesso</span> <i
                    class="fa fa-angle-left pull-right"></i></a>
            <ul class="treeview-menu">
                <li><a href='{{ backpack_url('/admin/usuario') }}'><i class='fa fa-user'></i> <span>Usuários</span></a>
                </li>
                <li><a href="{{ backpack_url('/role') }}"><i class="fa fa-group"></i> <span>Grupos</span></a></li>
                <li><a href="{{ backpack_url('/permission') }}"><i class="fa fa-key"></i> <span>Permissões</span></a>
                </li>
            </ul>
        </li>
        <li class="treeview">
            <a href="#"><i class='fa fa-gear'></i> <span>Outros</span> <i
                    class="fa fa-angle-left pull-right"></i></a>
            <ul class="treeview-menu">
                <li><a href='{{ backpack_url('/admin/codigo') }}'><i class='fa fa-gear'></i>
                        <span>Códigos e Itens</span></a></li>
                <li><a href="{{ backpack_url('/admin/certificadosf') }}"><i class="fa fa-lock"></i> <span>Certificado Siafi</span></a>
                </li>
            </ul>
        </li>
        <li class="treeview">
            <a href="#"><i class='fa fa-terminal'></i> <span>Logs</span> <i class="fa fa-angle-left pull-right"></i></a>
            <ul class="treeview-menu">
                <li><a href='{{ url(config('backpack.base.route_prefix', 'admin').'/log') }}'><i
                            class='fa fa-terminal'></i> <span>Logs</span></a></li>
                <li><a href='{{ backpack_url('/activitylog') }}'><i class='fa fa-terminal'></i>
                        <span>Logs Banco</span></a></li>
            </ul>
        </li>
    </ul>
</li>
{{--@endcan--}}
