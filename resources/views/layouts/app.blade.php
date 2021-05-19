<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">


    <title>{{ config('app.name') }}</title>

    <link rel="shortcut icon" href="/assets/fav.png" type="image/x-icon">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-wEmeIV1mKuiNpC+IOBjI7aAzPcEZeedi5yW5f2yOq55WWLwNGmvvx4Um1vskeMj0" crossorigin="anonymous">

    <style>
        html, body {
            height: 100%;
        }
    </style>

    <script src="{{ mix('dist/js/app.js') }}" defer></script>

    <style>
        .popup-img-winner {
            background-image: url("/assets/back.png");
            background-color: #fff;
            background-repeat: no-repeat;
            background-size: contain;
            background-position: center;
            position: fixed;
            width: 100%;
            height: 100vh;
            overflow: hidden;
            top: 0;
            left: 0;
            z-index: 9999;
        }
    </style>
</head>
<body>
<div id="app" class="d-flex h-100 justify-content-center align-items-center"
     style="
             background-repeat: no-repeat;
     @if(\Illuminate\Support\Facades\Route::currentRouteName() !== 'registration')background-image: url('/assets/back.png');@endif
     -moz-background-size: 100%;
     -webkit-background-size:
     100%;-o-background-size:
     100%;background-size: 100%;
"
>
    @yield('content')
</div>
</body>
</html>