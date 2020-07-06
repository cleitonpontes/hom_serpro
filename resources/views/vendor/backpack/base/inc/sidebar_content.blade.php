<!-- This file is used to store sidebar items, starting with Backpack\Base 0.9.0 -->
<li><a href="{{ backpack_url('inicio') }}"><i class="fa fa-home"></i> <span>Início</span></a></li>
<li class="treeview">
    <a href="#"><i class="fa fa-bar-chart"></i> <span>Painéis</span> <i
            class="fa fa-angle-left pull-right"></i></a>
    <ul class="treeview-menu">
        <li class="treeview">
            <a href="#"><i class='fa fa-edit'></i> <span>Orçamento e Finanças</span> <i
                    class="fa fa-angle-left pull-right"></i></a>
            <ul class="treeview-menu">
                {{--                <li><a href="{{ backpack_url('/painel/financeiro') }}"><i class="fa fa-money"></i>--}}
                {{--<span>Financeiro</span></a></li>--}}
                <li><a href="{{ backpack_url('/painel/orcamentario') }}"><i class="fa fa-money"></i>
                        <span>Orçamentário</span></a></li>

            </ul>
        </li>

    </ul>
</li>
<li><a href="{{ backpack_url('/transparencia') }}"><i class="fa fa-indent"></i> <span>Transparência</span></a></li>
<li class="treeview">
    <a href="#"><i class="fa fa-dollar"></i> <span>Execução Financeira</span> <i
            class="fa fa-angle-left pull-right"></i></a>
    <ul class="treeview-menu">
        <li class="treeview">
            <a href="#"><i class='fa fa-edit'></i> <span>Apropriação</span> <i
                    class="fa fa-angle-left pull-right"></i></a>
            <ul class="treeview-menu">
                {{--                <li><a href="{{ backpack_url('/fatura/apropriacao') }}"><i class="fa fa-money"></i>--}}
                {{--<span>Fatura</span></a></li>--}}
                <li><a href="{{ backpack_url('/folha/apropriacao') }}"><i class="fa fa-money"></i>
                        <span>Folha</span></a></li>
            </ul>
        </li>
        <li class="treeview">
            <a href="#"><i class='fa fa-edit'></i> <span>Cadastro</span> <i
                    class="fa fa-angle-left pull-right"></i></a>
            <ul class="treeview-menu">
                <li><a href="{{ backpack_url('/execfin/empenho') }}"><i class="fa fa-money"></i>
                        <span>Empenho</span></a></li>
                <li><a href="{{ backpack_url('/execfin/situacaosiafi') }}"><i class="fa fa-list-ol"></i>
                        <span>Situação Siafi</span></a></li>
                <li><a href="{{ backpack_url('/execfin/rhrubrica') }}"><i class="fa fa-list-ol"></i>
                        <span>Rubrica</span></a></li>
                <li><a href="{{ backpack_url('/execfin/rhsituacao') }}"><i class="fa fa-list-ol"></i>
                        <span>RH - Situação</span></a></li>
            </ul>
        </li>
    </ul>
</li>
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
                <li><a href="{{ backpack_url('/gescon/despesaacessoria') }}"><i class="fa fa-list"></i>
                        <span>Despesas Acessórias</span></a>
                </li><li><a href="{{ backpack_url('/gescon/fornecedor') }}"><i class="fa fa-users"></i>
                        <span>Fornecedores</span></a>
                </li>
                <li><a href="{{ backpack_url('/gescon/subrogacao') }}"><i class="fa fa-copy"></i>
                        <span>Sub-rogações</span></a>
                </li>
            </ul>
        </li>
        <li class="treeview">
            <a href="#">
                <i class='fa fa-files-o'></i>
                <span>Consultas</span>
                <i class="fa fa-angle-left pull-right"></i>
            </a>
            <ul class="treeview-menu">
                <li>
                    <a href="{{ backpack_url('/gescon/consulta/arquivos') }}">
                        <i class="fa fa-file-o"></i>
                        <span>Arquivos</span>
                    </a>
                </li>
                <li>
                    <a href="{{ backpack_url('/gescon/consulta/cronogramas') }}">
                        <i class="fa fa-calendar"></i>
                        <span>Cronogramas</span>
                    </a>
                </li>
                <li>
                    <a href="{{ backpack_url('/gescon/consulta/empenhos') }}">
                        <i class="fa fa-money"></i>
                        <span>Empenhos</span>
                    </a>
                </li>
                <li>
                    <a href="{{ backpack_url('/gescon/consulta/faturas') }}">
                        <i class="fa fa-file-text-o"></i>
                        <span>Faturas</span>
                    </a>
                </li>
                <li>
                    <a href="{{ backpack_url('/gescon/consulta/garantias') }}">
                        <i class="fa fa-gift"></i>
                        <span>Garantias</span>
                    </a>
                </li>
                <li>
                    <a href="{{ backpack_url('/gescon/consulta/historicos') }}">
                        <i class="fa fa-history"></i>
                        <span>Históricos</span>
                    </a>
                </li>
                <li>
                    <a href="{{ backpack_url('/gescon/consulta/itens') }}">
                        <i class="fa fa-list-ul"></i>
                        <span>Itens</span>
                    </a>
                </li>
                <li>
                    <a href="{{ backpack_url('/gescon/consulta/ocorrencias') }}">
                        <i class="fa fa-check-square-o"></i>
                        <span>Ocorrências</span>
                    </a>
                </li>
                <li>
                    <a href="{{ backpack_url('/gescon/consulta/prepostos') }}">
                        <i class="fa fa-black-tie"></i>
                        <span>Prepostos</span>
                    </a>
                </li>
                <li>
                    <a href="{{ backpack_url('/gescon/consulta/responsaveis') }}">
                        <i class="fa fa-user-secret"></i>
                        <span>Responsáveis</span>
                    </a>
                </li>
                <li>
                    <a href="{{ backpack_url('/gescon/consulta/terceirizados') }}">
                        <i class="fa fa-users"></i>
                        <span>Terceirizados</span>
                    </a>
                </li>
            </ul>
        </li>
        <li><a href="{{ backpack_url('/gescon/meus-contratos') }}"><i class="fa fa-file-text-o"></i> <span>Meus Contratos</span></a>
        </li>
    </ul>
</li>
<li class="treeview">
    <a href="#"><i class="fa fa-file-text-o"></i> <span>Relatórios</span> <i
            class="fa fa-angle-left pull-right"></i></a>
    <ul class="treeview-menu">

        <li class="treeview">
            <a href="#"><i class='fa fa-edit'></i> <span>Contratos</span> <i
                    class="fa fa-angle-left pull-right"></i></a>
            <ul class="treeview-menu">
                <li><a href="{{ route('relatorio.listacontratosug') }}"><i class="fa fa-file-text-o"></i>
                        <span>Lista Contratos da UG</span></a></li>
                <li><a href="{{ route('relatorio.listacontratosorgao') }}"><i class="fa fa-file-text-o"></i>
                        <span>Lista Contratos do Órgão</span></a></li>
                <li><a href="{{ route('relatorio.listatodoscontratos') }}"><i class="fa fa-file-text-o"></i>
                <span>Lista Todos Contratos</span></a></li>
            </ul>
        </li>
    </ul>
</li>

@if(backpack_user()->hasRole('Administrador') or backpack_user()->hasRole('Administrador Órgão') or backpack_user()->hasRole('Administrador Unidade'))
    <li class="treeview">
        <a href="#"><i class="fa fa-gears"></i> <span>Administração</span> <i
                class="fa fa-angle-left pull-right"></i></a>
        <ul class="treeview-menu">
            @if(backpack_user()->hasRole('Administrador') or backpack_user()->hasRole('Administrador Órgão') or backpack_user()->hasRole('Administrador Unidade'))
                <li class="treeview">
                    <a href="#"><i class='fa fa-bank'></i> <span>Estrutura</span> <i
                            class="fa fa-angle-left pull-right"></i></a>
                    <ul class="treeview-menu">
                        @if(backpack_user()->hasRole('Administrador'))
                            <li><a href="{{ backpack_url('/admin/orgaosuperior') }}"><i class="fa fa-building"></i>
                                    <span>Órgão Superior</span></a>
                            </li>
                        @endif
                        @if(backpack_user()->hasRole('Administrador') or backpack_user()->hasRole('Administrador Órgão'))
                            <li><a href="{{ backpack_url('/admin/orgao') }}"><i class="fa fa-building"></i>
                                    <span>Órgão</span></a>
                            </li>
                        @endif
                        @if(backpack_user()->hasRole('Administrador') or backpack_user()->hasRole('Administrador Órgão') or backpack_user()->hasRole('Administrador Unidade'))
                            <li><a href="{{ backpack_url('/admin/unidade') }}"><i class="fa fa-building"></i>
                                    <span>Unidade</span></a>
                            </li>
                        @endif
{{--                        @if(backpack_user()->hasRole('Administrador Unidade'))--}}
{{--                            <li><a href="{{ backpack_url('/admin/administradorunidade') }}"><i--}}
{{--                                        class="fa fa-building"></i>--}}
{{--                                    <span>Unidade Admin UG</span></a>--}}
{{--                            </li>--}}
{{--                        @endif--}}
                    </ul>
                </li>
            @endif
            @if(backpack_user()->hasRole('Administrador') or backpack_user()->hasRole('Administrador Órgão') or backpack_user()->hasRole('Administrador Unidade'))
                <li class="treeview">
                    <a href="#"><i class='fa fa-lock'></i> <span>Acesso</span> <i
                            class="fa fa-angle-left pull-right"></i></a>
                    <ul class="treeview-menu">
                        @if(backpack_user()->hasRole('Administrador'))
                            <li><a href='{{ backpack_url('/admin/usuario') }}'><i class='fa fa-user'></i>
                                    <span>Usuários</span></a>
                            </li>
                        @endif
                        @if(backpack_user()->hasRole('Administrador Órgão'))
                            <li><a href='{{ backpack_url('/admin/usuarioorgao') }}'><i class='fa fa-user'></i> <span>Usuários Órgão</span></a>
                            </li>
                        @endif
                        @if(backpack_user()->hasRole('Administrador Unidade'))
                            <li><a href='{{ backpack_url('/admin/usuariounidade') }}'><i class='fa fa-user'></i> <span>Usuários Unidade</span></a>
                            </li>
                        @endif
                        @if(backpack_user()->hasRole('Administrador'))
                            <li><a href="{{ backpack_url('/role') }}"><i class="fa fa-group"></i>
                                    <span>Grupos</span></a>
                            </li>
                            <li><a href="{{ backpack_url('/permission') }}"><i class="fa fa-key"></i>
                                    <span>Permissões</span></a>
                            </li>
                        @endif
                    </ul>
                </li>
            @endif
            @if(backpack_user()->hasRole('Administrador'))
                <li class="treeview">
                    <a href="#"><i class='fa fa-gear'></i> <span>Outros</span> <i
                            class="fa fa-angle-left pull-right"></i></a>
                    <ul class="treeview-menu">
                        <li><a href="{{ backpack_url('/admin/catmatseratualizacao') }}"><i class="fa fa-list"></i>
                                <span>Atualização CatMatSer</span></a>
                        </li>
                        <li><a href="{{ backpack_url('/admin/sfcertificado') }}"><i class="fa fa-lock"></i> <span>Certificado Siafi</span></a>
                        </li>
                        <li><a href='{{ backpack_url('/admin/codigo') }}'><i class='fa fa-gear'></i> <span>Códigos e Itens</span></a>
                        </li>
                        <li><a href='{{ backpack_url('/admin/comunica') }}'><i class='fa fa-envelope'></i> <span>Comunica</span></a>
                        </li>
                        <li><a href="{{ backpack_url('/admin/justificativafatura') }}"><i class="fa fa-list"></i> <span>Justificativa Fatura</span></a>
                        </li>
                        <li><a href="{{ backpack_url('/admin/tipolistafatura') }}"><i class="fa fa-list"></i> <span>Tipo Lista Fatura</span></a>
                        </li>
                        <li><a href="{{ backpack_url('/admin/importacao') }}"><i class="fa fa-files-o"></i> <span>Importações</span></a>
                        </li>
                    </ul>
                </li>
                <li class="treeview">
                    <a href="#"><i class='fa fa-terminal'></i> <span>Logs</span> <i
                            class="fa fa-angle-left pull-right"></i></a>
                    <ul class="treeview-menu">
                        <li><a href='{{ url(config('backpack.base.route_prefix', 'admin').'/log') }}'><i
                                    class='fa fa-terminal'></i> <span>Logs</span></a></li>
                        <li><a href='{{ backpack_url('/admin/activitylog') }}'><i class='fa fa-terminal'></i>
                                <span>Logs Banco</span></a></li>
                    </ul>
                </li>
            @endif
        </ul>
    </li>
@endif
