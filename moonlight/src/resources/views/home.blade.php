@extends('moonlight::base')

@section('title', 'Moonlight')

@section('css')
@endsection

@section('js')
<script>
    $(function() {
        
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
@if ($favoriteRubrics)
    @foreach ($favoriteRubrics as $favoriteRubric)
    <div class="block-elements">
        <h2>{{ $favoriteRubric->name }}</h2>
        <ul class="elements">
        @foreach ($favorites as $favorite)
            @if ($favorite->rubric_id == $favoriteRubric->id)
            <li><a href="">{{ $favorite->getElement()->name }}</a></li>
            @endif
        @endforeach
        </ul>
    </div>
    @endforeach
@endif
</div>
@endsection