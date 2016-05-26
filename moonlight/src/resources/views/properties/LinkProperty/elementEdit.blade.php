<span>{{ $title }}</span>:
@if ($readonly)
	@if ($element)
		<a href="{{ URL::route('admin.edit', array('class' => get_class($element), 'id' => $element->id)) }}">{{ $element->$mainProperty }}</a>
	@else
		Не определено
	@endif
@else
	{{ Form::hidden($name, $element ? $element->id : null) }}
	@if ($element)
		<span id="{{ $name }}_show"><a href="{{ URL::route('admin.edit', array('class' => get_class($element), 'id' => $element->id)) }}">{{ $element->$mainProperty }}</a></span>
	@else
		<span id="{{ $name }}_show">Не определено</span>
	@endif
	{? $url = URL::route('admin.multihint', array('itemName' => $item->getName(), 'propertyName' => $name)) ?}
	&nbsp;{{ Form::text($name.'_name', 'Введите ID или название', array('class' => 'prop-mini grey', 'onetoone' => 'name', 'url' => $url, 'propertyName' => $name, 'default' => 'Введите ID или название')) }}
	&nbsp;&nbsp;<span id="{{ $name }}_reset" onetoone="reset" propertyName="{{ $name }}" class="small dashed hand">Очистить</span><br />
	<div class="error"><span error="{{ $name }}"></span></div>
@endif