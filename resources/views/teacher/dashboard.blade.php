<x-app-layout>
    <div class="row fade-in">
        <div class="col-12 mb-4">
            <h2 class="fw-bold text-primary">
                <i class="fas fa-home me-2"></i>{{ __('messages.welcome') }} {{ auth()->user()->name }}
            </h2>
        </div>

        <div class="col-lg-8 mb-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-calendar-day me-2"></i>{{ __('messages.my_reservations_today') }}
                    </h5>
                </div>
                <div class="card-body" style="max-height: 310px; overflow-y: auto;">
                    @forelse($todayBookings as $booking)
                        @php
                            $bookingRoom = $booking->bookings->firstWhere('asset.type', 'room');
                            $bookingEquipment = $booking->bookings->where('asset.type', 'equipment');
                            $mainTitle = $bookingRoom
                                ? $bookingRoom->asset->name
                                : ($bookingEquipment->first()
                                    ? $bookingEquipment->first()->asset->name
                                    : 'Booking');
                            $icon = $bookingRoom ? 'fa-building' : 'fa-laptop';
                            $iconColor = $bookingRoom ? 'primary' : 'info';
                        @endphp
                        <div class="d-flex align-items-center mb-3 pb-3 border-bottom cursor-pointer hover-card"
                            onclick="showBookingDetails({{ json_encode($booking->bookings) }}, '{{ $booking->user->name }}', '{{ $booking->start_time->format('H:i') }}', '{{ $booking->end_time->format('H:i') }}', {{ $booking->can_cancel ? 'true' : 'false' }}, {{ $booking->id }})">
                            <div class="rounded-circle bg-{{ $iconColor }} bg-opacity-10 d-flex align-items-center justify-content-center me-3"
                                style="width: 50px; height: 50px; min-width: 50px;">
                                <i class="fas {{ $icon }} fa-lg text-{{ $iconColor }}"></i>
                            </div>
                            <div class="flex-grow-1">
                                <h6 class="mb-1">{{ $mainTitle }}</h6>
                                @if ($bookingEquipment->count() > 0)
                                    <small class="text-muted">
                                        {{ $bookingEquipment->take(3)->pluck('asset.name')->join(', ') }}
                                        @if ($bookingEquipment->count() > 3)
                                            <span
                                                class="badge bg-secondary">{{ trans_choice('messages.more_choice', $remaining, ['count' => $remaining]) }}</span>
                                        @endif
                                    </small>
                                    <br>
                                @endif
                                <small class="text-muted">
                                    <i class="fas fa-clock me-1"></i>{{ $booking->start_time->format('H:i') }} -
                                    {{ $booking->end_time->format('H:i') }}
                                </small>
                            </div>
                            @if ($booking->can_cancel)
                                <div class="me-2" onclick="event.stopPropagation()">
                                    <button type="button" class="btn btn-sm btn-outline-danger"
                                        onclick="confirmCancelBooking({{ $booking->id }}, '{{ $mainTitle }}')">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                            @endif
                            <i class="fas fa-chevron-right text-muted"></i>
                        </div>
                    @empty
                        <p class="text-muted text-center mb-0">{{ __('messages.no_bookings_today') }}</p>
                    @endforelse
                </div>
            </div>

            <div class="card border-0 shadow-sm mt-4">
                <div class="card-header bg-secondary text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="fas fa-history me-2"></i>{{ __('messages.my_recent_bookings') }}</h5>
                    <a href="{{ route('bookings.index') }}"
                        class="btn btn-sm btn-light">{{ __('messages.view_all') }}</a>
                </div>
                <div class="card-body">
                    <div class="row">
                        @foreach ($recentBookings->take(4) as $booking)
                            @php
                                $bookingRoom = $booking->bookings->firstWhere('asset.type', 'room');
                                $bookingEquipment = $booking->bookings->where('asset.type', 'equipment');
                                $mainTitle = $bookingRoom
                                    ? $bookingRoom->asset->name
                                    : ($bookingEquipment->first()
                                        ? $bookingEquipment->first()->asset->name
                                        : 'Booking');
                                $icon = $bookingRoom ? 'fa-building' : 'fa-laptop';
                                $iconColor = $bookingRoom ? 'primary' : 'info';
                            @endphp
                            <div class="col-md-6 mb-3">
                                <div class="card h-100 cursor-pointer hover-card-lift border-0 shadow-sm"
                                    onclick="showBookingDetails({{ json_encode($booking->bookings) }}, '{{ $booking->user->name }}', '{{ $booking->start_time->format('d/m/Y H:i') }}', '{{ $booking->end_time->format('H:i') }}', {{ $booking->can_cancel ? 'true' : 'false' }}, {{ $booking->id }})">
                                    <div class="card-body">
                                        <div class="d-flex align-items-center mb-2">
                                            <div class="rounded-circle bg-{{ $iconColor }} bg-opacity-10 d-flex align-items-center justify-content-center me-2"
                                                style="width: 40px; height: 40px;">
                                                <i class="fas {{ $icon }} text-{{ $iconColor }}"></i>
                                            </div>
                                            <h6 class="card-title mb-0">{{ $mainTitle }}</h6>
                                        </div>
                                        <p class="card-text small mb-0">
                                            <i
                                                class="fas fa-calendar me-1"></i>{{ $booking->start_time->format('d/m/Y') }}<br>
                                            <i class="fas fa-clock me-1"></i>{{ $booking->start_time->format('H:i') }}
                                            - {{ $booking->end_time->format('H:i') }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0"><i class="fas fa-door-open me-2"></i>{{ __('messages.rooms') }}</h5>
                </div>
                <div class="card-body p-0" style="max-height: 315px; overflow-y: auto;">
                    <div class="list-group list-group-flush">
                        @foreach ($rooms as $room)
                            <a href="{{ route('assets.show', $room) }}"
                                class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                                <span>{{ $room->name }}</span>
                                <span
                                    class="badge rounded-pill bg-{{ $room->status === 'available' ? 'success' : ($room->status === 'in_use' ? 'warning' : 'danger') }}">
                                    {{ __('messages.' . $room->status) }}
                                </span>
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="card border-0 shadow-sm">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0"><i class="fas fa-laptop me-2"></i>{{ __('messages.equipment') }} </h5>
                </div>
                <div class="card-body p-0" style="max-height: 315px; overflow-y: auto;">
                    <div class="list-group list-group-flush">
                        @foreach ($equipment as $item)
                            <a href="{{ route('assets.show', $item) }}"
                                class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                                <span>{{ $item->name }}</span>
                                <span
                                    class="badge rounded-pill bg-{{ $item->status === 'available' ? 'success' : ($item->status === 'in_use' ? 'warning' : 'danger') }}">
                                    {{ __('messages.' . $item->status) }} 
                                </span>
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    <button class="btn btn-primary btn-lg rounded-circle position-fixed shadow-lg"
        style="bottom: 20px; right: 20px; width: 60px; height: 60px;" data-bs-toggle="modal"
        data-bs-target="#bookingModal" title="{{ __('messages.book_asset') }}">
        <i class="fas fa-plus"></i>
    </button>

    <button class="btn btn-danger btn-lg rounded-circle position-fixed shadow-lg"
        style="bottom: 90px; right: 20px; width: 60px; height: 60px;" data-bs-toggle="modal"
        data-bs-target="#reportModal" title="{{ __('messages.report_problem') }}">
        <i class="fas fa-exclamation-triangle"></i>
    </button>

    @include('profile.partials.booking-modal')
    @include('profile.partials.report-modal')
    @include('profile.partials.booking-details-modal')

    <form id="cancelBookingForm" method="POST" style="display: none;">
        @csrf
        @method('DELETE')
    </form>

    @push('styles')
    <style>
        .cursor-pointer {
            cursor: pointer;
        }

        .hover-card {
            transition: all 0.3s ease;
        }

        .hover-card:hover {
            background-color: var(--light-bg);
            transform: translateX(5px);
        }

        .hover-card-lift {
            transition: all 0.3s ease;
        }

        .hover-card-lift:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 16px rgba(21, 66, 105, 0.15) !important;
        }

        .card-body::-webkit-scrollbar {
            width: 4px;
        }

        .card-body::-webkit-scrollbar-track {
            background: var(--light-bg);
        }

        .card-body::-webkit-scrollbar-thumb {
            background: var(--info-color);
            border-radius: 10px;
        }
    </style>
@endpush

    @push('scripts')
        <script>
            function confirmCancelBooking(bookingId, bookingName) {
                const modal = document.createElement('div');
                modal.className = 'modal fade';
                modal.innerHTML = `
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content border-0 shadow-lg">
                                <div class="modal-header bg-danger text-white border-0">
                                    <h5 class="modal-title"><i class="fas fa-exclamation-triangle me-2"></i>{{ __('messages.cancel_booking') }}</h5>
                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body text-center py-4">
                                    <i class="fas fa-calendar-times fa-4x text-danger mb-3"></i>
                                    <h5 class="mb-3">{{ __('messages.are_you_sure_cancel') }}</h5>
                                    <p class="text-muted mb-1"><strong>${bookingName}</strong></p>
                                </div>
                                <div class="modal-footer border-0 justify-content-center">
                                    <button type="button" class="btn btn-secondary px-4" data-bs-dismiss="modal">{{ __('messages.no') }}</button>
                                    <button type="button" class="btn btn-danger px-4" onclick="cancelBooking(${bookingId})">{{ __('messages.yes_cancle') }}</button>
                                </div>
                            </div>
                        </div>`;
                document.body.appendChild(modal);
                new bootstrap.Modal(modal).show();
            }

            function cancelBooking(bookingId) {
                const form = document.getElementById('cancelBookingForm');
                form.action = '/bookings/' + bookingId;
                form.submit();
            }
        </script>
    @endpush
</x-app-layout>