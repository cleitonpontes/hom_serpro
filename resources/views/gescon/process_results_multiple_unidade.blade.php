processResults: function (data, params) {
    params.page = params.page || 1;

    return {
        results: $.map(data.data, function (item) {
            return {
                text: item["codigo"]+' - '+item['nomeresumido'],
                id: item["{{ $connected_entity_key_name }}"]
                }
            }),
        pagination: {
            more: data.current_page < data.last_page
        }
    };
},
