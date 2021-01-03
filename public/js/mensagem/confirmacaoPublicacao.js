$(document).on('click', "#btn-submit-itens-contrato", function () {
    //verifica se tipo contrato vem do hidden
    var tipo_contrato = $('#tipo_contrato').val();
    //verifica se tipo contrato vem da combo de tipo contrato
    if ($("[name='tipo_id'] option").filter(':selected').text() != "") {
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
        this.closest('form').submit();
    } else {
        Swal.fire({
            title: 'O instrumento será publicado no diário oficial, deseja continuar?',
            showDenyButton: false,
            showCancelButton: true,
            confirmButtonText: `Sim`,
        }).then((result) => {
            /* Read more about isConfirmed, isDenied below */
            if (result.isConfirmed) {
                this.closest('form').submit();
            }
        })
    }
});
