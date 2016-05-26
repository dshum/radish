<label>{{ $title }}:</label><br>
@if ($readonly && $value['human'])
{{ $value['human'] }}
@elseif ($readonly)
<span class="grey">Не определено</span>
@else
<input type="text" name="{{ $name }}" value="{{ $value['date'] }}" placeholder="{{ $title }}">
@endif