<span switch="true" name="{{ $name }}" class="dashed hand" style="color: blue;">{{ $title }}</span><span id="{{ $name }}_sign" class="grey" style="display: {{ $value ? 'inline' : 'none' }};"> (введите ID или название)</span>:<br>
<div id="{{ $name }}_block" style="display: {{ $value ? 'block' : 'none' }};">
<input type="hidden" id="{{ $name }}" name="{{ $name }}" value="{{ $value }}"{{ $value ? '' : ' disabled="disabled"' }}>
ID <span id="{{ $name }}_show">{{ $value ? $value->id : 'не определен' }}</span>
{{ Form::text($name.'_name', $valueName, array('onetoone' => 'name', 'url' => $url, 'propertyName' => $name, 'disabled' => $value ? null : 'disabled')) }}
<span id="{{ $name }}_reset" onetoone="reset" propertyName="{{ $name }}" class="small dashed hand">Очистить</span>
</div>