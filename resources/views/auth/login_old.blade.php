<x-auth-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    @php
        $file = settingByUnique('pict_login');

        if ($file != null) {
            if (!file_exists($file)) {
                $file = 'img/login/balloon.jpg';
            } else {
                $file = $file;
            }
        } else {
            $file = 'img/login/balloon.jpg';
        }
    @endphp

    <div class="container">
        <div class="row h-100">
            <div class="col-12 col-md-10 mx-auto my-auto">
                <div class="card auth-card">
                    <div class="position-relative image-side"
                        style="background: url({{ asset($file) }}) no-repeat center; background-size: contain; margin-top: 20px; margin-bottom: 20px;">
                    </div>
                    <div class="form-side">
                        <div class="mb-5 text-center">
                            <img src="{{ asset(settingByUnique('pict_logo')) }}" alt=""
                                style="max-height: 60px;">
                        </div>

                        <h6 class="mb-4">Login</h6>
                        <form method="POST" action="{{ route('login') }}">
                            @csrf

                            <label class="form-group has-float-label mb-4">
                                <input type="email" id="email" name="email" class="form-control" />
                                <span>{{ __('Email') }}</span>
                                @if ($errors->get('email'))
                                    <small class="form-text text-muted">{{ $errors->get('email')[0] }}</small>
                                @endif
                            </label>

                            <label class="form-group has-float-label mb-4">
                                <input name="password" id="password" class="form-control" type="password"
                                    placeholder="" />
                                <span>{{ __('Password') }}</span>
                                @if ($errors->get('password'))
                                    <small class="form-text text-muted">{{ $errors->get('password')[0] }}</small>
                                @endif
                            </label>

                            <div class="d-flex justify-content-between align-items-center">
                                <a href=""></a>

                                <button class="btn btn-primary btn-lg btn-shadow"
                                    type="submit">{{ __('Log in') }}</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

</x-auth-layout>
