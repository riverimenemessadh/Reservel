<x-app-layout>
    <style>
        .fade-in { animation: fadeIn 0.5s ease-in-out; }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(16px); } to { opacity: 1; transform: translateY(0); } }
    </style>

    <div class="fade-in max-w-6xl mx-auto px-4 py-8">
        <div class="rounded-[12px] shadow-md overflow-hidden bg-white flex flex-col lg:flex-row">

            {{-- LEFT: Image --}}
            <div class="lg:w-2/5 w-full flex-shrink-0">
                @if ($asset->image)
                    <img src="{{ $asset->image }}" alt="{{ $asset->name }}"
                        class="w-full h-72 lg:h-full object-cover cursor-pointer"
                        style="min-height: 320px;"
                        onclick="openImageLightbox('{{ $asset->image }}')">
                @else
                    <div class="w-full h-72 lg:h-full flex items-center justify-center bg-gray-100" style="min-height: 320px;">
                        <i class="fas fa-{{ $asset->type == 'room' ? 'building' : 'laptop' }} text-gray-300 text-7xl"></i>
                    </div>
                @endif
            </div>

            {{-- RIGHT: Details --}}
            <div class="lg:w-3/5 w-full p-8 flex flex-col gap-6">

                {{-- Title + Status --}}
                <div class="flex items-start justify-between gap-4">
                    <h2 class="text-2xl font-bold text-[#154269] flex items-center gap-2">
                        <i class="fas fa-{{ $asset->type == 'room' ? 'building' : 'laptop' }} text-[#4c9183]"></i>
                        {{ $asset->name }}
                    </h2>
                    <span class="flex-shrink-0 px-4 py-1 rounded-full text-sm font-semibold
                        @if ($asset->status === 'available') bg-green-100 text-green-700
                        @elseif ($asset->status === 'booked') bg-yellow-100 text-yellow-700
                        @elseif ($asset->status === 'maintenance') bg-red-100 text-red-700
                        @else bg-gray-100 text-gray-600 @endif">
                        <i class="fas fa-circle text-[0.5rem] mr-1 align-middle"></i>
                        {{ __('messages.' . $asset->status) }}
                    </span>
                </div>

                {{-- Type --}}
                <div>
                    <p class="text-xs uppercase font-semibold text-gray-400 mb-1">{{ __('messages.type') }}</p>
                    <span class="inline-block px-3 py-1 rounded-full text-sm font-medium
                        {{ $asset->type === 'room' ? 'bg-[#154269] text-white' : 'bg-[#4c9183] text-white' }}">
                        <i class="fas fa-{{ $asset->type === 'room' ? 'building' : 'laptop' }} mr-1"></i>
                        {{ ucfirst(__('messages.' . $asset->type)) }}
                    </span>
                </div>

                {{-- Description --}}
                @if ($asset->description)
                    <div>
                        <p class="text-xs uppercase font-semibold text-gray-400 mb-1">{{ __('messages.description') }}</p>
                        <p class="text-gray-600 leading-relaxed">{{ $asset->description }}</p>
                    </div>
                @endif

                {{-- Current Booking --}}
                @if ($asset->currentBooking)
                    <div class="rounded-[12px] border border-[#4c9183] bg-teal-50 p-4">
                        <h6 class="text-sm font-semibold text-[#4c9183] mb-2 flex items-center gap-2">
                            <i class="fas fa-info-circle"></i>
                            {{ __('messages.currently_booked') }}
                        </h6>
                        <p class="text-sm text-gray-700 mb-1">
                            <span class="font-semibold">{{ __('messages.booked_by') }}</span>
                            {{ $asset->currentBooking->user->name }}
                        </p>
                        <p class="text-sm text-gray-700">
                            <span class="font-semibold">{{ __('messages.time') }}</span>
                            {{ $asset->currentBooking->start_time->format('d/m/Y H:i') }} –
                            {{ $asset->currentBooking->end_time->format('H:i') }}
                        </p>
                    </div>
                @endif

                {{-- Pending Reports --}}
                @if ($asset->reports->count() > 0)
                    <div class="flex flex-col gap-3">
                        <h6 class="text-sm font-semibold text-red-600 flex items-center gap-2">
                            <i class="fas fa-exclamation-triangle"></i>
                            {{ __('messages.in_repair') }}
                        </h6>
                        @foreach ($asset->reports as $report)
                            <div class="rounded-[12px] border border-red-200 bg-red-50 p-4">
                                <p class="text-sm font-semibold text-gray-800 mb-1">{{ $report->problem_description }}</p>
                                <p class="text-xs text-gray-500">
                                    {{ __('messages.reported_by_on', [
                                        'name' => $report->user->name,
                                        'date' => $report->created_at->format('d/m/Y'),
                                    ]) }}
                                </p>
                            </div>
                        @endforeach
                    </div>
                @endif

                {{-- Actions --}}
                <div class="flex flex-wrap gap-3 mt-auto pt-2">
                    @if (auth()->user()->isAdmin())
                        <a href="{{ route('assets.edit', $asset) }}"
                            class="inline-flex items-center gap-2 px-5 py-2 rounded-[12px] bg-[#154269] text-white text-sm font-medium hover:bg-[#1a5280] transition">
                            <i class="fas fa-edit"></i>{{ __('messages.edit') }}
                        </a>
                    @endif
                    <a href="{{ route('assets.index') }}"
                        class="inline-flex items-center gap-2 px-5 py-2 rounded-[12px] bg-gray-100 text-gray-700 text-sm font-medium hover:bg-gray-200 transition">
                        <i class="fas fa-arrow-left"></i>{{ __('messages.back_to_assets') }}
                    </a>
                </div>

            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            function openImageLightbox(imageUrl) {
                const lightboxHtml = `
                    <div id="imageLightbox" class="position-fixed top-0 start-0 w-100 h-100 d-flex align-items-center justify-content-center"
                         style="background: rgba(0,0,0,0.9); z-index: 9999; cursor: pointer;"
                         onclick="this.remove()">
                        <img src="${imageUrl}" class="img-fluid" style="max-width: 90%; max-height: 90%; border-radius: 8px; box-shadow: 0 0 30px rgba(255,255,255,0.3);">
                        <button class="btn btn-light position-absolute top-0 end-0 m-3" onclick="document.getElementById('imageLightbox').remove(); event.stopPropagation();">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                `;
                document.body.insertAdjacentHTML('beforeend', lightboxHtml);
            }
        </script>
    @endpush
</x-app-layout>