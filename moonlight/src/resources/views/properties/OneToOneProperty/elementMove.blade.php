<span error="{{ $name }}">{{ $title }}</span>:
@if (isset($treeView))
	<span id="{{ $name }}_title" onetoone="title" name="{{ $name }}" class="dashed hand">{{ ! $element ? 'Не менять' : ($value ? $value->$mainProperty : 'Не определено') }}</span>
	<div id="{{ $name }}_block" class="blank dnone one">
		@if ( ! $element)
			<div class="tree">{{ Form::radio($name, -1, $element ? false : true, array('id' => $name.'__1', 'onetoone' => 'radio')) }} {{ Form::label($name.'__1', 'Не менять') }}</div>
		@endif
		@if ( ! $required)
			<div class="tree">{{ Form::radio($name, '', ! $element || $value ? false : true, array('id' => $name.'_0', 'onetoone' => 'radio')) }} {{ Form::label($name.'_0', 'Не определено') }}</div>
		@endif
		{{ $treeView }}
	</div>
@else
	@if ( ! $element)
		{{ Form::hidden($name, -1) }}
		<span id="{{ $name }}_show">Не менять</span>
	@elseif ( ! $value)
		{{ Form::hidden($name, null) }}
		<span id="{{ $name }}_show">Не определено</span>
	@else
		{{ Form::hidden($name, $value->id) }}
		<span id="{{ $name }}_show"><a href="{{ URL::route('admin.edit', array('class' => get_class($value), 'id' => $value->id)) }}">{{ $value->$mainProperty }}</a></span>
	@endif
	{? $url = URL::route('admin.hint', array('class' => $relatedClass)) ?}
	&nbsp;{{ Form::text($name.'_name', 'Введите ID или название', array('class' => 'prop-mini grey', 'onetoone' => 'name', 'url' => $url, 'propertyName' => $name, 'default' => 'Введите ID или название')) }}
	&nbsp;&nbsp;<span id="{{ $name }}_reset" onetoone="reset" propertyName="{{ $name }}" default="Не определено" class="small dashed hand">Очистить</span>
	&nbsp;&nbsp;<span id="{{ $name }}_unset" onetoone="unset" propertyName="{{ $name }}" default="Не менять" class="small dashed hand">Сбросить</span>
@endif