@extends('moonlight::base')

@section('title', 'Профиль')

@section('css')
        <link media="all" type="text/css" rel="stylesheet" href="/packages/moonlight/touch/css/profile.css">
@endsection

@section('sidebar')
                    <li><a href="">Пользователь</a></li>
                    <li><a href="">Безналичный счет</a></li>
                    <li><a href="">Квитанция сбербанка</a></li>
                    <li><a href="">Служебный раздел</a></li>
                    <li><a href="">Агентство недвижимости</a></li>
                    <li><hr></li>
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
                        <input type="email" name="email" value="{{$email}}" placeholder="E-mail">
                    </div>
                    <div class="row">
                        Дата создания: {{$created_at->format('d.m.Y')}} <small>{{$created_at->format('H:i:s')}}</small><br>
                        Последний логин: {{$updated_at->format('d.m.Y')}} <small>{{$updated_at->format('H:i:s')}}</small>
                    </div>
                    <div class="row">
                        <input type="submit" value="Сохранить" class="btn">
                    </div>
                </form>
            </div>
@endsection