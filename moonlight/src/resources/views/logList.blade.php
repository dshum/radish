@if (sizeof($userActions))
    @if ($currentPage == 1)
    <div class="count">Всего {{ $total }} {{ Moonlight\Utils\RussianTextUtils::selectCaseForNumber($total, ['операция', 'операции', 'операций']) }}.</div>
    @else
    <div class="page">Страница {{ $currentPage }}</div>
    @endif
    <ul class="log">
    @foreach ($userActions as $userAction)
    <li>
        <div class="date">{{ $userAction->created_at->format('d.m.Y') }}<br><span class="time">{{ $userAction->created_at->format('H:i:s') }}</span></div>
        <span class="user">{{ $userAction->user->login }}</span> <small>{{ $userAction->user->first_name }} {{ $userAction->user->last_name }}</small><br>
        <span class="title">{{ $userAction->getActionTypeName() }}</span> {{ $userAction->comments }}
    </li>
    @endforeach
    </ul>
    @if ($hasMorePages)
    <div><span class="next" page="{{ $currentPage + 1 }}">Дальше <span class="halflings halflings-menu-right"></span></span></div>
    @endif
@else
    <div class="empty">Операций не найдено.</div>
@endif