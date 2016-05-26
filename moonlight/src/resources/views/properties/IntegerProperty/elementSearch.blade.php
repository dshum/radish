<span switch="true" name="{{ $name }}" class="dashed hand" style="color: orange;">{{ $title }}</span>:<br>
<div id="{{ $name }}_block" style="display: {{ strlen($from) || strlen($to) ? 'block' : 'none' }};">
от <input type="text" class="prop-number" name="{{ $name }}_from" value="{{{ $from }}}"{{ strlen($from) || strlen($to) ? '' : ' disabled="disabled"' }}>
до <input type="text" class="prop-number" name="{{ $name }}_to" value="{{{ $to }}}"{{ strlen($from) || strlen($to) ? '' : ' disabled="disabled"' }}>
</div>