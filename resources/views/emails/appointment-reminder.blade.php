<!DOCTYPE html>
<html>
<head>
    <title>Appointment Reminder</title>
</head>
<body>
    <p>Hello {{ $appointment->client_name }},</p>
    <p>This is a reminder that you have an appointment at <strong>{{ $formattedDateTime }}</strong>.</p>
    <p>See you soon!</p>
</body>
</html>
