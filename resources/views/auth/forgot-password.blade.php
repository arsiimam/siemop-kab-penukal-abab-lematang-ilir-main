<x-auth-layout>

    <div class="container">
        <div class="row h-100">
            <div class="col-12 col-md-10 mx-auto my-auto">
                <div class="card auth-card">
                    <div class="position-relative image-side ">
                        <p class=" text-white h2">MAGIC IS IN THE DETAILS</p>
                        <p class="white mb-0">
                            {{ __('Forgot your password? No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.') }}
                            <br>If you are not a member, please
                            <a href="#" class="white">register</a>.
                        </p>
                    </div>
                    <div class="form-side">
                        <div class="mb-5">
                            <a href="/">
                                <x-application-logo class="w-10 h-10 fill-current text-gray-500" />
                            </a>
                        </div>
                        <h6 class="mb-4">Forgot Password</h6>
                        <form method="POST" action="{{ route('password.email') }}">
                            @csrf

                            <label class="form-group has-float-label mb-4">
                                <input type="email" id="email" name="email" class="form-control" />
                                <span>{{ __('Email') }}</span>
                                @if ($errors->get('email'))
                                    <small class="form-text text-muted">{{ $errors->get('email')[0] }}</small>                         
                                @endif
                            </label>

                            <div class="d-flex justify-content-between align-items-center">
                                @if (Route::has('login'))
                                    <a href="{{ route('login') }}">{{ __('Log in') }}</a>
                                @endif

                                <button class="btn btn-primary btn-lg btn-shadow" type="submit">{{ __('Email Password Reset Link') }}</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

</x-auth-layout>