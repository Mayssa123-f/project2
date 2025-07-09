<x-mail::message>
# ✈️ Flight Reminder

Hello {{ $flight['passengers']['firstName'] ?? 'Passenger' }} {{ $flight['passengers']['lastName'] ?? '' }},

We’re reminding you of your upcoming flight. Below are your travel details:

---

**Flight Number:** {{ $flight['number'] }}  
**From:** {{ $flight['departure_city'] }}  
**To:** {{ $flight['arrival_city'] }}  
**Departure Time:** {{ \Carbon\Carbon::parse($flight['departure_time'])->format('l, F j, Y g:i A') }}  
**Arrival Time:** {{ \Carbon\Carbon::parse($flight['arrival_time'])->format('l, F j, Y g:i A') }}

<x-mail::button :url="'https://your-airline.com/checkin/' . $flight['number']">
Check-In Online
</x-mail::button>

If you have any questions or need assistance, feel free to contact our support.

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
