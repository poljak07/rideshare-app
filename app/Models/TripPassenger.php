<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TripPassenger extends Model
{

    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function trip()
    {
        return $this->belongsTo(Trip::class);
    }
}
