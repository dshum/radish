@if ($element)
<a href="{{ URL::route('admin.edit', array('class' => get_class($element), 'id' => $element->id)) }}">{{ $element->$mainProperty }}</a>
@endif