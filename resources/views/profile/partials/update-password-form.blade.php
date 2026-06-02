<form method="post" action="{{ route('password.update') }}">
    @csrf
    @method('put')

    <div style="display: flex; flex-direction: column; gap: 1.25rem;">

        {{-- Current Password --}}
        <div>
            <label for="update_password_current_password" class="pf-label">
                <i class="fas fa-key me-1" style="color:#4c9183;"></i>{{ __('Current Password') }}
            </label>
            <input
                id="update_password_current_password"
                name="current_password"
                type="password"
                class="pf-input {{ $errors->updatePassword->get('current_password') ? 'is-invalid' : '' }}"
                autocomplete="current-password"
            >
            @foreach ($errors->updatePassword->get('current_password') as $message)
                <p class="pf-error"><i class="fas fa-exclamation-circle"></i>{{ $message }}</p>
            @endforeach
        </div>

        {{-- New Password --}}
        <div>
            <label for="update_password_password" class="pf-label">
                <i class="fas fa-lock me-1" style="color:#4c9183;"></i>{{ __('New Password') }}
            </label>
            <input
                id="update_password_password"
                name="password"
                type="password"
                class="pf-input {{ $errors->updatePassword->get('password') ? 'is-invalid' : '' }}"
                autocomplete="new-password"
            >
            @foreach ($errors->updatePassword->get('password') as $message)
                <p class="pf-error"><i class="fas fa-exclamation-circle"></i>{{ $message }}</p>
            @endforeach
        </div>

        {{-- Confirm Password --}}
        <div>
            <label for="update_password_password_confirmation" class="pf-label">
                <i class="fas fa-lock me-1" style="color:#4c9183;"></i>{{ __('Confirm Password') }}
            </label>
            <input
                id="update_password_password_confirmation"
                name="password_confirmation"
                type="password"
                class="pf-input {{ $errors->updatePassword->get('password_confirmation') ? 'is-invalid' : '' }}"
                autocomplete="new-password"
            >
            @foreach ($errors->updatePassword->get('password_confirmation') as $message)
                <p class="pf-error"><i class="fas fa-exclamation-circle"></i>{{ $message }}</p>
            @endforeach
        </div>

        {{-- Save row --}}
        <div style="display:flex; align-items:center; gap:1rem; padding-top:0.25rem;">
            <button type="submit" class="pf-btn-navy">
                <i class="fas fa-save"></i>{{ __('Save') }}
            </button>

            @if (session('status') === 'password-updated')
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