<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Booking_flight extends Model
{
    use HasApiTokens, HasFactory, Notifiable;
    protected $id='id';
    protected $table='booking_flights';
    protected $fillable = [
        'status',
        'flightClass',
        'flight_id',
        'user_id',
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function flight()
    {
        return $this->belongsTo(Flight::class);
    }
}
