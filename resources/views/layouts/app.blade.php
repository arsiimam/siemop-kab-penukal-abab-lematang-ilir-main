<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>{{ ENV('APP_NAME') }}</title>
    <link rel="icon" type="image/x-icon" href="{{ asset(settingByUnique('pict_favicon')) }}">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    @include('layouts.assets.css')

    {{-- addon css --}}
    @yield('css_addon')

</head>

<body id="app-container" class="menu-sub-hidden show-spinner">

    @include('layouts.partials.navbar')

    @include('layouts.partials.sidebar')

    <main style="margin-left: 150px;
    margin-top: 120px;
    margin-right: 30px;
    margin-bottom: 20px;">
        <div class="container-fluid">

            @yield('form-open')

            <div class="row">
                <div class="col-12">
                    @yield('header')

                    @yield('action-button')

                    <nav class="breadcrumb-container d-none d-sm-block d-lg-inline-block" aria-label="breadcrumb">
                        <ol class="breadcrumb pt-0">
                            <li class="breadcrumb-item">
                                <a href="{{ route('dashboard') }}">{{ __('Beranda') }}</a>
                            </li>

                            @yield('breadcrumb')

                        </ol>
                    </nav>
                    <div class="separator mb-5"></div>
                </div>
            </div>

            @yield('content')

            @yield('form-close')

            {{-- modal lg --}}
            <div class="modal fade bd-example-modal-lg" id="empModal" role="dialog" aria-labelledby="empModal"
                aria-hidden="true" style="overflow:hidden;" data-backdrop="static" data-keyboard="false">
                <div class="modal-dialog modal-dialog-scrollable modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header pt-3 pb-3 bg-primary">
                            <h5 class="modal-title modal-title-load">{{ __('Title') }}</h5>
                            <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>

                        <div class="modal-body p-0">
                            <div class="modal-body-load">

                            </div>
                        </div>

                    </div>
                </div>
            </div>

            {{-- modal xl --}}
            <div class="modal fade bd-example-modal-lg" id="empModal-xl" role="dialog" aria-labelledby="empModal-xl"
                aria-hidden="true" style="overflow:hidden;" data-backdrop="static" data-keyboard="false">
                <div class="modal-dialog modal-dialog-scrollable modal-xl" role="document">
                    <div class="modal-content">
                        <div class="modal-header pt-3 pb-3 bg-primary">
                            <h5 class="modal-title modal-title-load">{{ __('Title') }}</h5>
                            <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>

                        <div class="modal-body p-0">
                            <div class="modal-body-load">

                            </div>
                        </div>

                    </div>
                </div>
            </div>

        </div>
    </main>

    @include('layouts.partials.footer')

    @include('layouts.assets.js')

    <script>
        $(document).ready(function() {
            $('.example').DataTable();
        });
    </script>

    {{-- addon javascript --}}
    @stack('scripts')

    @if (Session::has('success'))
        <script>
            $(document).ready(function() {
                $.toast({
                    heading: 'Success',
                    text: '{{ Session::get('success') }}',
                    icon: 'success',
                    position: 'top-right',
                    hideAfter: 3000,
                    showHideTransition: 'slide'
                })
            });
        </script>
    @endif

    @if (Session::has('error'))
        <script>
            $(document).ready(function() {
                $.toast({
                    heading: 'Error',
                    text: '{{ Session::get('error') }}',
                    icon: 'error',
                    position: 'top-right',
                    hideAfter: 3000,
                    showHideTransition: 'slide'
                })
            });
        </script>
    @endif

    @if (count($errors) > 0)
        <script>
            var mystring = '{{ json_encode($errors->all()) }}';
            var messagesError = mystring.replace(/&quot;/g, '"');
            $(document).ready(function() {
                $.toast({
                    heading: 'Required',
                    text: JSON.parse(messagesError),
                    icon: 'info',
                    position: 'top-right',
                    hideAfter: 5000,
                    showHideTransition: 'slide'
                })
            });
        </script>
    @endif

</body>

</html>
