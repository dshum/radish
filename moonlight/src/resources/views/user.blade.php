@extends('moonlight::base')

@section('title', $user->login)

@section('css')
        <link media="all" type="text/css" rel="stylesheet" href="/packages/moonlight/touch/css/profile.css">
@endsection

@section('js')
        <script>
            $(function() {
                $('form').submit(function() {
                    $('[name]').removeClass('invalid');
                    $.blockUI();
                    
                    $(this).ajaxSubmit({
                        url: this.action,
                        dataType: 'json',
                        success: function(data) {
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

@section('nav')
        <nav>
            <ul>
                <li><a href="{{ route('users') }}"><span class="halflings halflings-menu-left"></span></a></li>
            </ul>
            <a href="{{ route('home') }}" class="brand-logo">{{ $user->login }}</a>
            <ul class="right">
                <li><a href="search.html"><span class="glyphicons glyphicons-search"></span></a></li>
            </ul>
        </nav>
@endsection

@section('sidebar')
                    <li><a href="">Пользователь</a></li>
                    <li><a href="">Безналичный счет</a></li>
                    <li><a href="">Квитанция сбербанка</a></li>
                    <li><a href="">Служебный раздел</a></li>
                    <li><a href="">Агентство недвижимости</a></li>
                    <li><hr></li>
@endsection

@section('alert')
        <div class="alert">
            <div class="alert-container">
                <div class="hide">&#215;</div>
                <div class="content"></div>
            </div>
        </div>
@endsection

@section('main')
            <div class="profile-form">
                <form action="{{route('user', $user->id)}}" autocomplete="off" method="POST">
                    <div class="row">
                        <label>Логин:</label><br>
                        <input type="text" name="login" value="{{$user->login}}" placeholder="Логин">
                    </div>
                    <div class="row">
                        <label>Новый пароль:</label><br>
                        <input type="password" name="password">
                    </div>
                    <div class="row">
                        <label>Имя:</label><br>
                        <input type="text" name="first_name" value="{{$user->first_name}}" placeholder="Имя">
                    </div>
                    <div class="row">
                        <label>Фамилия:</label><br>
                        <input type="text" name="last_name" value="{{$user->last_name}}" placeholder="Фамилия">
                    </div>
                    <div class="row">
                        <label>E-mail:</label><br>
                        <input type="text" name="email" value="{{$user->email}}" placeholder="E-mail">
                    </div>
                    <div class="row">
                        @if ($loggedUser->isSuperUser())
                        <b>Суперпользователь</b><br>
                        @endif
                        @if (sizeof($userGroups))
                            Состоит в группах:
                            @foreach ($userGroups as $k => $group)
                            <a href="">{{ $group->name }}</a>{{ $k < sizeof($userGroups) - 1 ? ', ' : '' }}
                            @endforeach
                            <br>
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
@endsection