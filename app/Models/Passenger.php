<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Passenger extends Model
{
    use HasFactory, LogsActivity;

    protected $guarded = [];
    protected $casts = [
        'password' => 'hashed',
    ];
    public function flight()
    {
        return $this->belongsTo(Flight::class);
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->useLogName('passenger')
            ->logOnlyDirty();
    }
}
