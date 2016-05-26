<span>{{ $title }}</span>:
@if ($readonly)
{{ Form::text($name, null, array('class' => 'prop-number', 'readonly')) }}
@else
{{ Form::text($name, null, array('class' => 'prop-number')) }}<br />
<div class="error"><span error="{{ $name }}"></span></div>
@endif