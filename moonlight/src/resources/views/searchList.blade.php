@if ($items)
<div class="search-field">
    <input type="text" id="filter" placeholder="Введите название">
</div>
<div class="search-sort">
    Сортировать классы по <b>{{ $sorts[$sort] }}</b><br>
    или по
    @foreach ($sorts as $name => $title)
        @if ($name != $sort)
        <span class="dashed hand" sort="{{ $name }}">{{ $title }}</span>,
        @endif
    @endforeach
</div>
<ul class="items">
    @foreach ($items as $item)
    <li item="{{ $item->getNameId() }}">
        <a href="{{ route('search.item', $item->getNameId()) }}">{{ $item->getTitle() }}</a><br><small>{{ $item->getNameId() }}</small>
    </li>
    @endforeach
</ul>
@endif