<x-app-layout>
    <x-slot name="header">
        <div class="d-flex align-items-center gap-2">
            <i class="fas fa-user-circle" style="color:#154269; font-size:1.3rem;"></i>
            <h2 class="mb-0 fw-semibold" style="color:#154269; font-size:1.15rem; letter-spacing:0.01em;">
                {{ __('Profile') }}
            </h2>
        </div>
    </x-slot>

    @push('styles')
    <style>
        @keyframes profileFadeIn {
            from { opacity: 0; transform: translateY(18px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        .profile-wrapper {
            animation: profileFadeIn 0.45s cubic-bezier(0.22, 1, 0.36, 1) both;
        }
        .profile-card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 2px 16px rgba(21,66,105,0.08), 0 1px 4px rgba(21,66,105,0.06);
            overflow: hidden;
            margin-bottom: 1.5rem;
        }
        .profile-card:nth-child(2) { animation-delay: 0.07s; }
        .profile-card:nth-child(3) { animation-delay: 0.13s; }
        .profile-card:nth-child(4) { animation-delay: 0.19s; }
        .profile-card-header {
            padding: 0.85rem 1.4rem;
            display: flex;
            align-items: center;
            gap: 0.6rem;
        }
        .profile-card-header .header-icon {
            width: 32px;
            height: 32px;
            border-radius: 8px;
            background: rgba(255,255,255,0.15);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.85rem;
            color: #fff;
            flex-shrink: 0;
        }
        .profile-card-header h3 {
            margin: 0;
            font-size: 0.95rem;
            font-weight: 600;
            color: #fff;
            letter-spacing: 0.01em;
        }
        .profile-card-header p {
            margin: 0;
            font-size: 0.75rem;
            color: rgba(255,255,255,0.75);
            margin-top: 0.1rem;
        }
        .profile-card-body {
            background: #fff;
            padding: 1.75rem 1.5rem;
        }
    </style>
    @endpush

    <div class="profile-wrapper" style="max-width: 760px; margin: 2.5rem auto; padding: 0 1rem;">

        {{-- Profile Information Card --}}
        <div class="profile-card">
            <div class="profile-card-header" style="background: #154269;">
                <div class="header-icon"><i class="fas fa-id-card"></i></div>
                <div>
                    <h3>{{ __('Profile Information') }}</h3>
                    <p>{{ __("Update your account's profile information and email address.") }}</p>
                </div>
            </div>
            <div class="profile-card-body">
                @include('profile.partials.update-profile-information-form')
            </div>
        </div>

        {{-- Update Password Card --}}
        <div class="profile-card">
            <div class="profile-card-header" style="background: #154269;">
                <div class="header-icon"><i class="fas fa-lock"></i></div>
                <div>
                    <h3>{{ __('Update Password') }}</h3>
                    <p>{{ __('Ensure your account is using a long, random password to stay secure.') }}</p>
                </div>
            </div>
            <div class="profile-card-body">
                @include('profile.partials.update-password-form')
            </div>
        </div>

        {{-- Delete Account Card --}}
        <div class="profile-card">
            <div class="profile-card-header" style="background: #ae2e3c;">
                <div class="header-icon"><i class="fas fa-trash-alt"></i></div>
                <div>
                    <h3>{{ __('Delete Account') }}</h3>
                    <p>{{ __('Permanently remove your account and all associated data.') }}</p>
                </div>
            </div>
            <div class="profile-card-body">
                @include('profile.partials.delete-user-form')
            </div>
        </div>

    </div>
</x-app-layout>