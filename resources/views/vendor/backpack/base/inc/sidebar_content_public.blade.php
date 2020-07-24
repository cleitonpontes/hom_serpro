<!-- This file is used to store sidebar items, starting with Backpack\Base 0.9.0 -->
@if(intval(request()->input('orgao')))
    <li><a href="{{ backpack_url('/transparencia/?orgao='.request()->input('orgao')) }}"><i class="fa fa-indent"></i> <span>Transparência</span></a></li>
@else
    <li><a href="{{ backpack_url('/transparencia') }}"><i class="fa fa-indent"></i> <span>Transparência</span></a></li>
@endif

<li class="treeview">
    <a href="#"><i class="fa fa-bar-chart"></i> <span>Painéis</span> <i
            class="fa fa-angle-left pull-right"></i></a>
    <ul class="treeview-menu">
        <li>
            <a href="https://app.powerbi.com/view?r=eyJrIjoiYzQxMmQwZjgtMGJkOC00OGY4LTk2MmItMWVjMGU5NmNiMTBhIiwidCI6IjRkNzlkMzdhLTFlNGUtNGEzOS05ZmRlLWYxNjMxY2I2MDdkNCJ9"
               target="_blank"><i class="fa fa-dashboard"></i>
                <span>Contratos</span></a></li>
    </ul>
</li>
<li class="treeview">
    <a href="#"><i class="fa fa-table"></i> <span>Consulta</span> <i
            class="fa fa-angle-left pull-right"></i></a>
    <ul class="treeview-menu">
        @if(intval(request()->input('orgao')))
            <li><a href="{{ backpack_url('/transparencia/contratos/?orgao='.request()->input('orgao')) }}"><i
                        class="fa fa-table"></i>
                    <span> Contratos</span></a></li>
            <li><a href="{{ backpack_url('/transparencia/faturas/?orgao='.request()->input('orgao')) }}"><i
                        class="fa fa-table"></i>
                    <span> Faturas</span></a></li>
            <li><a href="{{ backpack_url('/transparencia/terceirizados/?orgao='.request()->input('orgao')) }}"><i
                        class="fa fa-table"></i>
                    <span> Terceirizados</span></a></li>
        @else
            <li><a href="{{ backpack_url('/transparencia/contratos') }}"><i class="fa fa-table"></i>
                    <span> Contratos</span></a></li>
            <li><a href="{{ backpack_url('/transparencia/faturas') }}"><i class="fa fa-table"></i>
                    <span> Faturas</span></a></li>
            <li><a href="{{ backpack_url('/transparencia/terceirizados') }}"><i class="fa fa-table"></i>
                    <span> Terceirizados</span></a></li>
        @endif
    </ul>
</li>
@if (backpack_auth()->check())
{{--    <li><a href="{{ backpack_url('/inicio') }}"><i class="fa fa-reply"></i> <span>Voltar</span></a></li>--}}
@endif
