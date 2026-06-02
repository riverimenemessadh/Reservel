@push('styles')
<style>
    .pf-label {
        display: block;
        font-size: 0.8rem;
        font-weight: 600;
        color: #374151;
        margin-bottom: 0.35rem;
        letter-spacing: 0.02em;
        text-transform: uppercase;
    }
    .pf-input {
        display: block;
        width: 100%;
        padding: 0.6rem 0.85rem;
        font-size: 0.9rem;
        color: #1e293b;
        background: #f8fafc;
        border: 1.5px solid #d1d5db;
        border-radius: 8px;
        outline: none;
        transition: border-color 0.18s, box-shadow 0.18s, background 0.18s;
        line-height: 1.5;
    }
    .pf-input:focus {
        border-color: #4c9183;
        background: #fff;
        box-shadow: 0 0 0 3px rgba(76,145,131,0.15);
    }
    .pf-input.is-invalid {
        border-color: #ae2e3c;
        background: #fff5f5;
    }
    .pf-input.is-invalid:focus {
        box-shadow: 0 0 0 3px rgba(174,46,60,0.12);
    }
    .pf-error {
        margin-top: 0.35rem;
        font-size: 0.78rem;
        color: #ae2e3c;
        display: flex;
        align-items: center;
        gap: 0.3rem;
    }
    .pf-btn-navy {
        display: inline-flex;
        align-items: center;
        gap: 0.45rem;
        padding: 0.6rem 1.4rem;
        background: #154269;
        color: #fff;
        border: none;
        border-radius: 8px;
        font-size: 0.88rem;
        font-weight: 600;
        letter-spacing: 0.02em;
        cursor: pointer;
        transition: background 0.18s, box-shadow 0.18s;
    }
    .pf-btn-navy:hover {
        background: #0f2f4d;
        box-shadow: 0 4px 14px rgba(21,66,105,0.25);
    }
    .pf-saved-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.3rem;
        font-size: 0.8rem;
        color: #166534;
        background: #f0fdf4;
        border: 1px solid #bbf7d0;
        border-radius: 999px;
        padding: 0.25rem 0.7rem;
        font-weight: 500;
    }
</style>
@endpush

<form id="send-verification" method="post" action="{{ route('verification.send') }}">
    @csrf
</form>

<form method="post" action="{{ route('profile.update') }}">
    @csrf
    @method('patch')

    <div style="display: flex; flex-direction: column; gap: 1.25rem;">

        {{-- Name --}}
        <div>
            <label for="name" class="pf-label">
                <i class="fas fa-user me-1" style="color:#4c9183;"></i>{{ __('Name') }}
            </label>
            <input
                id="name"
                name="name"
                type="text"
                class="pf-input {{ $errors->get('name') ? 'is-invalid' : '' }}"
                value="{{ old('name', $user->name) }}"
                required
                autofocus
                autocomplete="name"
            >
            @foreach ($errors->get('name') as $message)
                <p class="pf-error"><i class="fas fa-exclamation-circle"></i>{{ $message }}</p>
            @endforeach
        </div>

        {{-- Email --}}
        <div>
            <label for="email" class="pf-label">
                <i class="fas fa-envelope me-1" style="color:#4c9183;"></i>{{ __('Email') }}
            </label>
            <input
                id="email"
                name="email"
                type="email"
                class="pf-input {{ $errors->get('email') ? 'is-invalid' : '' }}"
                value="{{ old('email', $user->email) }}"
                required
                autocomplete="username"
            >
            @foreach ($errors->get('email') as $message)
                <p class="pf-error"><i class="fas fa-exclamation-circle"></i>{{ $message }}</p>
            @endforeach

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div style="margin-top:0.6rem; padding:0.75rem 1rem; background:#fffbeb; border:1px solid #fde68a; border-radius:8px;">
                    <p style="font-size:0.82rem; color:#92400e; margin:0;">
                        <i class="fas fa-exclamation-triangle me-1"></i>
                        {{ __('Your email address is unverified.') }}
                        <button form="send-verification"
                                style="background:none; border:none; padding:0; color:#92400e; text-decoration:underline; cursor:pointer; font-size:0.82rem;">
                            {{ __('Click here to re-send the verification email.') }}
                        </button>
                    </p>
                    @if (session('status') === 'verification-link-sent')
                        <p style="margin:0.4rem 0 0; font-size:0.8rem; color:#166534; font-weight:500;">
                            <i class="fas fa-check-circle me-1"></i>
                            {{ __('A new verification link has been sent to your email address.') }}
                        </p>
                    @endif
                </div>
            @endif
        </div>

        {{-- Save row --}}
        <div style="display:flex; align-items:center; gap:1rem; padding-top:0.25rem;">
            <button type="submit" class="pf-btn-navy">
                <i class="fas fa-save"></i>{{ __('Save') }}
            </button>

            @if (session('status') === 'profile-updated')
                <span
                    class="pf-saved-badge"
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                >
                    <i class="fas fa-check-circle"></i>{{ __('Saved.') }}
                </span>
            @endif
        </div>

    </div>
</form>