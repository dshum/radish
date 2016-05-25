@extends('moonlight::base')

@section('title', $group ? $group->name : 'Новая группа')

@section('css')
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
                    $.unblockUI();
                    
                    if (data.error) {
                        $.alert(data.error);
                    } else if (data.errors) {
                        var message = '';

                        for (var field in data.errors) {
                            $('[name="'+field+'"]').addClass('invalid');

                            message += data.errors[field]+'<br />';
                        }

                        $.alert(message);  
                    } else if (data.added) {
                        document.location.href = "{{ route('users') }}";
                    }
                },
                error: function() {
                    $.unblockUI();
                }
            });

            return false;
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
        <form action="{{ $group ? route('group.save', $group->id) : route('group.add') }}" autocomplete="off" method="POST">
            <div class="row">
                <label>Название:</label><br>
                <input type="text" name="name" value="{{ $group ? $group->name : '' }}" placeholder="Название">
            </div>
            <div class="row">
                <p><input type="checkbox" name="admin" id="admin" value="1"{{ $group && $group->hasAccess('admin') ? ' checked' : '' }}> <label for="admin">Управление пользователями</label></p>
            </div>
            <div class="row">
                Доступ к элементам по умолчанию:
                <p><input type="radio" name="default_permission" id="permission_deny" value="deny"{{ $group && $group->default_permission == 'deny' ? ' checked' : '' }}> <label for="permission_deny">Доступ закрыт</label></p>
                <p><input type="radio" name="default_permission" id="permission_view" value="view"{{ $group && $group->default_permission == 'view' ? ' checked' : '' }}> <label for="permission_view">Просмотр</label></p>
                <p><input type="radio" name="default_permission" id="permission_update" value="update"{{ $group && $group->default_permission == 'update' ? ' checked' : '' }}> <label for="permission_update">Изменение</label></p>
                <p><input type="radio" name="default_permission" id="permission_delete" value="delete"{{ $group && $group->default_permission == 'delete' ? ' checked' : '' }}> <label for="permission_delete">Удаление</label></p>
            </div>
            <div class="row">
                @if ($group && $group->created_at)
                Дата создания: {{ $group->created_at->format('d.m.Y') }} <small>{{ $group->created_at->format('H:i:s') }}</small><br>
                @endif
                @if ($group && $group->updated_at)
                Последнее изменение: {{ $group->updated_at->format('d.m.Y') }} <small>{{ $group->updated_at->format('H:i:s') }}</small><br>
                @endif
            </div>
            <div class="row">
                <input type="submit" value="Сохранить" class="btn">
            </div>
        </form>
    </div>
</div>
@endsection