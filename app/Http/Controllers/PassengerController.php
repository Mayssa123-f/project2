<?php

namespace App\Http\Controllers;

use App\Models\Passenger;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class PassengerController extends Controller
{
     public function index(Request $request){
        $passengers=QueryBuilder::for(Passenger::class)->
        allowedFilters([
            AllowedFilter::partial('firstName'),
            AllowedFilter::partial('lastName'),
            AllowedFilter::partial('email'),
        ])
        ->allowedSorts(['id', 'firstName', 'lastName', 'email'])
        ->paginate($request->get('perPage',10))
        ->appends($request->query());
        return response()->json($passengers);

    }
    public function store(Request $request){
     $formfields=$request->validate([
       'firstName'=>'required',
       'lastName'=>'required',
       'flight_id'=>'required|exists:flights,id',
       'email'=>'required|email|unique:passengers,email',
       'password'=>'required|min:8',
       'DOB'=>'required|date|before:today',
       'passport_expiry_date'=>'required|date|after:today'
        ]);
        $formfields['password'] = bcrypt($formfields['password']);
        $passenger=Passenger::create($formfields);
        return response()->json([
            'message'=>'passenger created successfully',
            'date'=>$passenger
        ],201);
}
    public function update(Request $request,Passenger $passenger){
$formfields=$request->validate([
       'firstName'=>'required|sometimes',
       'lastName'=>'required|sometimes',
       'flight_id'=>'required||sometimes|exists:flights,id',
       'email' => 'sometimes|required|email|unique:passengers,email,' . $passenger->id,
       'password'=>'required|sometimes|min:8',
       'DOB'=>'required|sometimes|date|before:today',
       'passport_expiry_date'=>'required|date|after:today|sometimes'
        ]);

        $passenger->update($formfields);
         return response()->json([
        'message' => 'Passenger updated successfully',
        'data' => $passenger,
    ]);
}
    public function destroy(Passenger $passenger){
        $passenger->delete();
        return response()->json([
        'message' => 'Passenger deleted successfully.'
    ]);

}
}
