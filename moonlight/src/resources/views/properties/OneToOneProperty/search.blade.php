<script>
$(function() {
    $(':text[name="{{ $name }}_autocomplete"]').autocomplete({
        serviceUrl: '{{ route('elements.autocomplete') }}',
        params: {
            item: '{{ $relatedClass }}'
        },
        onSelect: function (suggestion) {
            $('span[name="{{ $name }}"][container]').html(suggestion.value);
            $(':text[name="{{ $name }}"]').val(suggestion.data);
        },
        appendTo: $('span.autocomplete-container[name="{{ $name }}_auto"]'),
        minChars: 0
    });
    
    $('span[name="{{ $name }}"][reset]').click(function() {
        $('span[name="{{ $name }}"][container]').html('Не определено');
        $(':text[name="{{ $name }}"]').val('');
        $(':text[name="{{ $name }}_autocomplete"]').val('');
    });
});
</script>
<div class="label one" property="{{ $name }}"><span class="glyphicons glyphicons-tag"></span><span>{{ $title }}</span></div>
<div{!! $value ? '' : ' class="dnone"' !!} container="property" property="{{ $name }}">
    <input type="hidden" name="{{ $name }}" value="{{ $value ? $value->classId : null }}">
    <input type="text" name="{{ $name }}_autocomplete" value="" placeholder="ID или название"{!! $value ? '' : ' disabled="disabled"' !!}>
    <span name="{{ $name }}_auto" class="autocomplete-container"></span>
</div>