<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Passenger extends Model
{
    use HasFactory;

    protected $guarded = [];
    protected $casts = [
        'password' => 'hashed',
    ];
    public function flight()
    {
        return $this->belongsTo(Flight::class);
    }
}
