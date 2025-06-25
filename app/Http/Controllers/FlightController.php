<?php

namespace App\Http\Controllers;

use App\Models\Flight;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\QueryBuilder;
use Spatie\QueryBuilder\AllowedFilter;

class FlightController extends Controller
{
        public function index(Request $request){
            $flights=QueryBuilder::for(Flight::class)->
            allowedFilters([
                AllowedFilter::partial('departure_city'),
                AllowedFilter::partial('arrival_city')
            ])
                ->allowedSorts(['id','departure_city','arrival_city','departure_time'])
                ->paginate($request->get('per_page', 10))
                ->appends($request->query());
                 return response()->json($flights);
        }
     public function getPassengers($id) {
    $passengers = \App\Models\Passenger::where('flight_id', $id)->get();
    return response()->json($passengers);
}
    public function store(Request $request){
    $formfields=$request->validate([
       'number'=>'required|integer|between:0,1000',
       'departure_city'=>'required',
       'arrival_city'=>'required|different:departure_city',
       'departure_time'=>'required|date|after:now',
       'arrival_time'=>'required|date|after:departure_time'
    ]);
    $flight=Flight::create($formfields);
return response()->json([
    'message'=>'flight created successfully',
    'data' => $flight
],201);
    }
public function update(Request $request,Flight $flight){
 $formfields = $request->validate([
        'number' => 'sometimes|required|between:0,1000|unique:flights,number,' . $flight->id,
        'departure_city' => 'sometimes|required',
        'arrival_city' => 'sometimes|required|different:departure_city',
        'departure_time' => 'sometimes|required|date|after:now',
        'arrival_time' => 'sometimes|required|date|after:departure_time',
    ]);
      $flight->update($formfields);
      return response()->json([
        'message' => 'Flight updated successfully.',
        'data' => $flight
    ]);
}
public function destroy(Flight $flight){
    $flight->delete();
    return response()->json([
        'message' => 'Flight deleted successfully.'
    ]);

}

}
