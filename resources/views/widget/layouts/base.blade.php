<!DOCTYPE html>
    <html lang="">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
        @section('title')
        <title>{{ config('app.name') }}</title>
        @show

        @yield('meta')

        @section('style')
        <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.6/css/bootstrap.min.css">
        <style> body { overflow-x: hidden; overflow-y: hidden; cursor: default; } </style>
        @show
    </head>
    <body>
        @yield('content')

        @section('script')
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>
        @show

        @include('partials.ga')
    </body>
</html>