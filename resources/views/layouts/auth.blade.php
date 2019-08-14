<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="shortcut icon" href="{{ asset('img/favicon.ico') }}">
        <title>Application BRS</title>
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">
        <link rel="stylesheet" href="/css/bootstrap.min.css">
		<link rel="stylesheet" href="/css/login.css">
    </head>
    <body>
        @yield('content')
        <script src="/js/jquery-3.3.1.slim.min.js"></script>
		<script src="/js/bootstrap.min.js"></script>
    </body>
</html>
