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

            var msgQuestao = 'Confirma a apropriação de :number fatura(s)?';
            var message = (msgQuestao).replace(":number", crud.checkedItems.length);

            if (confirm(message) == true) {
                var route = '{{ route('apropriacao.fatura.create.bulk') }}';

                $.ajax({
                    url: route,
                    type: 'PUT',
                    data: { entries: crud.checkedItems },
                    success: function(result) {
                        alert('sucesso!');
                        // console.log(result);

                        new PNotify({
                            title: ('Apropriação de Faturas'),
                            text: crud.checkedItems.length + ' faturas incluídas na apropriação.',
                            type: "success"
                        });

                        crud.checkedItems = [];
                        crud.table.ajax.reload();
                    },
                    error: function(result) {
                        alert('erro!!!!!!');
                        // console.log(result);

                        new PNotify({
                            title: 'Erro na Apropriação de Faturas',
                            text: 'Um ou mais itens não puderam ser apropriados',
                            type: "warning"
                        });
                    }
                });

                alert(route);
                alert('Fim...');
            }
        }
    }
</script>
@endpush
