<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta property="og:title" content="g0v台灣空汙地圖資料來源管理">
    <meta property="og:description" content="Taiwan Air Pollution Map">
    <meta property="og:type" content="website">
    <meta property="og:url" content="http://datasource.airmap.asper.tw/">
    <meta property="og:image" content="https://i.imgur.com/AuINEkK.png">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>
    <link rel='shortcut icon' type='image/x-icon' href='https://i.imgur.com/Gro4juQ.png' />

    <!-- Styles -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.3/css/bootstrap.min.css" integrity="sha384-Zug+QiDoJOrZ5t4lssLdxGhVrurbmBWopoEl+M6BdEfwnCJZtKxi1KgxUyJq13dy" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/open-iconic/1.1.1/font/css/open-iconic-bootstrap.min.css" />
    <style>
        html, body { height: 100%; }
        #app {
            height: 100%;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }
        footer {
            color: #888;
            border-top: 1px dotted #e3e3e3;
            text-align: center;
            font-family: monospace;
            padding: .5em;
        }
    </style>
    @yield('style')
</head>
<body>
    <div id="app">
        @yield('navbar')

        @yield('content')

        <footer class="mt-auto">
            Asper &copy; 2018
        </footer>
    </div>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.2.1.min.js" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.11.0/umd/popper.min.js" integrity="sha384-b/U6ypiBEHpOf/4+1nzFpr53nxSS+GLCkfwBdFNTxtclqqenISfwAzpKaMNFNmj4" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.3/js/bootstrap.min.js" integrity="sha384-a5N7Y/aK3qNeh15eJKGWxsqtnX/wWdSZSKp+81YjTmS15nvnvxKHuzaWwXHDli+4" crossorigin="anonymous"></script>
    @stack('scripts')
</body>
</html>
