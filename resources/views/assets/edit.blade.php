<x-app-layout>
    <div class="row justify-content-center fade-in">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0"><i class="fas fa-edit me-2"></i>{{ __('messages.edit_asset') }}</h4>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('assets.update', $asset) }}" enctype="multipart/form-data" novalidate>
                        @csrf
                        @method('PUT')

                        @if($asset->image)
                            <div class="mb-3 text-center">
                                <img src="{{ $asset->image }}" alt="{{ $asset->name }}" class="img-fluid rounded" style="max-height: 200px;">
                            </div>
                        @endif

                        <div class="mb-3">
                            <label for="name" class="form-label fw-bold">{{ __('messages.name') }}</label>
                            <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $asset->name) }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">{{ __('messages.type') }}</label>
                            <input type="text" class="form-control" value="{{ ucfirst(__('messages.' . $asset->type)) }}" disabled>
                            <small class="text-muted">{{ __('messages.type_immutable_notice') }}</small>
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label fw-bold">{{ __('messages.description') }}</label>
                            <textarea name="description" id="description" class="form-control @error('description') is-invalid @enderror" rows="4">{{ old('description', $asset->description) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="image" class="form-label fw-bold">{{ __('messages.change_image') }}</label>
                            <input type="file" name="image" id="image" class="form-control @error('image') is-invalid @enderror" accept="image/*">
                            @error('image')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>{{ __('messages.update_asset') }}
                            </button>
                            <a href="{{ route('assets.index') }}" class="btn btn-secondary">
                                <i class="fas fa-times me-2"></i>{{ __('messages.cancel') }}
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>