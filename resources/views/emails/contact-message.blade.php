<!DOCTYPE html>
<html>
<head>
<title>Syndicate - Contact Us</title>
</head>
<body>
<br>
<table border="0" align="center" width="100%">
<tr>
<td colspan="2"><strong>The Syndicate Feedback Details</strong></td>
</tr>
<tr>
<td width="20%"><strong>Name:</strong></td>
<td width="80%">{{ ucfirst($senderName) }}</td>
</tr>
<tr>
<td width="20%"><strong>Email Address:</strong></td>
<td width="80%">{{ $senderEmail }}</td>
</tr>
<tr>
<td width="20%"><strong>Your Message:</strong></td>
<td width="80%">{!! ucfirst(nl2br(e($messageBody))) !!}</td>
</tr>
</table>
<p>Thanks and Regards.<br>Syndicate TEAM.</p>
</body>
</html>
