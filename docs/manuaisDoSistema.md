<script>
    function toggleList(name){
        element = document.getElementById(name+"List");
        button = document.getElementById(name+"Button");
        if(element.hasAttribute('hide-list')){
            element.removeAttribute('hide-list');
            button.setAttribute('src', '../assets/dash-circle.svg');
        } else {
            element.setAttribute('hide-list', true)
            button.setAttribute('src', '../assets/plus-circle.svg');
        }
    }
</script>

<style>
    ul[hide-list]{
        display: none;
    }
    .list-button{
        cursor: pointer;
    }
    .obs{
        font-size: 15px;
    }
    .pdf{
        width: 18px;
        color: #404040;
    }
</style>

<h1>Manuais do Sistema</h1>

Manuais de uso do [Comprasnet Contratos](https://contratos.comprasnet.gov.br/login).

<div class="obs">* Para visualizar a versão em PDF do manual clique em <img src="../assets/pdf.svg" width="15px" alt="pdf" ></div>

<hr>

<h2>Gestão Contratual <img class="list-button" id="gestaoContratualButton" onclick="toggleList('gestaoContratual')" src="../assets/plus-circle.svg" /></h2>
<ul id="gestaoContratualList" hide-list>
    <li>
        <h3><a href="../manuais/gestaoContratual/contratos/" target="_blank" >Contratos</a> <a href="https://gitlab.com/comprasnet/contratos/-/wikis/uploads/0604a3feac9d62247bbea383a08192d3/111_GestaoContratualContratos.pdf" target="_blank"><img src="../assets/pdf.svg" class="pdf"></a>
        </h3>
        <ul>
            <li>
                <h3>Itens Contrato <img class="list-button" id="itensContratoButton" onclick="toggleList('itensContrato')" src="../assets/plus-circle.svg" /></h3>
                <ul id="itensContratoList" hide-list>
                    <li>
                        <h4>
                        <a href="../manuais/gestaoContratual/itensContrato/arquivo/" target="_blank">
                            Arquivo
                        </a>
                        <a href="https://gitlab.com/comprasnet/contratos/-/wikis/uploads/72f40713ad275ce32657e99ef7759bdb/112_GestaoContratualContratosItensContratoArquivo.pdf" target="_blank"><img src="../assets/pdf.svg" class="pdf"></a>
                        </h4>
                    </li>
                    <li>
                        <h4>
                        <a href="../manuais/gestaoContratual/itensContrato/cronograma/" target="_blank">
                            Cronograma
                        </a>
                        <a href="https://gitlab.com/comprasnet/contratos/-/wikis/uploads/3aeb60612cd4f0890289695130e9bd46/113_GestaoContratualContratosItensContratoCronograma.pdf" target="_blank"><img src="../assets/pdf.svg" class="pdf"></a>
                        </h4>
                    </li>
                    <li>
                        <h4>
                        <a href="../manuais/gestaoContratual/itensContrato/despesasAcessorias/" target="_blank">
                            Despesas acessórias
                        </a>
                        <a href="https://gitlab.com/comprasnet/contratos/-/wikis/uploads/8ff1cf6459503f4832f552c4b8a4163d/114_GestaoContratualContratosItensContratoDespesasAcessorias.pdf" target="_blank"><img src="../assets/pdf.svg" class="pdf"></a>
                        </h4>
                    </li>
                    <li>
                        <h4>
                        <a href="../manuais/gestaoContratual/itensContrato/empenhos/" target="_blank">
                            Empenhos
                        </a>
                        <a href="https://gitlab.com/comprasnet/contratos/-/wikis/uploads/da4eaed13f82d3090993f6274dbb7e6c/115_GestaoContratualContratosItensContratoEmpenhos.pdf" target="_blank"><img src="../assets/pdf.svg" class="pdf"></a>
                        </h4>
                    </li>
                    <li>
                        <h4>
                        <a href="../manuais/gestaoContratual/itensContrato/garantias/" target="_blank">
                            Garantias
                        </a>
                        <a href="https://gitlab.com/comprasnet/contratos/-/wikis/uploads/da2fd164ef202c332afa1031135b1dab/116_GestaoContratualContratosItensContratoGarantias.pdf" target="_blank"><img src="../assets/pdf.svg" class="pdf"></a>
                        </h4>
                    </li>
                    <li>
                        <h4>
                        <a href="../manuais/gestaoContratual/itensContrato/historico/" target="_blank">
                            Histórico
                        </a>
                        <a href="https://gitlab.com/comprasnet/contratos/-/wikis/uploads/b28ced93392206ff80a3f8a7f5ade1ad/117_GestaoContratualContraosItensContratoHistorico.pdf" target="_blank"><img src="../assets/pdf.svg" class="pdf"></a>
                        </h4>
                    </li>
                    <li>
                        <h4>
                        <a href="../manuais/gestaoContratual/itensContrato/itens/" target="_blank">
                            Itens
                        </a>
                        <a href="https://gitlab.com/comprasnet/contratos/-/wikis/uploads/8fb8bff12b39d99d21b864f645c642f2/118_GestaoContratualContratosItensContratoItens.pdf" target="_blank"><img src="../assets/pdf.svg" class="pdf"></a>
                        </h4>
                    </li>
                    <li>
                        <h4>
                        <a href="../manuais/gestaoContratual/itensContrato/padroesDHSIAFI/" target="_blank">
                            Padrões DH SIAFI
                        </a>
                        <a href="https://gitlab.com/comprasnet/contratos/-/wikis/uploads/ec4472acc468a9da9a4c8d2cf1c44cfa/119_GestaoContratualContratosItensContratoPadroesDHSIAFI.pdf" target="_blank"><img src="../assets/pdf.svg" class="pdf"></a>
                        </h4>
                    </li>
                    <li>
                        <h4>
                        <a href="../manuais/gestaoContratual/itensContrato/prepostos/" target="_blank">
                            Prepostos
                        </a>
                        <a href="https://gitlab.com/comprasnet/contratos/-/wikis/uploads/e7d6b293222ef75105c0e5ccf289384c/120_GestaoContratualContratosItensContratoPrepostos.pdf" target="_blank"><img src="../assets/pdf.svg" class="pdf"></a>
                        </h4>
                    </li>
                    <li>
                        <h4>
                        <a href="../manuais/gestaoContratual/itensContrato/responsaveis/" target="_blank">
                            Responsáveis
                        </a>
                        <a href="https://gitlab.com/comprasnet/contratos/-/wikis/uploads/3f4a696faa93c933ddefe19f191a7df6/121_GestaoContratualContrataosItensContratoResponsaveis.pdf" target="_blank"><img src="../assets/pdf.svg" class="pdf"></a>
                        </h4>
                    </li>
                </ul>
            </li>
            <li>
                <h3>Modificar Contrato <img class="list-button" id="modificarContratoButton" onclick="toggleList('modificarContrato')" src="../assets/plus-circle.svg" /></h3>
                <ul id="modificarContratoList" hide-list>
                    <li>
                        <h4>
                        <a href="../manuais/gestaoContratual/modificarContrato/instrumentoInicial/" target="_blank">
                            Instrumento Inicial
                        </a>
                        <a href="https://gitlab.com/comprasnet/contratos/-/wikis/uploads/a2d91e26da028b07363cc037369f331a/123_GestaoContratualContratosModificarContratoInstrumentoInicial.pdf" target="_blank"><img src="../assets/pdf.svg" class="pdf"></a>
                        </h4>
                    </li>
                    <li>
                        <h4>
                        <a href="../manuais/gestaoContratual/modificarContrato/termoAditivo/" target="_blank">
                            Termo Aditivo
                        </a>
                        <a href="https://gitlab.com/comprasnet/contratos/-/wikis/uploads/c89b5e33ee2f5f1ca227f88fd00fd2b6/124_GestaoContratualContratosModificarContratoTermoAditivo.pdf" target="_blank"><img src="../assets/pdf.svg" class="pdf"></a>
                        </h4>
                    </li>
                    <li>
                        <h4>
                        <a href="../manuais/gestaoContratual/modificarContrato/termoApostilamento/" target="_blank">
                            Termo Apostilamento
                        </a>
                        <a href="https://gitlab.com/comprasnet/contratos/-/wikis/uploads/5b1133848cbe6125859f0f0e0b890a02/125_GestaoContratualContratosModificarContratoTermoApostilamento.pdf" target="_blank"><img src="../assets/pdf.svg" class="pdf"></a>
                        </h4>
                    </li>
                    <li>
                        <h4>
                        <a href="../manuais/gestaoContratual/modificarContrato/termoRescisao/" target="_blank">
                            Termo Rescisão
                        </a>
                        <a href="https://gitlab.com/comprasnet/contratos/-/wikis/uploads/4575a156dd1deb18bd2008dbca78fc8c/126_GestaoContratualContraosModificarContratoTermoRescisao.pdf" target="_blank"><img src="../assets/pdf.svg" class="pdf"></a>
                        </h4>
                    </li>
                </ul>
            </li>
        </ul>
    </li>
    <li>
        <h3>Inclusão de Termo Aditivo <img class="list-button" id="inclusaoTermoAditivoButton" onclick="toggleList('inclusaoTermoAditivo')" src="../assets/plus-circle.svg" /></h3>
        <ul id="inclusaoTermoAditivoList" hide-list>
            <li>
                <h4>
                <a href="../manuais/gestaoContratual/inclusaoTermoAditivo/acrescimo/" target="_blank">
                Supressão/Acréscimo
                </a>
                <a href="https://gitlab.com/comprasnet/contratos/-/wikis/uploads/c573d655ddb3a510382ee48ddf9a7092/Inclus%C3%A3oDeTermoAditivo_Acrescimo_v510_1509.pdf" target="_blank"><img src="../assets/pdf.svg" class="pdf"></a>
                </h4>
            </li>
            <li>
                <h4>
                <a href="../manuais/gestaoContratual/inclusaoTermoAditivo/prorrogacao/" target="_blank">
                Prorrogação de Vigência
                </a>
                <a href="https://gitlab.com/comprasnet/contratos/-/wikis/uploads/e1ba137fd653867ee6923fcceb3a1979/InclusaoDeTermoAditivo_Prorrogacao.pdf" target="_blank"><img src="../assets/pdf.svg" class="pdf"></a>
                </h4>
            </li>
        </ul>
    </li>
    <li>
        <h3>
        <a href="../manuais/gestaoContratual/fornecedores/" target="_blank">
            Fornecedores
        </a>
        <a href="https://gitlab.com/comprasnet/contratos/-/wikis/uploads/9537b1b195f60f08a20819f83c6a92a8/127_GestaoContratualFornecedores.pdf" target="_blank"><img src="../assets/pdf.svg" class="pdf"></a>
        </h3>
    </li>
    <li>
        <h3>
        <a href="../manuais/gestaoContratual/indicadores/" target="_blank">
            Indicadores
        </a>
        <a href="https://gitlab.com/comprasnet/contratos/-/wikis/uploads/4d60cb3c042faad1e67b8f2abe39202c/128_GestaoContratualIndicadores.pdf" target="_blank"><img src="../assets/pdf.svg" class="pdf"></a>
        </h3>
    </li>
    <li>
        <h3>
        <a href="../manuais/gestaoContratual/subRogacoes/" target="_blank">
            Sub-Rogações
        </a>
        <a href="https://gitlab.com/comprasnet/contratos/-/wikis/uploads/0af666169692f2df6706f74b2b021ebd/129_GestaoContrataulSubRogacoes.pdf" target="_blank"><img src="../assets/pdf.svg" class="pdf"></a>
        </h3>
    </li>
    <li>
        <h3>Importação SIASG <img class="list-button" id="importacaoSIASGButton" onclick="toggleList('importacaoSIASG')" src="../assets/plus-circle.svg" /></h3>
        <ul id="importacaoSIASGList" hide-list>
            <li>
                <h4>
                <a href="../manuais/gestaoContratual/importacaoSIASG/compras/" target="_blank">
                Compras
                </a>
                <a href="https://gitlab.com/comprasnet/contratos/-/wikis/uploads/1583723f66e68a1a6e31ed6c3a9153db/Importa%C3%A7%C3%A3oSIASG_COMPRAS_v510_1509.pdf" target="_blank"><img src="../assets/pdf.svg" class="pdf"></a>
                </h4>
            </li>
            <li>
                <h4>
                <a href="../manuais/gestaoContratual/importacaoSIASG/contratos/" target="_blank">
                Contratos
                </a>
                <a href="https://gitlab.com/comprasnet/contratos/-/wikis/uploads/ce931b312fd8e8d43ea442635ccbf4d7/Importa%C3%A7%C3%A3oSIASG_CONTRATOS_v510_1509.pdf" target="_blank"><img src="../assets/pdf.svg" class="pdf"></a>
                </h4>
            </li>
        </ul>
    </li>
    <li>
        <h3>Consultas <img class="list-button" id="consultasButton" onclick="toggleList('consultas')" src="../assets/plus-circle.svg" /></h3>
        <ul id="consultasList" hide-list>
            <li>
                <h4>
                <a href="../manuais/gestaoContratual/consultas/arquivos/" target="_blank">
                Arquivos
                </a>
                <a href="https://gitlab.com/comprasnet/contratos/-/wikis/uploads/df50551e895353e984a252c0760b680b/130_GestaoContratualConsultasArquivos.pdf" target="_blank"><img src="../assets/pdf.svg" class="pdf"></a>
                </h4>
            </li>
            <li>
                <h4>
                <a href="../manuais/gestaoContratual/consultas/cronogramas/" target="_blank">
                Cronogramas
                </a>
                <a href="https://gitlab.com/comprasnet/contratos/-/wikis/uploads/5a4e6e3d6b9371a12794c099aa83257a/131_GestaoContratualConsultasCronogramas.pdf" target="_blank"><img src="../assets/pdf.svg" class="pdf"></a>
                </h4>
            </li>
            <li>
                <h4>
                <a href="../manuais/gestaoContratual/consultas/despesasAcessorias/" target="_blank">
                Despesas Acessórias
                </a>
                <a href="https://gitlab.com/comprasnet/contratos/-/wikis/uploads/c5ee7659fc08f74fdfdf627afc4df9a7/132_GestaoContratualConsultasDespesasAcessorias.pdf" target="_blank"><img src="../assets/pdf.svg" class="pdf"></a>
                </h4>
            </li>
            <li>
                <h4>
                <a href="../manuais/gestaoContratual/consultas/empenho/" target="_blank">
                Empenho
                </a>
                <a href="https://gitlab.com/comprasnet/contratos/-/wikis/uploads/4ce2b275e8fe0e05d2a3f40359a2a7f2/133_GestaoContrataualConsultasEmpenho.pdf" target="_blank"><img src="../assets/pdf.svg" class="pdf"></a>
                </h4>
            </li>
            <li>
                <h4>
                <a href="../manuais/gestaoContratual/consultas/faturas/" target="_blank">
                Faturas
                </a>
                <a href="https://gitlab.com/comprasnet/contratos/-/wikis/uploads/d0a30355d998dc57bc48d69c5df9b282/134_GestaoContratualConsultasFaturas.pdf" target="_blank"><img src="../assets/pdf.svg" class="pdf"></a>
                </h4>
            </li>
            <li>
                <h4>
                <a href="../manuais/gestaoContratual/consultas/garantias/" target="_blank">
                Garantias
                </a>
                <a href="https://gitlab.com/comprasnet/contratos/-/wikis/uploads/a6595696164a781d03cf45790629741c/135_GestaoContratualConsultasGarantias.pdf" target="_blank"><img src="../assets/pdf.svg" class="pdf"></a>
                </h4>
            </li>
            <li>
                <h4>
                <a href="../manuais/gestaoContratual/consultas/historicos/" target="_blank">
                Históricos
                </a>
                <a href="https://gitlab.com/comprasnet/contratos/-/wikis/uploads/f6aa12ee81c607ab6265f09d8b135ff4/136_GestaoContratualConsultasHistoricos.pdf" target="_blank"><img src="../assets/pdf.svg" class="pdf"></a>
                </h4>
            </li>
            <li>
                <h4>
                <a href="../manuais/gestaoContratual/consultas/itens/" target="_blank">
                Itens
                </a>
                <a href="https://gitlab.com/comprasnet/contratos/-/wikis/uploads/f5bc4c698ca4387b028885f7a780011e/137_GestaoContratualConsultasItens.pdf" target="_blank"><img src="../assets/pdf.svg" class="pdf"></a>
                </h4>
            </li>
            <li>
                <h4>
                <a href="../manuais/gestaoContratual/consultas/ocorrencias/" target="_blank">
                Ocorrências
                </a>
                <a href="https://gitlab.com/comprasnet/contratos/-/wikis/uploads/f7729360d16637492c8e9eb0b39bc70a/138_GestaoContratualConsultasOcorrencias.pdf" target="_blank"><img src="../assets/pdf.svg" class="pdf"></a>
                </h4>
            </li>
            <li>
                <h4>
                <a href="../manuais/gestaoContratual/consultas/responsaveis/" target="_blank">
                Responsáveis
                </a>
                <a href="https://gitlab.com/comprasnet/contratos/-/wikis/uploads/ebf33f5302d1fd1dd55d61b75c6de5ae/140_GestaoContratualConsultasResponsaveis.pdf" target="_blank"><img src="../assets/pdf.svg" class="pdf"></a>
                </h4>
            </li>
        </ul>
    </li>
    <li>
        <h3>Relatórios <img class="list-button" id="relatoriosButton" onclick="toggleList('relatorios')" src="../assets/plus-circle.svg" /></h3>
        <ul id="relatoriosList" hide-list>
            <li>
                <h4>
                <a href="../manuais/gestaoContratual/relatorios/contratosDaUG/" target="_blank">
                Contratos da UG
                </a>
                <a href="https://gitlab.com/comprasnet/contratos/-/wikis/uploads/27693d63f6c4154e88a79bf593b28aa5/142_GestaoContratualRelatoriosContratosdaUG.pdf" target="_blank"><img src="../assets/pdf.svg" class="pdf"></a>
                </h4>
            </li>
            <li>
                <h4>
                <a href="../manuais/gestaoContratual/relatorios/contratosDoOrgao/" target="_blank">
                Contratos do Órgão
                </a>
                <a href="https://gitlab.com/comprasnet/contratos/-/wikis/uploads/36175d931aa53d519e92e0ee99c648fe/143_GestaoContratualRelatoriosContratosdoOrgao.pdf" target="_blank"><img src="../assets/pdf.svg" class="pdf"></a>
                </h4>
            </li>
            <li>
                <h4>
                <a href="../manuais/gestaoContratual/relatorios/todosContratos/" target="_blank" >
                Todos Contratos
                </a>
                <a href="https://gitlab.com/comprasnet/contratos/-/wikis/uploads/a2e1bf4dbbd63e74fb19242b907d9bf1/144_GestaoContratualRelatoriosTodosContratos.pdf" target="_blank"><img src="../assets/pdf.svg" class="pdf"></a>
                </h4>
            </li>
        </ul>
    </li>
    <li>
        <h3>
        <a href="../manuais/gestaoContratual/meusContratos/" target="_blank" >
        Meus Contratos
        </a>
        <a href="https://gitlab.com/comprasnet/contratos/-/wikis/uploads/aa4891e7a44f587b08c4f85c6a50b947/145_GestaoContratualMeusContratos.pdf" target="_blank"><img src="../assets/pdf.svg" class="pdf"></a>
        </h3>
    </li>
</ul>

<hr>

<h2>Gestão Orçamentária <img class="list-button" id="gestaoOrcamentariaButton" onclick="toggleList('gestaoOrcamentaria')" src="../assets/plus-circle.svg" /></h2>
<ul id="gestaoOrcamentariaList" hide-list>
    <li>
        <h4>
            <a href="../manuais/gestaoFinanceira/empenho/" target="_blank" >
                Funcionalidades Empenho
            </a>
            <a href="https://gitlab.com/comprasnet/contratos/-/wikis/uploads/5b2d7b37091c97aba4dfe2f643fccbb2/FuncionalidadesEmpenho_v510_1509.pdf"
                target="_blank"><img src="../assets/pdf.svg" class="pdf"></a>
        </h4>
    </li>
</ul>

<hr>

<h2>Gestão Financeira <img class="list-button" id="gestaoFinanceiraButton" onclick="toggleList('gestaoFinanceira')" src="../assets/plus-circle.svg" /></h2>
<ul id="gestaoFinanceiraList" hide-list>
    <li>
        <h3>
        <a href="../manuais/gestaoFinanceira/apropriacao/" target="_blank" >
        Apropriação
        </a>
        <a href="https://gitlab.com/comprasnet/contratos/-/wikis/uploads/b82202f7a7bbe8f79db43da595bc5769/FuncionalidadesApropria%C3%A7%C3%A3o_v510_1509.pdf" target="_blank"><img src="../assets/pdf.svg" class="pdf"></a>
        </h3>
    </li>
    <li>
        <h3>Cadastro <img class="list-button" id="cadastroButton" onclick="toggleList('cadastro')" src="../assets/plus-circle.svg" /></h3>
        <ul id="cadastroList" hide-list>
            <li>
                <h4>
                <a href="../manuais/gestaoFinanceira/situacaoSIAFI/" target="_blank" >
                Situação SIAFI
                </a>
                <a href="https://gitlab.com/comprasnet/contratos/-/wikis/uploads/f62085451d4a5403fcb718f238301e53/148_GestaoFinanceiraCadastroSituacaoSIAFI.pdf" target="_blank"><img src="../assets/pdf.svg" class="pdf"></a>
                </h4>
            </li>
            <li>
                <h4>
                <a href="../manuais/gestaoFinanceira/rubricas/" target="_blank" >
                Rubricas
                </a>
                <a href="https://gitlab.com/comprasnet/contratos/-/wikis/uploads/6faf40f9931c626f2a060dc53e0cbb50/149_GestaoFinanceiraCadastroRubrica.pdf" target="_blank"><img src="../assets/pdf.svg" class="pdf"></a>
                </h4>
            </li>
            <li>
                <h4>
                <a href="../manuais/gestaoFinanceira/RHsituacao/" target="_blank" >
                RH - Situação
                </a>
                <a href="https://gitlab.com/comprasnet/contratos/-/wikis/uploads/0bdf1b6b2c7efad866ac898487e7a124/150_GestaoFinanceiraCadastroRHSituacao.pdf" target="_blank"><img src="../assets/pdf.svg" class="pdf"></a>
                </h4>
            </li>
        </ul>
    </li>
</ul>

<hr>

<h2> Administração <img class="list-button" id="administracaoButton" onclick="toggleList('administracao')" src="../assets/plus-circle.svg" /></h2>
<ul id="administracaoList" hide-list>
    <li>
        <h3>
        <a href="../manuais/administracao/estrutura/orgao/" target="_blank">
        Estrutura - Órgão
        </a>
        <a href="https://gitlab.com/comprasnet/contratos/-/wikis/uploads/707362e25537d352ee6f3dc72a8f08d5/152_AdministracaoEstruturaOrgao.pdf" target="_blank"><img src="../assets/pdf.svg" class="pdf"></a>
        </h3>
    </li>
</ul>

<hr>