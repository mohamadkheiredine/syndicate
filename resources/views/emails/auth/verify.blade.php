<!DOCTYPE html>
<html>
<head>

</head>
<body>
	<p>Hello {{ $first_name . ' ' . $last_name }},</p><br>
	<p>You have successfully created an account on {{ env('APP_NAME') }}!</p>
	<p>Please enter the following verification code to complete your registration.</p>
	<p>Your verification code is: {{ $pin }}</p><br>

	<p>{{ env('APP_NAME') }} Team!</p>
</body>
</html>