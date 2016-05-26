<span>{{ $title }}</span>:
@if ($readonly)
{{ Form::password($name, array('class' => 'prop-pass', 'readonly')) }}
@else
{{ Form::password($name, array('class' => 'prop-pass')) }}<br />
<div class="error"><span error="{{ $name }}"></span></div>
@endif