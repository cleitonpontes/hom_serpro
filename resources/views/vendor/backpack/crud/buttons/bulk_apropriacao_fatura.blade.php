@if ($crud->hasAccess('update') && $crud->bulk_actions)
    <a href="javascript:void(0)"
       onclick="bulkApropriaFaturas(this)"
       class="btn btn-primary btn-sm bulk-button"
       title="Apropriação de faturas"
    >
        <i class="fa fa-file-text-o"></i> &nbsp;
        Apropriação de faturas
    </a>
@endif

@push('after_scripts')
<script>
    if (typeof bulkApropriaFaturas != 'function') {
        function bulkApropriaFaturas(button) {
            if (typeof crud.checkedItems === 'undefined' || crud.checkedItems.length == 0) {
                new PNotify({
                    title: 'Sem registros selecionados',
                    text: 'Favor selecionar um ou mais registros para efetuar a Apropriação de faturas em lote.',
                    type: 'warning'
                });

                return;
            }

            var msgQuestao = 'Tem a certeza que quer apagar estes :number itens?';
            var message = (msgQuestao).replace(":number", crud.checkedItems.length);
            {{-- var button = $(this); --}}

            if (confirm(message) == true) {
                var ajax_calls = [];
                // TODO: Alterar rota!
                // TODO: Criar rotina para múltiplos ids
                var delete_route = '{{ url($crud->route) }}/bulk-delete';

                $.ajax({
                    url: delete_route,
                    type: 'PUT',
                    data: { entries: crud.checkedItems },
                    success: function(result) {
                        new PNotify({
                            title: ('Apropriação de Faturas'),
                            text: crud.checkedItems.length + ' aturas incluídas na apropriação.',
                            type: "success"
                        });

                        crud.checkedItems = [];
                        crud.table.ajax.reload();
                    },
                    error: function(result) {
                        new PNotify({
                            title: 'Erro na Apropriação de Faturas',
                            text: 'Um ou mais itens não puderam ser apropriados',
                            type: "warning"
                        });
                    }
                });
            }

            alert(message);
        }
    }
</script>
@endpush
