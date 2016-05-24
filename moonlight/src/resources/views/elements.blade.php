@if (sizeof($elements))
    @if ($currentPage == 1)
    <div class="count">Всего {{ $total }} {{ Moonlight\Utils\RussianTextUtils::selectCaseForNumber($total, ['элемент', 'элемента', 'элементов']) }}. Отсортировано по порядку.</div>
    @else
    <div class="page">Страница {{ $currentPage }}</div>
    @endif
    <ul class="elements">
    @foreach ($elements as $element)
    <li elementid="Agency.1">
        <div class="check" elementid="Agency.1"></div>
        <div class="date">{{ $element->created_at->format('d.m.Y') }}<br><span class="time">{{ $element->created_at->format('H:i:s') }}</span></div>
        <div class="edit"><a href="{{ route('element.edit', $element->getClassId()) }}"><span class="halflings halflings-pencil"></span></a></div>
        <div><a href="{{ route('browse.element', $element->getClassId()) }}">{{ $element->{$currentItem->getMainProperty()} }}</a></div>
    </li>
    @endforeach
    </ul>
    @if ($hasMorePages)
    <div><span class="next" page="{{ $currentPage + 1 }}">Дальше <span class="halflings halflings-menu-right"></span></span></div>
    @endif
@else
    <div class="empty">Элементов не найдено.</div>
@endif