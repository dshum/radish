<label>{{ $title }}:</label><br>
@if ($exists)
<small><a href="{{ $path }}" target="_blank">{{ $filename }}</a>, {{ $filesize }} Кб</small><br>
@endif
@if ( ! $readonly)
<div class="loadfile">
    @if ($maxFilesize > 0)
    <small class="red">Максимальный размер файла {{ $maxFilesize }} Кб</small><br />
    @endif
    <div class="file" property="{{ $name }}">Выберите файл</div>
    <input type="file" name="{{ $name }}">
    <p>
        <input type="checkbox" name="{{ $name }}_drop" id="{{ $name }}_drop_checkbox" value="1">
        <label for="{{ $name }}_drop_checkbox">Удалить</label>
    </p>
</div>
@endif