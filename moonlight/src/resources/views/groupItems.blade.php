@extends('moonlight::base')

@section('title', $group->name)

@section('css')
<link media="all" type="text/css" rel="stylesheet" href="/packages/moonlight/touch/css/permissions.css">
@endsection

@section('js')
<script>
jQuery.expr[':'].contains = function(a, i, m) {
    return jQuery(a).text().toUpperCase().indexOf(m[3].toUpperCase()) >= 0;
};
        
$(function() {
    var checked = [];
    
    var cancelSelection = function() {
        $('left').html('<a href="{{ route('users') }}"><span class="halflings halflings-menu-left"></span></a>');
        $('center').html('<a href="{{ route('home') }}">@yield('title')</a>');
        $('right').html('<a href="{{ route('search') }}"><span class="glyphicons glyphicons-search"></span></a>');
        $.bottomMenuClose();

        $('ul.items li.checked')
            .prop('checked', false)
            .removeClass('checked');

        checked = [];
    };

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

    $('ul.items li a').click(function(e) {
        e.stopPropagation();
    });

    $('ul.items li').click(function() {
        var item = $(this).attr('item');
        
        $(this).toggleClass('checked');

        if ($(this).prop('checked') == true) {
            $(this).prop('checked', false);
            
            var i = checked.indexOf(item);
            if (i > -1) {
                checked.splice(i, 1);
            }
        } else {
            $(this).prop('checked', true);
            
            checked.push(item);
        }

        if (checked.length == 1) {
            $('left').html('<span>Выделено</span>');
            $('right').html('<span id="cancelSelection">Отмена</span>');
            $.bottomMenu();
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
    
    $('.button[permission]').click(function() {
        var url = '{{ route('group.items.save', $group->id) }}';
        var permission = $(this).attr('permission');
        
        $.blockUI();
        
        $.post(url, {checked: checked, permission: permission}, function (data) {
            $.unblockUI();
            
            if (data.permissions) {
                for (var item in data.permissions) {
                    var permission = data.permissions[item].permission;
                    var title = data.permissions[item].title;
                    
                    $('ul.items li[item="'+item+'"] div.permission').remove();
                    $('ul.items li[item="'+item+'"]').prepend('<div class="permission '+permission+'">'+title+'</div>');
                }
            }
            
            cancelSelection();
        }, 'json').fail(function (data) {
            $.unblockUI();
        });
    });
});
</script>
@endsection

@section('body')
<nav>
    <left><a href="{{ route('users') }}"><span class="halflings halflings-menu-left"></span></a></left>
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
<div class="bottom-context-menu">
    <div class="button deny" permission="deny"><span class="halflings halflings-ban-circle"></span><br>Закрыто</div>
    <div class="button view" permission="view"><span class="halflings halflings-file"></span><br>Просмотр</div>
    <div class="button update" permission="update"><span class="halflings halflings-pencil"></span><br>Изменение</div>
    <div class="button delete" permission="delete"><span class="halflings halflings-trash"></span><br>Удаление</div>
</div>
<div class="main">
    <div class="search-field">
        <input type="text" id="filter" placeholder="Введите название">
    </div>
    @if ($items)
    <ul class="items">
        @foreach ($items as $item)
            @if ($item->getElementPermissions())
            <li item="{{ $item->getNameId() }}">
                @if ($permissions[$item->getNameId()] == 'deny')
                <div class="permission deny">Закрыто</div>
                @elseif ($permissions[$item->getNameId()] == 'view')
                <div class="permission view">Просмотр</div>
                @elseif ($permissions[$item->getNameId()] == 'update')
                <div class="permission update">Изменение</div>
                @elseif ($permissions[$item->getNameId()] == 'delete')
                <div class="permission delete">Удаление</div>
                @endif
                <a href="{{ route('group.elements', [$group->id, $item->getNameId()]) }}">{{ $item->getTitle() }}</a><br>
                <small>{{ $item->getNameId() }}</small>
            </li>
            @endif
        @endforeach
        @foreach ($items as $item)
            @if ( ! $item->getElementPermissions())
            <li item="{{ $item->getNameId() }}">
                @if ($permissions[$item->getNameId()] == 'deny')
                <div class="permission deny">Закрыто</div>
                @elseif ($permissions[$item->getNameId()] == 'view')
                <div class="permission view">Просмотр</div>
                @elseif ($permissions[$item->getNameId()] == 'update')
                <div class="permission update">Изменение</div>
                @elseif ($permissions[$item->getNameId()] == 'delete')
                <div class="permission delete">Удаление</div>
                @endif
                {{ $item->getTitle() }}<br>
                <small>{{ $item->getNameId() }}</small>
            </li>
            @endif
        @endforeach
    </ul>
    @endif
</div>
@endsection