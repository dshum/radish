<script>
$(function() {
    $(':text[name="{{ $name }}_copy_autocomplete"]').autocomplete({
        serviceUrl: '{{ route('elements.autocomplete') }}',
        params: {
            item: '{{ $relatedClass }}'
        },
        onSelect: function (suggestion) {
            $('span[name="{{ $name }}_copy"][container]').html(suggestion.value);
            $(':hidden[name="{{ $name }}_copy"]').val(suggestion.id);
        },
        appendTo: $('span.autocomplete-container[name="{{ $name }}_copy_auto"]'),
        minChars: 0
    });
    
    $('span[name="{{ $name }}_copy"][reset]').click(function() {
        $('span[name="{{ $name }}_copy"][container]').html('Не определено');
        $(':hidden[name="{{ $name }}_copy"]').val('');
        $(':text[name="{{ $name }}_copy_autocomplete"]').val('');
    });
});
</script>
<label>{{ $title }}:</label>
<span name="{{ $name }}_copy" container>
@if ($value)
<a href="{{ route('element.edit', $value->classId) }}">{{ $value->name }}</a>
@else
Не определено
@endif
</span>
@if ( ! $readonly)
<br>
<input type="hidden" name="{{ $name }}_copy" copy="{{ $name }}" value="{{ $value ? $value->id : null }}">
<input type="text" name="{{ $name }}_copy_autocomplete" value="" placeholder="ID или название">
<span class="reset" name="{{ $name }}_copy" reset>&#215;</span>
<span name="{{ $name }}_copy_auto" class="autocomplete-container"></span>
@endif