@php
$settings = \App\Models\Setting::findOrFail(1);
@endphp

<!DOCTYPE html>
<html>
<head>
    <title>{{ env('APP_NAME') }}</title>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    {{-- Favicon --}}
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('assets-web/images/favicon/apple-touch-icon.png') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('assets-web/images/favicon/favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('assets-web/images/favicon/favicon-16x16.png') }}">
    <link rel="manifest" href="{{ asset('assets-web/images/favicon/site.webmanifest') }}">
    <link rel="mask-icon" href="{{ asset('assets-web/images/favicon/safari-pinned-tab.svg') }}" color="#25a3de">
    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="theme-color" content="#ffffff">
    {{-- Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@300;700&display=swap">
    {{-- Core CSS Files --}}
    @if(App::environment('local'))
    <link rel="stylesheet" type="text/css" href="{{ asset('assets-web/libraries/bootstrap-5.1.3/bootstrap.min.css') }}"/>
    @else
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    @endif
    {{-- Main CSS --}}
    <style type="text/css">
        html,
        body{
            overflow-x: hidden;
        }
        body{
            font-family: 'Open Sans', sans-serif;
            height: 100vh;
            color: #000000;
            background-image: url('{{ asset('assets-web/images/landing-page/bg.svg') }}');
            background-size: cover;
            background-repeat: no-repeat;
        }
        *{
            outline: none !important;
        }
        a{
            color: inherit !important;
            text-decoration: none !important;
        }
        b{
            font-weight: 700;
        }
        img{
            width: 100%;
            height: 100%;
        }

        .logo{
            width: 100%;
            max-width: 225px;
        }

        .phone-wrapper{
            position: relative;
            padding-top: 45%;
        }
        .phone-wrapper img{
            position: absolute;
            top: 0;
            left: 50%;
            transform: translateX(-50%);
            width: auto;
        }

        .title{
            font-size: 18px;
        }
        .title em{
            font-size: 22px;
        }

        @media (max-width: 991px) {

            .phone-wrapper{
                padding-top: 60%;
            }

        }

        @media (max-width: 767px) {

            body{
                font-size: 13px;
            }

            .logo{
                max-width: 150px;
            }

            .title{
                font-size: 13px;
            }
            .title em{
                font-size: 16px;
            }

            .phone-wrapper{
                padding-top: 100%;
            }
        }
    </style>
</head>
<body>
    <div class="d-flex align-items-center h-100">
        <div class="container">
            <img class="logo d-block mx-auto mb-3" src="{{ asset('assets-web/images/logo/logo.png') }}" alt="{{ env('APP_NAME') }} Logo">

            <div class="phone-wrapper">
                <img src="{{ asset('assets-web/images/landing-page/phone.png') }}" alt="{{ env('APP_NAME') }} Screenshot">
            </div>

            <p class="title text-center mb-2">DOWNLOAD <b><em>{{ env('APP_NAME') }}</em></b></p>

            <div class="row justify-content-center">
                <div class="col-10 col-sm-9 col-md-6 col-lg-4">
                    <div class="row gx-2">
                        <div class="col">
                            <a href="{{ $settings->apple_app }}" target="_blank">
                                <img src="{{ asset('assets-web/images/stores/apple_store.svg') }}">
                            </a>
                        </div>
                        <div class="col">
                            <a href="{{ $settings->android_app }}" target="_blank">
                                <img src="{{ asset('assets-web/images/stores/google_store.svg') }}">
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <footer class="text-center mt-5">
                <a href="#"><b>Privacy policy</b></a>
                <span>|</span>
                Powered by <a href="{{ env('POWERED_BY_LINK') }}" target="_blank"><b>{{ env('POWERED_BY') }}</b></a>
            </footer>
        </div>
    </div>
</body>
</html>
