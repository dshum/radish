@if ($readonly)
<span>{{ $title }}</span>: {{ $value ? $value->format('H:i:s') : 'не определено' }}
@else
<script type="text/javascript">
$(function() {
	$('#{{ $name }}_hour').keyup(function() {
		LT.Edit.setTime('{{ $name }}');
	}).change(function() {
		LT.Edit.setTime('{{ $name }}');
	});

	$('#{{ $name }}_minute').keyup(function() {
		LT.Edit.setTime('{{ $name }}');
	}).change(function() {
		LT.Edit.setTime('{{ $name }}');
	});

	$('#{{ $name }}_second').keyup(function() {
		LT.Edit.setTime('{{ $name }}');
	}).change(function() {
		LT.Edit.setTime('{{ $name }}');
	});
});
</script>
{{ Form::hidden($name, null, array('id' => $name)) }}
<span>{{ $title }}</span>:
{{ Form::text($name.'_hour', $value ? $value->format('H') : null, array('id' => $name.'_hour', 'class' => 'prop-time', 'maxlength' => 2))}} :
{{ Form::text($name.'_minute', $value ? $value->format('i') : null, array('id' => $name.'_minute', 'class' => 'prop-time', 'maxlength' => 2))}} :
{{ Form::text($name.'_second', $value ? $value->format('s') : null, array('id' => $name.'_second', 'class' => 'prop-time', 'maxlength' => 2))}}<br />
<div class="error"><span error="{{ $name }}"></span></div>
@endif