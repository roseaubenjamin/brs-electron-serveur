<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Application BRS</title>
        <!-- Fonts -->
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">
    </head>
    <body>
        <script>
            window.APP_URL = "{{ env( 'APP_URL' ) }}"
        </script>
        <div id="application" data-base="{{ route( 'home' , [] , false ) }}">
            @if (Auth::user()->role=="root")
                <app-admin :user="{{ Auth::user() }}"></app-admin>
            @endif
            @if (Auth::user()->role=="operateur")
                <app-operateur :user="{{ Auth::user() }}"></app-operateur>
            @endif
            @if (Auth::user()->role=="client")
                <app-brs :user="{{ Auth::user() }}"></app-brs>
            @endif
        </div>
        <script src="{{ asset('js/index.js') }}"></script>

    </body>
</html>
