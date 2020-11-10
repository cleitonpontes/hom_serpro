<div id="saveActions" class="form-group">

    <input type="hidden" name="save_action" value="{{ $saveAction['active']['value'] }}">

    <div class="btn-group">

        {!! Button::danger('<i class="fa fa fa-ban"></i> Cancelar')
                        ->asLinkTo(route('empenho.crud./minuta.index'))
                    !!}
        <button type="submit" class="btn btn-success">
            Próxima Etapa <i class="fa fa-arrow-right"></i>
        </button>
    </div>
</div>
