<span>{{ $title }}</span>: {{ RussianTextUtils::friendlyFileSize(strlen($value)) }}<br />
@if ( ! $readonly)
<div class="error"><span error="{{ $name }}"></span></div>
{{ Form::file($name) }}<br>
<small class="red">Максимальный размер файла {{ $maxFilesize }} Кб</small><br />
	@if ($value)
<script type="text/javascript">
$(function() {
	$('input:file[name={{ $name }}]').change(function() {
		$('input:checkbox[name="{{ $name }}_drop"]').prop('checked', false);
	});
	$('input:checkbox[name="{{ $name }}_drop"]').click(function() {
		if ($(this).prop('checked') == true) {
			$('input:file[name="{{ $name }}"]').val(null);
		}
	});
});
</script>
	@if ( ! $readonly)
{{ Form::checkbox($name.'_drop', 1, false, array('id' => $name.'_drop')) }} {{ Form::label($name.'_drop', 'Очистить') }}
	@endif
@endif