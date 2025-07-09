<?php

namespace App\Mail;

use App\Models\Flight;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Carbon\Carbon;

class FlightReminderEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $flight;

    public function __construct(Flight $flight)
    {
        $this->flight = $flight;
    }

    public function build()
    {
        $departureFormatted = Carbon::parse($this->flight->departure_time)->format('Y-m-d H:i');

        $html = "
            <h1>Flight Reminder</h1>
            <p>Flight number: {$this->flight->number}</p>
            <p>Departure time: {$departureFormatted}</p>
            <p>Don't forget to arrive at the airport on time.</p>
        ";

        return $this->subject('Flight Reminder Email')
            ->html($html);
    }
}
