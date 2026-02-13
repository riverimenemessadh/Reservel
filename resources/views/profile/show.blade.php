<x-app-layout>
    <div class="row justify-content-center fade-in">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0"><i class="fas fa-user-circle me-2"></i>{{ __('messages.my_profile') }}</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4 text-center mb-4">
                            <div class="rounded-circle bg-primary bg-opacity-10 d-flex align-items-center justify-content-center mx-auto"
                                style="width: 120px; height: 120px;">
                                <i class="fas fa-user fa-4x text-primary"></i>
                            </div>
                        </div>
                        <div class="col-md-8">
                            <div class="mb-3">
                                <label class="form-label fw-bold text-muted small">{{ __('messages.name') }}</label>
                                <p class="form-control-plaintext">{{ $user->name }}</p>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold text-muted small">{{ __('messages.email') }}</label>
                                <p class="form-control-plaintext">{{ $user->email }}</p>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold text-muted small">{{ __('messages.role') }}</label>
                                <p class="form-control-plaintext">
                                    <span class="badge bg-{{ $user->isAdmin() ? 'danger' : 'info' }}">
                                        {{ __('messages.' . $user->role) }}
                                    </span>
                                </p>
                            </div>
                        </div>
                    </div>

                    <hr>

                    <h5 class="fw-bold mb-3"><i class="fas fa-calendar-day me-2"></i>{{ __('messages.todays_bookings') }}</h5>

                    @php
                        $todayBookings = $user
                            ->bookings()
                            ->where('status', 'active')
                            ->whereDate('start_time', today())
                            ->with('asset')
                            ->get()
                            ->groupBy(function ($booking) {
                                return $booking->start_time->format('Y-m-d H:i') .
                                    '-' .
                                    $booking->end_time->format('H:i');
                            });
                    @endphp

                    @forelse($todayBookings as $timeSlot => $bookings)
                        <div class="card mb-3 border-0 bg-light">
                            <div class="card-body">
                                <h6 class="mb-2">
                                    <i class="fas fa-clock me-2 text-primary"></i>
                                    {{ $bookings->first()->start_time->format('H:i') }} -
                                    {{ $bookings->first()->end_time->format('H:i') }}
                                </h6>
                                <div class="d-flex flex-wrap gap-2">
                                    @foreach ($bookings as $booking)
                                        @php
                                            $isRoom = $booking->asset->type === 'room';
                                            $icon = $isRoom ? 'fa-building' : 'fa-laptop';
                                            $badgeColor = $isRoom ? 'primary' : 'info';
                                        @endphp
                                        <span class="badge bg-{{ $badgeColor }} p-2">
                                            <i class="fas {{ $icon }} me-1"></i>{{ $booking->asset->name }}
                                        </span>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="alert alert-info border-0">
                            <i class="fas fa-info-circle me-2"></i>{{ __('messages.no_bookings_today') }}
                        </div>
                    @endforelse

                    <div class="mt-4">
                        <a href="{{ route('dashboard') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-2"></i>{{ __('messages.back_dashboard') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
