<?php

namespace App\Http\Controllers;

use App\Models\Passenger;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\Rule;
use Spatie\QueryBuilder\QueryBuilder;
use Spatie\QueryBuilder\AllowedFilter;

class PassengerController extends Controller
{
    public function index(Request $request)
    {
        $passengers = QueryBuilder::for(Passenger::class)->allowedFilters([
            'firstName',
            'lastName',
            'email',
            AllowedFilter::exact('flight_id'),
        ])
            ->allowedSorts(['id', 'firstName', 'lastName', 'email'])
            ->paginate($request->get('perPage', 10))
            ->appends($request->query());
        return response(['success' => true, 'data' => $passengers]);
    }
    public function show(Passenger $passenger)
    {
        return response(['success' => true, 'data' => $passenger]);
    }
    public function store(Request $request)
    {
        $formfields = $request->validate([
            'firstName' => ['required'],
            'lastName' => ['required'],
            'flight_id' => ['required', 'exists:flights,id'],
            'email' => ['required', 'email', Rule::unique('passengers', 'email')],
            'password' => ['required', 'min:8'],
            'DOB' => ['required', 'date', 'before:today'],
            'passport_expiry_date' => ['required', 'date', 'after:today'],

        ]);
        $passenger = Passenger::create($formfields);
        return response(['success' => true, 'data' => $passenger]);
    }
    public function update(Request $request, Passenger $passenger)
    {
        $formfields = $request->validate([
            'firstName' => ['nullable'],
            'lastName' => ['nullable'],
            'flight_id' => ['nullable', 'exists:flights,id'],
            'email' => ['nullable', 'email', Rule::unique('passengers', 'email')->ignore($passenger->id)],
            'password' => ['nullable', 'min:8'],
            'DOB' => ['nullable', 'date', 'before:today'],
            'passport_expiry_date' => ['nullable', 'date', 'after:today']
        ]);

        $passenger->update($formfields);
        return response(['success' => true, 'data' => $passenger]);
    }
    public function destroy(Passenger $passenger)
    {
        $passenger->delete();
        return response(['success' => true], Response::HTTP_NO_CONTENT);
    }
}
