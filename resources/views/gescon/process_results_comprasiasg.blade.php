processResults: function (data, params) {
    params.page = params.page || 1;

    var result = {
        results: $.map(data.data, function (item) {
            return {
                text: item['unidadecompra']+' | '+item['numerocompra'],
                id: item['id']
            }
        }),
        pagination: {
            more: data.current_page < data.last_page
        }
    };

    return result;
},
