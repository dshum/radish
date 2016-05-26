<div edit="title" show="true" class="dinline dashed hand">{{ $value ? 'Да' : 'Нет' }}</div>
<div edit="container" class="dnone">
<div class="error"><span error="{{ $element->getClassId() }}.{{ $name }}"></span></div>
<label>{{ Form::radio("edit[{$element->getClass()}][{$element->id}][$name]", 1, $value ? true : false, array('edit' => 'input', 'disabled')) }} Да</label><br />
<label>{{ Form::radio("edit[{$element->getClass()}][{$element->id}][$name]", 0, $value ? false : true, array('edit' => 'input', 'disabled')) }} Нет</label>
</div>