@extends('layouts.app')

@section('content')
    @if (session('state'))
        <script>
            window.success_create = true ;      
        </script>
    @endif
    <div id="application" data-base="read">
        <app-mobile :user="{{ Auth::user() }}"></app-mobile>
    </div>
@endsection