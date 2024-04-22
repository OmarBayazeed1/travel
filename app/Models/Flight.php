<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Flight extends Model
{
    use HasFactory;
    protected $primaryKey='id';
    protected $table='flights';
    protected $fillable = [
        'flight_number',
        'airline',
        'origin',
        'destination',
        'boarding_time',
        'arrival_time',
        'price',
        'distanceInKilo',
        'capacity',
        'serviceOwner_id'
    ];
    public function bookingFlights()
    {
        return $this->hasMany(Booking_flight::class);
    }

}
