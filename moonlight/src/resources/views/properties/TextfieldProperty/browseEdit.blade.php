<div edit="title" show="true" class="dinline dashed hand">{{ $value }}</div>
<div edit="container" class="dnone">
<div class="error"><span error="{{ $element->getClassId() }}.{{ $name }}"></span></div>
{{ Form::text("edit[{$element->getClass()}][{$element->id}][$name]", $value, array('edit' => 'input', 'disabled')) }}
</div>