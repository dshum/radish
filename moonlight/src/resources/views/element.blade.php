@extends('moonlight::base')

@section('title', $element->{$currentItem->getMainProperty()})

@section('css')
<link media="all" type="text/css" rel="stylesheet" href="/packages/moonlight/touch/css/browse.css">
@endsection

@section('js')
<script src="/packages/moonlight/touch/js/jquery.autocomplete.min.js"></script>
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
        var classId = li.attr('classId');
        var item = li.attr('item');
        
        $.getJSON('{{ route('elements.list') }}', {
            classId: classId,
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
        var classId = $(this).attr('classId');
        var item = $(this).attr('item');
        var url = '{{ route('elements.count') }}';

        $.getJSON(url, {
            classId: classId, 
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
        var classId = li.attr('classId');
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
        var classId = next.attr('classId');
        var item = next.attr('item');
        var url = '{{ route('elements.list') }}';

        next.addClass('waiting');
        $.blockUI();

        $.getJSON(url, {
            classId: classId,
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
    
    $('#favorite-toggler').click(function() {
        var enabled = $(this).attr('enabled');
        var classId = $(this).attr('classId');
        var url = '{{ route('elements.favorite') }}';

        if (enabled == 'true') {
            $.post(url, {
                classId: classId,
                action: 'drop'
            }, function(data) {
                $('#favorite-toggler').attr('enabled', false).removeClass('active'); 
            });
        } else {
            $.confirm();
        }
    });

    $(':text[name="rubric"]').autocomplete({
        serviceUrl: '{{ route('elements.favorites') }}',
        appendTo: $('span.autocomplete-container[name="rubric_auto"]'),
        minChars: 0
    });

    $('.ok').click(function() {
        var classId = $('#favorite-toggler').attr('classId');
        var rubric = $(':text[name="rubric"]').val();
        var url = '{{ route('elements.favorite') }}';
        
        $.post(url, {
            classId: classId,
            rubric: rubric,
            action: 'add'
        }, function(data) {
            if (data.error) {
                $.alert(data.error);
            } else if (data.added) {
                $('#favorite-toggler').attr('enabled', true).addClass('active');
            }
            
            $(':text[name="rubric"]').val('');
            $.confirmClose();
        }).fail(function() {
            $.alertDefaultError();
        });
    });

    $('.cancel').click(function() {
        $('#favorite-toggler').attr('enabled', false).removeClass('active');
        $(':text[name="rubric"]').val('');
        $.confirmClose();
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
            <li><a href="{{ route('search') }}">Поиск</a></li>
            @if ($parent)
            <li><a href="{{ route('search.item', $parent->getItem()->getNameId()) }}">{{ $parent->getItem()->getTitle() }}</a></li>
            @endif
            <li><a href="{{ route('search.item', $currentItem->getNameId()) }}">{{ $currentItem->getTitle() }}</a></li>
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
<div class="bottom-context-menu">
    <div class="button copy"><span class="halflings halflings-duplicate"></span><br>Копировать</div>
    <div class="button move"><span class="halflings halflings-arrow-right"></span><br>Переместить</div>
    <div class="button delete"><span class="halflings halflings-trash"></span><br>Удалить</div>
</div>
<div class="confirm">
<div class="container">
    <div class="content">
        <p>Добавить на главную страницу?</p>
        <p>
            <input type="text" name="rubric" value="" placeholder="Рубрика">
            <span name="rubric_auto" class="autocomplete-container"></span>
        </p>
    </div>
    <div class="buttons">
        <input type="button" value="Добавить" class="btn ok">
        <input type="button" value="Отмена" class="btn cancel">
    </div>
</div>
</div>
<div class="main">
    @if ($parent)
    <div class="path">
        <a href="{{ route('browse') }}">Корень сайта</a>
        <span class="halflings halflings-menu-right"></span>
        <a href="{{ route('browse.element', $parent->getClassId()) }}">{{ $parent->name }}</a>
        <span class="halflings halflings-menu-right"></span></div>
    @else
    <div class="path">
        <a href="{{ route('browse') }}">Корень сайта</a>
        <span class="halflings halflings-menu-right"></span>
    </div>
    @endif
    <div id="favorite-toggler" classId="{{ $element->getClassId() }}" {!! $favorite ? 'enabled="true" class="right options active"' : 'class="right options"' !!}><span class="glyphicons glyphicons-pushpin"></span></div>
    <h2>{{ $element->{$currentItem->getMainProperty()} }}</h2>
    <ul class="elements">
        <li>
            <div class="date">{{ $element->created_at->format('d.m.Y') }}<br><span class="time">{{ $element->created_at->format('H:i:s') }}</span></div>
            <div class="edit"><a href="{{ route('element.edit', $element->getClassId()) }}"><span class="halflings halflings-pencil"></span></a></div>
            @if ($element->getTouchListView())
            {!! $element->getTouchListView() !!}
            @else
            <div>{{ $currentItem->getTitle() }}</div>
            @endif
        </li>
    </ul>
    @if ($items)
    <ul class="items">
        @foreach ($items as $item)
        <li item="{{ $item->getNameId() }}" classId="{{ $element->getClassId() }}">
            <span class="a">{{ $item->getTitle() }}</span>
        </li>
        @endforeach
    </ul>
    @endif
</div>
@endsection