<x-auth-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    @php
        $file = settingByUnique('pict_login');
        $auth_logo = settingByUnique('pict_auth_logo');

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

    <section class="custom-container">
        <div class="container">
            <div class="row justify-content-center" style="border: 0px #000 solid;">
                <div class="col-md-6 text-center hide-on-portrait">
                    <img src="{{ asset($file) }}" alt="" width="300">

                    <p class="text-white mt-2" style="font-weight: bolder; font-size: 26px; text-transform: uppercase;">
                        Sekretariat Daerah
                        <br>
                        Bagian Administrasi Pembangunan
                    </p>
                </div>
                <div class="col-md-6 padding-auth">
                    <div class="login-wrap p-0 login-center">

                        @if ($auth_logo != null)
                            <div class="text-center mb-5">
                                <img src="{{ asset($auth_logo) }}" alt="" width="250">
                            </div>
                        @endif

                        <form method="POST" action="{{ route('login') }}">
                            @csrf

                            <div class="form-group">
                                <input type="text" name="email" class="form-control" placeholder="Email"
                                    style="border-color: #f6c90d;">
                                @if ($errors->get('email'))
                                    <small class="form-text text-white">{{ $errors->get('email')[0] }}</small>
                                @endif
                            </div>
                            <div class="form-group">
                                <input id="password-field" name="password" type="password" class="form-control"
                                    placeholder="Password" style="border-color: #f6c90d;">
                                <span toggle="#password-field"
                                    class="fa fa-fw fa-eye field-icon toggle-password"></span>
                                @if ($errors->get('password'))
                                    <small class="form-text text-white">{{ $errors->get('password')[0] }}</small>
                                @endif
                            </div>
                            <div class="form-group">
                                <button type="submit" class="form-control btn btn-primary submit px-3"
                                    style="background: #016436 !important; color: #fff !important; font-weight: bold; border-color: #f6c90d !important;">Login</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

</x-auth-layout>
