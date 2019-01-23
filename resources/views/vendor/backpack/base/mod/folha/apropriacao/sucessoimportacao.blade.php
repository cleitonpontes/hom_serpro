<?php
$sucessos = session('validacao.sucesso');
session(['validacao.sucesso' => null]);

if (isset($sucessos)) {
    $apropriacao = array_pop($sucessos);
    $id = $apropriacao->id;
    ?>
    <div class="box-body">
    	<h4 class="box-title text-success">
    		Detalhes da importação
    	</h4>
		
        <?php
        $arquivoAnterior = '';
        foreach($sucessos as $sucesso) {
            $arquivoDaVez = $sucesso['arquivo'];
            $qtde = count($sucesso['conteudo']);
            
            if ($arquivoDaVez != $arquivoAnterior) {
                ?>
                </ul>
                
                <p><strong>{{ $arquivoDaVez }}</strong></p>
                
                <ul>
                <?php
                $arquivoAnterior = $arquivoDaVez;
            }
            ?>
        	<li> Quantidade de registros: {{ $qtde }}  </li>
        	<?php
        }
        ?>
        </ul>
        <br />
        
        <div class="text-right">
	        <button type="button"
	        		onclick=window.location="/folha/apropriacao/passo/2/apid/{{$id}}"
	        		class="btn btn-primary">
    	    	<i class="fa fa-share"></i> Pŕoximo
        	</button>
        </div>
    </div>
    <?php
}
