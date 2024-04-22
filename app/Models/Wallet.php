<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Wallet extends Model
{
    use HasApiTokens, HasFactory, Notifiable;
    protected $id='id';
    protected $table='wallets';
    protected $fillable = [
        'amount',
        'user_id',

    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
