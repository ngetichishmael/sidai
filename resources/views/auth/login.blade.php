<!DOCTYPE html>
<html class="loading" lang="en" data-textdirection="ltr">
@section('title', 'Login')
<!-- BEGIN: Head-->
@include('partials._head')
<!-- END: Head-->
<style>
    body {
        background-color: #200b0b;
        display: flex;
        align-items: center;
        justify-content: center;
        height: 100vh;
        margin: 0;
    }
    
    .login-form {
        background-color: #991d1d00;
        padding: 20px;
        border-radius: 5px;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.3);
    }
    
    .login-form input[type="text"],
    .login-form input[type="password"],
    .login-form button {
        display: block;
        width: 100%;
        padding: 10px;
        margin-bottom: 10px;
        border: 1px solid #ccc;
        border-radius: 3px;
        font-size: 16px;
    }
    
    .login-form button {
        background-color: #4caf50;
        color: #fff;
        cursor: pointer;
    }
</style>
<!-- BEGIN: Body-->
<body>
    <div class="login-form">
        <h4 class="mb-1 card-title">Welcome to Sidai! 👋</h4>
        <p class="mb-2 card-text">Please sign-in to your account</p>
        <form class="mt-2 auth-login-form" action="{{ route('login') }}" method="POST">
            @csrf
            <div class="mb-1">
                <label for="login-email" class="form-label">Email</label>
                <input type="text" class="form-control" id="login-email" name="email"
                    placeholder="sidai@sokoflow.com" aria-describedby="login-email" tabindex="1"
                    autofocus />
                @if ($errors->has('email'))
                    <span class="help-block">
                        <strong class="text-danger">{{ $errors->first('email') }}</strong>
                    </span>
                @endif
            </div>
            <div class="mb-1">
                <div class="d-flex justify-content-left">
                    <label class="form-label" for="login-password">Password</label>
                    <a href="{{ route('password.request') }}">
                        <small>Forgot Password?</small>
                    </a>
                </div>
                <div class="input-group input-group-merge form-password-toggle">
                    <input type="password" class="form-control form-control-merge" id="login-password"
                        name="password" tabindex="2"
                        placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;"
                        aria-describedby="login-password" />
                    <span class="cursor-pointer input-group-text"><i data-feather="eye"></i></span>
                    @if ($errors->has('password'))
                        <span class="help-block">
                            <strong class="text-danger">{{ $errors->first('password') }}</strong>
                        </span>
                    @endif
                </div>
            </div>
            <div class="mb-1">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="remember-me" tabindex="3" />
                    <label class="form-check-label" for="remember-me"> Remember Me </label>
                </div>
            </div>
            <button type="submit" class="btn btn-primary w-100" tabindex="4">Sign in</button>
        </form>
    </div>
</body>
<!-- END: Body-->

</html>
