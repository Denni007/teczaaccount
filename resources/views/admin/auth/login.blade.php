@extends('admin.app-admin')

@section('content')
<div class="login-box">
    <div class="login-logo">
      <a href="{{ url('/') }}"><b>{{ env('APP_NAME','Tecza-Accounting')}}</b></a>
    </div>
    <!-- /.login-logo -->
    <div class="card">
        <div class="card-body login-card-body">
        <p class="login-box-msg"></p>
        @if (session('status'))
            <div class="alert alert-success">
                {{ session('status') }}
            </div>
        @endif
        @if (session('err_status'))
            <div class="alert alert-danger">
                {{ session('err_status') }}
            </div>
        @endif
        <form class="login-form" method="POST" action="{{ route('admin.login') }}" autocomplete="off">
            @csrf
            <div class="input-group mb-3">
                <input id="email" type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ old('email') }}" placeholder="Email" required autofocus>
                <div class="input-group-append">
                    <div class="input-group-text">
                        <span class="fas fa-envelope"></span>
                    </div>
                </div>
                @if ($errors->has('email'))
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $errors->first('email') }}</strong>
                    </span>
                @endif
            </div>
            <div class="input-group mb-3">
                <input id="password" type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password"  placeholder="Password" required>
                <div class="input-group-append">
                    <div class="input-group-text">
                        <span class="fas fa-lock"></span>
                    </div>
                </div>
                @if ($errors->has('password'))
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $errors->first('password') }}</strong>
                    </span>
                @endif
            </div>
            <div class="row">
                <div class="col-4">
                    <!-- <div class="icheck-primary">
                        <input type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }} class="form-input-styled">
                        <label for="remember"> Remember Me </label>
                    </div> -->
                </div>
                <!-- /.col -->
                <div class="col-4">
                    <button type="submit" class="btn btn-primary btn-block">Sign In</button>
                </div>
                <!-- /.col -->
            </div>
        </form>
        @if (Route::has('password.request'))
            <p class="mb-1">
                <a href="{{ route('admin.password.request') }}" class="ml-auto">I forgot my password</a>
            </p>
        @endif
      </div>
      <!-- /.login-card-body -->
    </div>
  </div>
@endsection
