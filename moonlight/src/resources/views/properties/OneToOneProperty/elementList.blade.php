@if ($value)
<a href="{{ URL::route('admin.edit', array('class' => get_class($value), 'id' => $value->id)) }}">{{ $value->$mainProperty }}</a>
@endif