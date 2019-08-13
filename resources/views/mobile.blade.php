@extends('layouts.app')

@section('content')
    <script>
        window.APP_URL = "{{ env( 'APP_URL' ) }}"
    </script>
    <div id="application" data-base="mobile">
        <app-mobile :user="{{ Auth::user() }}"></app-mobile>
    </div>
@endsection
