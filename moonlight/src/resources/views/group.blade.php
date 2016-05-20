@extends('moonlight::base')

@section('title', $group->name)

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
            <a href="{{ route('home') }}" class="brand-logo">{{ $group->name }}</a>
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
                <form action="{{route('group', $group->id)}}" autocomplete="off" method="POST">
                    <div class="row">
                        <label>Название:</label><br>
                        <input type="text" name="title" value="{{ $group->name }}" placeholder="Название">
                    </div>
                    <div class="row">
                        <p><input type="checkbox" name="admin" id="admin" value="1"{{ $group->hasAccess('admin') ? ' checked' : '' }}> <label for="admin">Управление пользователями</label></p>
                    </div>
                    <div class="row">
                        Доступ к элементам по умолчанию:
                        <p><input type="radio" name="permission" id="permission_denied" value="denied"{{ $group->default_permission == 'deny' ? ' checked' : '' }}> <label for="permission_denied">Доступ закрыт</label></p>
                        <p><input type="radio" name="permission" id="permission_view" value="view"{{ $group->default_permission == 'view' ? ' checked' : '' }}> <label for="permission_view">Просмотр</label></p>
                        <p><input type="radio" name="permission" id="permission_update" value="update"{{ $group->default_permission == 'update' ? ' checked' : '' }}> <label for="permission_update">Изменение</label></p>
                        <p><input type="radio" name="permission" id="permission_delete" value="delete"{{ $group->default_permission == 'delete' ? ' checked' : '' }}> <label for="permission_delete">Удаление</label></p>
                    </div>
                    <div class="row">
                        @if ($group->created_at)
                        Дата создания: {{$group->created_at->format('d.m.Y')}} <small>{{$group->created_at->format('H:i:s')}}</small><br>
                        @endif
                        @if ($group->updated_at)
                        Последнее изменение: {{$group->updated_at->format('d.m.Y')}} <small>{{$group->updated_at->format('H:i:s')}}</small><br>
                        @endif
                    </div>
                    <div class="row">
                        <input type="submit" value="Сохранить" class="btn">
                    </div>
                </form>
            </div>
@endsection