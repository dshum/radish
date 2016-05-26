<span switch="true" name="{{ $name }}" class="dashed hand" style="color: #000;">{{ $title }}</span>:<br>
<div id="{{ $name }}_block" style="display: {{ $value ? 'block' : 'none' }};">
<input type="text" class="prop" name="{{ $name }}" value="{{{ $value }}}"{{ $value ? '' : ' disabled="disabled"' }}>
</div>