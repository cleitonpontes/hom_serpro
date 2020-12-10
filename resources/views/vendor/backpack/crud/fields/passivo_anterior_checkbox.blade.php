<!-- checkbox field -->

<?php

//$items = old(square_brackets_to_dots($field['name'])) ?? $field['value'] ?? $field['default'] ?? '';
//dd($items);

?>
<div @include('crud::inc.field_wrapper_attributes') >
    @include('crud::inc.field_translatable_icon')
    <div class="checkbox" >
    	<label>
            <input type="hidden" name="{{ $field['name'] }}" value="0">
    	  <input type="checkbox" value="1"

          name="{{ $field['name'] }}"

          @if (old(square_brackets_to_dots($field['name'])) ?? $field['value'] ?? $field['default'] ?? false)
                 checked="checked"
          @endif

          @if (isset($field['attributes']))
              @foreach ($field['attributes'] as $attribute => $value)
    			{{ $attribute }}="{{ $value }}"
        	  @endforeach
          @endif
          > {!! $field['label'] !!}
    	</label>
        
        {{-- HINT --}}
        @if (isset($field['hint']))
            <p class="help-block">{!! $field['hint'] !!}</p>
        @endif
    </div>
</div>

{{--conta_contabil_passivo_anterior--}}

<!-- include field specific select2 js-->
@push('crud_fields_scripts')
    <script>
        jQuery(document).ready(function($) {
            // trigger select2 for each untriggered select2 box
            $("#select2_ajax_{{ $field['name'] }}").each(function (i, obj) {
                {{ $field['name'] }}



            });

        });
    </script>
@endpush
{{-- End of Extra CSS and JS --}}
{{-- ########################################## --}}
