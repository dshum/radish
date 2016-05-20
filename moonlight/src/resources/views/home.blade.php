@extends('moonlight::base')

@section('title', 'Moonlight')

@section('css')
@endsection

@section('js')
<script>
    $(function() {
        
    });
</script>
@endsection

@section('body')
<nav>
    <left><span class="hamburger"><span class="glyphicons glyphicons-menu-hamburger"></span></span></left>
    <center><a href="{{ route('home') }}">@yield('title')</a></center>
    <right><a href="{{ route('search') }}"><span class="glyphicons glyphicons-search"></span></a></right>
</nav>
<div class="sidebar">
    <div class="sidebar-container">
        <ul class="menu">
            <li><a href="">Пользователь</a></li>
            <li><a href="">Безналичный счет</a></li>
            <li><a href="">Квитанция сбербанка</a></li>
            <li><a href="">Служебный раздел</a></li>
            <li><a href="">Агентство недвижимости</a></li>
            <li><hr></li>
            <li><a href="{{ route('browse') }}">Корень сайта</a></li>
            <li><a href="{{ route('trash') }}">Корзина</a></li>
            <li><hr></li>
            <li><a href="{{ route('users') }}">Пользователи</a></li>
            <li><a href="{{ route('log') }}">Журнал</a></li>
            <li><hr></li>
            <li><a href="{{ route('profile') }}">{{ $loggedUser->first_name }} {{ $loggedUser->last_name }}</a></li>
            <li><a href="{{ route('logout') }}">Выход</a></li>
        </ul>
    </div>
</div>
<div class="main">
    <div class="block-elements">
        <h2>Статистика</h2>
        <ul class="elements">
            <li><a href="">Выручка</a></li>
            <li><a href="">Выручка по способам оплаты</a></li>
            <li><a href="expenses.html">Расходы</a></li>
            <li><a href="">Счета и акты</a></li>
            <li><a href="">Акты сверки</a></li>
            <li><a href="">Скорректированные акты</a></li>
            <li><a href="">Сводная таблица услуг, пополнений и актов</a></li>
            <li><a href="">Пользователи с актами за последний год</a></li>
            <li><a href="">Черная книга бухгалтера</a></li>
        </ul>
    </div>
    <div class="block-elements">
        <h2>Объявления</h2>
        <ul class="elements">
            <li><a href="">Продажа квартир и комнат</a></li>
            <li><a href="">Аренда квартир и комнат</a></li>
            <li><a href="">Продажа домов и дач</a></li>
            <li><a href="">Аренда домов и дач</a></li>
            <li><a href="">Продажа коммерческой недвижимости</a></li>
            <li><a href="">Аренда коммерческой недвижимости</a></li>
            <li><a href="">Продажа участков</a></li>
            <li><a href="">Аренда участков</a></li>
            <li><a href="">Продажа гаражей</a></li>
            <li><a href="">Аренда гаражей</a></li>
        </ul>
        </div>
    <div class="block-elements">
    <h2>Разделы сайта</h2>
        <ul class="elements">
            <li><a href="browse-section.html">Агентства недвижимости</a></li>
            <li><a href="">Застройщики</a></li>
            <li><a href="">Жилые комплексы</a></li>
            <li><a href="">Коттеджные поселки</a></li>
            <li><a href="">Бизнес-центры</a></li>
        </ul>
    </div>
    <div class="block-elements">
        <h2>Прочее</h2>
        <ul class="elements">
            <li><a href="">Расходы</a></li>
            <li><a href="">Синхрофазотрон</a></li>
            <li><a href="browse-agency.html">СемьЯ-недвижимость</a></li>
            <li><a href="browse-siteuser.html">2003322@mail.ru</a></li>
        </ul>
    </div>
</div>
@endsection