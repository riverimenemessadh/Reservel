<x-app-layout>
    <style>
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(18px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        .fade-in { animation: fadeIn 0.45s cubic-bezier(.4,0,.2,1) both; }

        .teal-focus:focus {
            outline: none;
            box-shadow: 0 0 0 3px rgba(76, 145, 131, 0.35);
            border-color: #4c9183;
        }
        .dropzone-active {
            border-color: #4c9183 !important;
            background-color: #f0faf8 !important;
        }
    </style>

    <div class="fade-in flex items-start justify-center min-h-screen py-12 px-4">
        <div class="w-full max-w-2xl">

            {{-- Card --}}
            <div class="bg-white shadow-xl overflow-hidden" style="border-radius: 12px;">

                {{-- Card Header --}}
                <div class="px-8 py-5 flex items-center gap-3" style="background-color: #154269;">
                    <div class="flex items-center justify-center w-9 h-9 rounded-full bg-white/15">
                        <i class="fas fa-layer-group text-white text-sm"></i>
                    </div>
                    <div>
                        <h4 class="text-white font-semibold text-lg leading-tight m-0">
                            {{ __('messages.add_new_asset') }}
                        </h4>
                        <p class="text-white/60 text-xs mt-0.5 m-0">{{ __('messages.fill_details_below') }}</p>
                    </div>
                </div>

                {{-- Thin teal accent stripe --}}
                <div class="h-1 w-full" style="background: linear-gradient(90deg, #4c9183 0%, #6dbfad 100%);"></div>

                {{-- Card Body --}}
                <div class="px-8 py-8">
                    <form method="POST" action="{{ route('assets.store') }}" enctype="multipart/form-data" novalidate>
                        @csrf

                        {{-- Name --}}
                        <div class="mb-6">
                            <label for="name" class="block text-sm font-semibold mb-1.5" style="color: #154269;">
                                <i class="fas fa-tag mr-1.5 text-xs" style="color: #4c9183;"></i>
                                {{ __('messages.name') }}
                            </label>
                            <input
                                type="text"
                                name="name"
                                id="name"
                                class="teal-focus w-full px-4 py-2.5 text-sm text-gray-800 bg-gray-50 border rounded-lg transition-all duration-200 @error('name') border-red-400 bg-red-50 @else border-gray-200 @enderror"
                                style="border-radius: 8px;"
                                value="{{ old('name') }}"
                                placeholder="{{ __('messages.enter_asset_name') }}"
                                required
                            >
                            @error('name')
                                <p class="mt-1.5 text-xs text-red-600 flex items-center gap-1">
                                    <i class="fas fa-circle-exclamation"></i> {{ $message }}
                                </p>
                            @enderror
                        </div>

                        {{-- Type --}}
                        <div class="mb-6">
                            <label for="type" class="block text-sm font-semibold mb-1.5" style="color: #154269;">
                                <i class="fas fa-shapes mr-1.5 text-xs" style="color: #4c9183;"></i>
                                {{ __('messages.type') }}
                            </label>
                            <div class="relative">
                                <select
                                    name="type"
                                    id="type"
                                    class="teal-focus appearance-none w-full px-4 py-2.5 text-sm text-gray-800 bg-gray-50 border rounded-lg transition-all duration-200 pr-10 @error('type') border-red-400 bg-red-50 @else border-gray-200 @enderror"
                                    style="border-radius: 8px;"
                                    required
                                >
                                    <option value="" disabled selected>-- {{ __('messages.select_type') }} --</option>
                                    <option value="room"      {{ old('type') == 'room'      ? 'selected' : '' }}>{{ __('messages.room') }}</option>
                                    <option value="equipment" {{ old('type') == 'equipment' ? 'selected' : '' }}>{{ __('messages.equipment_singular') }}</option>
                                </select>
                                <span class="pointer-events-none absolute inset-y-0 right-3 flex items-center text-gray-400">
                                    <i class="fas fa-chevron-down text-xs"></i>
                                </span>
                            </div>
                            @error('type')
                                <p class="mt-1.5 text-xs text-red-600 flex items-center gap-1">
                                    <i class="fas fa-circle-exclamation"></i> {{ $message }}
                                </p>
                            @enderror
                        </div>

                        {{-- Description --}}
                        <div class="mb-6">
                            <label for="description" class="block text-sm font-semibold mb-1.5" style="color: #154269;">
                                <i class="fas fa-align-left mr-1.5 text-xs" style="color: #4c9183;"></i>
                                {{ __('messages.description') }}
                            </label>
                            <textarea
                                name="description"
                                id="description"
                                rows="4"
                                class="teal-focus w-full px-4 py-2.5 text-sm text-gray-800 bg-gray-50 border rounded-lg transition-all duration-200 resize-none @error('description') border-red-400 bg-red-50 @else border-gray-200 @enderror"
                                style="border-radius: 8px;"
                                placeholder="{{ __('messages.enter_description') }}"
                            >{{ old('description') }}</textarea>
                            @error('description')
                                <p class="mt-1.5 text-xs text-red-600 flex items-center gap-1">
                                    <i class="fas fa-circle-exclamation"></i> {{ $message }}
                                </p>
                            @enderror
                        </div>

                        {{-- Image Upload Dropzone --}}
                        <div class="mb-6">
                            <label class="block text-sm font-semibold mb-1.5" style="color: #154269;">
                                <i class="fas fa-image mr-1.5 text-xs" style="color: #4c9183;"></i>
                                {{ __('messages.image') }}
                            </label>

                            <label
                                for="image"
                                id="dropzone"
                                class="flex flex-col items-center justify-center w-full cursor-pointer border-2 border-dashed border-gray-300 rounded-xl py-8 px-4 transition-all duration-200 hover:border-teal-500 hover:bg-teal-50/40 group"
                                style="border-radius: 12px;"
                            >
                                <div class="flex flex-col items-center gap-2 text-center pointer-events-none">
                                    <div class="w-12 h-12 rounded-full flex items-center justify-center mb-1 transition-colors duration-200"
                                         style="background-color: rgba(76,145,131,0.1);">
                                        <i class="fas fa-cloud-arrow-up text-xl" style="color: #4c9183;"></i>
                                    </div>
                                    <p class="text-sm font-semibold text-gray-700">
                                        {{ __('messages.image_selected') ?? 'Image selected' }}
                                        <span style="color: #4c9183;">{{ __('messages.browse') ?? 'browse' }}</span>
                                    </p>
                                    <p class="text-xs text-gray-400">{{ __('messages.image_support_info') }}</p>
                                </div>
                                <input
                                    type="file"
                                    name="image"
                                    id="image"
                                    class="sr-only @error('image') is-invalid @enderror"
                                    accept="image/*"
                                >
                            </label>

                            @error('image')
                                <p class="mt-1.5 text-xs text-red-600 flex items-center gap-1">
                                    <i class="fas fa-circle-exclamation"></i> {{ $message }}
                                </p>
                            @enderror

                            {{-- Image Preview --}}
                            <div id="imagePreview" class="mt-4" style="display: none;">
                                <div class="relative inline-block rounded-xl overflow-hidden shadow-md" style="border-radius: 10px;">
                                    <img
                                        id="previewImg"
                                        src=""
                                        alt="{{ __('messages.preview') }}"
                                        class="block max-h-48 w-auto object-cover"
                                    >
                                    <div class="absolute inset-0 bg-gradient-to-t from-black/20 to-transparent pointer-events-none"></div>
                                </div>
                                <p class="mt-1.5 text-xs text-gray-400 flex items-center gap-1">
                                    <i class="fas fa-check-circle" style="color: #4c9183;"></i>
                                    {{ __('messages.image_selected') ?? 'Image selected' }}
                                </p>
                            </div>
                        </div>

                        {{-- Divider --}}
                        <div class="border-t border-gray-100 mb-6"></div>

                        {{-- Actions --}}
                        <div class="flex items-center gap-3">
                            <button
                                type="submit"
                                class="inline-flex items-center gap-2 px-6 py-2.5 text-sm font-semibold text-white rounded-lg shadow-sm transition-all duration-200 hover:opacity-90 hover:shadow-md active:scale-95"
                                style="background-color: #154269; border-radius: 8px;"
                            >
                                <i class="fas fa-floppy-disk"></i>
                                {{ __('messages.create_asset') }}
                            </button>

                            <a
                                href="{{ route('assets.index') }}"
                                class="inline-flex items-center gap-2 px-6 py-2.5 text-sm font-semibold rounded-lg border-2 transition-all duration-200 hover:bg-gray-50 active:scale-95"
                                style="color: #154269; border-color: #154269; border-radius: 8px;"
                            >
                                <i class="fas fa-xmark"></i>
                                {{ __('messages.cancel') }}
                            </a>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
    document.getElementById('image').addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(event) {
                document.getElementById('previewImg').src = event.target.result;
                document.getElementById('imagePreview').style.display = 'block';
            };
            reader.readAsDataURL(file);
        }
    });

    // Dropzone drag-and-drop highlight
    const dropzone = document.getElementById('dropzone');
    ['dragenter', 'dragover'].forEach(evt => {
        dropzone.addEventListener(evt, e => { e.preventDefault(); dropzone.classList.add('dropzone-active'); });
    });
    ['dragleave', 'drop'].forEach(evt => {
        dropzone.addEventListener(evt, e => { e.preventDefault(); dropzone.classList.remove('dropzone-active'); });
    });
    </script>
    @endpush
</x-app-layout>