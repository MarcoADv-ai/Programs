<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="{{ config('app.name', 'Laravel') }} aims to bring back the good old times! We have many custom additions that will get you thrilled and busy! In our community you will find infinity of surprises that you will not be able to see in the rest of the servers and Staff committed in providing the best support. ">
    <meta name="author" content="EveeX#7433 ~ xPanel">
    <meta name="keywords" content="{{ config('app.name', 'Laravel') }}, xPanel, Ragnarok, Rathena, Ragnarok Online, Mid-Rates">
    <meta property="og:locale" content="en_US"/>
    <meta property="og:type" content="website">
    <meta property="og:title" content="{{ config('app.name', 'Laravel') }}">
    <meta property="og:description" content="{{ config('app.name', 'Laravel') }} | Mid-Rates Server 100x100x50 | Exclusives System ~ New Battlegrounds Modes ~ Stability ~ Gepard Shield">
    <meta property="og:url" content="{{env('APP_URL')}} ">
    <meta property="og:site_name" content="{{ config('app.name', 'Laravel') }}">
    <meta property="og:image" content="/img/web_share.jpg">
    <meta property="og:image:width" content="512">
    <meta property="og:image:height" content="512">
    <meta property="og:image:type" content="image/jpg">

    <meta name="viewport" content="width=device-width, minimum-scale=1.0">
    <meta name="apple-mobile-web-app-capable" content="yes">

    <!-- Favicon  ================================================== -->
    <link rel="apple-touch-icon" sizes="180x180" href="/img/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/img/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/img/favicon-16x16.png">
    <link rel="manifest" href="/site.webmanifest">
    <link rel="mask-icon" href="/safari-pinned-tab.svg" color="#5bbad5">
    <meta name="msapplication-TileColor" content="#da532c">
    <meta name="theme-color" content="#ffffff">


    <title inertia>{{ config('app.name', 'Laravel') }}</title>
    <link rel="stylesheet" href="https://unpkg.com/aos@next/dist/aos.css" />
    <link rel="stylesheet" href="{{asset('css/ck-editor.css')}}" type="text/css">
    <link rel="stylesheet" href="https://naver.github.io/egjs-flicking-plugins/release/latest/dist/flicking-plugins.css">
    <!-- Scripts -->
    @routes
    @vite('resources/js/app.js')
    @inertiaHead
</head>

<body>

    @inertia

    @env ('local')
    <script src="http://localhost:3000/browser-sync/browser-sync-client.js"></script>
    @endenv

<script src="https://unpkg.com/aos@next/dist/aos.js"></script>
<script>
    AOS.init();
</script>
</body>

</html>