<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, height=device-height, user-scalable=no, initial-scale=1.0">
        <meta name="msapplication-tap-highlight" content="no">
        <title>@yield('title')</title>
        <link rel="shortcut icon" href="/packages/moonlight/touch/img/moonlight16.png" type="image/x-icon">
        <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
        <link media="all" type="text/css" rel="stylesheet" href="/packages/moonlight/touch/css/bootstrap-additions.min.css">
        <link media="all" type="text/css" rel="stylesheet" href="/packages/moonlight/touch/css/glyphicons.css">
        <link media="all" type="text/css" rel="stylesheet" href="/packages/moonlight/touch/css/glyphicons-halflings.css">
        <link media="all" type="text/css" rel="stylesheet" href="/packages/moonlight/touch/css/glyphicons-bootstrap.css">
        <link media="all" type="text/css" rel="stylesheet" href="/packages/moonlight/touch/css/default.css">
        <link media="all" type="text/css" rel="stylesheet" href="/packages/moonlight/touch/css/common.css">
@section('css')
@show
        <script src="http://code.jquery.com/jquery-2.2.0.min.js"></script>
        <script src="/packages/moonlight/touch/js/jquery.form.min.js"></script>
        <script>
            $(function() {
                $('#hamburger').click(function() {
                    $('.sidebar').fadeToggle('fast');

                    return false;
              });
            });
        </script>
@section('js')
@show
    </head>
    <body>
@section('nav')
        <nav>
            <ul>
                <li><a href="menu.html" id="hamburger"><span class="glyphicons glyphicons-menu-hamburger"></span></a></li>
            </ul>
            <a href="{{ route('home') }}" class="brand-logo">@yield('title')</a>
            <ul class="right">
                <li><a href="search.html"><span class="glyphicons glyphicons-search"></span></a></li>
            </ul>
        </nav>
@show
        <div class="sidebar">
            <div class="sidebar-container">
                <ul class="menu">
@section('sidebar')
@show
                    <li><a href="browse.html">Корень сайта</a></li>
                    <li><a href="trash.html">Корзина</a></li>
                    <li><hr></li>
                    <li><a href="{{route('users')}}">Пользователи</a></li>
                    <li><a href="log.html">Журнал</a></li>
                    <li><hr></li>
                    <li><a href="{{route('profile')}}">{{$loggedUser->first_name}} {{$loggedUser->last_name}}</a></li>
                    <li><a href="{{route('logout')}}">Выход</a></li>
                </ul>
            </div>
        </div>
        <div class="block-ui">
            <div></div>
        </div>
@section('alert')
@show
        <div class="main">
@section('main')
@show
        </div>
    </body>
</html>