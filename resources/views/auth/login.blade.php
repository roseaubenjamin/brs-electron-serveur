@extends('layouts.auth')

@section('content')

<div class="wrapper fadeInDown">
    <div id="formContent">
        <!-- Login Form --> 
        <div class="card-header">{{ __('Login') }}</div>
        
        <form method="POST" action="{{ route('login') }}">
            @csrf
            <div class="form-group">
                <input style="height: calc(3.5rem + 2px);" type="text" id="login" class="@error('email') is-invalid @enderror fadeIn second form-control" name="email" value="{{ old('email') }}" placeholder="{{ __('E-Mail Address') }}" autocomplete="email" autofocus >
                @error('email')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
            <div class="form-group">
                <input style="height: calc(3.5rem + 2px);" type="password" id="password" class="fadeIn third form-control @error('password') is-invalid @enderror " name="password" placeholder="{{ __('Password') }}" autocomplete="current-password" />
                @error('password')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
            <div class="form-group fadeIn">
                <label style=" text-align: left;" class="container-input fadeIn third">
                    <input
                        type="checkbox" name="remember" id="remember"
                        {{ old('remember') ? 'checked' : '' }}/>
                    <span class="checkmark"></span>
                    <span>{{ __('Remember Me') }}</span>
                </label>
            </div>
            <input style="display: block; width: 85%; margin-left: auto; margin-right: auto;" type="submit" class="fadeIn fourth" value="{{ __('Login') }}">
        </form>
        <!-- Remind Passowrd -->
        <div id="formFooter">
            <a class="underlineHover" href="/register">{{ __('Register') }}</a>|
            <a class="underlineHover" href="{{ route('password.request') }}">
                {{ __('Forgot Your Password?') }}
            </a>
        </div>
    </div>
</div>
@endsection
