@extends('dashboard')
@section('content')
<main class="signup-form">
    <div class="cotainer">
        <div class="row justify-content-center">
            <div class="col-md-4">
                <div class="card">
                    <h3 class="card-header text-center">Register Member</h3>
                    <div class="card-body">
                        <form action="{{ route('register.member') }}" method="POST">
                            @csrf
                            <input type="hidden" name="type" value="member" />

                            <div class="form-group mb-3">
                                <input type="email" placeholder="Email address" id="email_address" class="form-control"
                                    name="email" required autofocus>
                                @if ($errors->has('email'))
                                <span class="text-danger">{{ $errors->first('email') }}</span>
                                @endif
                            </div>

                            <div class="form-group mb-3">
                                <input type="text" placeholder="Gender male or female..." id="gender" class="form-control" name="gender"
                                    required autofocus>
                                @if ($errors->has('gender'))
                                <span class="text-danger">{{ $errors->first('gender') }}</span>
                                @endif
                            </div>

                            <div class="form-group mb-3">
                                <input type="text" placeholder="First Name" id="fname" class="form-control" name="fname"
                                    required autofocus>
                                @if ($errors->has('fname'))
                                <span class="text-danger">{{ $errors->first('fname') }}</span>
                                @endif
                            </div>

                            <div class="form-group mb-3">
                                <input type="text" placeholder="surname" id="surname" class="form-control" name="surname"
                                    required autofocus>
                                @if ($errors->has('surname'))
                                <span class="text-danger">{{ $errors->first('surname') }}</span>
                                @endif
                            </div>

                            <div class="form-group mb-3">
                                <input type="password" placeholder="Password" id="password" class="form-control"
                                    name="password" required>
                                @if ($errors->has('password'))
                                <span class="text-danger">{{ $errors->first('password') }}</span>
                                @endif
                            </div>

                            <div class="form-group mb-3">
                                <input type="password" placeholder="Password Confirmation" id="password_confirmation" class="form-control"
                                    name="password_confirmation" required>
                                @if ($errors->has('passwordConfirmation'))
                                <span class="text-danger">{{ $errors->first('passwordConfirmation') }}</span>
                                @endif
                            </div>

                            <div class="form-group mb-3">
                                <div class="checkbox">
                                    <label><input type="checkbox" name="remember"> Remember Me</label>
                                </div>
                            </div>

                            <div class="form-group mb-3">
                                <div class="checkbox">
                                    <a href="{{route('forget.password.get')}}">Forget Password</a>
                                </div>
                            </div>

                            <div class="d-grid mx-auto">
                                <button type="submit" class="btn btn-dark btn-block">Sign up</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
@endsection
