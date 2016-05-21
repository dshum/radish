@extends('moonlight::base')

@section('title', $user->login)

@section('js')
<script>
    $(function() {
        $('#password-toggler').click(function() {
            $('#password-container').slideToggle('fast');
        });
        
        $('[name="email"]').val('{{ $user->email }}');
        $('[name="password"]').val('');
        
        $('form').submit(function() {
            $('[name]').removeClass('invalid');
            $.blockUI();

            $(this).ajaxSubmit({
                url: this.action,
                dataType: 'json',
                success: function(data) {
                    if (data.error) {
                        $.alert(data.error);
                    } else if (data.errors) {
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
                    }

                    $.unblockUI();
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
    <left><a href="{{ route('users') }}"><span class="halflings halflings-menu-left"></span></a></left>
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
        <form action="{{ route('user', $user->id )}}" autocomplete="off" method="POST">
            <div class="row">
                <label>Логин:</label><br>
                <input type="text" name="login" value="{{ $user->login }}" placeholder="Логин">
            </div>
            <div class="row">
                <label>Имя:</label><br>
                <input type="text" name="first_name" value="{{ $user->first_name }}" placeholder="Имя">
            </div>
            <div class="row">
                <label>Фамилия:</label><br>
                <input type="text" name="last_name" value="{{ $user->last_name }}" placeholder="Фамилия">
            </div>
            <div class="row">
                <label>E-mail:</label><br>
                <input type="text" name="email" value="{{ $user->email }}" placeholder="E-mail">
            </div>
            <div class="row">
                Группы:<br>
                @foreach ($groups as $group)
                <p>
                    <input type="checkbox" name="groups[]" id="group_{{ $group->id }}" value="{{ $group->id }}"{{ isset($userGroups[$group->id]) ? ' checked' : '' }}>
                    <label for="group_{{ $group->id }}">{{ $group->name }}</label>
                </p>
                @endforeach
            </div>
            <div class="row">
                <span id="password-toggler" class="dashed hand">Сменить пароль</span>
            </div>
            <div id="password-container" class="dnone">
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
                @if ($loggedUser->isSuperUser())
                <b>Суперпользователь</b><br>
                @endif
                @if ($user->created_at)
                Дата создания: {{$user->created_at->format('d.m.Y')}} <small>{{$user->created_at->format('H:i:s')}}</small><br>
                @endif
                @if ($user->last_login)
                Последний логин: {{$user->last_login->format('d.m.Y')}} <small>{{$user->last_login->format('H:i:s')}}</small><br>
                @endif
            </div>
            <div class="row">
                <input type="submit" value="Сохранить" class="btn">
            </div>
        </form>
    </div>
</div>
@endsection