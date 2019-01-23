<?php
$erros = session('validacao.erros');
session(['validacao.erros' => null]);

if (isset($erros)) {
    ?>
    <div class="box-body">
    	<h4 class="box-title text-danger">
    		Pendências nas Validações da Importação
    	</h4>
		
        <?php
        $arquivoAnterior = '';
        foreach($erros as $erro) {
            $arquivoDaVez = $erro['arquivo'];
            $linha = $erro['linha'];
            $descricao = $erro['descricao'];
            
            if ($arquivoDaVez != $arquivoAnterior) {
                ?>
                </ul>
                
                <p><strong>{{ $arquivoDaVez }}</strong></p>
                
                <ul>
                <?php
                $arquivoAnterior = $arquivoDaVez;
            }
            ?>
        	<li> Linha {{ $linha }}: {{ $descricao }} </li>
        	<?php
        }
        ?>
    </div>
    <?php
}
