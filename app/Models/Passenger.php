<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Passenger extends Model
{
    use HasFactory;
    
protected $fillable = [
    'firstName',
    'lastName',
    'flight_id',
    'email',
    'password',
    'DOB',
    'passport_expiry_date',
];

    public function flight()
{
    return $this->belongsTo(Flight::class);
}

}
