<p style="font-size: 16px; color: #333;">Hello {{ $appointment->client_name }},</p>

<p style="font-size: 15px; color: #555;">
    Thank you for booking with <strong>Jamir Barbershop</strong>!  
    Here are the details of your upcoming appointment:
</p>

<table style="font-size: 15px; color: #333; margin: 15px 0; border-collapse: collapse;">
    <tr>
        <td style="padding: 5px 10px;"><strong>Barber:</strong></td>
        <td style="padding: 5px 10px;">{{ $appointment->barber->name }}</td>
    </tr>
    <tr>
        <td style="padding: 5px 10px;"><strong>Location:</strong></td>
        <td style="padding: 5px 10px;">{{ $appointment->branch->address }}</td>
    </tr>
    <tr>
        <td style="padding: 5px 10px;"><strong>Date:</strong></td>
        <td style="padding: 5px 10px;">{{ $appointment->appointment_date }}</td>
    </tr>
    <tr>
        <td style="padding: 5px 10px;"><strong>Time:</strong></td>
        <td style="padding: 5px 10px;">{{ $appointment->appointment_time }}</td>
    </tr>
</table>

<p style="font-size: 15px; color: #555;">
    Please confirm your appointment by clicking the button below:
</p>

<p style="text-align: center; margin: 20px 0;">
    <a href="{{ route('appointments.confirm', $appointment->confirmation_token) }}"
    style="background: #E8B931; color: #fff; padding: 12px 20px; text-decoration: none; border-radius: 6px; font-weight: bold; font-size: 15px;">
        Confirm Appointment
    </a>
</p>

<p style="font-size: 14px; color: #777;">
    If you didn’t request this appointment, you may safely ignore this email.  
</p>

<hr style="margin: 25px 0; border: none; border-top: 1px solid #eee;">

<p style="font-size: 13px; color: #999; text-align: center;">
    Jamir Barbershop • Thank you for choosing us!
</p>
