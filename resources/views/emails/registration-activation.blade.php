<!DOCTYPE html>
<html>
<body style="font-family: Arial, sans-serif; color: #333;">
    <p>Hello <strong>{{ $firstName }} {{ $lastName }}</strong>,</p>

    <p>
        Thank you for registering with Syndicate application!
        <p>Please click the below link to activate your account so you can start using our app.</p>
        <p><strong><a style="text-decoration: none;" href="{{ $activationUrl }}">Click here</a></strong></p>
    </p>

    <p>
        Sincerely,<br>
        Syndicate Team
    </p>
</body>
</html>
