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

        .dropzone-area {
            border: 2px dashed #cbd5e1;
            transition: border-color 0.2s, background 0.2s;
        }

        .dropzone-area:hover,
        .dropzone-area.dragover {
            border-color: #4c9183;
            background: rgba(76, 145, 131, 0.04);
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
                    <form method="POST" action="{{ route('assets.update', $asset) }}" enctype="multipart/form-data"
                        novalidate x-data="assetEditForm()">
                        @csrf
                        @method('PUT')

                        {{-- ── Existing image preview ── --}}
                        @if ($asset->image)
                            <div class="mb-7 flex flex-col items-center gap-3">
                                <p class="text-xs font-semibold uppercase tracking-widest text-slate-400">
                                    {{ __('messages.current_image') }}
                                </p>
                                <div class="relative group">
                                    <img id="current-asset-image" src="{{ $asset->image }}" alt="{{ $asset->name }}"
                                        class="rounded-xl object-cover shadow-md"
                                        style="max-height:180px; max-width:100%; border-radius:12px;">
                                    <div class="absolute inset-0 rounded-xl bg-black/30 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center"
                                        style="border-radius:12px;">
                                        <span class="text-white text-xs font-medium"><i class="fas fa-image me-1"></i>
                                            Replace below</span>
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

                        {{-- ── Image upload dropzone ── --}}
                        <div class="mb-7">
                            <label class="block text-sm font-semibold text-slate-700 mb-1.5">
                                <i class="fas fa-image text-[#4c9183] me-1.5"></i>{{ __('messages.change_image') }}
                            </label>

                            <div class="dropzone-area rounded-xl p-6 text-center cursor-pointer relative"
                                style="border-radius:12px;" @dragover.prevent="$el.classList.add('dragover')"
                                @dragleave.prevent="$el.classList.remove('dragover')"
                                @drop.prevent="handleDrop($event); $el.classList.remove('dragover')"
                                @click="$refs.imageInput.click()">
                                <input type="file" name="image" id="image" x-ref="imageInput"
                                    class="hidden @error('image') border-red-400 @enderror" accept="image/*"
                                    @change="previewImage($event)">
                                <input type="hidden" name="image" x-ref="imageBase64">
                                {{-- Default prompt --}}
                                <div x-show="!newPreview" class="pointer-events-none">
                                    <div
                                        class="mx-auto mb-3 flex items-center justify-center w-12 h-12 rounded-full bg-slate-100">
                                        <i class="fas fa-cloud-arrow-up text-[#4c9183] text-xl"></i>
                                    </div>
                                    <p class="text-slate-600 text-sm font-medium">{{ __('messages.drag_drop_or') }}
                                        <span class="text-[#4c9183] font-semibold">{{ __('messages.browse') }}</span>
                                    </p>
                                    <p class="text-slate-400 text-xs mt-1">{{ __('messages.image_formats') }}</p>
                                </div>

                                {{-- New image preview --}}
                                <div x-show="newPreview" class="pointer-events-none flex flex-col items-center gap-2">
                                    <img :src="newPreview" alt="{{ __('messages.new_preview') }}" ...>
                                    <p ...><i class="fas fa-check-circle me-1"></i>
                                        {{ __('messages.new_image_selected') }}</p>
                                </div>
                            </div>

                            @error('image')
                                <p class="mt-1.5 text-xs text-red-500 flex items-center gap-1">
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
        function assetEditForm() {
            return {
                newPreview: null,

                previewImage(event) {
                    const file = event.target.files[0];
                    if (!file) return;
                    const reader = new FileReader();
                    reader.onload = (e) => {
                        this.newPreview = e.target.result;
                        this.$refs.imageBase64.value = e.target.result;
                    };
                    reader.readAsDataURL(file);
                },

                handleDrop(event) {
                    const file = event.dataTransfer.files[0];
                    if (!file || !file.type.startsWith('image/')) return;

                    // Transfer to the real file input
                    const dt = new DataTransfer();
                    dt.items.add(file);
                    this.$refs.imageInput.files = dt.files;

                    const reader = new FileReader();
                    reader.onload = (e) => {
                        this.newPreview = e.target.result;
                        this.$refs.imageBase64.value = e.target.result;
                    };
                    reader.readAsDataURL(file);
                }
            }
        }
    </script>
</x-app-layout>
