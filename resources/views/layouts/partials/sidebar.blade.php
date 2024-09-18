<div class="menu">
    <div class="main-menu">
        <div class="scroll">
            <ul class="list-unstyled">
                <li @if (Request::is('/') || Request::is('dashboard')) class="active" @endif>
                    <a href="{{ route('dashboard') }}">
                        <i class="iconsminds-home"></i>
                        <span>{{ __('Beranda') }}</span>
                    </a>
                </li>

                @if (Gate::check('Manage Activity Program'))
                    <li @if (Request::is('activity-program*')) class="active" @endif>
                        <a href="{{ route('activity-program.index') }}">
                            <i class="iconsminds-box-full"></i> {{ __('Program Kerja') }}
                        </a>
                    </li>
                @endif

                @if (Gate::check('Manage Activity Report'))
                    <li @if (Request::is('activity-report*') || Request::is('description-of-activities*')) class="active" @endif>
                        <a href="{{ route('activity-report.index') }}" class="text-center">
                            <i class="iconsminds-check"></i> Realisasi <br> Fisik dan Keuangan
                        </a>
                    </li>
                @endif
                @if (Gate::check('Manage Development'))
                    <li @if (Request::is('development*') || Request::is('development*')) class="active" @endif>
                        <a href="{{ route('development.index') }}" class="text-center">
                            <i class="iconsminds-financial"></i> Pembangunan <br> Fisik & Non Fisik
                        </a>
                    </li>
                @endif

                @if (Gate::check('Manage Report'))
                    <li @if (Request::is('report*')) class="active" @endif>
                        {{-- <a href="{{ route('report.index') }}"> --}}
                        <a href="#report">
                            <i class="iconsminds-line-chart-1"></i> {{ __('Laporan') }}
                        </a>
                    </li>
                @endif

                @if (Gate::check('Manage Statistics'))
                    <li @if (Request::is('statistics*')) class="active" @endif>
                        <a href="{{ route('statistics.index') }}">
                            <i class="iconsminds-bar-chart-4"></i> {{ __('Statistik') }}
                        </a>
                    </li>
                @endif

                @if (Gate::check('Manage Announcement'))
                    <li @if (Request::is('announcement*')) class="active" @endif>
                        <a href="{{ route('announcement.index') }}">
                            <i class="iconsminds-bell"></i> {{ __('Pengumuman') }}
                        </a>
                    </li>
                @endif

                @if (Gate::check('Manage Institute') && !Gate::check('Manage Any Institute'))
                    <li @if (Request::is('institute*')) class="active" @endif>
                        <a href="{{ route('institute.index') }}">
                            <i class="iconsminds-tag-3"></i> <span class="d-inline-block">{{ __('Setting') }}</span>
                        </a>
                    </li>
                @endif

                @if (Gate::check('Manage User') ||
                        Gate::check('Manage Role') ||
                        Gate::check('Manage Permission') ||
                        Gate::check('Manage Any Institute'))
                    <li @if (Request::is('users*') || Request::is('roles*') || Request::is('permission*') || Request::is('institute*')) class="active" @endif>
                        <a href="#users">
                            <i class="iconsminds-male-female"></i> {{ __('Pengguna') }}
                        </a>
                    </li>
                @endif

                {{-- @if (Gate::check('Manage User')) --}}
                <li @if (Request::is('profile*')) class="active" @endif>
                    <a href="{{ route('profile.edit') }}">
                        <i class="iconsminds-type-pass"></i> {{ __('Profil') }}
                    </a>
                </li>
                {{-- @endif --}}

                @if (Gate::check('Manage Setting'))
                    <li @if (Request::is('settings*')) class="active" @endif>
                        <a href="{{ route('settings.index') }}">
                            <i class="iconsminds-gears"></i> {{ __('Pengaturan') }}
                        </a>
                    </li>
                @endif

            </ul>
        </div>
    </div>

    <div class="sub-menu">
        <div class="scroll">
            <ul class="list-unstyled" data-link="users">

                @can('Manage User')
                    <li @if (Request::is('users*')) class="active" @endif>
                        <a href="{{ route('users.index') }}">
                            <i class="iconsminds-add-user"></i> <span class="d-inline-block">{{ __('Pengguna') }}</span>
                        </a>
                    </li>
                @endcan

                @can('Manage Institute')
                    <li @if (Request::is('institute*')) class="active" @endif>
                        <a href="{{ route('institute.index') }}">
                            <i class="iconsminds-tag-3"></i> <span
                                class="d-inline-block">{{ __('Perangkat Daerah') }}</span>
                        </a>
                    </li>
                @endcan

                @can('Manage Role')
                    <li @if (Request::is('roles*')) class="active" @endif>
                        <a href="{{ route('roles.index') }}">
                            <i class="iconsminds-security-settings"></i> <span
                                class="d-inline-block">{{ __('Akses') }}</span>
                        </a>
                    </li>
                @endcan

                @can('Manage Permission')
                    <li @if (Request::is('permissions*')) class="active" @endif>
                        <a href="{{ route('permissions.index') }}">
                            <i class="iconsminds-check"></i> <span class="d-inline-block">{{ __('Perizinan') }}</span>
                        </a>
                    </li>
                @endcan
            </ul>

            <ul class="list-unstyled" data-link="report">
                @can('Manage Report')
                    <li @if (Request::is('report-realisasi*')) class="active" @endif>
                        <a href="{{ route('report.index') }}">
                            <i class="iconsminds-line-chart-1"></i> <span
                                class="d-inline-block">{{ __('Laporan Realisasi') }}</span>
                        </a>
                    </li>
                    <li @if (Request::is('report-pembangunan*')) class="active" @endif>
                        <a href="{{ route('report.pembangunan.index') }}">
                            <i class="iconsminds-bar-chart-4"></i> <span
                                class="d-inline-block">{{ __('Laporan Pembangunan') }}</span>
                        </a>
                    </li>
                @endcan
            </ul>
        </div>
    </div>
</div>
