@extends('moonlight::base')

@section('title', 'Профиль')

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
@if (isset($errors) && sizeof($errors))
            <div class="errors">
                <ul>
    @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
    @endforeach
                </ul>
            </div>
@endif
            <div class="profile-form">
                <form action="{{route('profile')}}" autocomplete="off" method="POST">
                    <div class="row">
                        <label>Логин:</label><br>
                        <input type="text" name="login" value="{{$login}}" readonly>
                    </div>
                    <div class="row">
                        <label>Новый пароль:</label><br>
                        <input type="password" name="password">
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
                    @if (sizeof($groups))
                    <div class="row">
                        Состоит в группах:
                        @foreach ($groups as $k => $group)
                            {{ $group->name }}{{ $k < sizeof($groups) - 1 ? ', ' : '' }}
                        @endforeach
                    </div>
                    @endif
                    @if ($loggedUser->isSuperUser())
                    <div class="row">
                        <b>Суперпользователь</b>
                    </div>
                    @endif
                    <div class="row">
                        Дата создания: {{$created_at->format('d.m.Y')}} <small>{{$created_at->format('H:i:s')}}</small><br>
                        Последний логин: {{$last_login->format('d.m.Y')}} <small>{{$last_login->format('H:i:s')}}</small>
                    </div>
                    <div class="row">
                        <input type="submit" value="Сохранить" class="btn">
                    </div>
                </form>
            </div>
@endsection