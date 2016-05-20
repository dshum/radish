@extends('moonlight::base')

@section('title', 'Пользователи')

@section('css')
        <link media="all" type="text/css" rel="stylesheet" href="/packages/moonlight/touch/css/users.css">
@endsection

@section('js')
        <script>
            $(function() {
                $('ul.users li .remove[url]:not(.disabled)').click(function() {
                    var url = $(this).attr('url');
                    var name = $(this).attr('name');
                    var html = 'Удалить группу &laquo;'+name+'&raquo;?';
                    
                    $('#confirm .remove').attr('url', url);

                    $('#confirm .content').html(html);
                    $('#confirm').fadeIn('fast');
                });
                
                $('#confirm .cancel').click(function() {
                   $('#confirm').fadeOut('fast');
                });
                
                $('#message .hide').click(function() {
                    $('#message').fadeOut('fast');
                    $('.block-ui').fadeOut('fast');
                });
                
                 $('#confirm .container').click(function(e) {
                    return false;
                });
                
                $('#message').click(function() {
                    $('#message').fadeOut('fast');
                    $('.block-ui').fadeOut('fast');
                });

                $('#confirm .remove').click(function() {
                    let url = $(this).attr('url');
                    
                    if ( ! url) return false;
                    
                    $('#confirm').fadeOut('fast');
                    $('.block-ui').fadeIn('fast');
  
                    $.post(
                        url,
                        {},
                        function(data) {
                            if (data.error) {
                                $('#message .content').html(data.error);
                                $('#message').fadeIn('fast');
                            } else if (data.group) {
                                $('li[group="'+data.group+'"]').slideUp('fast');
                                $('.block-ui').fadeOut('fast');
                            }
                        }
                    );
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
            <div id="confirm" class="confirm">
                <div class="container">
                    <div class="content"></div>
                    <div class="buttons">
                        <input type="button" value="Удалить" class="btn danger remove">
                        <input type="button" value="Отмена" class="btn cancel">
                    </div>
                </div>
            </div>
            <div id="message" class="alert">
                <div class="container">
                    <div class="hide">&#215;</div>
                    <div class="content"></div>
                </div>
            </div>
@endsection

@section('main')
            @if (sizeof($groups))
            <h2>Группы<a href="{{ route('group.create') }}" class="addnew"><span class="halflings halflings-plus-sign"></span></a></h2>
            <ul class="users">
                @foreach ($groups as $group)
                <li group="{{ $group->id }}">
                    <div class="remove" url="{{ route('group.delete', $group->id) }}" name="{{ $group->name }}"><span class="halflings halflings-remove-circle"></span></div>
                    <div class="date">{{ $group->created_at->format('d.m.Y') }}<br><span class="time">{{ $group->created_at->format('H:i:s') }}</span></div>
                    <a href="{{ route('group', $group->id) }}">{{ $group->name }}</a><br>
                    <small>{{ $group->getPermissionTitle() }}</small><br>
                    @if ($group->hasAccess('admin'))
                    <small>Управление пользователями</small><br>
                    @endif
                    <a href="group-item-permissions.html" class="perms">Доступ по умолчанию</a>
                </li>
                @endforeach
            </ul>
            @endif
            @if (sizeof($users))
            <h2>Пользователи<a href="{{ route('user.create') }}" class="addnew"><span class="halflings halflings-plus-sign"></span></a></h2>
            <ul class="users">
                @foreach ($users as $user)
                <li>
                    @if ($user->isSuperUser() || $user->id == $loggedUser->id)
                    <div class="remove disabled"><span class="halflings halflings-remove-circle"></span></div>
                    <div class="date">{{ $user->created_at->format('d.m.Y') }}<br><span class="time">{{ $user->created_at->format('H:i:s') }}</span></div>
                    {{ $user->login }}
                    @else
                    <div class="remove" user-id="{{ $user->id }}" user-name="{{ $user->first_name }} {{ $user->last_name }}"><span class="halflings halflings-remove-circle"></span></div>
                    <div class="date">{{ $user->created_at->format('d.m.Y') }}<br><span class="time">{{ $user->created_at->format('H:i:s') }}</span></div>
                    <a href="{{ route('user', $user->id) }}">{{ $user->login }}</a>
                    @endif
                    {{ $user->first_name }} {{ $user->last_name }}<br>
                    <span>{{ $user->email }}</span><br>
                    @if ($user->isSuperUser())
                    <small>Суперпользователь</small><br>
                    @endif
                    @if (isset($userGroups[$user->id]))
                        @foreach ($userGroups[$user->id] as $group)
                        <small>{{ $group->name }}</small>
                        @endforeach
                    @endif
                </li>
                 @endforeach
            </ul>
            @endif
@endsection