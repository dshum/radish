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
            var url = '{{ route('elements.count') }}';

            $.getJSON(url, {
                item: item
            }, function(data) {
                if (data && data.count) {
                    var span = $('<span class="total">'+data.count+'</span>');
                    var div = $('<div item="'+item+'" class="dnone list-container"></div>');

                    li.append(span).append(div);
                } else {
                    li.addClass('grey');
                }
            });
        });
        
        $('ul.items li span.a').click(function() {
            var li = $(this).parents('li');
            var item = li.attr('item');
            var url = '{{ route('elements.list') }}';
            
            li.addClass('waiting');
            
            $.getJSON(url, {
                item: item
            }, function(data) {
                li.removeClass('waiting');
                
                if (data.html) {
                    $('.list-container[item!="'+item+'"]').slideUp(200);
                    $('.list-container[item="'+item+'"]').html(data.html).slideDown(200);
                }
            }).fail(function() {
                li.removeClass('waiting');
                
                $.alertDefaultError();
            });
        
            return false;
        });
        
        $('body').on('click', '.next', function() {
            var next = $(this);
            var page = next.attr('page');
            var item = next.attr('item');
            var url = '{{ route('elements.list') }}';

            next.addClass('waiting');
            $.blockUI();

            $.getJSON(url, {
                item: item,
                page: page
            }, function(data) {
                $.unblockUI();
                
                next.remove();

                if (data.html) {
                    $('.list-container').append(data.html);
                }
            }).fail(function() {
                $.unblockUI();
                next.removeClass('waiting');
                
                $.alertDefaultError();
            });
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
<div class="bottom-context-menu">
    <div class="button copy"><span class="halflings halflings-duplicate"></span><br>Копировать</div>
    <div class="button move"><span class="halflings halflings-arrow-right"></span><br>Переместить</div>
    <div class="button delete"><span class="halflings halflings-trash"></span><br>Удалить</div>
</div>
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
    @if ($items)
    <ul class="items">
        @foreach ($items as $item)
        <li item="{{ $item->getNameId() }}">
            <span class="a">{{ $item->getTitle() }}</span>
        </li>
        @endforeach
    </ul>
    <br>
    @endif
</div>
@endsection