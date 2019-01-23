<?php
$apid = Request()->apid;
$passoAtual = session('apropriacao_passo');
$passoMenos = $passoAtual -1;

$passoAnterior = "/folha/apropriacao/passo/$passoMenos/apid/$apid";
$passoProximo = "/folha/apropriacao/passo/$passoAtual/avanca/apid/$apid";

// Exceções
if ($passoAtual == 2) {
    $passoAnterior = '/folha/apropriacao';
}

if ($passoAtual == 7) {
    $passoProximo = '';
}

?>
<div class="row">
	<div class="col-md-6 text-left">
		@if($passoAnterior != '')
        	<button type="button"
        		id="btnAnterior"
        		onclick=window.location="{{$passoAnterior}}"
        		class="btn btn-primary text-left">
        		<i class="fa fa-reply"></i> Anterior
        	</button>
    	@endif
	</div>
	
	<div class="col-md-6 text-right">
		@if($passoProximo != '')
        	<button type="button"
        		id="btnProximo"
        		onclick=window.location="{{$passoProximo}}"
        		class="btn btn-primary text-right">
        		<i class="fa fa-share"></i> Pŕoximo
        	</button>
    	@endif
	</div>
</div>
