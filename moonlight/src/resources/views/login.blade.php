@extends('moonlight::small')

@section('title', 'Moonlight')

@section('js')
        <script>
            $(function() {
                if ($('[name="login"]').val()) {
                    $('[name="password"]').focus();
                } else {
                    $('[name="login"]').focus();
                }
            });
        </script>
@endsection

@section('content')
        <div class="login">
            <div class="form">
                <div class="title">Moonlight</div>
                @if (isset($message))
                <div class="error">{{$message}}</div>
                @endif
                <form action="{{route('login')}}" method="POST">
                    <div class="row">
                        <input type="text" name="login" value="{{$login or ''}}" placeholder="Логин">
                    </div>
                    <div class="row">
                        <input type="password" name="password" placeholder="Пароль">
                    </div>
                    <div class="row">
                        <input type="submit" value="Войти" class="btn">
                    </div>
                </form>
            </div>
        </div>
@endsection