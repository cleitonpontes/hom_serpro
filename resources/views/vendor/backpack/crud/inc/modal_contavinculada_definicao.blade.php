<!-- Esta modal é chamada por include em list.blade.php -->

<!-- Início função que chama a modal - mvascs -->
<script type="text/javascript">
    $(function() {
        $('#myModal1').on('shown.bs.modal', function() {
            var $me = $(this);
            $me.delay(3000).hide(0, function() {
                $me.modal('hide');
            });
        });
    });
</script>
<!-- Fim função que chama a modal - mvascs -->



<!-- Início modal - mvascs@gmail.com -->
<div class="modal fade" id="myModal1" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <!-- <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button> -->
        <h4 class="modal-title" id="myModalLabel">CONTA-DEPÓSITO VINCULADA - BLOQUEADA PARA MOVIMENTAÇÃO</h4>
      </div>
      <div class="modal-body">
        Conta aberta pela Administração em nome da empresa contratada,
        destinada exclusivamente ao pagamento de férias, 13º (décimo terceiro) salário e verbas rescisórias aos trabalhadores da contratada,
        não se constituindo em um fundo de reserva, utilizada na contratação de serviços com dedicação exclusiva de mão de obra.
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
      </div>
    </div>
  </div>
</div>
<!-- Início modal - mvascs -->
