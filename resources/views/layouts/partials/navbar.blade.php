<nav class="navbar fixed-top">
    <div class="d-flex align-items-center navbar-left">
        <a href="#" class="menu-button d-none d-md-block">
            <svg class="main" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 9 17">
                <rect x="0.48" y="0.5" width="7" height="1" />
                <rect x="0.48" y="7.5" width="7" height="1" />
                <rect x="0.48" y="15.5" width="7" height="1" />
            </svg>
            <svg class="sub" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 18 17">
                <rect x="1.56" y="0.5" width="16" height="1" />
                <rect x="1.56" y="7.5" width="16" height="1" />
                <rect x="1.56" y="15.5" width="16" height="1" />
            </svg>
        </a>

        <a href="#" class="menu-button-mobile d-xs-block d-sm-block d-md-none">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 26 17">
                <rect x="0.5" y="0.5" width="25" height="1" />
                <rect x="0.5" y="7.5" width="25" height="1" />
                <rect x="0.5" y="15.5" width="25" height="1" />
            </svg>
        </a>

        <span class="name ml-2" style="text-transform: uppercase;"><i class="iconsminds-male-2"></i>
            {{ Auth::user()->institute->name }}</span>

    </div>

    <div
        style="
        position: absolute;
        width: 180px;
        height: 50px;
        display: flex;
        justify-content: center;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);">
        <img src="{{ asset(settingByUnique('pict_logo')) }}" alt="" style="height: auto;">
    </div>

    {{-- <div class="navbar-right">
        <div class="header-icons d-inline-block align-middle">
            <div class="d-none d-md-inline-block align-text-bottom mr-3">
                <div class="custom-switch custom-switch-primary-inverse custom-switch-small pl-1" data-toggle="tooltip"
                    data-placement="left" title="Dark Mode">
                    <input class="custom-switch-input" id="switchDark" type="checkbox" checked>
                    <label class="custom-switch-btn" for="switchDark"></label>
                </div>
            </div>
        </div>

        <div class="user d-inline-block">
            <button class="btn btn-empty p-0" type="button" data-toggle="dropdown" aria-haspopup="true"
                aria-expanded="false">
                <span class="name">{{ Auth::user()->name }}</span>
                <span>
                    @php
                        $avatar = Auth::user()->avatar;
                    @endphp
                    @if ($avatar != null and file_exists($avatar))
                        <img alt="Profile Picture" src="{{ asset($avatar) }}"
                            style="height: 40px; object-fit: cover;" />
                    @else
                        <img alt="Profile Picture" src="{{ asset('img/profiles/l-1.jpg') }}" />
                    @endif

                </span>
            </button>

            <div class="dropdown-menu dropdown-menu-right mt-3">
                <a class="dropdown-item" href="{{ route('profile.edit') }}">{{ __('Profil') }}</a>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <a class="dropdown-item" href="{{ route('logout') }}"
                        onclick="event.preventDefault();
                                this.closest('form').submit();">{{ __('Log Out') }}</a>
                </form>
            </div>
        </div>
    </div> --}}

    <div class="navbar-right">
        <div class="header-icons d-inline-block align-middle">
            <div class="d-none d-md-inline-block align-text-bottom mr-3">
                <div class="custom-switch custom-switch-primary-inverse custom-switch-small pl-1" data-toggle="tooltip"
                    data-placement="left" title="" data-original-title="Dark Mode">
                    <input class="custom-switch-input" id="switchDark" type="checkbox">
                    <label class="custom-switch-btn" for="switchDark"></label>
                </div>
            </div>

            <div class="position-relative d-inline-block">

                <a href="{{ route('announcement.index') }}?action=read" class="header-icon btn btn-empty"
                    id="notificationButton">
                    <i class="simple-icon-bell"></i>
                    <span class="count" id="count_announcement">{{ get_announcement() }}</span>
                </a>

            </div>

            <button class="header-icon btn btn-empty d-none d-sm-inline-block" type="button" id="fullScreenButton">
                <i class="simple-icon-size-fullscreen"></i>
                <i class="simple-icon-size-actual"></i>
            </button>

        </div>

        <div class="user d-inline-block">
            <button class="btn btn-empty p-0" type="button" data-toggle="dropdown" aria-haspopup="true"
                aria-expanded="false">
                <span class="name">{{ Auth::user()->name }}</span>
                <span>
                    @php
                        $avatar = Auth::user()->avatar;
                    @endphp
                    @if ($avatar != null and file_exists($avatar))
                        <img alt="Profile Picture" src="{{ asset($avatar) }}"
                            style="height: 40px; object-fit: cover;" />
                    @else
                        <img alt="Profile Picture" src="{{ asset('img/profiles/l-1.jpg') }}" />
                    @endif

                </span>
            </button>

            <div class="dropdown-menu dropdown-menu-right mt-3">
                <a class="dropdown-item" href="{{ route('profile.edit') }}">{{ __('Profil') }}</a>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <a class="dropdown-item" href="{{ route('logout') }}"
                        onclick="event.preventDefault();
                                this.closest('form').submit();">{{ __('Log Out') }}</a>
                </form>
            </div>
        </div>
    </div>
</nav>
