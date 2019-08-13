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
        
        <script>
        
            document.csrf_token = "{{csrf_token()}}" ; 
            document.trans = JSON.parse("{{ json_encode(array_merge(trans('app'))) }}".replace(/&quot;/g,'"'));  ; 

            window.htmlentities = {

                encode : function(str) {
                    var buf = [];
                    
                    for (var i=str.length-1;i>=0;i--) {
                        buf.unshift(['&#', str[i].charCodeAt(), ';'].join(''));
                    }
                    
                    return buf.join('');
                },

                decode : function(str) {
                    return str.replace(/&#(\d+);/g, function(match, dec) {
                        return String.fromCharCode(dec);
                    });
                }

            };
            console.log( document.trans );
            window.APP_URL = "{{ env( 'APP_URL' ) }}"

        </script>

    </head>
    <body>
        @yield('content')
        <script src="{{ asset('js/index.js') }}"></script>
    </body>
</html>
