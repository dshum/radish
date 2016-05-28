@extends('moonlight::base')

@section('title', 'Moonlight')

@section('css')
<link media="all" type="text/css" rel="stylesheet" href="/packages/moonlight/touch/css/home.css">
@endsection

@section('js')
<script>
    $(function() {
        $('.sortable').sortable();
        $('.sortable').disableSelection();
    
        $('.remove[rubricId]').click(function() {
            var remove = $(this);
            var rubricId = $(this).attr('rubricId');
            var url = '{{ route('elements.favorite') }}';
            
            $.post(url, {
                rubricId: rubricId,
                action: 'dropRubric'
            }, function(data) {
                if (data.deleted) {
                    remove.parents('h2').fadeOut('fast').remove();
                }
            });
        });
        
        $('.remove[classId]').click(function() {
            var remove = $(this);
            var classId = $(this).attr('classId');
            var url = '{{ route('elements.favorite') }}';
        
            $.post(url, {
                classId: classId,
                action: 'drop'
            }, function(data) {
                if (data.deleted) {
                    remove.parents('li').fadeOut('fast').remove();
                }
            });
        });
        
        $('.edit-favorites-toggler').click(function() {
            $('.remove').fadeToggle('fast');
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
@if ($favoriteRubrics)
    <div class="edit-favorites-toggler"><span class="glyphicons glyphicons-cogwheel"></span></div>
    <ul class="sortable">
        <li>Статистика</li>
        <li>Разделы</li>
        <li>Магазин</li>
        <li>Разное</li>
    </ul>
    <div class="sortable">
    @foreach ($favoriteRubrics as $favoriteRubric)
    <div class="block-elements">
        <h2>
            {{ $favoriteRubric->name }}
            @if ( ! isset($map[$favoriteRubric->id]))
            <span class="remove" rubricId="{{ $favoriteRubric->id }}"><span><span class="halflings halflings-remove-circle"></span></span></span>
            @endif
        </h2>
        <ul class="elements">
        @foreach ($favorites as $favorite)
            @if ($favorite->rubric_id == $favoriteRubric->id)
            <li>
                <a href="{{ route('browse.element', $favorite->getElement()->getClassId()) }}">{{ $favorite->getElement()->name }}</a>
                <span class="remove small" classId="{{ $favorite->class_id }}"><span><span class="halflings halflings-remove-circle"></span></span></span>
            </li>
            @endif
        @endforeach
        </ul>
    </div>
    @endforeach
    </div>
@endif
</div>
@endsection