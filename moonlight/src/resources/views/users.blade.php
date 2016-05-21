@extends('moonlight::base')

@section('title', 'Пользователи')

@section('css')
<link media="all" type="text/css" rel="stylesheet" href="/packages/moonlight/touch/css/users.css">
@endsection

@section('js')
<script>
    $(function() {
        $('ul.users li .remove[group]:not(.disabled)').click(function() {
            var url = $(this).attr('url');
            var name = $(this).attr('name');
            var html = 'Удалить группу &laquo;'+name+'&raquo;?';

            $('.confirm .remove').attr('url', url);
            
            $.confirm(html);
        });
        
        $('ul.users li .remove[user]:not(.disabled)').click(function() {
            var url = $(this).attr('url');
            var name = $(this).attr('name');
            var html = 'Удалить пользователя &laquo;'+name+'&raquo;?';

            $('.confirm .remove').attr('url', url);
            
            $.confirm(html);
        });

        $('.confirm .remove').click(function() {
            let url = $(this).attr('url');

            if ( ! url) return false;

            $.confirmClose();
            $.blockUI();

            $.post(
                url,
                {},
                function(data) {
                    $.unblockUI();

                    if (data.error) {
                        $.alert(data.error);
                    } else if (data.group) {
                        $('li[group="'+data.group+'"]').slideUp('fast');
                    } else if (data.user) {
                        $('li[user="'+data.user+'"]').slideUp('fast');
                    }
                }
            );
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
<div class="confirm">
    <div class="container">
        <div class="content"></div>
        <div class="buttons">
            <input type="button" value="Удалить" class="btn danger remove">
            <input type="button" value="Отмена" class="btn cancel">
        </div>
    </div>
</div>
<div class="main">
@if (sizeof($groups))
    <h2>Группы<a href="{{ route('group.create') }}" class="addnew"><span class="halflings halflings-plus-sign"></span></a></h2>
    <ul class="users">
    @foreach ($groups as $group)
        <li group="{{ $group->id }}">
            <div class="remove" group url="{{ route('group.delete', $group->id) }}" name="{{ $group->name }}"><span class="halflings halflings-remove-circle"></span></div>
            <div class="date">{{ $group->created_at->format('d.m.Y') }}<br><span class="time">{{ $group->created_at->format('H:i:s') }}</span></div>
            <a href="{{ route('group', $group->id) }}">{{ $group->name }}</a><br>
            <small>{{ $group->getPermissionTitle() }}</small><br>
            @if ($group->hasAccess('admin'))
            <small>Управление пользователями</small><br>
            @endif
            <a href="group-item-permissions.html" class="perms">Доступ по умолчанию</a>
        </li>
    @endforeach
    </ul>
@endif
@if (sizeof($users))
    <h2>Пользователи<a href="{{ route('user.create') }}" class="addnew"><span class="halflings halflings-plus-sign"></span></a></h2>
    <ul class="users">
    @foreach ($users as $user)
        <li user="{{ $user->id }}">
        @if ($user->isSuperUser() || $user->id == $loggedUser->id)
            <div class="remove disabled"><span class="halflings halflings-remove-circle"></span></div>
            <div class="date">{{ $user->created_at->format('d.m.Y') }}<br><span class="time">{{ $user->created_at->format('H:i:s') }}</span></div>
            <span class="user">{{ $user->login }}</span>
        @else
            <div class="remove" user url="{{ route('user.delete', $user->id) }}" name="{{ $user->first_name }} {{ $user->last_name }}"><span class="halflings halflings-remove-circle"></span></div>
            <div class="date">{{ $user->created_at->format('d.m.Y') }}<br><span class="time">{{ $user->created_at->format('H:i:s') }}</span></div>
            <a href="{{ route('user', $user->id) }}">{{ $user->login }}</a>
        @endif
            {{ $user->first_name }} {{ $user->last_name }}<br>
            <span class="email">{{ $user->email }}</span><br>
        @if ($user->isSuperUser())
            <small>Суперпользователь</small><br>
        @endif
        @if (isset($userGroups[$user->id]))
            @foreach ($userGroups[$user->id] as $k => $group)
            <small>{{ $group->name }}</small>{{ $k < sizeof($userGroups[$user->id]) - 1 ? ',' : '' }}
            @endforeach
        @endif
        </li>
    @endforeach
    </ul>
@endif
</div>
@endsection