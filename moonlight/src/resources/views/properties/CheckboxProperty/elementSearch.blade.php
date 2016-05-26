<span switch="true" name="{{ $name }}" class="dashed hand" style="color: deepskyblue;">{{ $title }}</span>:<br>
<div id="{{ $name }}_block" style="display: {{ $value ? 'block' : 'none' }};">
<input type="radio" id="{{ $name }}_true" name="{{ $name }}" value="true"{{ $value === 'true' ? ' checked' : '' }}{{ $value === null ? ' disabled="true"' : '' }}><label for="{{ $name }}_true">Да</label>
<input type="radio" id="{{ $name }}_false" name="{{ $name }}" value="false"{{ $value === 'false' ? ' checked' : '' }}{{ $value === null ? ' disabled="true"' : '' }}><label for="{{ $name }}_false">Нет</label>
<input type="radio" id="{{ $name }}_null" name="{{ $name }}" value=""{{ ! $value ? ' checked' : '' }}{{ $value === null ? ' disabled="true"' : '' }}><label for="{{ $name }}_null">Не важно</label>
</div>