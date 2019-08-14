@extends('layouts.auth')

@section('content')

<div class="wrapper fadeInDown">
    <div id="formContent">
        <!-- Login Form --> 
        <div class="card-header">{{ __('Register') }}</div>
        
        <form method="POST" action="{{ route('register') }}">
            @csrf
            <div class="form-group">
                <input required style="height: calc(3.5rem + 2px);" type="text" id="name" class="@error('name') is-invalid @enderror fadeIn second form-control" name="name" value="{{ old('name') }}" placeholder="{{ __('Name') }}" autofocus >
                @error('email')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
            <div class="form-group">
                <input required style="height: calc(3.5rem + 2px);" type="text" id="login" class="@error('email') is-invalid @enderror fadeIn second form-control" name="email" value="{{ old('email') }}" placeholder="{{ __('E-Mail Address') }}" autocomplete="email" autofocus >
                @error('email')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
            <div class="form-group">
                <input required style="height: calc(3.5rem + 2px);" type="password" id="password" class="fadeIn third form-control @error('password') is-invalid @enderror " name="password" placeholder="{{ __('Password') }}" autocomplete="current-password" />
                @error('password')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
            <div class="form-group">
                <input required style="height: calc(3.5rem + 2px);" type="password" id="password-confirm" class="fadeIn third form-control @error('password') is-invalid @enderror " name="password_confirmation" placeholder="{{ __('Confirm Password') }}" autocomplete="current-password" />
                @error('password')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div> 

            <input style="display: block; width: 85%; margin-left: auto; margin-right: auto;" type="submit" class="fadeIn fourth" value="{{ __('Login') }}">
        </form>
        <!-- Remind Passowrd -->
        <div id="formFooter">
            <a class="underlineHover" href="/login">{{ __('Login') }}</a>
        </div>
    </div>
</div>
@endsection