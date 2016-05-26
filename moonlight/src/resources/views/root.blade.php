@extends('moonlight::base')

@section('title', 'Корень сайта')

@section('css')
<link media="all" type="text/css" rel="stylesheet" href="/packages/moonlight/touch/css/browse.css">
@endsection

@section('js')
<script>
$(function() {
    var checked = [];
    var opened = null;

    var cancelSelection = function() {
        $('left').html('<span class="hamburger"><span class="glyphicons glyphicons-menu-hamburger"></span></span>');
        $('center').html('<a href="{{ route('home') }}">@yield('title')</a>');
        $('right').html('<a href="{{ route('search') }}"><span class="glyphicons glyphicons-search"></span></a>');
        $('.bottom-context-menu').fadeOut('fast');

        $('ul.items li.checked')
            .prop('checked', false)
            .removeClass('checked');

        checked = [];
    };
    
    var loadElements = function(li) {
        var item = li.attr('item');
        
        $.getJSON('{{ route('elements.list') }}', {
            item: item
        }, function(data) {
            if (data.html) {
                $('.list-container[item="'+item+'"]').html(data.html).slideDown(200);
                
                opened = item;
            }
        }).fail(function() {
            $.alertDefaultError();
        });
    };

    $('ul.items li').each(function() {
        var li = $(this);
        var item = $(this).attr('item');
        var url = '{{ route('elements.count') }}';

        $.getJSON(url, {
            item: item
        }, function(data) {
            if (data && data.count) {
                var span = $('<span class="dnone total">'+data.count+'</span>');
                var div = $('<div item="'+item+'" class="dnone list-container"></div>');

                li.append(span).append(div);
                span.fadeIn(200);
            } else {
                li.addClass('grey');
            }
        });
    });

    $('ul.items li span.a').click(function() {
        var li = $(this).parents('li');
        var item = li.attr('item');
        var url = '{{ route('elements.list') }}';
        
        if (li.hasClass('grey')) return false;
        if (opened == item) return false;

        if (opened) {
            $('.list-container[item="'+opened+'"]').slideUp(200, function() {
                cancelSelection();
                loadElements(li);
            });
        } else {
            loadElements(li);
        }

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

    $('body').on('click', '.check', function() {
        var classId = $(this).attr('classId');

        $('ul.elements li[classId="'+classId+'"]').toggleClass('checked');

        if ($(this).prop('checked') == true) {
            $(this).prop('checked', false);

            var i = checked.indexOf(classId);
            if (i > -1) {
                checked.splice(i, 1);
            }
        } else {
            $(this).prop('checked', true);

            checked.push(classId);
        }

        if (checked.length == 1) {
            $('left').html('<span>Выделено</span>');
            $('right').html('<span id="cancelSelection">Отмена</span>');
            $('.bottom-context-menu').fadeIn('fast');
        }

        if (checked.length) {
            $('center').html(checked.length);
        } else {
            cancelSelection();
        }
    });
    
    $('body').on('click', '#cancelSelection', function() {
        cancelSelection();
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