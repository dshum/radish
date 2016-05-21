<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, height=device-height, user-scalable=no, initial-scale=1.0">
<meta name="msapplication-tap-highlight" content="no">
<title>@yield('title')</title>
<link rel="shortcut icon" href="/packages/moonlight/touch/img/moonlight16.png" type="image/x-icon">
<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
<link media="all" type="text/css" rel="stylesheet" href="/packages/moonlight/touch/css/bootstrap-additions.min.css">
<link media="all" type="text/css" rel="stylesheet" href="/packages/moonlight/touch/css/glyphicons.css">
<link media="all" type="text/css" rel="stylesheet" href="/packages/moonlight/touch/css/glyphicons-halflings.css">
<link media="all" type="text/css" rel="stylesheet" href="/packages/moonlight/touch/css/glyphicons-bootstrap.css">
<link media="all" type="text/css" rel="stylesheet" href="/packages/moonlight/touch/css/loader.css">
<link media="all" type="text/css" rel="stylesheet" href="/packages/moonlight/touch/css/default.css">
<link media="all" type="text/css" rel="stylesheet" href="/packages/moonlight/touch/css/common.css">
<link media="all" type="text/css" rel="stylesheet" href="/packages/moonlight/touch/js/calendarview/jquery.calendar.css">
@section('css')
@show
<script src="http://code.jquery.com/jquery-2.2.0.min.js"></script>
<script src="/packages/moonlight/touch/js/jquery.form.min.js"></script>
<script src="/packages/moonlight/touch/js/calendarview/jquery.calendar.js"></script>
<script src="/packages/moonlight/touch/js/addclear.min.js"></script>
<script src="/packages/moonlight/touch/js/common.js"></script>
@section('js')
@show
</head>
<body>
<div class="block-ui">
    <div class="block-ui-container">
        <div class="wrapper">
            <div class="cssload-loader"></div>
        </div>
    </div>
</div>
<div class="alert">
    <div class="container">
        <div class="hide">&#215;</div>
        <div class="content"></div>
    </div>
</div>
@section('body')
@show
</body>
</html>