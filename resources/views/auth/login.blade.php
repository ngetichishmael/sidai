<!DOCTYPE html>
<html class="loading" lang="en" data-textdirection="ltr">
@section('title', 'Login')
<!-- BEGIN: Head-->
@include('partials._head')
<!-- END: Head-->
<!-- BEGIN: Body-->

<body>
    <div class="dashboard-landing">
        <div class="left-side">
            
            <img src="{{ asset('app-assets/images/loginpage.svg') }}" alt="" class="img-fluid">
            
        </div>
        <div class="right-side">
            <div class="login-fields">
                <!-- Login v1 -->
                <div>
                    
                    <div class="card-body">
                            <img src={{ asset('app-assets/images/sidaiweblogo.png') }} class="logo" alt="Sidai" width="100px" height="80px" />
                        
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
                                <a href="{{ route('password.request') }}">
                                    <small>Forgot Password?</small>
                                </a>
                            </div>
                            <button type="submit" class="btn btn-primary w-100" tabindex="4">Sign in</button>
                        </form>
                    </div>
                </div>
                <!-- /Login v1 -->
            </div>
        </div>
    </div>
    <!-- END: Content-->
    @include('partials._javascripts')
</body>
<!-- END: Body-->

</html>

