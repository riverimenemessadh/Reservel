<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Asset extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'image',
        'type',
        'status',
    ];

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    public function reports()
    {
        return $this->hasMany(Report::class);
    }

    public function currentBooking()
    {
        return $this->hasOne(Booking::class)
            ->where('status', 'active')
            ->where('start_time', '<=', now())
            ->where('end_time', '>=', now());
    }

    public function isAvailable()
    {
        return $this->status === 'available';
    }

    public function isInUse()
    {
        return $this->status === 'in_use';
    }

    public function isInRepair()
    {
        return $this->status === 'in_repair';
    }
}
