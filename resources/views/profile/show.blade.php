<x-app-layout>

@push('styles')
<style>
    @import url('https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=DM+Mono:wght@400;500&display=swap');

    .profile-page * {
        font-family: 'DM Sans', sans-serif;
    }

    .fade-in {
        animation: fadeInUp 0.55s cubic-bezier(0.22, 1, 0.36, 1) both;
    }

    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(22px); }
        to   { opacity: 1; transform: translateY(0); }
    }

    .stagger-1 { animation-delay: 0.05s; }
    .stagger-2 { animation-delay: 0.13s; }
    .stagger-3 { animation-delay: 0.21s; }
    .stagger-4 { animation-delay: 0.29s; }

    .booking-card {
        border-radius: 12px;
        border-left: 4px solid #4c9183;
        transition: box-shadow 0.2s ease, transform 0.2s ease;
    }
    .booking-card:hover {
        box-shadow: 0 8px 28px rgba(21, 66, 105, 0.10);
        transform: translateY(-2px);
    }

    .avatar-circle {
        width: 80px; height: 80px;
        background: #154269;
        border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        font-size: 2rem; font-weight: 700; color: #fff;
        letter-spacing: -1px; flex-shrink: 0;
        box-shadow: 0 4px 18px rgba(21, 66, 105, 0.22);
    }

    .profile-card {
        border-radius: 12px; border: 1px solid #e8edf3;
        background: #fff; box-shadow: 0 2px 14px rgba(21, 66, 105, 0.07);
    }

    .section-card {
        border-radius: 12px; border: 1px solid #e8edf3;
        background: #fff; box-shadow: 0 2px 14px rgba(21, 66, 105, 0.07);
    }

    .time-pill {
        font-family: 'DM Mono', monospace;
        background: #eaf1f8; color: #154269;
        font-size: 0.80rem; font-weight: 500;
        padding: 3px 12px; border-radius: 999px;
        display: inline-flex; align-items: center; gap: 5px;
    }

    .asset-chip {
        display: inline-flex; align-items: center; gap: 6px;
        font-size: 0.82rem; font-weight: 500;
        padding: 5px 13px; border-radius: 8px;
    }
    .asset-chip.room      { background: #eaf1f8; color: #154269; }
    .asset-chip.equipment { background: #e6f3f1; color: #306b5f; }

    .status-badge {
        font-size: 0.72rem; font-weight: 600;
        text-transform: uppercase; letter-spacing: 0.06em;
        padding: 2px 10px; border-radius: 999px;
        background: #e6f3f1; color: #2e7d6d;
        display: inline-flex; align-items: center; gap: 4px;
    }

    .page-header-icon {
        background: #154269; color: #fff;
        width: 44px; height: 44px; border-radius: 11px;
        display: flex; align-items: center; justify-content: center;
        flex-shrink: 0;
    }

    .detail-row {
        display: flex; align-items: center; gap: 10px;
        padding: 10px 0; border-bottom: 1px solid #f0f4f8;
    }
    .detail-row:last-child { border-bottom: none; padding-bottom: 0; }
    .detail-label {
        font-size: 0.73rem; font-weight: 600;
        text-transform: uppercase; letter-spacing: 0.07em;
        color: #9aabba; width: 68px; flex-shrink: 0;
    }
    .detail-value { font-size: 0.95rem; font-weight: 500; color: #1e3449; }
</style>
@endpush

<div class="profile-page fade-in" style="max-width: 720px; margin: 0 auto; padding: 32px 16px 64px;">

    {{-- Page Header --}}
    <div class="fade-in stagger-1 d-flex align-items-center gap-3 mb-4">
        <div class="page-header-icon">
            {{-- user icon --}}
            <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="white" viewBox="0 0 24 24">
                <path d="M12 12c2.7 0 4.8-2.1 4.8-4.8S14.7 2.4 12 2.4 7.2 4.5 7.2 7.2 9.3 12 12 12zm0 2.4c-3.2 0-9.6 1.6-9.6 4.8v2.4h19.2v-2.4c0-3.2-6.4-4.8-9.6-4.8z"/>
            </svg>
        </div>
        <div>
            <h1 style="font-size:1.45rem; font-weight:700; color:#154269; margin:0; line-height:1.2;">
                {{ __('messages.my_profile') }}
            </h1>
            <p style="font-size:0.82rem; color:#9aabba; margin:0; margin-top:2px;">
                {{ __('messages.profile_subtitle') ?? 'Your account details and today\'s bookings' }}
            </p>
        </div>
    </div>

    {{-- Profile Card --}}
    <div class="profile-card p-4 mb-4 fade-in stagger-2">
        <div class="d-flex align-items-center gap-4">
            <div class="avatar-circle">
                {{ strtoupper(mb_substr($user->name, 0, 1)) }}
            </div>

            <div style="flex:1; min-width:0;">
                <h2 style="font-size:1.25rem; font-weight:700; color:#154269; margin:0 0 2px;">
                    {{ $user->name }}
                </h2>
                <p style="font-size:0.88rem; color:#6b8299; margin:0 0 10px; display:flex; align-items:center; gap:5px;">
                    {{-- envelope icon --}}
                    <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" fill="#6b8299" viewBox="0 0 24 24">
                        <path d="M20 4H4c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 4-8 5-8-5V6l8 5 8-5v2z"/>
                    </svg>
                    {{ $user->email }}
                </p>

                {{-- Role Badge --}}
                @if($user->isAdmin())
                    <span style="display:inline-flex; align-items:center; gap:6px; background:#154269; color:#fff; font-size:0.75rem; font-weight:600; text-transform:uppercase; letter-spacing:0.07em; padding:3px 12px; border-radius:999px;">
                        {{-- shield icon --}}
                        <svg xmlns="http://www.w3.org/2000/svg" width="11" height="11" fill="white" viewBox="0 0 24 24">
                            <path d="M12 1L3 5v6c0 5.55 3.84 10.74 9 12 5.16-1.26 9-6.45 9-12V5l-9-4z"/>
                        </svg>
                        {{ __('messages.' . $user->role) }}
                    </span>
                @else
                    <span style="display:inline-flex; align-items:center; gap:6px; background:#4c9183; color:#fff; font-size:0.75rem; font-weight:600; text-transform:uppercase; letter-spacing:0.07em; padding:3px 12px; border-radius:999px;">
                        {{-- person/teacher icon --}}
                        <svg xmlns="http://www.w3.org/2000/svg" width="11" height="11" fill="white" viewBox="0 0 24 24">
                            <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
                        </svg>
                        {{ __('messages.' . $user->role) }}
                    </span>
                @endif
            </div>
        </div>

        <div style="margin-top:20px; border-top:1px solid #f0f4f8; padding-top:16px;">
            <div class="detail-row">
                <span class="detail-label">{{ __('messages.name') }}</span>
                <span class="detail-value">{{ $user->name }}</span>
            </div>
            <div class="detail-row">
                <span class="detail-label">{{ __('messages.email') }}</span>
                <span class="detail-value">{{ $user->email }}</span>
            </div>
            <div class="detail-row">
                <span class="detail-label">{{ __('messages.role') }}</span>
                <span class="detail-value">{{ __('messages.' . $user->role) }}</span>
            </div>
        </div>
    </div>

    {{-- Today's Bookings --}}
    <div class="section-card p-4 fade-in stagger-3">
        <div class="d-flex align-items-center gap-2 mb-3">
            {{-- calendar icon --}}
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="#4c9183" viewBox="0 0 24 24">
                <path d="M20 3h-1V1h-2v2H7V1H5v2H4C2.9 3 2 3.9 2 5v16c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm0 18H4V8h16v13z"/>
            </svg>
            <h3 style="font-size:1.05rem; font-weight:700; color:#154269; margin:0;">
                {{ __('messages.todays_bookings') }}
            </h3>
        </div>

        @php
            $groupedBookings = $bookings->groupBy(function ($booking) {
                return $booking->start_time->format('Y-m-d H:i') . '-' . $booking->end_time->format('H:i');
            });
        @endphp

        @forelse($groupedBookings as $timeSlot => $slotBookings)
            <div class="booking-card p-3 mb-3 fade-in stagger-4" style="background:#fafcff;">
                <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-2">
                    <span class="time-pill">
                        {{-- clock icon --}}
                        <svg xmlns="http://www.w3.org/2000/svg" width="11" height="11" fill="#154269" viewBox="0 0 24 24">
                            <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm.5 5v5.25l4.5 2.67-.75 1.23L11 13V7h1.5z"/>
                        </svg>
                        {{ $slotBookings->first()->start_time->format('H:i') }}
                        &ndash;
                        {{ $slotBookings->first()->end_time->format('H:i') }}
                    </span>
                    <span class="status-badge">
                        {{-- check circle icon --}}
                        <svg xmlns="http://www.w3.org/2000/svg" width="11" height="11" fill="#2e7d6d" viewBox="0 0 24 24">
                            <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/>
                        </svg>
                        {{ __('messages.active') }}
                    </span>
                </div>

                <div class="d-flex flex-wrap gap-2 mt-2">
                    @foreach ($slotBookings as $booking)
                        @php
                            $isRoom = $booking->asset->type === 'room';
                            $chip   = $isRoom ? 'room' : 'equipment';
                        @endphp
                        <span class="asset-chip {{ $chip }}">
                            @if($isRoom)
                                {{-- building icon --}}
                                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M17 11V3H7v4H3v14h8v-4h2v4h8V11h-4zm-6 4H9v-2h2v2zm0-4H9V9h2v2zm0-4H9V5h2v2zm4 8h-2v-2h2v2zm0-4h-2V9h2v2zm4 8h-2v-2h2v2zm0-4h-2v-2h2v2z"/>
                                </svg>
                            @else
                                {{-- laptop icon --}}
                                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M20 18c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2H4c-1.1 0-2 .9-2 2v10c0 1.1.9 2 2 2H0v2h24v-2h-4zM4 6h16v10H4V6z"/>
                                </svg>
                            @endif
                            {{ $booking->asset->name }}
                        </span>
                    @endforeach
                </div>
            </div>
        @empty
            <div style="border-radius:10px; background:#f4f8fb; border:1px dashed #c9d9e8; padding:28px 20px; text-align:center;">
                {{-- calendar-x icon --}}
                <svg xmlns="http://www.w3.org/2000/svg" width="36" height="36" fill="#b8cad8" viewBox="0 0 24 24" style="display:block; margin: 0 auto 10px;">
                    <path d="M20 3h-1V1h-2v2H7V1H5v2H4C2.9 3 2 3.9 2 5v16c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm0 18H4V8h16v13zm-8-5.5 2.5 2.5 1.5-1.5-2.5-2.5 2.5-2.5-1.5-1.5-2.5 2.5-2.5-2.5-1.5 1.5 2.5 2.5-2.5 2.5 1.5 1.5 2.5-2.5z"/>
                </svg>
                <p style="font-size:0.92rem; font-weight:600; color:#6b8299; margin:0;">
                    {{ __('messages.no_bookings_today') }}
                </p>
            </div>
        @endforelse
    </div>

    {{-- Back Button --}}
    <div class="mt-4 fade-in stagger-4">
        <a href="{{ route('dashboard') }}"
           style="display:inline-flex; align-items:center; gap:8px; background:#154269; color:#fff; font-size:0.88rem; font-weight:600; padding:10px 22px; border-radius:9px; text-decoration:none; transition:background 0.18s, box-shadow 0.18s; box-shadow:0 3px 12px rgba(21,66,105,0.18);"
           onmouseover="this.style.background='#1a5080'"
           onmouseout="this.style.background='#154269'">
            {{-- arrow-left icon --}}
            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="white" viewBox="0 0 24 24">
                <path d="M20 11H7.83l5.59-5.59L12 4l-8 8 8 8 1.41-1.41L7.83 13H20v-2z"/>
            </svg>
            {{ __('messages.back_dashboard') }}
        </a>
    </div>

</div>

@push('scripts')
{{-- No additional scripts required --}}
@endpush

</x-app-layout>