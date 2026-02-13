<x-app-layout>
    <div class="row justify-content-center fade-in">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm">
                @if ($asset->image)
                    <img src="{{ asset('storage/' . $asset->image) }}" alt="{{ $asset->name }}"
                        class="card-img-top cursor-pointer" style="max-height: 400px; object-fit: cover;"
                        onclick="openImageLightbox('{{ asset('storage/' . $asset->image) }}')">
                @else
                    <div class="bg-light d-flex align-items-center justify-content-center" style="height: 300px;">
                        <i class="fas fa-{{ $asset->type == 'room' ? 'building' : 'laptop' }} fa-5x text-muted"></i>
                    </div>
                @endif
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <h3 class="card-title mb-0">
                            <i class="fas fa-{{ $asset->type == 'room' ? 'building' : 'laptop' }} me-2"></i>
                            {{ $asset->name }}
                        </h3>
                        <span class="status-badge status-{{ $asset->status }}">
                            {{ __('messages.' . $asset->status) }}
                        </span>
                    </div>

                    <div class="mb-3">
                        <h6 class="text-muted">{{ __('messages.type') }}</h6>
                        <p><span
                                class="badge bg-{{ $asset->type === 'room' ? 'primary' : 'info' }}">{{ ucfirst(__('messages.' . $asset->type)) }}</span>
                        </p>
                    </div>

                    @if ($asset->description)
                        <div class="mb-3">
                            <h6 class="text-muted">{{ __('messages.description') }}</h6>
                            <p>{{ $asset->description }}</p>
                        </div>
                    @endif

                    @if ($asset->currentBooking)
                        <div class="alert alert-warning border-0 shadow-sm">
                            <h6><i class="fas fa-info-circle me-2"></i>{{ __('messages.currently_booked') }}</h6>
                            <p class="mb-1"><strong>{{ __('messages.booked_by') }}</strong>
                                {{ $asset->currentBooking->user->name }}</p>
                            <p class="mb-0">
                                <strong>{{ __('messages.time') }}</strong>
                                {{ $asset->currentBooking->start_time->format('d/m/Y H:i') }} -
                                {{ $asset->currentBooking->end_time->format('H:i') }}
                            </p>
                        </div>
                    @endif

                    @if ($asset->reports->count() > 0)
                        <div class="alert alert-danger border-0 shadow-sm">
                            <h6><i class="fas fa-exclamation-triangle me-2"></i>{{ __('messages.in_repair') }}</h6>
                            @foreach ($asset->reports as $report)
                                <div class="mb-2">
                                    <p class="mb-1"><strong>{{ $report->problem_description }}</strong></p>
                                    <small class="text-muted">
                                        {{ __('messages.reported_by_on', [
                                            'name' => $report->user->name,
                                            'date' => $report->created_at->format('d/m/Y'),
                                        ]) }}
                                    </small>
                                </div>
                            @endforeach
                        </div>
                    @endif

                    <div class="d-flex gap-2">
                        @if (auth()->user()->isAdmin())
                            <a href="{{ route('assets.edit', $asset) }}" class="btn btn-primary">
                                <i class="fas fa-edit me-2"></i>{{ __('messages.edit') }}
                            </a>
                        @endif
                        <a href="{{ route('assets.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-2"></i>{{ __('messages.back_to_assets') }}
                        </a>
                    </div>
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
