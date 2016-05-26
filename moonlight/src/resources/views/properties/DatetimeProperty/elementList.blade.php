@if ($value)
{{ $value->format('d.m.Y') }}<br>
<small>{{ $value->format('H:i:s') }}</small>
@endif