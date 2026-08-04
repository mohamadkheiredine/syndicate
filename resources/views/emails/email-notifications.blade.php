<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional //EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:v="urn:schemas-microsoft-com:vml" lang="en">
<head>
    <title>{{ env('APP_NAME') }}</title>
    <meta property="og:title" content="Email template">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" hs-webfonts="true" href="https://fonts.googleapis.com/css?family=Lato|Lato:i,b,bi">
    <style type="text/css">
        #email{
            margin: auto;
            width: 100%;
            max-width: 600px;
            background-color: white;
        }
        .image{
            width: 100%;
        }
        button{
            font-size: 14px;
            width: 220px;
            color: #000000 !important;
            background-color: #F1F3F4;
            border: 0;
            border-radius: 6px;
            padding: .8rem 1.3rem;
            letter-spacing: 0.5px;
            font-weight: bold;
            margin-top: 35px
        }
    </style>
</head>

<body bgcolor="#F1F3F4" style="width: 100%; margin: 25px 0; padding: 0; font-family: Lato, sans-serif; font-size: 18px; word-break: break-word">
    <div id="email">

        <table role="presentation" width="100%" border="0" cellpadding="0" cellspacing="10px" style="padding: 30px;">
            <tr>
                <td align="center" style="color: white;">
                    <img src="{{ $cms_logo ? $cms_logo : asset('assets-cms/images/logo.png') }}" width="150px" align="middle" alt="{{ env('APP_NAME') }} Logo">
                </td>
            </tr>
        </table>

        @if(isset($image) && $image)
        <table role="presentation" width="100%">
            <tr>
                <td align="center">
                    <img class="image" src="{{ asset($image) }}" width="400px" align="middle">
                </td>
            </tr>
        </table>
        @endif

        <table role="presentation" width="100%" border="0" cellpadding="0" cellspacing="10px" style="padding: 30px;">
            <tr>
                <td>
                    <h2 style="margin-top: 0;">{{ $subject }}</h2>
                    <div>
                        {!! $msg !!}
                    </div>
                    <p>{{ env('APP_NAME') }} Team!</p>
                    @if(isset($link) && $link)
                    <div style="text-align: center;">
                        <a href="{{ $link }}">
                            <button>Go to Link</button>
                        </a>
                    </div>
                    @endif
                </td>
            </tr>
        </table>

    </div>
</body>
</html>
