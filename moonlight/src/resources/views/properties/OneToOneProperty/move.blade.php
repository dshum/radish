<script>
$(function() {
    $(':text[name="{{ $name }}_one_autocomplete"]').autocomplete({
        serviceUrl: '{{ route('elements.autocomplete') }}',
        params: {
            item: '{{ $relatedClass }}'
        },
        onSelect: function (suggestion) {
            $('span[name="{{ $name }}_one"][container]').html(suggestion.value);
            $(':hidden[name="{{ $name }}_one"]').val(suggestion.id);
        },
        appendTo: $('span.autocomplete-container[name="{{ $name }}_one_auto"]'),
        minChars: 0
    });
    
    $('span[name="{{ $name }}_one"][reset]').click(function() {
        $('span[name="{{ $name }}_one"][container]').html('Не определено');
        $(':hidden[name="{{ $name }}_one"]').val('');
        $(':text[name="{{ $name }}_one_autocomplete"]').val('');
    });
});
</script>
<label>{{ $title }}:</label>
<span name="{{ $name }}_one" container>
@if ($value)
<a href="{{ route('element.edit', $value->classId) }}">{{ $value->name }}</a>
@else
Не определено
@endif
</span>
@if ( ! $readonly)
<br>
<input type="hidden" name="{{ $name }}_one" one="{{ $name }}" value="{{ $value ? $value->id : null }}">
<input type="text" name="{{ $name }}_one_autocomplete" value="" placeholder="ID или название">
<span class="reset" name="{{ $name }}_one" reset>&#215;</span>
<span name="{{ $name }}_one_auto" class="autocomplete-container"></span>
@endif