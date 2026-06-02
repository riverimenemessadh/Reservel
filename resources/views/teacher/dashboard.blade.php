<x-app-layout>

    @push('styles')
    <style>
        :root {
            --navy: #154269;
            --teal: #4c9183;
            --navy-light: #1e5080;
            --teal-light: #5da898;
            --navy-faint: rgba(21, 66, 105, 0.07);
            --teal-faint: rgba(76, 145, 131, 0.10);
            --shadow-card: 0 2px 12px rgba(21, 66, 105, 0.10);
            --shadow-hover: 0 8px 24px rgba(21, 66, 105, 0.16);
            --radius: 12px;
        }

        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(18px); }
            to   { opacity: 1; transform: translateY(0);    }
        }

        .fade-in-up {
            animation: fadeInUp 0.45s ease both;
        }

        .stagger-1 { animation-delay: 0.05s; }
        .stagger-2 { animation-delay: 0.12s; }
        .stagger-3 { animation-delay: 0.19s; }
        .stagger-4 { animation-delay: 0.26s; }

        /* ── Scrollbar ── */
        .scroll-area::-webkit-scrollbar { width: 4px; }
        .scroll-area::-webkit-scrollbar-track { background: transparent; }
        .scroll-area::-webkit-scrollbar-thumb { background: var(--teal); border-radius: 99px; }

        /* ── Cards ── */
        .r-card {
            background: #fff;
            border-radius: var(--radius);
            box-shadow: var(--shadow-card);
            border: none;
            overflow: hidden;
        }

        .r-card-header {
            padding: 0.85rem 1.25rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-weight: 600;
            font-size: 0.95rem;
            letter-spacing: 0.01em;
        }

        .r-card-header.navy  { background: var(--navy);  color: #fff; }
        .r-card-header.teal  { background: var(--teal);  color: #fff; }

        /* ── Booking row ── */
        .booking-row {
            display: flex;
            align-items: center;
            gap: 0.85rem;
            padding: 0.85rem 1.1rem;
            border-bottom: 1px solid #f0f4f8;
            cursor: pointer;
            transition: background 0.2s, transform 0.2s;
        }
        .booking-row:last-child { border-bottom: none; }
        .booking-row:hover {
            background: var(--navy-faint);
            transform: translateX(4px);
        }

        .booking-icon {
            width: 44px; height: 44px; min-width: 44px;
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.1rem;
        }
        .booking-icon.navy { background: var(--navy-faint); color: var(--navy); }
        .booking-icon.teal { background: var(--teal-faint); color: var(--teal); }

        /* ── Recent booking mini-cards ── */
        .recent-card {
            border-radius: 10px;
            border: 1px solid #e8eef4;
            box-shadow: 0 1px 6px rgba(21,66,105,0.07);
            cursor: pointer;
            transition: transform 0.22s, box-shadow 0.22s;
            background: #fff;
        }
        .recent-card:hover {
            transform: translateY(-4px);
            box-shadow: var(--shadow-hover);
        }

        /* ── Asset list items ── */
        .asset-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0.7rem 1.1rem;
            border-bottom: 1px solid #f0f4f8;
            font-size: 0.88rem;
            font-weight: 500;
            color: #334155;
            transition: background 0.15s;
        }
        .asset-item:last-child { border-bottom: none; }
        .asset-item:hover { background: var(--navy-faint); }
        .asset-item a { text-decoration: none; color: inherit; }

        /* ── Status badges ── */
        .badge-status {
            padding: 0.22em 0.7em;
            border-radius: 99px;
            font-size: 0.75rem;
            font-weight: 600;
            letter-spacing: 0.02em;
        }
        .badge-available { background: #dcfce7; color: #15803d; }
        .badge-in_use    { background: #fef9c3; color: #a16207; }
        .badge-default   { background: #fee2e2; color: #b91c1c; }

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
        }
        .fab:hover { transform: scale(1.09); box-shadow: 0 8px 24px rgba(0,0,0,0.28); }
        .fab-book   { bottom: 24px;  background: var(--navy);  color: #fff; }
        .fab-report { bottom: 92px; background: var(--teal);  color: #fff; }
    </style>
    @endpush

    <div class="fade-in-up" style="padding: 1.25rem 0;">

        {{-- ── Page heading ── --}}
        <div class="mb-5 stagger-1 fade-in-up">
            <h2 style="font-size:1.55rem; font-weight:700; color: var(--navy); margin-bottom:0.2rem;">
                <i class="fas fa-home me-2" style="color:var(--teal);"></i>{{ __('messages.welcome') }}{{ auth()->user()->name }}
            </h2>
            <p style="color:#64748b; font-size:0.9rem; margin:0;">
                {{ now()->translatedFormat('l, d F Y') }}
            </p>
        </div>

        <div class="row g-4">

            {{-- ══════════════════════════════════════════════
                 LEFT COLUMN — Today's & Recent Bookings
            ══════════════════════════════════════════════ --}}
            <div class="col-lg-8">

                {{-- Today's bookings ── --}}
                <div class="r-card stagger-2 fade-in-up mb-4">
                    <div class="r-card-header navy">
                        <i class="fas fa-calendar-day"></i>
                        {{ __('messages.my_reservations_today') }}
                    </div>
                    <div class="scroll-area" style="max-height: 320px; overflow-y: auto;">
                        @forelse($todayBookings as $booking)
                            @php
                                $bookingRoom      = $booking->bookings->firstWhere('asset.type', 'room');
                                $bookingEquipment = $booking->bookings->where('asset.type', 'equipment');
                                $mainTitle = $bookingRoom
                                    ? $bookingRoom->asset->name
                                    : ($bookingEquipment->first() ? $bookingEquipment->first()->asset->name : 'Booking');
                                $icon      = $bookingRoom ? 'fa-building' : 'fa-laptop';
                                $iconClass = $bookingRoom ? 'navy' : 'teal';
                                $remaining = max(0, $bookingEquipment->count() - 3);
                            @endphp
                            <div class="booking-row"
                                onclick="showBookingDetails(
                                    {{ json_encode($booking->bookings) }},
                                    '{{ $booking->user->name }}',
                                    '{{ $booking->start_time->format('H:i') }}',
                                    '{{ $booking->end_time->format('H:i') }}',
                                    {{ $booking->can_cancel ? 'true' : 'false' }},
                                    {{ $booking->id }}
                                )">

                                <div class="booking-icon {{ $iconClass }}">
                                    <i class="fas {{ $icon }}"></i>
                                </div>

                                <div style="flex:1; min-width:0;">
                                    <div style="font-weight:600; font-size:0.92rem; color:#1e293b; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">
                                        {{ $mainTitle }}
                                    </div>
                                    @if ($bookingEquipment->count() > 0)
                                        <div style="font-size:0.8rem; color:#64748b; margin-top:1px;">
                                            {{ $bookingEquipment->take(3)->pluck('asset.name')->join(', ') }}
                                            @if ($remaining > 0)
                                                <span class="badge-status" style="background:var(--navy-faint); color:var(--navy); margin-left:4px;">
                                                    {{ trans_choice('messages.more_choice', $remaining, ['count' => $remaining]) }}
                                                </span>
                                            @endif
                                        </div>
                                    @endif
                                    <div style="font-size:0.8rem; color:#94a3b8; margin-top:2px;">
                                        <i class="fas fa-clock me-1"></i>
                                        {{ $booking->start_time->format('H:i') }} – {{ $booking->end_time->format('H:i') }}
                                    </div>
                                </div>

                                @if ($booking->can_cancel)
                                    <div onclick="event.stopPropagation()">
                                        <button type="button"
                                            class="btn btn-sm"
                                            style="border:1.5px solid #ef4444; color:#ef4444; border-radius:8px; padding:4px 10px; background:transparent; transition:background 0.15s, color 0.15s;"
                                            onmouseover="this.style.background='#ef4444';this.style.color='#fff';"
                                            onmouseout="this.style.background='transparent';this.style.color='#ef4444';"
                                            onclick="confirmCancelBooking({{ $booking->id }}, '{{ $mainTitle }}')">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </div>
                                @endif

                                <i class="fas fa-chevron-right" style="color:#cbd5e1; font-size:0.8rem;"></i>
                            </div>
                        @empty
                            <div style="padding:2.5rem; text-align:center; color:#94a3b8;">
                                <i class="fas fa-calendar-xmark fa-2x mb-2" style="display:block; color:#cbd5e1;"></i>
                                {{ __('messages.no_bookings_today') }}
                            </div>
                        @endforelse
                    </div>
                </div>

                {{-- Recent bookings ── --}}
                <div class="r-card stagger-3 fade-in-up">
                    <div class="r-card-header teal" style="justify-content:space-between;">
                        <span>
                            <i class="fas fa-history me-2"></i>{{ __('messages.my_recent_bookings') }}
                        </span>
                        <a href="{{ route('bookings.index') }}"
                           style="font-size:0.8rem; background:rgba(255,255,255,0.18); color:#fff; border-radius:6px; padding:3px 12px; text-decoration:none; font-weight:500;">
                            {{ __('messages.view_all') }}
                        </a>
                    </div>
                    <div style="padding:1rem;">
                        <div class="row g-3">
                            @foreach ($recentBookings->take(4) as $booking)
                                @php
                                    $bookingRoom      = $booking->bookings->firstWhere('asset.type', 'room');
                                    $bookingEquipment = $booking->bookings->where('asset.type', 'equipment');
                                    $mainTitle = $bookingRoom
                                        ? $bookingRoom->asset->name
                                        : ($bookingEquipment->first() ? $bookingEquipment->first()->asset->name : 'Booking');
                                    $icon      = $bookingRoom ? 'fa-building' : 'fa-laptop';
                                    $iconClass = $bookingRoom ? 'navy' : 'teal';
                                @endphp
                                <div class="col-md-6">
                                    <div class="recent-card p-3"
                                        onclick="showBookingDetails(
                                            {{ json_encode($booking->bookings) }},
                                            '{{ $booking->user->name }}',
                                            '{{ $booking->start_time->format('d/m/Y H:i') }}',
                                            '{{ $booking->end_time->format('H:i') }}',
                                            {{ $booking->can_cancel ? 'true' : 'false' }},
                                            {{ $booking->id }}
                                        )">
                                        <div style="display:flex; align-items:center; gap:0.65rem; margin-bottom:0.5rem;">
                                            <div class="booking-icon {{ $iconClass }}" style="width:36px;height:36px;min-width:36px;font-size:0.95rem;">
                                                <i class="fas {{ $icon }}"></i>
                                            </div>
                                            <span style="font-weight:600; font-size:0.88rem; color:#1e293b; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">
                                                {{ $mainTitle }}
                                            </span>
                                        </div>
                                        <div style="font-size:0.8rem; color:#64748b; line-height:1.7;">
                                            <i class="fas fa-calendar me-1" style="color:var(--teal);"></i>{{ $booking->start_time->format('d/m/Y') }}<br>
                                            <i class="fas fa-clock me-1" style="color:var(--teal);"></i>{{ $booking->start_time->format('H:i') }} – {{ $booking->end_time->format('H:i') }}
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            {{-- ══════════════════════════════════════════════
                 RIGHT COLUMN — Rooms & Equipment
            ══════════════════════════════════════════════ --}}
            <div class="col-lg-4">

                {{-- Rooms ── --}}
                <div class="r-card stagger-2 fade-in-up mb-4">
                    <div class="r-card-header navy">
                        <i class="fas fa-door-open"></i>
                        {{ __('messages.rooms') }}
                    </div>
                    <div class="scroll-area" style="max-height: 320px; overflow-y: auto;">
                        @foreach ($rooms as $room)
                            <a href="{{ route('assets.show', $room) }}" style="display:block; text-decoration:none;">
                                <div class="asset-item">
                                    <span>{{ $room->name }}</span>
                                    <span class="badge-status
                                        {{ $room->status === 'available' ? 'badge-available' : ($room->status === 'in_use' ? 'badge-in_use' : 'badge-default') }}">
                                        {{ __('messages.' . $room->status) }}
                                    </span>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>

                {{-- Equipment ── --}}
                <div class="r-card stagger-3 fade-in-up">
                    <div class="r-card-header navy">
                        <i class="fas fa-laptop"></i>
                        {{ __('messages.equipment') }}
                    </div>
                    <div class="scroll-area" style="max-height: 320px; overflow-y: auto;">
                        @foreach ($equipment as $item)
                            <a href="{{ route('assets.show', $item) }}" style="display:block; text-decoration:none;">
                                <div class="asset-item">
                                    <span>{{ $item->name }}</span>
                                    <span class="badge-status
                                        {{ $item->status === 'available' ? 'badge-available' : ($item->status === 'in_use' ? 'badge-in_use' : 'badge-default') }}">
                                        {{ __('messages.' . $item->status) }}
                                    </span>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>

            </div>{{-- /right col --}}
        </div>{{-- /row --}}
    </div>{{-- /fade-in-up --}}

    {{-- ── Floating Action Buttons ── --}}
    <button class="fab fab-book"
        data-bs-toggle="modal"
        data-bs-target="#bookingModal"
        title="{{ __('messages.book_asset') }}">
        <i class="fas fa-plus"></i>
    </button>

    <button class="fab fab-report"
        data-bs-toggle="modal"
        data-bs-target="#reportModal"
        title="{{ __('messages.report_problem') }}">
        <i class="fas fa-exclamation-triangle"></i>
    </button>

    {{-- ── Partials ── --}}
    @include('profile.partials.booking-modal')
    @include('profile.partials.report-modal')
    @include('profile.partials.booking-details-modal')

    <form id="cancelBookingForm" method="POST" style="display:none;">
        @csrf
        @method('DELETE')
    </form>

</x-app-layout>