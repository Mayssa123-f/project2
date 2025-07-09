<?php

namespace App\Http\Controllers;

use App\Models\Flight;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Mail\FlightReminderEmail;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Cache;
use Spatie\QueryBuilder\QueryBuilder;

class FlightController extends Controller
{
    public function index(Request $request)
    {
        $cacheKey = 'flights_' . md5($request->fullUrl());

        $fromCache = Cache::has($cacheKey);

        $flights = Cache::remember($cacheKey, now()->addMinutes(10), function () use ($request) {
            return QueryBuilder::for(Flight::class)
                ->allowedFilters(['departure_city', 'arrival_city'])
                ->allowedSorts(['id', 'departure_city', 'arrival_city', 'departure_time'])
                ->paginate($request->get('per_page', 10))
                ->appends($request->query());
        });

        return response()->json([
            'success' => true,
            'from_cache' => $fromCache,
            'data' => $flights,
        ]);
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
}
