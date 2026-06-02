<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'asset_id',
        'start_time',
        'end_time',
        'status',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'start_time' => 'datetime',
            'end_time' => 'datetime',
        ];
    }

    // -------------------------------------------------------------------------
    // Relationships
    // -------------------------------------------------------------------------

    /**
     * Get the user who made this booking.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the asset associated with this booking.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function asset()
    {
        return $this->belongsTo(Asset::class);
    }

    // -------------------------------------------------------------------------
    // Query Scopes
    // -------------------------------------------------------------------------

    /**
     * Scope a query to only include bookings with an 'active' status.
     * Usage: Booking::active()->get()
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope a query to only include bookings for a given user that overlap
     * with the specified time slot. Uses half-open interval overlap logic:
     * start_time < $endTime AND end_time > $startTime.
     * Usage: Booking::forTimeSlot($userId, $start, $end)->get()
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  int                                    $userId
     * @param  \Carbon\Carbon|string                  $startTime
     * @param  \Carbon\Carbon|string                  $endTime
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeForTimeSlot($query, $userId, $startTime, $endTime)
    {
        return $query->where('user_id', $userId)
                     ->where('start_time', '<', $endTime)
                     ->where('end_time', '>', $startTime);
    }

    /**
     * Scope a query to only include active bookings that are currently in
     * progress, i.e. where start_time is in the past and end_time is in
     * the future relative to the current timestamp.
     * Usage: Booking::current()->get()
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeCurrent($query)
    {
        $now = Carbon::now();

        return $query->where('status', 'active')
                     ->where('start_time', '<=', $now)
                     ->where('end_time', '>=', $now);
    }

    // -------------------------------------------------------------------------
    // Helper Methods
    // -------------------------------------------------------------------------

    /**
     * Determine whether this booking is currently active.
     *
     * @return bool
     */
    public function isActive()
    {
        return $this->status === 'active';
    }
}