<!DOCTYPE html>
<html>
<head>
	<title>@if(View::hasSection('page_title'))@yield('page_title')@endif</title>
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
    {{-- Main CSS --}}
	<style type="text/css">
		html,
		body {
			margin: 0;
			overflow: hidden;
			position: relative;
		}
		body {
			font-family: 'Open Sans', sans-serif;
			color: #000000;
			background-color: #ffffff;
			height: 100vh;
			display: flex;
			align-items: center;
			flex-direction: column;
			justify-content: center;
			text-align: center;
		}
		a{
			color: inherit !important;
			box-shadow: none !important;
		}
		h1 {
			font-size: 10em;
			margin: 0;
			line-height: 1;
		}
		p {
			font-size: 1.2em;
		}
		.container{
			padding: 0 15px;
		}
		.text-muted {
			color: #C4C4C4;
		}

		@media(max-width: 575px) {
			h1 {
				font-size: 8em;
			}
			p {
				font-size: 1em;
			}
		}
	</style>
</head>
<body>
	@yield('content')
</body>
</html>