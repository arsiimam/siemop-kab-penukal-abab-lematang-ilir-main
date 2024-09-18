<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <title>Authentication</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no"">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="icon" type="image/x-icon" href="{{ settingByUnique('pict_favicon') }}">

    <link href="https://fonts.googleapis.com/css?family=Lato:300,400,700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="{{ asset('theme/authv2/css/style.css') }}">

    <style>
        body {
            font-family: Arial, Helvetica, sans-serif;
        }

        .custom-container {
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        @media (orientation:landscape) {
            .padding-auth {
                padding: 3rem;
            }
        }

        @media (orientation:portrait) {
            .hide-on-portrait {
                display: none;
            }

            .padding-auth {
                padding: 15px;
            }
        }

        .login-center {
            height: 100%;
            display: flex;
            justify-content: center;
            align-items: center;
            align-items: stretch;
            flex-direction: column;
        }
    </style>
</head>

@php
    $file = settingByUnique('pict_banner');

    if ($file != null) {
        if (!file_exists($file)) {
            $file = 'theme/authv2/images/bg2.jpg';
        } else {
            $file = $file;
        }
    } else {
        $file = 'theme/authv2/images/bg2.jpg';
    }
@endphp

<body class="img js-fullheight" style="background-image: url({{ asset($file) }});">

    <main>
        {{ $slot }}
    </main>

    <script src="{{ asset('theme/authv2/js/jquery.min.js') }}"></script>
    <script src="{{ asset('theme/authv2/js/popper.js') }}"></script>
    <script src="{{ asset('theme/authv2/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('theme/authv2/js/main.js') }}"></script>
</body>

</html>
