<!DOCTYPE html>
<html class="loading" lang="en" data-textdirection="ltr">
@section('title', 'Login')
<!-- BEGIN: Head-->
@include('partials._head')
<!-- END: Head-->
<!-- BEGIN: Body-->

<body style="background-color: #01010e">
    <div class="container-scroller">
        <div class="container-fluid page-body-wrapper full-page-wrapper">
          <div class="content-wrapper d-flex align-items-center auth px-0">
            <div class="row w-100 mx-0">
              <div class="col-lg-4 mx-auto">
                <div class="card" style="background-image: url({{ asset('app-assets/images/background1.jpg') }}">
                <div class="auth-form-light text-left py-5 px-4 px-sm-5">
                    
                    <h4 class="mb-1 card-title" style="color: white">Welcome to Sidai! 👋</h4>
                    <p class="mb-2 card-text" style="color: white">Please sign-in to your account</p>
                    <form class="mt-2 auth-login-form" action="{{ route('login') }}" method="POST">
                        @csrf
                        <div class="mb-1">
                            <label for="login-email" class="form-label" style="color: white">Email</label>
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
                            <div class="d-flex justify-content-between">
                                <label class="form-label" for="login-password" style="color: white">Password</label>
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
                </div>
              </div>
            </div>
          </div>
          <!-- content-wrapper ends -->
        </div>
        <!-- page-body-wrapper ends -->
      </div>
    
    <!-- END: Content-->
    @include('partials._javascripts')
</body>
<!-- END: Body-->

</html>
