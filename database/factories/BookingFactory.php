<?php

namespace Database\Factories;

use App\Models\Booking;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;
use App\Models\Asset;

class BookingFactory extends Factory
{
    protected $model = Booking::class;

    public function definition(): array
{
    return [
        'user_id'    => User::factory(),
        'asset_id'   => Asset::factory(),
        'start_time' => now()->addDay()->setHour(8)->setMinute(0)->setSecond(0),
        'end_time'   => now()->addDay()->setHour(10)->setMinute(0)->setSecond(0),
        'status'     => 'active',
    ];
}
}