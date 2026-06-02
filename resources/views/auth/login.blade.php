
<x-guest-layout>

    @if (session('status'))
        <div class="mb-4 text-sm font-medium text-green-600">
            {{ session('status') }}
        </div>
    @endif

    <form method="POST" action="{{ route('login') }}" novalidate>
        @csrf

        {{-- Email --}}
        <div>
            <label for="email" class="block text-sm font-medium text-gray-700">
                {{ __('messages.email') }}
            </label>
            <input
                id="email"
                type="email"
                name="email"
                value="{{ old('email') }}"
                required
                autofocus
                autocomplete="username"
                class="mt-1 block w-full rounded-lg border border-gray-300 px-3 py-2 text-sm text-gray-900 shadow-sm placeholder-gray-400
                       focus:outline-none focus:ring-2 focus:border-transparent transition"
                style="--tw-ring-color: #4c9183; focus-ring: #4c9183;"
                onfocus="this.style.boxShadow='0 0 0 3px rgba(76,145,131,0.35)'; this.style.borderColor='#4c9183';"
                onblur="this.style.boxShadow=''; this.style.borderColor='';"
            />
            @error('email')
                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        {{-- Password --}}
        <div class="mt-4">
            <label for="password" class="block text-sm font-medium text-gray-700">
                {{ __('messages.password') }}
            </label>
            <input
                id="password"
                type="password"
                name="password"
                required
                autocomplete="current-password"
                class="mt-1 block w-full rounded-lg border border-gray-300 px-3 py-2 text-sm text-gray-900 shadow-sm placeholder-gray-400
                       focus:outline-none transition"
                onfocus="this.style.boxShadow='0 0 0 3px rgba(76,145,131,0.35)'; this.style.borderColor='#4c9183';"
                onblur="this.style.boxShadow=''; this.style.borderColor='';"
            />
            @error('password')
                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        {{-- Remember Me --}}
        <div class="mt-4 flex items-center">
            <input
                id="remember_me"
                type="checkbox"
                name="remember"
                class="h-4 w-4 rounded border-gray-300 text-teal-600 focus:ring-2 focus:ring-offset-0 cursor-pointer"
                style="accent-color: #4c9183;"
            />
            <label for="remember_me" class="ms-2 text-sm text-gray-600 cursor-pointer select-none">
                {{ __('messages.remember_me') }}
            </label>
        </div>

        {{-- Submit --}}
        <div class="mt-6">
            <button
                type="submit"
                class="w-full rounded-lg px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:opacity-90 active:opacity-80 focus:outline-none focus:ring-2 focus:ring-offset-2"
                style="background-color: #154269; --tw-ring-color: #154269;"
            >
                {{ __('messages.login_btn') }}
            </button>
        </div>

    </form>

</x-guest-layout>