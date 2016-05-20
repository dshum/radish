@extends('moonlight::base')

@section('title', 'Профиль')

@section('css')
@endsection

@section('js')
<script>
    $(function() {
        $('#password-toggler').click(function() {
            $('#password-container').slideToggle('fast');
        });

        $('form').submit(function() {
            $('[name]').removeClass('invalid');
            $.blockUI();

            $(this).ajaxSubmit({
                url: this.action,
                dataType: 'json',
                success: function(data) {
                    $.unblockUI();
                    
                    if (data.errors) {
                        let message = '';

                        for (let field in data.errors) {
                            $('[name="'+field+'"]').addClass('invalid');

                            message += data.errors[field]+'<br />';
                        }

                        $.alert(message);  
                    } else if (data.user) {
                        for (let field in data.user) {
                            $('[name="'+field+'"]').val(data.user[field]).blur();
                        }

                        $('#password-container').slideUp('fast');
                    }
                },
                error: function() {
                    $.unblockUI();
                }
            });

            event.preventDefault();
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
    <div class="form">
        <form action="{{route('profile')}}" autocomplete="off" method="POST">
            <div class="row">
                Логин: {{$login}}<br>
                @if ($loggedUser->isSuperUser())
                <b>Суперпользователь</b><br>
                @endif
                @if (sizeof($groups))
                Состоит в группах:
                    @foreach ($groups as $k => $group)
                    <a href="">{{ $group->name }}</a>{{ $k < sizeof($groups) - 1 ? ', ' : '' }}
                    @endforeach
                <br>
                @endif
                Дата создания: {{$created_at->format('d.m.Y')}} <small>{{$created_at->format('H:i:s')}}</small><br>
                Последний логин: {{$last_login->format('d.m.Y')}} <small>{{$last_login->format('H:i:s')}}</small>
            </div>
            <div class="row">
                <label>Имя:</label><br>
                <input type="text" name="first_name" value="{{$first_name}}" placeholder="Имя">
            </div>
            <div class="row">
                <label>Фамилия:</label><br>
                <input type="text" name="last_name" value="{{$last_name}}" placeholder="Фамилия">
            </div>
            <div class="row">
                <label>E-mail:</label><br>
                <input type="text" name="email" value="{{$email}}" placeholder="E-mail">
            </div>
            <div class="row">
                <span id="password-toggler" class="dashed hand">Сменить пароль</span>
            </div>
            <div id="password-container" class="dnone">
                <div class="row">
                    <label>Текущий пароль:</label><br>
                    <input type="password" name="password_old">
                </div>
                <div class="row">
                    <label>Новый пароль:</label><br>
                    <input type="password" name="password">
                </div>
                <div class="row">
                    <label>Подтверждение:</label><br>
                    <input type="password" name="password_confirmation">
                </div>
            </div>
            <div class="row">
                <input type="submit" value="Сохранить" class="btn">
            </div>
        </form>
    </div>
</div>
@endsection