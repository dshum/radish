@extends('moonlight::base')

@section('title', $element->{$currentItem->getMainProperty()})

@section('css')
<link media="all" type="text/css" rel="stylesheet" href="/packages/moonlight/touch/css/browse.css">
@endsection

@section('js')
<script>
    $(function() {
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
            var classId = li.attr('classId');
            var item = li.attr('item');
            var url = '{{ route('elements.list') }}';
            
            if (li.hasClass('grey')) return false;
            
            li.addClass('waiting');
            
            $.getJSON(url, {
                classId: classId,
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
    <div id="favorite-toggler" class="right options active"><span class="glyphicons glyphicons-pushpin"></span></div>
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