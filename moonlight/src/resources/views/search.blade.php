@extends('moonlight::base')

@section('title', 'Поиск')

@section('css')
<link media="all" type="text/css" rel="stylesheet" href="/packages/moonlight/touch/css/search.css">
@endsection

@section('js')
<script>
jQuery.expr[':'].contains = function(a, i, m) {
    return jQuery(a).text().toUpperCase().indexOf(m[3].toUpperCase()) >= 0;
};

$(function() {
    $('#hamburger').click(function() {
        $('.sidebar').fadeToggle('fast');
        $('.main').toggleClass('non-overflow');

        return false;
    });

    $('#filter').addClear({
        right: 10,
        paddingRight: "25px",
        onClear: function(){
            $(".items li").show();
        }
    }).keyup(function() {
        var str = $(this).val()

        if (str.length > 0) {
            $(".items li:not(:contains('"+str+"'))").hide();
            $(".items li:contains('"+str+"')").show();
        } else {
            $(".items li").show();
        } 
    }).change(function() {
        var str = $(this).val()

        if (str.length > 0) {
            $(".items li:not(:contains('"+str+"'))").hide();
            $(".items li:contains('"+str+"')").show();
        } else {
            $(".items li").show();
        } 
    });
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
    @if ($items)
    <div class="search-field">
        <input type="text" id="filter" placeholder="Введите название">
    </div>
    <div class="search-sort">
        Сортировать классы по <b>частоте</b><br>
        или по <a href="">дате</a>, <a href="">названию</a>, <a href="">умолчанию</a>
    </div>
    <ul class="items">
        @foreach ($items as $item)
        <li item="{{ $item->getNameId() }}">
            <a href="{{ route('search.item', $item->getNameId()) }}">{{ $item->getTitle() }}</a><br><small>{{ $item->getNameId() }}</small>
        </li>
        @endforeach
    </ul>
    <br>
    @endif
</div>
@endsection