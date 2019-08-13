@extends('layouts.app')

@section('content')
    @if (isset($tokenauth))
        <script>
            chrome.runtime.sendMessage( "{{ env( 'CHROME_KEY' ) }}" , JSON.parse("{{ json_encode($tokenauth) }}".replace(/&quot;/g,'"')) );
        </script>
    @endif
    @if (isset($newteam))
        <script>
            window.newteam = true ; 
        </script>
    @endif
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
@endsection
