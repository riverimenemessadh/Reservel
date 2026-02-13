<div class="modal fade" id="reportModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="{{ route('reports.store') }}" id="reportForm">
                @csrf
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title"><i
                            class="fas fa-exclamation-triangle me-2"></i>{{ __('messages.report_problem') }}</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="reportAsset" class="form-label fw-bold">
                            {{ __('messages.select_asset') }} <span class="text-danger"></span>
                        </label>
                        <select name="asset_id" id="reportAsset" class="form-select" required>
                            <option value="" disabled selected> {{ __('messages.asset_dropdown') }}</option>
                            @foreach (\App\Models\Asset::all() as $asset)
                                @php
                                    $hasPendingReport = $asset->reports()->where('status', 'pending')->exists();
                                @endphp
                                <option value="{{ $asset->id }}"
                                    data-image="{{ $asset->image ? asset('storage/' . $asset->image) : '' }}"
                                    {{ $hasPendingReport ? 'disabled' : '' }}>

                                    {{ $asset->name }}
                                    ({{ __('messages.' . $asset->type) }})
                                    @if ($hasPendingReport)
                                        - {{ __('messages.already_reported') }}
                                    @endif
                                </option>
                            @endforeach
                        </select>
                        <div class="invalid-feedback">
                            {{ __('messages.select_asset') }} {{ __('messages.required') }}
                        </div>
                        <div id="assetPreview" class="mt-2"></div>
                    </div>

                    <div class="mb-3">
                        <label for="problemDescription" class="form-label fw-bold">
                            {{ __('messages.problem_description') }} <span class="text-danger"></span>
                        </label>
                        <textarea name="problem_description" id="problemDescription" class="form-control" rows="4" required
                            minlength="10" placeholder="{{ __('messages.describe_problem') }}"></textarea>
                        <div class="invalid-feedback">
                            {{ __('messages.problem_description') }} {{ __('messages.required') }} (minimum 10 caractères)
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="possibleCause" class="form-label fw-bold"> {{ __('messages.cause') }}</label>
                        <textarea name="possible_cause" id="possibleCause" class="form-control" rows="2"
                            placeholder="{{ __('messages.describe_cause') }}"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary"
                        data-bs-dismiss="modal">{{ __('messages.cancel') }}</button>
                    <button type="submit" class="btn btn-danger">{{ __('messages.submit_report') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const reportForm = document.getElementById('reportForm');
            const reportAssetSelect = document.getElementById('reportAsset');
            const problemDescription = document.getElementById('problemDescription');

            // Asset selection with image preview
            if (reportAssetSelect) {
                reportAssetSelect.addEventListener('change', function() {
                    const selectedOption = this.options[this.selectedIndex];
                    const imageUrl = selectedOption.getAttribute('data-image');
                    const preview = document.getElementById('assetPreview');

                    if (imageUrl) {
                        preview.innerHTML = '<img src="' + imageUrl +
                            '" alt="Asset" class="img-fluid rounded shadow-sm" style="max-height: 100px;">';
                    } else {
                        preview.innerHTML = '';
                    }

                    // Remove invalid state on selection
                    this.classList.remove('is-invalid');
                });
            }

            // Form validation
            if (reportForm) {
                reportForm.addEventListener('submit', function(e) {
                    let isValid = true;

                    // Validate asset selection
                    if (!reportAssetSelect.value) {
                        reportAssetSelect.classList.add('is-invalid');
                        isValid = false;
                    } else {
                        reportAssetSelect.classList.remove('is-invalid');
                    }

                    // Validate problem description
                    if (!problemDescription.value.trim() || problemDescription.value.trim().length < 10) {
                        problemDescription.classList.add('is-invalid');
                        isValid = false;
                    } else {
                        problemDescription.classList.remove('is-invalid');
                    }

                    if (!isValid) {
                        e.preventDefault();
                        e.stopPropagation();
                    }
                });

                // Real-time validation for description
                problemDescription.addEventListener('input', function() {
                    if (this.value.trim().length >= 10) {
                        this.classList.remove('is-invalid');
                        this.classList.add('is-valid');
                    } else {
                        this.classList.remove('is-valid');
                    }
                });
            }
        });
    </script>
@endpush

@push('styles')
    <style>
        .is-invalid {
            border-color: #dc3545 !important;
        }
        .is-valid {
            border-color: #198754 !important;
        }
        .invalid-feedback {
            display: none;
            color: #dc3545;
            font-size: 0.875rem;
            margin-top: 0.25rem;
        }
        .is-invalid ~ .invalid-feedback {
            display: block;
        }
    </style>
@endpush
