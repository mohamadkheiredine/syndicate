@php
$maintenance = \App\Models\Maintenance::findOrFail(1);
$cms_setting = \App\Models\CmsSetting::findOrFail(1);
$social_medias = \App\Models\SocialMedia::GeneralScope()->get();
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
            font-family: 'Open Sans', sans-serif;
            overflow-x: hidden;
        }
        body{
            font-size: 13px;
        }
        a{
            font-size: inherit !important;
            color: inherit !important;
            text-decoration: none !important;
            box-shadow: none !important;
        }
        b{
            font-weight: 700;
        }
        .display-4{
            font-size: 40px;
        }
        .h4,h4{
            font-size: 18px;
        }
        p{
            margin: 0;
        }

        main{
            position: relative;
            text-align: center;
            height: 100vh;
            color: #FFFFFF;
            background-image: url('{{ $maintenance->image ? asset($maintenance->image) : asset('assets-web/images/landing-page/bg.svg') }}');
            background-repeat: no-repeat;
            background-size: cover;
            background-position: center;
        }
        main:before{
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0,0,0,0.5);
        }

        .logo{
            position: absolute;
            top: 50px;
            left: 50%;
            transform: translateX(-50%);
            width: 250px;
        }

        footer{
            position: absolute;
            bottom: 50px;
            left: 0;
            width: 100%;
        }

        .social-media img{
            width: 25px;
            height: 25px;
            transition: transform .3s;
        }
        .social-media img:hover{
            transform: translateY(-5px);
        }
    </style>
</head>
<body>
    <main>
        <img class="logo" src="{{ $cms_setting->logo ? $cms_setting->logo : asset('assets-web/images/logo/logo.png') }}" alt="{{ env('APP_NAME') }} Logo">

        <div class="container position-relative h-100">
            <div class="row justify-content-center align-items-center h-100">
                <div class="col-lg-10">
                    <div class="mb-5">
                        <h1 class="display-4 mb-4"><b>{{ $maintenance->title }}</b></h1>
                        <p class="h4">{!! $maintenance->text !!}</p>
                    </div>
                </div>
            </div>
        </div>

        <footer>
            @if(count($social_medias))
            <ul class="social-media list-unstyled list-inline mb-4">
                @foreach($social_medias as $social_media)
                <li class="list-inline-item mx-3"><a href="{{ $social_media->link }}" target="_blank"><img src="{{ $social_media->icon }}" alt="{{ env('APP_NAME') . ' ' . $social_media->title }}"></a></li>
                @endforeach
            </ul>
            @endif

            <span>{{ env('APP_NAME') }} &copy; {{ date('Y') }} All Rights Reserved | Powered By: <a href="{{ env('POWERED_BY_LINK') }}" target="_blank">{{ env('POWERED_BY') }}</a></span>
        </footer>
    </main>
</body>
</html>
