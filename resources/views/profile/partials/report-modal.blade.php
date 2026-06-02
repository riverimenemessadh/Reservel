<div class="modal fade" id="reportModal" tabindex="-1" aria-labelledby="reportModalLabel" aria-modal="true" role="dialog">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 540px;">
        <div class="modal-content overflow-hidden"
             style="border-radius: 1rem; border: none; box-shadow: 0 25px 60px rgba(0,0,0,0.22), 0 8px 24px rgba(0,0,0,0.12);">
            <form method="POST" action="{{ route('reports.store') }}" id="reportForm" novalidate>
                @csrf

                {{-- Header --}}
                <div class="modal-header border-0 px-5 py-4"
                     style="background: linear-gradient(135deg, #ae2e3c 0%, #8b1e2a 100%); border-radius: 1rem 1rem 0 0;">
                    <div class="d-flex align-items-center gap-3">
                        <div class="d-flex align-items-center justify-content-center flex-shrink-0"
                             style="width:40px;height:40px;background:rgba(255,255,255,0.15);border-radius:0.6rem;">
                            <i class="fas fa-exclamation-triangle" style="color:#fff;font-size:1.05rem;"></i>
                        </div>
                        <div>
                            <h5 class="modal-title mb-0 fw-bold" id="reportModalLabel"
                                style="color:#fff;font-size:1.1rem;letter-spacing:0.01em;">
                                {{ __('messages.report_problem') }}
                            </h5>
                            <p class="mb-0 mt-1" style="color:rgba(255,255,255,0.7);font-size:0.78rem;">
                                {{ __('messages.report_problem_subtitle') ?? 'Signalez un problème sur un équipement' }}
                            </p>
                        </div>
                    </div>
                    <button type="button"
                            class="btn-close btn-close-white ms-auto"
                            data-bs-dismiss="modal"
                            aria-label="Close"
                            style="opacity:0.8;"></button>
                </div>

                {{-- Body --}}
                <div class="modal-body px-5 py-4" style="background:#fff;">

                    {{-- Asset Select --}}
                    <div class="mb-4">
                        <label for="reportAsset" class="d-flex align-items-center gap-2 fw-semibold mb-2"
                               style="color:#1e293b;font-size:0.85rem;letter-spacing:0.03em;text-transform:uppercase;">
                            <span class="d-inline-flex align-items-center justify-content-center"
                                  style="width:22px;height:22px;background:#fce8ea;border-radius:0.35rem;">
                                <i class="fas fa-layer-group" style="color:#ae2e3c;font-size:0.65rem;"></i>
                            </span>
                            {{ __('messages.select_asset') }}
                            <span style="color:#ae2e3c;">*</span>
                        </label>

                        <div class="position-relative">
                            <select name="asset_id" id="reportAsset"
                                    class="form-select"
                                    required
                                    style="border:1.5px solid #e2e8f0;border-radius:0.65rem;padding:0.65rem 2.5rem 0.65rem 1rem;font-size:0.9rem;color:#334155;background-color:#f8fafc;transition:border-color .2s,box-shadow .2s;appearance:none;-webkit-appearance:none;outline:none;">
                                <option value="" disabled selected>{{ __('messages.asset_dropdown') }}</option>
                                @foreach (\App\Models\Asset::all() as $asset)
                                    @php
                                        $hasPendingReport = $asset->reports()->where('status', 'pending')->exists();
                                    @endphp
                                    <option value="{{ $asset->id }}"
                                            data-image="{{ $asset->image ?? '' }}"
                                            {{ $hasPendingReport ? 'disabled' : '' }}
                                            style="{{ $hasPendingReport ? 'color:#94a3b8;background:#f1f5f9;' : '' }}">
                                        {{ $asset->name }}
                                        ({{ __('messages.' . $asset->type) }})
                                        @if ($hasPendingReport)
                                            — {{ __('messages.already_reported') }}
                                        @endif
                                    </option>
                                @endforeach
                            </select>
                            <span class="position-absolute top-50 translate-middle-y pe-none"
                                  style="right:1rem;color:#94a3b8;pointer-events:none;">
                                <i class="fas fa-chevron-down" style="font-size:0.75rem;"></i>
                            </span>
                        </div>

                        <div class="invalid-feedback" id="assetError"
                             style="display:none;color:#ae2e3c;font-size:0.8rem;margin-top:0.35rem;">
                            <i class="fas fa-circle-exclamation me-1"></i>
                            {{ __('messages.select_asset') }} {{ __('messages.required') }}
                        </div>

                        {{-- Asset image preview --}}
                        <div id="assetPreview" class="mt-3"></div>
                    </div>

                    {{-- Problem Description --}}
                    <div class="mb-4">
                        <label for="problemDescription" class="d-flex align-items-center gap-2 fw-semibold mb-2"
                               style="color:#1e293b;font-size:0.85rem;letter-spacing:0.03em;text-transform:uppercase;">
                            <span class="d-inline-flex align-items-center justify-content-center"
                                  style="width:22px;height:22px;background:#fce8ea;border-radius:0.35rem;">
                                <i class="fas fa-pen-to-square" style="color:#ae2e3c;font-size:0.65rem;"></i>
                            </span>
                            {{ __('messages.problem_description') }}
                            <span style="color:#ae2e3c;">*</span>
                        </label>

                        <textarea name="problem_description"
                                  id="problemDescription"
                                  class="form-control"
                                  rows="4"
                                  required
                                  minlength="10"
                                  placeholder="{{ __('messages.describe_problem') }}"
                                  style="border:1.5px solid #e2e8f0;border-radius:0.65rem;padding:0.65rem 1rem;font-size:0.9rem;color:#334155;background:#f8fafc;resize:vertical;transition:border-color .2s,box-shadow .2s;outline:none;"></textarea>

                        <div class="d-flex justify-content-between align-items-center mt-1">
                            <div class="invalid-feedback" id="descriptionError"
                                 style="display:none;color:#ae2e3c;font-size:0.8rem;">
                                <i class="fas fa-circle-exclamation me-1"></i>
                                {{ __('messages.problem_description') }} {{ __('messages.required') }} (minimum 10 caractères)
                            </div>
                            <span id="charCount"
                                  class="ms-auto"
                                  style="font-size:0.75rem;color:#94a3b8;white-space:nowrap;">0 / 10 min</span>
                        </div>
                    </div>

                    {{-- Possible Cause --}}
                    <div class="mb-2">
                        <label for="possibleCause" class="d-flex align-items-center gap-2 fw-semibold mb-2"
                               style="color:#1e293b;font-size:0.85rem;letter-spacing:0.03em;text-transform:uppercase;">
                            <span class="d-inline-flex align-items-center justify-content-center"
                                  style="width:22px;height:22px;background:#e8f5f3;border-radius:0.35rem;">
                                <i class="fas fa-magnifying-glass" style="color:#4c9183;font-size:0.65rem;"></i>
                            </span>
                            {{ __('messages.cause') }}
                            <span style="font-size:0.75rem;color:#94a3b8;font-weight:400;text-transform:none;letter-spacing:0;">
                                ({{ __('messages.optional') ?? 'optionnel' }})
                            </span>
                        </label>

                        <textarea name="possible_cause"
                                  id="possibleCause"
                                  class="form-control"
                                  rows="2"
                                  placeholder="{{ __('messages.describe_cause') }}"
                                  style="border:1.5px solid #e2e8f0;border-radius:0.65rem;padding:0.65rem 1rem;font-size:0.9rem;color:#334155;background:#f8fafc;resize:vertical;transition:border-color .2s,box-shadow .2s;outline:none;"></textarea>
                    </div>

                </div>

                {{-- Footer --}}
                <div class="modal-footer border-0 px-5 py-4 gap-2" style="background:#f8fafc;border-radius:0 0 1rem 1rem;">
                    <button type="button"
                            class="btn px-4 py-2"
                            data-bs-dismiss="modal"
                            style="border:1.5px solid #cbd5e1;border-radius:0.65rem;color:#64748b;background:transparent;font-size:0.875rem;font-weight:500;transition:all .15s;">
                        <i class="fas fa-xmark me-2" style="font-size:0.8rem;"></i>
                        {{ __('messages.cancel') }}
                    </button>
                    <button type="submit"
                            class="btn px-5 py-2 ms-1"
                            style="background:linear-gradient(135deg,#ae2e3c,#8b1e2a);border:none;border-radius:0.65rem;color:#fff;font-size:0.875rem;font-weight:600;letter-spacing:0.02em;box-shadow:0 4px 14px rgba(174,46,60,0.35);transition:all .2s;">
                        <i class="fas fa-paper-plane me-2" style="font-size:0.8rem;"></i>
                        {{ __('messages.submit_report') }}
                    </button>
                </div>

            </form>
        </div>
    </div>
</div>

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const reportForm = document.getElementById('reportForm');
            const reportAssetSelect = document.getElementById('reportAsset');
            const problemDescription = document.getElementById('problemDescription');
            const charCount = document.getElementById('charCount');

            // Focus ring: teal for asset select and textareas
            const focusTeal = [reportAssetSelect, problemDescription, document.getElementById('possibleCause')];
            focusTeal.forEach(function (el) {
                if (!el) return;
                el.addEventListener('focus', function () {
                    this.style.borderColor = '#4c9183';
                    this.style.boxShadow = '0 0 0 3px rgba(76,145,131,0.15)';
                    this.style.backgroundColor = '#fff';
                });
                el.addEventListener('blur', function () {
                    if (!this.classList.contains('is-invalid')) {
                        this.style.borderColor = '#e2e8f0';
                        this.style.boxShadow = 'none';
                        this.style.backgroundColor = '#f8fafc';
                    }
                });
            });

            // Asset selection with image preview
            if (reportAssetSelect) {
                reportAssetSelect.addEventListener('change', function () {
                    const selectedOption = this.options[this.selectedIndex];
                    const imageUrl = selectedOption.getAttribute('data-image');
                    const preview = document.getElementById('assetPreview');

                    if (imageUrl) {
                        preview.innerHTML =
                            '<div style="border:1.5px solid #e2e8f0;border-radius:0.65rem;overflow:hidden;display:inline-block;background:#f8fafc;">' +
                            '<img src="' + imageUrl + '" alt="Asset" class="img-fluid" ' +
                            'style="max-height:90px;max-width:100%;display:block;object-fit:cover;">' +
                            '</div>';
                    } else {
                        preview.innerHTML = '';
                    }

                    // Remove invalid state on selection
                    this.classList.remove('is-invalid');
                    this.style.borderColor = '#4c9183';
                    this.style.boxShadow = '0 0 0 3px rgba(76,145,131,0.15)';
                    document.getElementById('assetError').style.display = 'none';
                });
            }

            // Character count for description
            if (problemDescription && charCount) {
                problemDescription.addEventListener('input', function () {
                    const len = this.value.trim().length;
                    charCount.textContent = len + ' / 10 min';
                    charCount.style.color = len >= 10 ? '#4c9183' : '#94a3b8';

                    if (len >= 10) {
                        this.classList.remove('is-invalid');
                        this.classList.add('is-valid');
                        this.style.borderColor = '#4c9183';
                        this.style.boxShadow = '0 0 0 3px rgba(76,145,131,0.15)';
                        document.getElementById('descriptionError').style.display = 'none';
                    } else {
                        this.classList.remove('is-valid');
                        this.style.borderColor = '#e2e8f0';
                        this.style.boxShadow = 'none';
                    }
                });
            }

            // Form validation
            if (reportForm) {
                reportForm.addEventListener('submit', function (e) {
                    let isValid = true;

                    // Validate asset selection
                    if (!reportAssetSelect.value) {
                        reportAssetSelect.classList.add('is-invalid');
                        reportAssetSelect.style.borderColor = '#ae2e3c';
                        reportAssetSelect.style.boxShadow = '0 0 0 3px rgba(174,46,60,0.12)';
                        document.getElementById('assetError').style.display = 'block';
                        isValid = false;
                    } else {
                        reportAssetSelect.classList.remove('is-invalid');
                        document.getElementById('assetError').style.display = 'none';
                    }

                    // Validate problem description
                    if (!problemDescription.value.trim() || problemDescription.value.trim().length < 10) {
                        problemDescription.classList.add('is-invalid');
                        problemDescription.style.borderColor = '#ae2e3c';
                        problemDescription.style.boxShadow = '0 0 0 3px rgba(174,46,60,0.12)';
                        document.getElementById('descriptionError').style.display = 'block';
                        isValid = false;
                    } else {
                        problemDescription.classList.remove('is-invalid');
                        document.getElementById('descriptionError').style.display = 'none';
                    }

                    if (!isValid) {
                        e.preventDefault();
                        e.stopPropagation();
                    }
                });

                // Real-time validation for description
                problemDescription.addEventListener('input', function () {
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
        #reportModal .modal-backdrop,
        .modal-backdrop {
            backdrop-filter: blur(3px);
            -webkit-backdrop-filter: blur(3px);
            background-color: rgba(15, 23, 42, 0.55) !important;
        }

        #reportModal .modal-content {
            animation: reportModalIn 0.22s cubic-bezier(0.34, 1.3, 0.64, 1) both;
        }

        @keyframes reportModalIn {
            from { opacity: 0; transform: scale(0.96) translateY(10px); }
            to   { opacity: 1; transform: scale(1)    translateY(0); }
        }

        #reportAsset option:disabled {
            color: #94a3b8;
            background: #f1f5f9;
        }

        #reportForm .form-control.is-invalid,
        #reportForm .form-select.is-invalid {
            border-color: #ae2e3c !important;
            box-shadow: 0 0 0 3px rgba(174, 46, 60, 0.12) !important;
            background-color: #fff8f8 !important;
        }

        #reportForm .form-control.is-valid,
        #reportForm .form-select.is-valid {
            border-color: #4c9183 !important;
            box-shadow: 0 0 0 3px rgba(76, 145, 131, 0.12) !important;
        }

        /* Override Bootstrap's default valid/invalid backgrounds */
        #reportForm .form-control,
        #reportForm .form-select {
            background-image: none !important;
        }

        .modal-footer .btn:first-child:hover {
            background: #f1f5f9 !important;
            border-color: #94a3b8 !important;
            color: #334155 !important;
        }

        .modal-footer button[type="submit"]:hover {
            background: linear-gradient(135deg, #c0303f, #9e2230) !important;
            box-shadow: 0 6px 18px rgba(174, 46, 60, 0.45) !important;
            transform: translateY(-1px);
        }

        .modal-footer button[type="submit"]:active {
            transform: translateY(0);
        }
    </style>
@endpush