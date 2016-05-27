<script>
$(function() {
    $(':text[name="{{ $name }}_autocomplete"]').autocomplete({
        serviceUrl: '{{ route('elements.autocomplete') }}',
        params: {
            item: '{{ $relatedClass }}'
        },
        onSelect: function (suggestion) {
            $('span[name="{{ $name }}"]').html(suggestion.value);
            $(':text[name="{{ $name }}"]').val(suggestion.data);
        },
        appendTo: $('span.autocomplete-container[name="{{ $name }}_auto"]'),
        minChars: 0
    });
});
</script>
<label>{{ $title }}:</label>
<span name="{{ $name }}">
@if ($value)
<a href="{{ route('element.edit', $value->classId) }}">{{ $value->name }}</a>
@else
Не определено
@endif
</span>
@if ( ! $readonly)
<br>
<input type="hidden" name="{{ $name }}" value="{{ $value ? $value->classId : null }}">
<input type="text" name="{{ $name }}_autocomplete" value="" placeholder="ID или название">
<span name="{{ $name }}_auto" class="autocomplete-container"></span>
@endif