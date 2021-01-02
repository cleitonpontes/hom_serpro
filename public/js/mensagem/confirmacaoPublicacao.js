$(document).on('click', "#btn-submit-itens-contrato", function () {

    var tipo_contrato = $('#tipo_contrato').val();
    if ($("[name='tipo_id']").length === 1) {
        var selected = $("[name='tipo_id']").find(':selected'),
            array_selected = [];
        selected.each(function (index, option) {
            array_selected[index] = option.text;
        })
        if (array_selected[0] !== 'Selecione...') {
            tipo_contrato = array_selected[0];
        }
    }
    if (tipo_contrato === 'Outros' || tipo_contrato === 'Empenho' || tipo_contrato === '') {
        $('form').submit();
    } else {
        Swal.fire({
            title: 'O instrumento será publicado, deseja continuar?',
            showDenyButton: false,
            showCancelButton: true,
            confirmButtonText: `Sim`,
        }).then((result) => {
            /* Read more about isConfirmed, isDenied below */
            if (result.isConfirmed) {
                $('form').submit();
            }
        })
    }
});
