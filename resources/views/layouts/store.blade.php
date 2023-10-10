<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta http-equiv="x-ua-compatible" content="ie=edge">

  <meta name="csrf-token" content="{{ csrf_token() }}">

  <title>{{ config('app.name', 'Laravel+Vue Take-Home Test') }}</title>

  <link rel="stylesheet" href="{{ mix('/css/app.css') }}">
</head>
<body>

<div class="wrapper" id="app">
    <header>
        <nav class="navbar navbar-expand-lg navbar-dark d-flex justify-content-between">
            <a class="navbar-brand font-weight-bolder" href="#">{{ config('app.name', 'Laravel+Vue Take-Home Test') }}</a>
            <div>
                <shopping-cart-button :user="{{ Auth::user() }}"></shopping-cart-button>
            </div>
        </nav>
    </header>

    <router-view></router-view>
</div>

@auth
    <script>
        window.user = @json(auth()->user())
    </script>
@endauth
<script src="{{ mix('/js/app.js') }}"></script>
</body>
</html>
