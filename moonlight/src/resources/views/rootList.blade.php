@extends('moonlight::base')

@section('title', 'Корень сайта')

@section('css')
<link media="all" type="text/css" rel="stylesheet" href="/packages/moonlight/touch/css/browse.css">
@endsection

@section('js')
<script>
    $(function() {
        $('[type="text"]').addClear({
          right: 10,
          paddingRight: "25px"
        });

        $('#form-toggler').click(function() {
          $('#form-container').slideToggle('fast');
        });
        
        $('ul.menu li[item]').hide().each(function() {
            var li = $(this);
            var item = $(this).attr('item');

            $.getJSON(
                '{{ route('elements.count') }}',
                {item: item},
                function(data) {
                   if (data && data.count) {
                        var span = $('<span class="total">'+data.count+'</span>');

                        li.append(span).show();
                    } else {
                        li.addClass('grey');
                    }
                }
            );
        });
        
        $('form').submit(function() {
            let url = $(this).attr('action');
            
            $('#form-container').slideUp('fast', function() {
                $.blockUI();

                $.getJSON(url, {
                    item: '{{ $currentItem->getNameId() }}'
                }, function(data) {
                    $.unblockUI();

                    if (data.html) {
                        $('.list-container').html(data.html);
                    }
                }).fail(function() {
                    $.unblockUI();

                    $.alertDefaultError();
                });
            });
            
            event.preventDefault();
        });
        
        $('body').on('click', '.next', function() {
            let next = $(this);
            let page = next.attr('page');
            let url = $('form').attr('action');

            next.addClass('waiting');
            $.blockUI();

            $.getJSON(url, {
                item: '{{ $currentItem->getNameId() }}',
                page: page
            }, function(data) {
                $.unblockUI();
                
                next.remove();

                if (data.html) {
                    $('.list-container').append(data.html);
                }
            }).fail(function() {
                $.unblockUI();
                
                $.alertDefaultError();
            });
        });
        
        $.getJSON($('form').attr('action'), {
            item: '{{ $currentItem->getNameId() }}'
        }, function(data) {
            if (data.html) {
                $('.list-container').html(data.html);
            }
        }).fail(function() {
            $.alertDefaultError();
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
<div class="bottom-context-menu">
    <div class="button copy"><span class="halflings halflings-duplicate"></span><br>Копировать</div>
    <div class="button move"><span class="halflings halflings-arrow-right"></span><br>Переместить</div>
    <div class="button delete"><span class="halflings halflings-trash"></span><br>Удалить</div>
</div>
<div class="sidebar">
    <div class="sidebar-container">
        <ul class="menu">
            @if ($items)
            @foreach ($items as $item)
            <li item="{{ $item->getNameId() }}"><span><a href="{{ route('browse.root.list', $item->getNameId()) }}">{{ $item->getTitle() }}</a></span></li>
            @endforeach
            <li><hr></li>
            @endif
            <li><a href="{{ route('search') }}">Поиск</a></li>
            <li><a href="{{ route('search.item', $currentItem->getNameId()) }}">{{ $currentItem->getTitle() }}</a></li>
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
    <h2><a href="{{ route('browse') }}">Корень сайта</a></h2>
    <h3>{{ $currentItem->getTitle() }}<a href="new-agency.html" class="addnew"><span class="halflings halflings-plus-sign"></span></a></h3>
    <form action="{{ route('elements.list') }}" autocomplete="off">
        <div class="browse-form">
            <div class="right"><span id="form-toggler" class="icon"><span class="glyphicons glyphicons-adjust-alt"></span></span></div>
            <input type="text" name="search" placeholder="Поиск">
        </div>
        <div id="form-container" class="dnone browse-form-params">
            <div class="row">
                <div class="block">
                    <div class="label textfield" property="method"><span class="glyphicons glyphicons-pencil"></span><span>Метод</span></div>
                    <div class="dnone" container="property" property="method"><input type="text" name="method" placeholder="Метод"></div>
                </div>
                <div class="block">
                    <div class="label textarea" property="comments"><span class="glyphicons glyphicons-comments"></span><span>Описание</span></div>
                    <div class="dnone" container="property" property="comments"><input type="text" name="comments" placeholder="Описание"></div>
                </div>
                <div class="block">
                    <div class="label date" property="date"><span class="glyphicons glyphicons-calendar"></span><span>Дата</span></div>
                    <div class="dnone" container="property" property="date">
                        <input type="text" name="date-from" value="" class="date" placeholder="ГГГГ-ММ-ДД" readonly> &#151;
                        <input type="text" name="date-to" value="" class="date" placeholder="ГГГГ-ММ-ДД" readonly>
                        <span class="reset">&#215;</span>
                    </div>
                </div>
            </div>
            <div class="row">
                <input type="submit" value="Найти" class="btn">
            </div>
        </div>
    </form>
    <div class="list-container"></div>
</div>
@endsection