@if ($readonly)
<span>{{ $title }}</span>: {{ $value ? $value->format('d.m.Y') : 'не определено' }}
@else
<script type="text/javascript">
$(function() {
	$('#{{ $name }}').calendar({
		triggerElement: '#{{ $name }}_show',
		dateFormat: '%Y-%m-%d',
		selectHandler: function() {
			$('#{{ $name }}_show').html(this.date.print('%e %G %Y года'));
			$('#{{ $name }}').val(this.date.print(this.dateFormat));
		}
	});
});
</script>
{{ Form::hidden($name, null, array('id' => $name)) }}
<span>{{ $title }}</span>: <span id="{{ $name }}_show" class="dashed hand">{{ $value ? sprintf('%d %s %04d года', $value->day, RussianTextUtils::getMonthInGenitiveCase($value->month), $value->year) : 'не определено' }}</span><br />
<div class="error"><span error="{{ $name }}"></span></div>
@endif