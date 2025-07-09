<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;


class Flight extends Model
{
    use HasFactory, LogsActivity;
    protected $guarded = [];
    public function passengers()
    {
        return $this->hasMany(Passenger::class);
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->useLogName('flight')
            ->logOnlyDirty();
    }
}
