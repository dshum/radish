<span>{{ $title }}</span>:<br />
@if ($exists)
<span class="grey">Загружено изображение: <a href="{{ $src }}" target="_blank">{{ $filename }}</a>, <span title="Размер изображения">{{ $width }}&#215;{{ $height }}</span> пикселов, {{ $filesize }} Кб<br /></span>
<img class="framed" src="{{ $src }}" width="{{ $width }}" height="{{ $height }}" alt="{{ $filename }}"><br />
@endif
@if (isset($resizes))
	@foreach ($resizes as $resizeName => $resize)
		@if ($resize['exists'])
<span class="grey">Загружено изображение: <a href="{{ $resize['src'] }}" target="_blank">{{ $resize['filename'] }}</a>, <span title="Размер изображения">{{ $resize['width'] }}&#215;{{ $resize['height'] }}</span> пикселов, {{ $resize['filesize'] }} Кб<br /></span>
<img class="framed" src="{{ $resize['src'] }}" width="{{ $resize['width'] }}" height="{{ $resize['height'] }}" alt="{{ $resize['filename'] }}"><br />
		@endif
	@endforeach
@endif
@if ( ! $readonly)
<div class="error"><span error="{{ $name }}"></span></div>
<input type="file" name="{{ $name }}"><br />
<small class="red">Максимальный размер файла {{ $maxFilesize }} Кб</small><br />
	@if ($maxWidth > 0 and $maxHeight > 0)
<small class="red">Максимальный размер изображения {{ $maxWidth }}&#215;{{ $maxHeight }} пикселей</small><br />
	@elseif ($maxWidth > 0)
<small class="red">Максимальная ширина изображения {{ $maxWidth }} пикселей</small><br />
	@elseif ($maxHeight > 0)
<small class="red">Максимальная высота изображения {{ $maxHeight }} пикселей</small><br />
	@endif
	@if ($exists)
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
    <p>
        <input type="checkbox" name="{{ $name }}_drop" id="{{ $name }}_drop_checkbox" value="1">
        <label for="{{ $name }}_drop_checkbox">Удалить</label>
    </p>
	@endif
@endif