@extends('moonlight::base')

@section('title', 'Поиск')

@section('css')
<link media="all" type="text/css" rel="stylesheet" href="/packages/moonlight/touch/css/browse.css">
@endsection

@section('js')
<script src="/packages/moonlight/touch/js/element.js"></script>
<script>
var favoritesUrl = '{{ route('home.favorites') }}';
var homeUrl = '{{ route('home') }}';
var searchUrl = '{{ route('search') }}';
var elementsUrl = '{{ route('elements.list') }}';
var title = '@yield('title')';
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
<div class="main">
    <div class="path">
        <a href="{{ route('search') }}">Поиск</a>
        <span class="halflings halflings-menu-right"></span>
    </div>
    <h2>{{ $currentItem->getTitle() }}</h2>
    <form>
        <div class="browse-form">
            <div class="right"><span id="form-toggler" class="icon"><span class="glyphicons glyphicons-adjust-alt"></span></span></div>
            <input type="text" name="search" placeholder="Поиск">
        </div>
        @if ($properties)
        <div id="form-container" class="dnone browse-form-params">
            <div class="row">
                @foreach ($properties as $property)
                    @if ($view = $property->getSearchView())
                    <div class="block">
                    {!! $view !!}
                    </div>
                    @endif
                @endforeach
            </div>
            <div class="row">
                <input type="submit" value="Найти" class="btn">
            </div>
        </div>
        @endif
    </form>
    <div class="list-container"></div>
</div>
@endsection