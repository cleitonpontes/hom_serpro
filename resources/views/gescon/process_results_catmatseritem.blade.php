processResults: function (data, params) {
    params.page = params.page || 1;

    var result = {
        results: $.map(data.data, function (item) {
            return {
                text: item['codigo_siasg']+' - '+item['descricao'],
                id: item["{{ $connected_entity_key_name }}"]
            }
        }),
        pagination: {
            more: data.current_page < data.last_page
        }
    };

    return result;
},
