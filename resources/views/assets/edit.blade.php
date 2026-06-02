<x-app-layout>
    <style>
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(18px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .fade-in {
            animation: fadeIn 0.45s cubic-bezier(.4, 0, .2, 1) both;
        }

        .teal-focus:focus {
            outline: none;
            border-color: #4c9183;
            box-shadow: 0 0 0 3px rgba(76, 145, 131, 0.18);
        }

        .btn-navy {
            background: #154269;
            color: #fff;
            transition: background 0.18s, box-shadow 0.18s;
        }

        .btn-navy:hover {
            background: #1a5284;
            box-shadow: 0 4px 16px rgba(21, 66, 105, 0.18);
        }

        .btn-ghost {
            background: transparent;
            color: #154269;
            border: 1.5px solid #154269;
            transition: background 0.18s, color 0.18s;
        }

        .btn-ghost:hover {
            background: #154269;
            color: #fff;
        }
    </style>

    <div class="min-h-screen bg-slate-50 py-10 px-4 fade-in">
        <div class="max-w-2xl mx-auto">

            {{-- Card --}}
            <div class="rounded-xl overflow-hidden shadow-lg" style="border-radius:12px;">

                {{-- Header --}}
                <div class="px-7 py-5 flex items-center gap-3" style="background:#154269;">
                    <span class="flex items-center justify-center w-9 h-9 rounded-lg bg-white/10 text-white text-lg">
                        <i class="fas fa-pen-to-square"></i>
                    </span>
                    <h2 class="text-white font-semibold text-xl tracking-wide m-0">
                        {{ __('messages.edit_asset') }}
                    </h2>
                </div>

                {{-- Body --}}
                <div class="bg-white px-7 py-8">
                    <form method="POST" action="{{ route('assets.update', $asset) }}"
                        enctype="multipart/form-data" novalidate>
                        @csrf
                        @method('PUT')

                        {{-- ── Existing image preview ── --}}
                        @if ($asset->image)
                            <div class="mb-7 flex flex-col items-center gap-3">
                                <p class="text-xs font-semibold uppercase tracking-widest text-slate-400">
                                    {{ __('messages.current_image') }}
                                </p>
                                <div class="relative group">
                                    <img src="{{ $asset->image }}" alt="{{ $asset->name }}"
                                        class="rounded-xl object-cover shadow-md"
                                        style="max-height:180px; max-width:100%; border-radius:12px;">
                                    <div class="absolute inset-0 rounded-xl bg-black/30 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center"
                                        style="border-radius:12px;">
                                        <span class="text-white text-xs font-medium">
                                            <i class="fas fa-image me-1"></i>Replace below
                                        </span>
                                    </div>
                                </div>
                            </div>
                        @endif

                        {{-- ── Name ── --}}
                        <div class="mb-5">
                            <label for="name" class="block text-sm font-semibold text-slate-700 mb-1.5">
                                <i class="fas fa-tag text-[#4c9183] me-1.5"></i>{{ __('messages.name') }}
                            </label>
                            <input type="text" name="name" id="name"
                                class="teal-focus w-full rounded-lg border border-slate-200 bg-slate-50 px-4 py-2.5 text-slate-800 text-sm transition @error('name') border-red-400 bg-red-50 @enderror"
                                value="{{ old('name', $asset->name) }}" required
                                placeholder="{{ __('messages.name') }}">
                            @error('name')
                                <p class="mt-1.5 text-xs text-red-500 flex items-center gap-1">
                                    <i class="fas fa-circle-exclamation"></i> {{ $message }}
                                </p>
                            @enderror
                        </div>

                        {{-- ── Type (immutable) ── --}}
                        <div class="mb-5">
                            <label class="block text-sm font-semibold text-slate-700 mb-1.5">
                                <i class="fas fa-shapes text-[#4c9183] me-1.5"></i>{{ __('messages.type') }}
                            </label>
                            <div class="relative">
                                <input type="text"
                                    class="w-full rounded-lg border border-slate-200 bg-slate-100 px-4 py-2.5 text-slate-500 text-sm cursor-not-allowed"
                                    value="{{ ucfirst(__('messages.' . $asset->type)) }}" disabled>
                                <span class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 text-xs">
                                    <i class="fas fa-lock"></i>
                                </span>
                            </div>
                            <p class="mt-1 text-xs text-slate-400">
                                <i class="fas fa-circle-info me-1"></i>{{ __('messages.type_immutable_notice') }}
                            </p>
                        </div>

                        {{-- ── Description ── --}}
                        <div class="mb-5">
                            <label for="description" class="block text-sm font-semibold text-slate-700 mb-1.5">
                                <i class="fas fa-align-left text-[#4c9183] me-1.5"></i>{{ __('messages.description') }}
                            </label>
                            <textarea name="description" id="description" rows="4"
                                class="teal-focus w-full rounded-lg border border-slate-200 bg-slate-50 px-4 py-2.5 text-slate-800 text-sm transition resize-none @error('description') border-red-400 bg-red-50 @enderror"
                                placeholder="{{ __('messages.description') }}...">{{ old('description', $asset->description) }}</textarea>
                            @error('description')
                                <p class="mt-1.5 text-xs text-red-500 flex items-center gap-1">
                                    <i class="fas fa-circle-exclamation"></i> {{ $message }}
                                </p>
                            @enderror
                        </div>

                        {{-- ── Image upload ── --}}
                        <div class="mb-7">
                            <label class="block text-sm font-semibold text-slate-700 mb-1.5">
                                <i class="fas fa-image text-[#4c9183] me-1.5"></i>{{ __('messages.change_image') }}
                            </label>
                            <input type="file" name="image" id="image" accept="image/*"
                                class="w-full rounded-lg border border-slate-200 bg-slate-50 px-4 py-2.5 text-sm text-slate-800">
                            <div id="imagePreview" class="mt-4" style="display:none;">
                                <img id="previewImg" src="" class="rounded-xl shadow-md"
                                    style="max-height:180px;">
                                <p class="mt-1.5 text-xs text-[#4c9183]">
                                    <i class="fas fa-check-circle me-1"></i>{{ __('messages.new_image_selected') }}
                                </p>
                            </div>
                            @error('image')
                                <p class="mt-1.5 text-xs text-red-500">
                                    <i class="fas fa-circle-exclamation"></i> {{ $message }}
                                </p>
                            @enderror
                        </div>

                        {{-- ── Actions ── --}}
                        <div class="flex gap-3 pt-1">
                            <button type="submit"
                                class="btn-navy flex items-center gap-2 px-6 py-2.5 rounded-lg text-sm font-semibold">
                                <i class="fas fa-floppy-disk"></i>
                                {{ __('messages.update_asset') }}
                            </button>
                            <a href="{{ route('assets.index') }}"
                                class="btn-ghost flex items-center gap-2 px-5 py-2.5 rounded-lg text-sm font-semibold no-underline">
                                <i class="fas fa-xmark"></i>
                                {{ __('messages.cancel') }}
                            </a>
                        </div>

                    </form>
                </div>{{-- /card-body --}}
            </div>{{-- /card --}}
        </div>
    </div>

    <script>
        document.getElementById('image').addEventListener('change', function (e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function (event) {
                    document.getElementById('previewImg').src = event.target.result;
                    document.getElementById('imagePreview').style.display = 'block';
                };
                reader.readAsDataURL(file);
            }
        });
    </script>
</x-app-layout>