<x-app-layout>
    <div class="fade-in">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="fw-bold text-primary">
                <i class="fas fa-calendar-check me-2"></i>{{ __('messages.bookings') }}
            </h2>
        </div>

        <div class="row">
            @forelse($bookings as $booking)
                @php
                    $room = $booking->bookings->firstWhere('asset.type', 'room');
                    $equipment = $booking->bookings->where('asset.type', 'equipment');
                    $mainAsset = $room ?: $equipment->first();
                    $mainTitle = $mainAsset ? $mainAsset->asset->name : __('messages.booking');
                    $hasImage = $mainAsset && $mainAsset->asset->image;
                    $imageUrl = $hasImage ? $mainAsset->asset->image : null;
                    $icon = $room ? 'fa-building' : 'fa-laptop';
                    $iconColor = $room ? 'primary' : 'info';
                @endphp
                <div class="col-md-6 col-lg-4 mb-4">
                    <div class="card h-100 cursor-pointer hover-card-lift border-0 shadow-sm"
                        onclick="showBookingDetails({{ json_encode($booking->bookings) }}, '{{ $booking->user->name }}', '{{ $booking->start_time->format('d/m/Y H:i') }}', '{{ $booking->end_time->format('H:i') }}', {{ $booking->can_cancel ? 'true' : 'false' }}, {{ $booking->id }})">
                        <div class="card-body">
                            <div class="d-flex align-items-center mb-3">
                                <div class="rounded-circle bg-{{ $iconColor }} bg-opacity-10 d-flex align-items-center justify-content-center me-3 booking-image-circle"
                                    style="width: 50px; height: 50px; min-width: 50px; overflow: hidden; {{ $hasImage ? 'padding: 0;' : '' }}">
                                    @if ($hasImage)
                                        <img src="{{ $imageUrl }}" alt="{{ $mainTitle }}" class="w-100 h-100"
                                            style="object-fit: cover;">
                                    @else
                                        <i class="fas {{ $icon }} fa-lg text-{{ $iconColor }}"></i>
                                    @endif
                                </div>
                                <div>
                                    <h5 class="card-title mb-0">{{ $mainTitle }}</h5>
                                    <small class="text-muted">{{ __('messages.click_to_view_details') }}</small>
                                </div>
                            </div>
                            @if ($equipment->count() > 0)
                                <p class="card-text small text-muted mb-2">
                                    <i class="fas fa-laptop me-1"></i>
                                    {{ $equipment->take(3)->pluck('asset.name')->join(', ') }}
                                    @if ($equipment->count() > 3)
                                        <span class="badge bg-secondary">+{{ $equipment->count() - 3 }}
                                            {{ __('messages.more') }}</span>
                                    @endif
                                </p>
                            @endif
                            <p class="card-text small mb-0">
                                <i class="fas fa-user me-1"></i>{{ $booking->user->name }}<br>
                                <i class="fas fa-calendar me-1"></i>{{ $booking->start_time->format('d/m/Y') }}<br>
                                <i class="fas fa-clock me-1"></i>{{ $booking->start_time->format('H:i') }} -
                                {{ $booking->end_time->format('H:i') }}
                            </p>
                            @if ($booking->can_cancel)
                                @can('cancel', $booking->bookings->first())
                                    <div class="mt-3" onclick="event.stopPropagation()">
                                        <button type="button" class="btn btn-danger btn-sm w-100"
                                            onclick="confirmCancelBooking({{ $booking->id }}, '{{ $mainTitle }}')">
                                            <i class="fas fa-times me-2"></i>{{ __('messages.cancel_this_booking') }}
                                        </button>
                                    </div>
                                @endcan
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="alert alert-info text-center border-0">
                        <i class="fas fa-info-circle me-2"></i>
                        @if (auth()->user()->isTeacher())
                            {{ __('messages.no_reservations_made') }}
                        @else
                            {{ __('messages.no_bookings_found') }}
                        @endif
                    </div>
                </div>
            @endforelse
        </div>

        @if ($groupedBookings->hasPages())
            <div class="mt-4 d-flex justify-content-center gap-2">
                @if ($groupedBookings->onFirstPage())
                    <button class="btn btn-sm btn-outline-secondary" disabled>
                        <i class="fas fa-chevron-left me-1"></i>{{ __('messages.previous') ?? 'Previous' }}
                    </button>
                @else
                    <a href="{{ $groupedBookings->previousPageUrl() }}" class="btn btn-sm btn-outline-primary">
                        <i class="fas fa-chevron-left me-1"></i>{{ __('messages.previous') ?? 'Previous' }}
                    </a>
                @endif

                <span class="btn btn-sm btn-outline-secondary" disabled>
                    {{ $groupedBookings->currentPage() }} / {{ $groupedBookings->lastPage() }}
                </span>

                @if ($groupedBookings->hasMorePages())
                    <a href="{{ $groupedBookings->nextPageUrl() }}" class="btn btn-sm btn-outline-primary">
                        {{ __('messages.next') ?? 'Next' }}<i class="fas fa-chevron-right ms-1"></i>
                    </a>
                @else
                    <button class="btn btn-sm btn-outline-secondary" disabled>
                        {{ __('messages.next') ?? 'Next' }}<i class="fas fa-chevron-right ms-1"></i>
                    </button>
                @endif
            </div>
        @endif
    </div>

    @if (!auth()->user()->isAdmin())
        <button class="btn btn-primary btn-lg rounded-circle position-fixed shadow-lg"
            style="bottom: 20px; right: 20px; width: 60px; height: 60px;" data-bs-toggle="modal"
            data-bs-target="#bookingModal" title="{{ __('messages.book_asset') }}">
            <i class="fas fa-plus"></i>
        </button>
        @include('profile.partials.booking-modal')
    @endif

    @include('profile.partials.booking-details-modal')

    <form id="cancelBookingForm" method="POST" style="display: none;">
        @csrf
        @method('DELETE')
    </form>

    @push('scripts')
        <script>
            function confirmCancelBooking(bookingId, bookingName) {
                const modal = document.createElement('div');
                modal.className = 'modal fade';
                modal.setAttribute('tabindex', '-1');
                modal.innerHTML = `
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content border-0 shadow-lg">
                        <div class="modal-header bg-danger text-white border-0">
                            <h5 class="modal-title">
                                <i class="fas fa-exclamation-triangle me-2"></i>{{ __('messages.cancel_booking') }}
                            </h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body text-center py-4">
                            <i class="fas fa-calendar-times fa-4x text-danger mb-3"></i>
                            <h5 class="mb-3">{{ __('messages.are_you_sure_cancel_booking') }}</h5>
                            <p class="text-muted mb-1"><strong>${bookingName}</strong></p>
                            <p class="small text-muted">{{ __('messages.action_cannot_be_undone') }}</p>
                        </div>
                        <div class="modal-footer border-0 justify-content-center">
                            <button type="button" class="btn btn-secondary px-4" data-bs-dismiss="modal">
                                <i class="fas fa-times me-2"></i>{{ __('messages.no_keep') }}
                            </button>
                            <button type="button" class="btn btn-danger px-4" onclick="cancelBooking(${bookingId})">
                                <i class="fas fa-check me-2"></i>{{ __('messages.yes_cancel') }}
                            </button>
                        </div>
                    </div>
                </div>
            `;
                document.body.appendChild(modal);
                const bsModal = new bootstrap.Modal(modal);
                bsModal.show();
                modal.addEventListener('hidden.bs.modal', () => modal.remove());
            }

            function cancelBooking(bookingId) {
                const form = document.getElementById('cancelBookingForm');
                form.action = '/bookings/' + bookingId;
                form.submit();
            }
        </script>
    @endpush

    @push('styles')
        <style>
            .cursor-pointer {
                cursor: pointer;
            }

            .hover-card-lift {
                transition: all 0.3s ease;
            }

            .hover-card-lift:hover {
                transform: translateY(-5px);
                box-shadow: 0 8px 16px rgba(21, 66, 105, 0.15) !important;
            }

            .booking-image-circle {
                position: relative;
            }

            .booking-image-circle img {
                border-radius: 50%;
            }
        </style>
    @endpush
</x-app-layout>
