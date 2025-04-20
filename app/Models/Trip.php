<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Trip extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'departure_time' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function car()
    {
        return $this->belongsTo(Car::class);
    }

    public function passengers()
    {
        return $this->hasMany(TripPassenger::class);
    }

    public function hasUserRequested($userId = null)
    {
        $userId = $userId ?: Auth::id();

        return $this->passengers()->where('user_id', $userId)->exists();
    }

    public function getUserRequestStatus($userId = null)
    {
        $userId = $userId ?: Auth::id();

        return $this->passengers()->where('user_id', $userId)->value('status');
    }
}
