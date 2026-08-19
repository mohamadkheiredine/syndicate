<!DOCTYPE html>
<html>
<body style="font-family: Arial, sans-serif; color: #333;">
    <p>Dear {{ $firstName }},</p>

    <p>Congratulations! You have successfully registered with Syndicate.</p>

    <p><a href="{{ $activationUrl }}">{{ $activationUrl }}</a></p>

    <p>Thanks and Regards.<br>Syndicate TEAM.</p>
</body>
</html>
