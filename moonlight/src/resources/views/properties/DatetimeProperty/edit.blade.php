<label>{{ $title }}:</label><br>
@if ($readonly && $value)
{{ $value->format('d.m.Y, H:i:s') }}
@elseif ($readonly)
<span class="grey">Не определено</span>
@else
<script>
$(function() {
    $('span[name="{{ $name }}_date"]').calendar({
        dateFormat: '%Y-%m-%d',
        selectHandler: function() {
            $('[name="{{ $name }}"]').val(this.date.print(this.dateFormat));
            $('span[name="{{ $name }}_date"]').html(this.date.print('%d.%m.%Y'));
        }
    });
});
</script>
<input type="hidden" name="{{ $name }}" value="{{ $value ? $value->format('Y-m-d H:i:s') : null }}">
<span class="dashed hand" name="{{ $name }}_date">{{ $value ? $value->format('d.m.Y') : 'Не определено' }}</span>,&nbsp;
<input type="text" class="time" value="{{ $value ? $value->hour : null }}"> : 
<input type="text" class="time" value="{{ $value ? $value->minute : null }}"> : 
<input type="text" class="time" value="{{ $value ? $value->second : null }}">
@endif