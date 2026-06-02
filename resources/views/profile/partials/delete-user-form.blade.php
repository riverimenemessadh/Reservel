{{-- Warning notice --}}
<div style="display:flex; align-items:flex-start; gap:0.75rem; padding:1rem 1.1rem; background:#fff5f5; border:1.5px solid #fecdd3; border-radius:10px; margin-bottom:1.5rem;">
    <i class="fas fa-exclamation-triangle" style="color:#ae2e3c; font-size:1rem; margin-top:0.1rem; flex-shrink:0;"></i>
    <p style="margin:0; font-size:0.83rem; color:#7f1d1d; line-height:1.6;">
        {{ __('Once your account is deleted, all of its resources and data will be permanently deleted. Before deleting your account, please download any data or information that you wish to retain.') }}
    </p>
</div>

{{-- Delete trigger button --}}
<button
    type="button"
    x-data=""
    x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')"
    style="display:inline-flex; align-items:center; gap:0.45rem; padding:0.6rem 1.4rem; background:#ae2e3c; color:#fff; border:none; border-radius:8px; font-size:0.88rem; font-weight:600; letter-spacing:0.02em; cursor:pointer; transition:background 0.18s, box-shadow 0.18s;"
    onmouseover="this.style.background='#921f2c'; this.style.boxShadow='0 4px 14px rgba(174,46,60,0.3)';"
    onmouseout="this.style.background='#ae2e3c'; this.style.boxShadow='none';"
>
    <i class="fas fa-trash-alt"></i>{{ __('Delete Account') }}
</button>

{{-- Confirmation Modal (Alpine x-modal component) --}}
<x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>
    <form method="post" action="{{ route('profile.destroy') }}" style="padding:1.75rem 1.5rem;">
        @csrf
        @method('delete')

        {{-- Modal header --}}
        <div style="display:flex; align-items:center; gap:0.65rem; margin-bottom:1rem;">
            <div style="width:40px; height:40px; border-radius:50%; background:#fff0f0; display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                <i class="fas fa-exclamation-triangle" style="color:#ae2e3c; font-size:1rem;"></i>
            </div>
            <div>
                <h2 style="margin:0; font-size:1rem; font-weight:700; color:#1e293b;">
                    {{ __('Are you sure you want to delete your account?') }}
                </h2>
            </div>
        </div>

        <p style="font-size:0.83rem; color:#6b7280; margin:0 0 1.25rem; line-height:1.65;">
            {{ __('Once your account is deleted, all of its resources and data will be permanently deleted. Please enter your password to confirm you would like to permanently delete your account.') }}
        </p>

        {{-- Password --}}
        <div style="margin-bottom:1.25rem;">
            <label for="password" class="sr-only">{{ __('Password') }}</label>
            <input
                id="password"
                name="password"
                type="password"
                class="pf-input {{ $errors->userDeletion->get('password') ? 'is-invalid' : '' }}"
                placeholder="{{ __('Enter your password to confirm') }}"
                style="width:100%;"
            >
            @foreach ($errors->userDeletion->get('password') as $message)
                <p class="pf-error"><i class="fas fa-exclamation-circle"></i>{{ $message }}</p>
            @endforeach
        </div>

        {{-- Actions --}}
        <div style="display:flex; justify-content:flex-end; gap:0.75rem;">
            <button
                type="button"
                x-on:click="$dispatch('close')"
                style="display:inline-flex; align-items:center; gap:0.4rem; padding:0.6rem 1.2rem; background:#fff; color:#374151; border:1.5px solid #d1d5db; border-radius:8px; font-size:0.86rem; font-weight:500; cursor:pointer; transition:background 0.15s, border-color 0.15s;"
                onmouseover="this.style.background='#f8fafc'; this.style.borderColor='#9ca3af';"
                onmouseout="this.style.background='#fff'; this.style.borderColor='#d1d5db';"
            >
                <i class="fas fa-times"></i>{{ __('Cancel') }}
            </button>

            <button
                type="submit"
                style="display:inline-flex; align-items:center; gap:0.4rem; padding:0.6rem 1.2rem; background:#ae2e3c; color:#fff; border:none; border-radius:8px; font-size:0.86rem; font-weight:600; cursor:pointer; transition:background 0.15s, box-shadow 0.15s;"
                onmouseover="this.style.background='#921f2c'; this.style.boxShadow='0 4px 12px rgba(174,46,60,0.3)';"
                onmouseout="this.style.background='#ae2e3c'; this.style.boxShadow='none';"
            >
                <i class="fas fa-trash-alt"></i>{{ __('Delete Account') }}
            </button>
        </div>
    </form>
</x-modal>