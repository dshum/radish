@extends('moonlight::base')

@section('title', 'Журнал')

@section('css')
<link media="all" type="text/css" rel="stylesheet" href="/packages/moonlight/touch/css/log.css">
@endsection

@section('js')
<script src="/packages/moonlight/touch/js/log.js"></script>
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
    <form action="{{ route('log.search') }}" autocomplete="off">
        <input type="submit" class="phantom">
        <div class="log-form">
            <div class="right"><span id="form-toggler" class="icon"><span class="glyphicons glyphicons-adjust-alt"></span></span></div>
            <input type="text" name="comments" placeholder="Поиск">
            <span class="submit-button"><span class="halflings halflings-menu-right"></span></span>
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