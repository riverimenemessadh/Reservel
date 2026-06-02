<x-app-layout>

    @push('styles')
    <style>
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(12px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        .fade-in { animation: fadeIn 0.4s ease both; }

        .stat-card { transition: transform 0.2s ease, box-shadow 0.2s ease; }
        .stat-card:hover { transform: translateY(-4px); box-shadow: 0 10px 24px rgba(21,66,105,0.14) !important; }

        .booking-row { transition: background 0.15s ease, transform 0.15s ease; border-bottom: 1px solid #f0f4f8; }
        .booking-row:last-child { border-bottom: none; }
        .booking-row:hover { background: #f0f7f6; transform: translateX(4px); }

        .teacher-row { transition: background 0.15s ease; border-bottom: 1px solid #f0f4f8; }
        .teacher-row:last-child { border-bottom: none; }
        .teacher-row:hover { background: #f0f7f6; }

        .recent-card { transition: transform 0.25s ease, box-shadow 0.25s ease; }
        .recent-card:hover { transform: translateY(-5px); box-shadow: 0 10px 20px rgba(21,66,105,0.13) !important; }

        .scrollable { max-height: 420px; overflow-y: auto; }
        .scrollable::-webkit-scrollbar { width: 4px; }
        .scrollable::-webkit-scrollbar-track { background: #f1f5f9; }
        .scrollable::-webkit-scrollbar-thumb { background: #4c9183; border-radius: 99px; }

        .progress-teal { background: #4c9183; }

        /* ── FABs ── */
        .fab {
            position: fixed;
            right: 22px;
            width: 56px; height: 56px;
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.25rem;
            border: none;
            cursor: pointer;
            box-shadow: 0 4px 16px rgba(0,0,0,0.22);
            transition: transform 0.2s, box-shadow 0.2s;
            z-index: 1000;
            color: #fff;
        }
        .fab:hover { transform: scale(1.09); box-shadow: 0 8px 24px rgba(0,0,0,0.28); }
        .fab-book   { bottom: 24px;  background: #154269; }
        .fab-report { bottom: 92px; background: #ae2e3c; }
    </style>
    @endpush

    <div class="fade-in px-2 py-4" style="max-width: 1400px; margin: 0 auto;">

        {{-- Page heading --}}
        <div class="mb-6">
            <h2 class="text-2xl font-bold flex items-center gap-2" style="color: #154269;">
                <i class="fas fa-tachometer-alt"></i>
                {{ __('messages.dashboard') }}
            </h2>
        </div>

        {{-- ── STAT CARDS ─────────────────────────────────────────────── --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-6">

            {{-- Assets in use --}}
            @php
                $occupancyRate = ($stats['total_assets'] ?? 0) > 0
                    ? round(($stats['in_use_now'] / $stats['total_assets']) * 100)
                    : 0;
            @endphp
            <div class="stat-card bg-white rounded-xl shadow overflow-hidden cursor-pointer"
                 style="border-radius:12px;"
                 onclick="showAssetsInUseModal()">
                <div class="flex items-center gap-2 px-5 py-3 text-white font-semibold text-sm"
                     style="background:#4c9183;">
                    <i class="fas fa-box"></i>
                    {{ __('messages.assets_in_use') }}
                </div>
                <div class="p-5">
                    <div class="flex items-end justify-between mb-3">
                        <p class="text-4xl font-bold" style="color:#154269; line-height:1;">
                            {{ $stats['in_use_now'] ?? 0 }}
                            <span class="text-xl font-normal text-gray-400">/ {{ $stats['total_assets'] ?? 0 }}</span>
                        </p>
                        <span class="text-sm font-semibold" style="color:#4c9183;">{{ $occupancyRate }}%</span>
                    </div>
                    <div class="w-full rounded-full" style="height:6px; background:#e5e7eb;">
                        <div class="rounded-full" style="height:6px; width:{{ $occupancyRate }}%; background:#4c9183; transition:width 0.5s ease;"></div>
                    </div>
                    <p class="text-xs text-gray-400 mt-2">{{ __('messages.click_to_see_details') }}</p>
                </div>
            </div>

            {{-- Pending reports --}}
            <div class="stat-card bg-white rounded-xl shadow overflow-hidden cursor-pointer"
                 style="border-radius:12px;"
                 onclick="window.location.href='{{ route('reports.index') }}'">
                <div class="flex items-center gap-2 px-5 py-3 text-white font-semibold text-sm"
                     style="background:#ae2e3c;">
                    <i class="fas fa-exclamation-circle"></i>
                    {{ __('messages.pending_reports') }}
                </div>
                <div class="p-5 flex items-center gap-4">
                    <div class="flex items-center justify-center rounded-xl w-16 h-16 shrink-0"
                         style="background:rgba(174,46,60,0.1);">
                        <span class="text-3xl font-bold" style="color:#ae2e3c;">{{ $stats['pending_reports'] ?? 0 }}</span>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-600">{{ __('messages.pending_reports') }}</p>
                        <p class="text-xs text-gray-400 mt-1">{{ __('messages.click_to_manage') }}</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- ── TODAY'S BOOKINGS + TEAM ─────────────────────────────────── --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-5 mb-6">

            {{-- Today's bookings (2/3 width) --}}
            <div class="lg:col-span-2 bg-white rounded-xl shadow flex flex-col" style="border-radius:12px;">
                <div class="flex items-center gap-2 px-5 py-4 text-white font-semibold text-base"
                     style="background:#154269; border-radius:12px 12px 0 0;">
                    <i class="fas fa-calendar-day"></i>
                    {{ __('messages.todays_bookings') }}
                </div>
                <div class="scrollable p-2">
                    @forelse($todayBookings->take(5) as $booking)
                        @php
                            $room      = $booking->bookings->firstWhere('asset.type', 'room');
                            $equipment = $booking->bookings->where('asset.type', 'equipment');
                            $mainTitle = $room
                                ? $room->asset->name
                                : ($equipment->first() ? $equipment->first()->asset->name : __('messages.booking'));
                            $isRoom    = (bool) $room;
                            $remaining = $equipment->count() > 3 ? $equipment->count() - 3 : 0;
                        @endphp
                        <div class="booking-row flex items-center gap-3 px-3 py-3 rounded-lg cursor-pointer"
                             onclick="showBookingDetails({{ json_encode($booking->bookings) }}, '{{ $booking->user->name }}', '{{ $booking->start_time->format('H:i') }}', '{{ $booking->end_time->format('H:i') }}', {{ $booking->can_cancel ? 'true' : 'false' }}, {{ $booking->id }})">

                            <div class="flex items-center justify-center rounded-xl w-11 h-11 shrink-0"
                                 style="background: {{ $isRoom ? 'rgba(21,66,105,0.1)' : 'rgba(76,145,131,0.1)' }};">
                                <i class="fas {{ $isRoom ? 'fa-building' : 'fa-laptop' }}"
                                   style="color: {{ $isRoom ? '#154269' : '#4c9183' }};"></i>
                            </div>

                            <div class="flex-1 min-w-0">
                                <p class="font-semibold text-sm truncate" style="color:#154269;">{{ $mainTitle }}</p>
                                @if($equipment->count() > 0)
                                    <p class="text-xs text-gray-400 truncate">
                                        {{ $equipment->take(3)->pluck('asset.name')->join(', ') }}
                                        @if($remaining > 0)
                                            <span class="inline-block ml-1 px-1.5 py-0.5 rounded text-white text-xs"
                                                  style="background:#4c9183;">
                                                {{ trans_choice('messages.more_choice', $remaining, ['count' => $remaining]) }}
                                            </span>
                                        @endif
                                    </p>
                                @endif
                                <p class="text-xs text-gray-400 mt-0.5">
                                    <i class="fas fa-user mr-1"></i>{{ $booking->user->name }}
                                    <i class="fas fa-clock ml-2 mr-1"></i>{{ __('messages.time_range', ['start' => $booking->start_time->format('H:i'), 'end' => $booking->end_time->format('H:i')]) }}
                                </p>
                            </div>

                            @if($booking->can_cancel)
                                <button type="button"
                                        title="{{ __('messages.cancel_booking') }}"
                                        onclick="event.stopPropagation(); confirmCancelBooking({{ $booking->id }}, '{{ $mainTitle }}')"
                                        class="flex items-center justify-center w-8 h-8 rounded-lg transition-colors shrink-0"
                                        style="border:1.5px solid #fca5a5; color:#ef4444; background:transparent;"
                                        onmouseover="this.style.background='#fef2f2'"
                                        onmouseout="this.style.background='transparent'">
                                    <i class="fas fa-times text-xs"></i>
                                </button>
                            @endif

                            <i class="fas fa-chevron-right text-gray-300 text-xs shrink-0"></i>
                        </div>
                    @empty
                        <div class="flex flex-col items-center justify-center py-12 text-gray-400 gap-2">
                            <i class="fas fa-calendar-times fa-2x"></i>
                            <p class="text-sm">{{ __('messages.no_bookings_today') }}</p>
                        </div>
                    @endforelse
                </div>
            </div>

            {{-- Team (1/3 width) --}}
            <div class="bg-white rounded-xl shadow flex flex-col" style="border-radius:12px;">
                <div class="flex items-center gap-2 px-5 py-4 text-white font-semibold text-base"
                     style="background:#4c9183; border-radius:12px 12px 0 0;">
                    <i class="fas fa-users"></i>
                    {{ __('messages.team') }}
                </div>
                <div class="scrollable p-2">
                    @foreach($teachers as $teacher)
                        <div class="teacher-row flex items-center gap-3 px-3 py-3 rounded-lg cursor-pointer"
                             onclick="window.location.href='{{ route('profile.show', $teacher->id) }}'">
                            <div class="flex items-center justify-center rounded-full w-9 h-9 shrink-0 text-white text-sm font-bold"
                                 style="background:#154269;">
                                {{ strtoupper(substr($teacher->name, 0, 1)) }}
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium truncate" style="color:#154269;">{{ $teacher->name }}</p>
                                <p class="text-xs text-gray-400 truncate">{{ $teacher->email }}</p>
                            </div>
                            <i class="fas fa-chevron-right text-gray-300 text-xs shrink-0"></i>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- ── RECENT BOOKINGS ─────────────────────────────────────────── --}}
        <div class="bg-white rounded-xl shadow" style="border-radius:12px;">
            <div class="flex items-center justify-between px-5 py-4 text-white"
                 style="background:#154269; border-radius:12px 12px 0 0;">
                <span class="flex items-center gap-2 font-semibold text-base">
                    <i class="fas fa-history"></i>
                    {{ __('messages.recent_bookings') }}
                </span>
                <a href="{{ route('bookings.index') }}"
                   style="font-size:0.8rem; background:rgba(255,255,255,0.18); color:#fff; border-radius:6px; padding:3px 12px; text-decoration:none; font-weight:500;">
                    {{ __('messages.view_all') }}
                </a>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 p-4">
                @foreach($recentBookings->take(8) as $booking)
                    @php
                        $room      = $booking->bookings->firstWhere('asset.type', 'room');
                        $equipment = $booking->bookings->where('asset.type', 'equipment');
                        $mainTitle = $room
                            ? $room->asset->name
                            : ($equipment->first() ? $equipment->first()->asset->name : __('messages.booking'));
                        $isRoom    = (bool) $room;
                    @endphp
                    <div class="recent-card bg-gray-50 rounded-xl cursor-pointer p-4 flex flex-col gap-3 border border-gray-100"
                         style="border-radius:10px;"
                         onclick="showBookingDetails({{ json_encode($booking->bookings) }}, '{{ $booking->user->name }}', '{{ $booking->start_time->format('d/m/Y H:i') }}', '{{ $booking->end_time->format('H:i') }}', {{ $booking->can_cancel ? 'true' : 'false' }}, {{ $booking->id }})">
                        <div class="flex items-center gap-2">
                            <div class="flex items-center justify-center rounded-lg w-9 h-9 shrink-0"
                                 style="background: {{ $isRoom ? 'rgba(21,66,105,0.1)' : 'rgba(76,145,131,0.1)' }};">
                                <i class="fas {{ $isRoom ? 'fa-building' : 'fa-laptop' }} text-sm"
                                   style="color: {{ $isRoom ? '#154269' : '#4c9183' }};"></i>
                            </div>
                            <p class="font-semibold text-sm truncate" style="color:#154269;">{{ $mainTitle }}</p>
                        </div>
                        <div class="text-xs text-gray-500 space-y-1">
                            <p><i class="fas fa-user mr-1" style="color:#4c9183;"></i>{{ $booking->user->name }}</p>
                            <p><i class="fas fa-calendar mr-1" style="color:#4c9183;"></i>{{ $booking->start_time->format('d/m/Y') }}</p>
                            <p><i class="fas fa-clock mr-1" style="color:#4c9183;"></i>{{ $booking->start_time->format('H:i') }} – {{ $booking->end_time->format('H:i') }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    {{-- ── FABs ────────────────────────────────────────────────────────── --}}
    <button class="fab fab-book"
            data-bs-toggle="modal" data-bs-target="#bookingModal"
            title="{{ __('messages.book_asset') }}">
        <i class="fas fa-plus"></i>
    </button>

    <button class="fab fab-report"
            data-bs-toggle="modal" data-bs-target="#reportModal"
            title="{{ __('messages.report_problem') }}">
        <i class="fas fa-exclamation-triangle"></i>
    </button>

    {{-- ── PARTIALS ────────────────────────────────────────────────────── --}}
    @include('profile.partials.booking-modal')
    @include('profile.partials.report-modal')
    @include('profile.partials.booking-details-modal')

    {{-- ── ASSETS IN USE MODAL ─────────────────────────────────────────── --}}
    <div class="modal fade" id="assetsInUseModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content border-0 shadow-lg" style="border-radius:12px;">
                <div class="modal-header text-white border-0" style="background:#154269; border-radius:12px 12px 0 0;">
                    <h5 class="modal-title flex items-center gap-2">
                        <i class="fas fa-box"></i>{{ __('messages.assets_in_use') }}
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="row g-3">
                        @forelse($stats['in_use_assets'] ?? [] as $asset)
                            <div class="col-md-6">
                                <div class="bg-gray-50 rounded-xl p-4 border border-gray-100" style="border-radius:10px;">
                                    <p class="font-semibold text-sm mb-2" style="color:#154269;">
                                        <i class="fas fa-{{ $asset->type === 'room' ? 'building' : 'laptop' }} mr-2" style="color:#4c9183;"></i>
                                        {{ $asset->name }}
                                    </p>
                                    @if($asset->currentBooking)
                                        <p class="text-xs text-gray-500 mb-0">
                                            <i class="fas fa-user mr-1"></i>{{ $asset->currentBooking->user->name }}<br>
                                            <i class="fas fa-clock mr-1"></i>{{ __('messages.ends_at') }} {{ $asset->currentBooking->end_time->format('H:i') }}
                                        </p>
                                    @endif
                                </div>
                            </div>
                        @empty
                            <div class="col-12 text-center py-8 text-gray-400">
                                <i class="fas fa-check-circle fa-2x mb-2 block" style="color:#4c9183;"></i>
                                <p class="text-sm">{{ __('messages.no_assets_in_use') }}</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ── CANCEL BOOKING FORM ─────────────────────────────────────────── --}}
    <form id="cancelBookingForm" method="POST" style="display:none;">
        @csrf
        @method('DELETE')
    </form>

    @push('scripts')
    <script>
        function showAssetsInUseModal() {
            new bootstrap.Modal(document.getElementById('assetsInUseModal')).show();
        }

        function cancelBooking(bookingId) {
            const form = document.getElementById('cancelBookingForm');
            form.action = '/bookings/' + bookingId;
            form.submit();
        }
    </script>
    @endpush

</x-app-layout>