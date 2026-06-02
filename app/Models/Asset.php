<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Asset extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'description',
        'image',
        'type',
        'status',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'status' => 'string',
    ];

    // -------------------------------------------------------------------------
    // Relationships
    // -------------------------------------------------------------------------

    /**
     * Get all bookings associated with this asset.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    /**
     * Get all reports associated with this asset.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function reports()
    {
        return $this->hasMany(Report::class);
    }

    /**
     * Get the current active booking for this asset, if any.
     * A booking is considered current when its status is 'active'
     * and the current timestamp falls within its start and end times.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function currentBooking()
    {
        return $this->hasOne(Booking::class)
            ->where('status', 'active')
            ->where('start_time', '<=', now())
            ->where('end_time', '>=', now());
    }

    // -------------------------------------------------------------------------
    // Query Scopes
    // -------------------------------------------------------------------------

    /**
     * Scope a query to only include assets with an 'available' status.
     * Usage: Asset::available()->get()
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeAvailable($query)
    {
        return $query->where('status', 'available');
    }

    /**
     * Scope a query to only include assets with an 'in_use' status.
     * Usage: Asset::inUse()->get()
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeInUse($query)
    {
        return $query->where('status', 'in_use');
    }

    /**
     * Scope a query to only include assets with an 'in_repair' status.
     * Usage: Asset::inRepair()->get()
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeInRepair($query)
    {
        return $query->where('status', 'in_repair');
    }

    // -------------------------------------------------------------------------
    // Helper Methods
    // -------------------------------------------------------------------------

    /**
     * Determine whether this asset is currently available for booking.
     *
     * @return bool
     */
    public function isAvailable()
    {
        return $this->status === 'available';
    }

    /**
     * Determine whether this asset is currently in use.
     *
     * @return bool
     */
    public function isInUse()
    {
        return $this->status === 'in_use';
    }

    /**
     * Determine whether this asset is currently undergoing repair.
     *
     * @return bool
     */
    public function isInRepair()
    {
        return $this->status === 'in_repair';
    }
}