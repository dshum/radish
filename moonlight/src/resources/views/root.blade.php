@extends('moonlight::base')

@section('title', 'Корень сайта')

@section('css')
<link media="all" type="text/css" rel="stylesheet" href="/packages/moonlight/touch/css/browse.css">
@endsection

@section('js')
<script>
    $(function() {
        $('ul.items li').each(function() {
            var li = $(this);
            var item = $(this).attr('item');

            $.getJSON(
                '{{ route('elements.count') }}',
                {item: item},
                function(data) {
                   if (data && data.count) {
                        let span = $('<span class="total">'+data.count+'</span>');
                        let div = $('<div item="'+item+'" class="list-container"></div>');

                        li.append(span).append(div);
                    } else {
                        let h = li.find('a').html();
                        li.addClass('grey').html(h);
                    }
                }
            );
        });
        
        $('ul.items li').click(function() {
            var li = $(this);
            var item = $(this).attr('item');
            var url = '{{ route('elements.list') }}';
            
            $.getJSON(url, {
                item: item
            }, function(data) {
                if (data.html) {
                    $('.list-container[item!="'+item+'"]').slideUp('fast');
                    $('.list-container[item="'+item+'"]').html(data.html).slideDown('fast');
                }
            }).fail(function() {
                $.alertDefaultError();
            });
        
            return false;
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
    <h2>Корень сайта</h2><br>
    @if ($items)
    <ul class="items">
        @foreach ($items as $item)
        <li item="{{ $item->getNameId() }}">
            <span><a href="{{ route('browse.root.list', $item->getNameId()) }}">{{ $item->getTitle() }}</a></span>
        </li>
        @endforeach
    </ul>
    <br>
    @endif
</div>
@endsection