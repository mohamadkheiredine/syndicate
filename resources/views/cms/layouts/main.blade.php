<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <title>{{ config('app.name') }} | Admin Dashboard</title>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    {{-- Favicon --}}
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('assets-cms/images/favicon/apple-touch-icon.png') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('assets-cms/images/favicon/favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('assets-cms/images/favicon/favicon-16x16.png') }}">
    <link rel="manifest" href="{{ asset('assets-cms/images/favicon/site.webmanifest') }}">
    <link rel="mask-icon" href="{{ asset('assets-cms/images/favicon/safari-pinned-tab.svg') }}" color="#25a3de">
    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="theme-color" content="#ffffff">
    {{-- Fonts --}}
    <link rel="stylesheet" type="text/css" href="{{ asset('assets-cms/fonts/open-sans/fontstyle.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets-cms/fonts/fontawesome/css/all.min.css') }}">
    {{-- Libraries --}}
    <link rel="stylesheet" type="text/css" href="{{ asset('assets-cms/libraries/datatables/datatables.min.css') }}"/>
    <link rel="stylesheet" type="text/css" href="{{ asset('assets-cms/libraries/select2-4.0.12/select2.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets-cms/libraries/quill/quill.snow.min.css') }}">
    {{-- Pushed StyleSheet --}}
    @stack('stylesheet')
    {{-- Main CSS --}}
    <link rel="stylesheet" type="text/css" href="{{ asset('assets-cms/css/cms.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets-cms/css/custom.min.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.8.7/chosen.css" integrity="sha512-0nkKORjFgcyxv3HbE4rzFUlENUMNqic/EzDIeYCgsKa/nwqr2B91Vu/tNAu4Q0cBuG4Xe/D1f/freEci/7GDRA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    @if(isset($cms_primary_color) && $cms_primary_color)
    <style type="text/css">
        a,
        a:hover {
            color: {{ $cms_primary_color  }};
        }

        .bg-primary,
        .bg-gradient-primary,
        .bg-gradient-default {
            background: {{ $cms_primary_color  }} !important;
        }

        .text-primary{
            color: {{ $cms_primary_color  }} !important;
        }
        .btn-primary{
            border-color: {{ $cms_primary_color  }} !important;
            background-color: {{ $cms_primary_color  }} !important;
        }
        .badge-primary {
            color: {{ $cms_primary_color  }};
            background-color: rgb(37 163 222 / 20%);
        }

        .navbar-vertical .navbar-nav .nav-link.active:before{
            border-color: {{ $cms_primary_color  }} !important;
        }

        .custom-toggle input:checked+.custom-toggle-slider:before{
            background: {{ $cms_primary_color  }};
        }
        .custom-toggle input:checked+.custom-toggle-slider {
            border-color: {{ $cms_primary_color  }};
        }

        .page-item.active .page-link {
            border-color: {{ $cms_primary_color  }};
            background-color: {{ $cms_primary_color  }};
        }
        .dropdown-item.active, .dropdown-item:active {
            background-color: {{ $cms_primary_color  }};
        }
    </style>
    @endif
</head>
<body class="{{ $class ?? '' }}">
    @if(Auth::guard('admin')->check())
    <form id="logout-form" action="{{ route('admin.logout') }}" method="POST" class="d-none">
        @csrf
    </form>
    @include('cms.layouts.navbars.sidebar')
    @endif

    <div class="main-content">
        @include('cms.layouts.navbars.navbar')
        @yield('content')
    </div>

    @if(!Auth::guard('admin')->check())
    @include('cms.layouts.footers.guest')
    @endif

    {{-- Core JS Files --}}
    <script type="text/javascript" src="{{ asset('assets-cms/libraries/jQuery/jquery-3.7.1.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('assets-cms/libraries/bootstrap-4.6.2/bootstrap.bundle.min.js') }}"></script>
    {{-- Libraries --}}
    <script type="text/javascript" src="{{ asset('assets-cms/libraries/datatables/datatables.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('assets-cms/libraries/select2-4.0.12/select2.full.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('assets-cms/libraries/tinymce/tinymce.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('assets-cms/libraries/quill/quill.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('assets-cms/libraries/quill/quill-textarea.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('assets-cms/libraries/jquery-ui/jquery-ui-1.13.2.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('assets-cms/libraries/bootstrap-datepicker/bootstrap-datepicker.min.js') }}"></script>
    {{-- Pushed Script --}}
    @stack('script')
    <!-- Main JS -->
    <script type="text/javascript" src="{{ asset('assets-cms/js/cms.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('assets-cms/js/custom.min.js') }}"></script>

    {{-- Pushed Chart Script --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.8.7/chosen.jquery.min.js" integrity="sha512-rMGGF4wg1R73ehtnxXBt5mbUfN9JUJwbk21KMlnLZDJh7BkPmeovBuddZCENJddHYYMkCh9hPFnPmS9sspki8g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    @stack('chart-script')
</body>
</html>
