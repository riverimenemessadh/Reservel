<x-app-layout>
    <div class="row fade-in">
        <div class="col-12 mb-4">
            <h2 class="fw-bold text-primary">
                <i class="fas fa-tachometer-alt me-2"></i>{{ __('messages.dashboard') }}
            </h2>
        </div>

        <div class="col-md-6 mb-4">
            <div class="card text-center border-0 shadow-sm cursor-pointer" onclick="showAssetsInUseModal()"
                style="transition: all 0.3s ease;">
                <div class="card-body">
                    <i class="fas fa-box fa-3x text-primary mb-3"></i>
                    <h3 class="fw-bold">{{ $stats['in_use_now'] ?? 0 }} / {{ $stats['total_assets'] }}</h3>
                    <p class="text-muted mb-2">{{ __('messages.assets_in_use') }}</p>
                    <div class="progress" style="height: 8px;">
                        @php
                            $occupancyRate = $stats['total_assets'] > 0 ? ($stats['in_use_now'] / $stats['total_assets']) * 100 : 0;
                        @endphp
                        <div class="progress-bar bg-primary" role="progressbar" style="width: {{ $occupancyRate }}%">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6 mb-4">
            <div class="card text-center border-0 shadow-sm cursor-pointer"
                onclick="window.location.href='{{ route('reports.index') }}'" style="transition: all 0.3s ease;">
                <div class="card-body">
                    <i class="fas fa-exclamation-circle fa-3x text-danger mb-3"></i>
                    <h3 class="fw-bold text-danger">{{ $stats['pending_reports'] }}</h3>
                    <p class="text-muted mb-0">{{ __('messages.pending_reports') }}</p>
                </div>
            </div>
        </div>

        <div class="col-lg-8 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-calendar-day me-2"></i>{{ __('messages.todays_bookings') }}</h5>
                </div>
                <div class="card-body" style="max-height: 480px; overflow-y: auto;">
                    @forelse($todayBookings->take(5) as $booking)
                        @php
                            $room = $booking->bookings->firstWhere('asset.type', 'room');
                            $equipment = $booking->bookings->where('asset.type', 'equipment');
                            $mainTitle = $room ? $room->asset->name : ($equipment->first() ? $equipment->first()->asset->name : __('messages.booking'));
                            $icon = $room ? 'fa-building' : 'fa-laptop';
                            $iconColor = $room ? 'primary' : 'info';
                        @endphp
                        <div class="d-flex align-items-center mb-3 pb-3 border-bottom cursor-pointer hover-card"
                            onclick="showBookingDetails({{ json_encode($booking->bookings) }}, '{{ $booking->user->name }}', '{{ $booking->start_time->format('H:i') }}', '{{ $booking->end_time->format('H:i') }}', {{ $booking->can_cancel ? 'true' : 'false' }}, {{ $booking->id }})">
                            <div class="rounded-circle bg-{{ $iconColor }} bg-opacity-10 d-flex align-items-center justify-content-center me-3"
                                style="width: 50px; height: 50px; min-width: 50px;">
                                <i class="fas {{ $icon }} fa-lg text-{{ $iconColor }}"></i>
                            </div>
                            <div class="flex-grow-1">
                                <h6 class="mb-1">{{ $mainTitle }}</h6>
                                @if($equipment->count() > 0)
                                    <small class="text-muted">
                                        {{ $equipment->take(3)->pluck('asset.name')->join(', ') }}
                                        @if($equipment->count() > 3)
                                            <span class="badge bg-secondary">
                                                {{ trans_choice('messages.more_choice', $equipment->count() - 3, ['count' => $equipment->count() - 3]) }}</span>
                                        @endif
                                    </small>
                                    <br>
                                @endif
                                <small class="text-muted">
                                    <i class="fas fa-user me-1"></i>{{ $booking->user->name }}
                                    <i class="fas fa-clock ms-2 me-1"></i>{{ __('messages.time_range', ['start' => $booking->start_time->format('H:i'),'end' => $booking->end_time->format('H:i')]) }}
                                </small>
                            </div>
                            @if($booking->can_cancel)
                                <div class="me-2" onclick="event.stopPropagation()">
                                    <button type="button" title ="{{ __('messages.cancel_booking') }}" class="btn btn-sm btn-outline-danger"
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
        </div>

        <div class="col-lg-4 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0"><i class="fas fa-users me-2"></i>{{ __('messages.team') }}</h5>
                </div>
                <div class="card-body">
                    @foreach($teachers as $teacher)
                        <div class="d-flex align-items-center mb-3 p-2 rounded cursor-pointer hover-card"
                            onclick="window.location.href='{{ route('profile.show', $teacher->id) }}'">
                            <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center me-3"
                                style="width: 40px; height: 40px; min-width: 40px;">
                                <i class="fas fa-user"></i>
                            </div>
                            <div class="overflow-hidden">
                                <h6 class="mb-0 text-truncate">{{ $teacher->name }}</h6>
                                <small class="text-muted text-truncate d-block">{{ $teacher->email }}</small>
                            </div>
                            <i class="fas fa-chevron-right ms-auto text-muted small"></i>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="col-12 mb-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-secondary text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="fas fa-history me-2"></i>{{ __('messages.recent_bookings') }}</h5>
                    <a href="{{ route('bookings.index') }}" class="btn btn-sm btn-light">{{ __('messages.view_all') }}</a>
                </div>
                <div class="card-body">
                    <div class="row">
                        @foreach($recentBookings->take(8) as $booking)
                            @php
                                $room = $booking->bookings->firstWhere('asset.type', 'room');
                                $equipment = $booking->bookings->where('asset.type', 'equipment');
                                $mainTitle = $room ? $room->asset->name : ($equipment->first() ? $equipment->first()->asset->name : __('messages.booking'));
                                $icon = $room ? 'fa-building' : 'fa-laptop';
                                $iconColor = $room ? 'primary' : 'info';
                            @endphp
                            <div class="col-md-6 col-lg-3 mb-3">
                                <div class="card h-100 cursor-pointer hover-card-lift border-0 shadow-sm"
                                    onclick="showBookingDetails({{ json_encode($booking->bookings) }}, '{{ $booking->user->name }}', '{{ $booking->start_time->format('d/m/Y H:i') }}', '{{ $booking->end_time->format('H:i') }}', {{ $booking->can_cancel ? 'true' : 'false' }}, {{ $booking->id }})">
                                    <div class="card-body">
                                        <div class="d-flex align-items-center mb-2">
                                            <div class="rounded-circle bg-{{ $iconColor }} bg-opacity-10 d-flex align-items-center justify-content-center me-2"
                                                style="width: 40px; height: 40px;">
                                                <i class="fas {{ $icon }} text-{{ $iconColor }}"></i>
                                            </div>
                                            <h6 class="card-title mb-0 text-truncate">{{ $mainTitle }}</h6>
                                        </div>
                                        <p class="card-text small mb-0">
                                            <i class="fas fa-user me-1 text-primary"></i>{{ $booking->user->name }}<br>
                                            <i
                                                class="fas fa-calendar me-1"></i>{{ $booking->start_time->format('d/m/Y') }}<br>
                                            <i class="fas fa-clock me-1"></i>{{ $booking->start_time->format('H:i') }} -
                                            {{ $booking->end_time->format('H:i') }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    <button class="btn btn-primary btn-lg rounded-circle position-fixed shadow-lg"
        style="bottom: 20px; right: 20px; width: 60px; height: 60px; z-index: 1030;" data-bs-toggle="modal"
        data-bs-target="#bookingModal" title="{{ __('messages.book_asset') }}">
        <i class="fas fa-plus"></i>
    </button>

    <button class="btn btn-danger btn-lg rounded-circle position-fixed shadow-lg"
        style="bottom: 90px; right: 20px; width: 60px; height: 60px; z-index: 1030;" data-bs-toggle="modal"
        data-bs-target="#reportModal" title="{{ __('messages.report_problem') }}">
        <i class="fas fa-exclamation-triangle"></i>
    </button>

    @include('profile.partials.booking-modal')
    @include('profile.partials.report-modal')
    @include('profile.partials.booking-details-modal')

    <div class="modal fade" id="assetsInUseModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title"><i class="fas fa-box me-2"></i>{{ __('messages.assets_in_use') }}</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        @forelse($stats['in_use_assets'] ?? [] as $asset)
                            <div class="col-md-6 mb-3">
                                <div class="card border-0 shadow-sm">
                                    <div class="card-body">
                                        <h6 class="fw-bold"><i
                                                class="fas fa-{{ $asset->type === 'room' ? 'building' : 'laptop' }} me-2 text-primary"></i>{{ $asset->name }}
                                        </h6>
                                        @if($asset->currentBooking)
                                            <p class="small text-muted mb-0">
                                                <i class="fas fa-user me-1"></i>{{ $asset->currentBooking->user->name }}<br>
                                                <i class="fas fa-clock me-1"></i>{{ __('messages.ends_at') }}
                                                {{ $asset->currentBooking->end_time->format('H:i') }}
                                            </p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="col-12 text-center py-4">
                                <p class="text-muted">{{ __('messages.no_assets_in_use') }}</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>

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
            transition: all 0.2s ease;
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
            width: 5px;
        }

        .card-body::-webkit-scrollbar-track {
            background: var(--light-bg);
        }

        .card-body::-webkit-scrollbar-thumb {
            background: var(--secondary-color);
            border-radius: 10px;
        }

        .bg-light i.fa-building,
        .bg-light i.fa-door-open,
        .bg-light i.fa-laptop,
        .bg-light i.fa-box {
            color: var(--primary-color) !important;
        }
    </style>
@endpush

    @push('scripts')
        <script>
            function showAssetsInUseModal() {
                new bootstrap.Modal(document.getElementById('assetsInUseModal')).show();
            }

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
                                    <h5 class="mb-3">{{ __('messages.are_you_sure_cancle') }}</h5>
                                    <p class="text-muted"><strong>${bookingName}</strong></p>
                                </div>
                                <div class="modal-footer border-0 justify-content-center">
                                    <button type="button" class="btn btn-secondary px-4" data-bs-dismiss="modal">{{ __('messages.no') }}</button>
                                    <button type="button" class="btn btn-danger px-4" onclick="cancelBooking(${bookingId})">{{ __('messages.yes_cancel') }}</button>
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