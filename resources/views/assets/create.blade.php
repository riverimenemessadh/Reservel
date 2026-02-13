<x-app-layout>
    <div class="row justify-content-center fade-in">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0"><i class="fas fa-plus-circle me-2"></i>{{ __('messages.add_new_asset') }}</h4>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('assets.store') }}" enctype="multipart/form-data" novalidate>
                        @csrf

                        <div class="mb-3">
                            <label for="name" class="form-label fw-bold">{{ __('messages.name') }}</label>
                            <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="type" class="form-label fw-bold">{{ __('messages.type') }}</label>
                            <select name="type" id="type" class="form-select @error('type') is-invalid @enderror" required>
                                <option value="" disabled selected>-- {{ __('messages.select_type') }} --</option>
                                <option value="room" {{ old('type') == 'room' ? 'selected' : '' }}>{{ __('messages.room') }}</option>
                                <option value="equipment" {{ old('type') == 'equipment' ? 'selected' : '' }}>{{ __('messages.equipment_singular') }}</option>
                            </select>
                            @error('type')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label fw-bold">{{ __('messages.description') }}</label>
                            <textarea name="description" id="description" class="form-control @error('description') is-invalid @enderror" rows="4">{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="image" class="form-label fw-bold">{{ __('messages.image') }}</label>
                            <input type="file" name="image" id="image" class="form-control @error('image') is-invalid @enderror" accept="image/*">
                            <small class="text-muted">{{ __('messages.image_support_info') }}</small>
                            @error('image')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div id="imagePreview" class="mb-3" style="display: none;">
                            <img id="previewImg" src="" alt="{{ __('messages.preview') }}" class="img-fluid rounded shadow-sm" style="max-height: 200px;">
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>{{ __('messages.create_asset') }}
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
    </script>
    @endpush
</x-app-layout>