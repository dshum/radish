@extends('moonlight::base')

@section('title', 'Журнал')

@section('css')
<link media="all" type="text/css" rel="stylesheet" href="/packages/moonlight/touch/css/log.css">
@endsection

@section('js')
<script>
$(function() {
    $('[name="comments"]').addClear({
        right: 10,
        paddingRight: "25px"
    });

    $('[name="date-from"]').calendar({
        dateFormat: '%Y-%m-%d',
        selectHandler: function() {
            $('[name="date-from"]').val(this.date.print(this.dateFormat));
        }
    });

    $('[name="date-to"]').calendar({
        dateFormat: '%Y-%m-%d',
        selectHandler: function() {
            $('[name="date-to"]').val(this.date.print(this.dateFormat));
        }
    });

    $('#form-toggler').click(function() {
        $('#form-container').slideToggle('fast');
    });

    $('.reset').click(function() {
        $('[name="date-from"]').val(null);
        $('[name="date-to"]').val(null);
    });

    $('form').submit(function() {
        let url = $(this).attr('action');
        let comments = $('[name="comments"]').val();
        let user = $('[name="user"]').val();
        let type = $('[name="type"]').val();
        let dateFrom = $('[name="date-from"]').val();
        let dateTo = $('[name="date-to"]').val();

        $('#form-container').slideUp('fast', function() {
            $.blockUI();

            $.getJSON(url, {
                comments: comments,
                user: user,
                type: type,
                dateFrom: dateFrom,
                dateTo: dateTo
            }, function(data) {
                $.unblockUI();

                if (data.html) {
                    $('.list-container').html(data.html);
                }
            }).fail(function() {
                $.unblockUI();

                $.alertDefaultError();
            });
        });

        event.preventDefault();
    });

    $('body').on('click', '.next', function() {
        let next = $(this);
        let page = next.attr('page');
        let url = $('form').attr('action');
        let comments = $('[name="comments"]').val();
        let user = $('[name="user"]').val();
        let type = $('[name="type"]').val();
        let dateFrom = $('[name="date-from"]').val();
        let dateTo = $('[name="date-to"]').val();

        next.addClass('waiting');
        $.blockUI();

        $.getJSON(url, {
            comments: comments,
            user: user,
            type: type,
            dateFrom: dateFrom,
            dateTo: dateTo,
            page: page
        }, function(data) {
            $.unblockUI();

            next.remove();

            if (data.html) {
                $('.list-container').append(data.html);
            }
        }).fail(function() {
            $.unblockUI();

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
    <form action="{{ route('log.search') }}" autocomplete="off">
        <input type="submit" class="phantom">
        <div class="log-form">
            <div class="right"><span id="form-toggler" class="icon"><span class="glyphicons glyphicons-adjust-alt"></span></span></div>
            <input type="text" name="comments" placeholder="Поиск">
        </div>
        <div id="form-container" class="dnone log-form-params">
            <div class="row">
                <div class="block">
                    <select name="user">
                        <option value="">Все пользователи</option>
                        @foreach ($users as $user)
                        <option value="{{ $user->id }}">{{ $user->login }} ({{ $user->first_name }} {{ $user->last_name }})</option>
                        @endforeach
                    </select>
                </div>
                <div class="block">
                    <select name="type">
                        <option value="">Все операции</option>
                        @foreach ($userActionTypes as $id => $name)
                        <option value="{{ $id }}">{{ $name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="block">
                    <input type="text" name="date-from" value="" class="date" placeholder="Дата от" readonly> &#151;
                    <input type="text" name="date-to" value="" class="date" placeholder="Дата до" readonly>
                    <span class="reset">&#215;</span>
                </div>
            </div>
            <div class="row">
                <input type="submit" value="Найти" class="btn">
            </div>
        </div>
    </form>
    <div class="list-container"></div>
</div>
@endsection