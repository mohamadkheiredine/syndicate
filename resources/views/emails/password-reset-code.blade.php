<!DOCTYPE html>
<html>
<body style="font-family: Arial, sans-serif; color: #333;">
    <p>Hello <strong>{{ $firstName }} {{ $lastName }}</strong>,</p>

    <p>
        You requested a password reset for your account <strong>{{ $email }}</strong>
        <p>Please use the following code in your application to reset your password: <strong>{{ $code }}</strong></p>
    </p>

    <p>
        Sincerely,<br>
        Syndicate Team
    </p>
</body>
</html>
