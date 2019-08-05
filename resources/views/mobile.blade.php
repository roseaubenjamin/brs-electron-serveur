<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Application BRS</title>
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">
    </head>
    <body>
        <script>
            window.APP_URL = "{{ env( 'APP_URL' ) }}"
        </script>
        <div id="application" data-base="mobile">
            <app-mobile :user="{{ Auth::user() }}"></app-mobile>
        </div>
        <script src="{{ asset('js/index.js') }}"></script>
    </body>
</html>
