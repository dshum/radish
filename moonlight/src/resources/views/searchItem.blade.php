@extends('moonlight::base')

@section('title', 'Поиск')

@section('css')
<link media="all" type="text/css" rel="stylesheet" href="/packages/moonlight/touch/css/browse.css">
@endsection

@section('js')
<script>
$(function() {
    var checked = [];

    $('#form-toggler').click(function() {
      $('#form-container').toggle();
    });

    $('.label[property]').click(function() {
      var property = $(this).attr('property');
      var container = $('[container="property"][property="'+property+'"]');

      if (container.hasClass('dnone')) {
          container.removeClass('dnone');
          container.find(':text').removeAttr('disabled');
      } else {
          container.addClass('dnone');
          container.find(':text').attr('disabled', 'disabled');
      }
    });

    $('.check').click(function() {
      var elementId = $(this).attr('elementid');

      $('ul.elements li[elementId="'+elementId+'"]').toggleClass('checked');

      if ($(this).prop('checked') == true) {
        $(this).prop('checked', false);
        checked--;
      } else {
        $(this).prop('checked', true);
        checked++;
      }

      if (checked == 1) {
        $('#left').html('<span>Выделено</span>');
        $('#right').html('<span>Отмена</span>');
        $('.bottom-context-menu').fadeIn('fast');
      }

      if (checked) {
        $('#center').html(checked);
      } else {
        $('#left').html('<a href="menu.html" id="hamburger"><span class="glyphicons glyphicons-menu-hamburger"></span></a>');
        $('#center').html('<a href="index.html">Поиск</a>');
        $('#right').html('<a href="search.html"><span class="glyphicons glyphicons-search"></span></a>');
        $('.bottom-context-menu').fadeOut('fast');
      }
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