<?php

namespace App\Http\Controllers;

use App\Models\Flight;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Mail\FlightReminderEmail;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use Spatie\QueryBuilder\QueryBuilder;

class FlightController extends Controller
{
    public function index(Request $request)
    {
        $flights = QueryBuilder::for(Flight::class)->allowedFilters(['departure_city', 'arrival_city'])
            ->allowedSorts(['id', 'departure_city', 'arrival_city', 'departure_time'])
            ->paginate($request->get('per_page', 10))
            ->appends($request->query());
        return response(['success' => true, 'data' => $flights]);
    }
    public function show(Flight $flight)
    {
        return response(['success' => true, 'data' => $flight]);
    }
    public function store(Request $request)
    {
        $formfields = $request->validate([
            'number' => ['required', 'integer', 'between:0,1000'],
            'departure_city' => ['required'],
            'arrival_city' => ['required', 'different:departure_city'],
            'departure_time' => ['required', 'date', 'after:now'],
            'arrival_time' => ['required', 'date', 'after:departure_time'],

        ]);
        $flight = Flight::create($formfields);
        return response(['success' => true, 'data' => $flight]);
    }
    public function update(Request $request, Flight $flight)
    {
        $formfields = $request->validate([
            'number' => ['nullable', 'between:0,1000', 'unique:flights,number,' . $flight->id],
            'departure_city' => ['nullable'],
            'arrival_city' => ['nullable', 'different:departure_city'],
            'departure_time' => ['nullable', 'date', 'after:now'],
            'arrival_time' => ['nullable', 'date', 'after:departure_time'],

        ]);
        $flight->update($formfields);
        return response(['success' => true, 'data' => $flight]);
    }
    public function destroy(Flight $flight)
    {
        $flight->delete();
        return response(['success' => true], Response::HTTP_NO_CONTENT);
    }
    // public function sendReminder($id)
    // {
    //     $flight = Flight::with('passengers')->find($id);

    //     if (!$flight) {
    //         return response(['success' => false]);
    //     }

    //     foreach ($flight->passengers as $passenger) {
    //         Mail::to($passenger->email)->send(new FlightReminderEmail($flight));
    //     }

    //     return response(['success' => true, 'data' => $flight]);
    // }
}
