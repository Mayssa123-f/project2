<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use App\Models\Flight;
use Illuminate\Console\Command;
use App\Mail\FlightReminderMail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SendFlightReminderEmails extends Command
{
    protected $signature = 'flights:send-reminders';
    protected $description = 'Send reminder emails to passengers 24 hours before departure';

    public function handle()
    {
        $now = Carbon::now();
        $targetTime = $now->copy()->addDay();

        Log::info("Now: {$now}");
        Log::info("Target time (24h later): {$targetTime}");
        Log::info("Querying flights departing between " . $targetTime->copy()->subHours(2) . " and " . $targetTime->copy()->addHours(2));

        $startRange = $targetTime->copy()->subHours(2);
        $endRange = $targetTime->copy()->addHours(2);

        $flights = Flight::with('passengers')->whereBetween('departure_time', [$startRange, $endRange])->get();

        if ($flights->isEmpty()) {
            $this->info('No flights found departing 24 hours from now.');
            Log::info('No flights found departing 24 hours from now.');
            return Command::SUCCESS;
        }

        foreach ($flights as $flight) {
            Log::info("Found flight {$flight->number} departing at {$flight->departure_time}");
            foreach ($flight->passengers as $passenger) {
                Mail::to($passenger->email)->send(new FlightReminderMail($flight));
                $this->info("Reminder sent to {$passenger->email} for flight {$flight->number}");
            }
        }

        return Command::SUCCESS;
    }
}
