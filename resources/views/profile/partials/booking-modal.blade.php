<div class="modal fade" id="bookingModal" tabindex="-1" aria-labelledby="bookingModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" style="max-width: 680px;">
        <div class="modal-content overflow-hidden"
             style="border-radius: 1rem; border: none; box-shadow: 0 25px 60px rgba(0,0,0,0.35);">

            <form method="POST" action="{{ route('bookings.store') }}" id="bookingForm" novalidate>
                @csrf

                {{-- ── Header ── --}}
                <div class="modal-header border-0 px-6 py-4"
                     style="background: #154269; border-radius: 1rem 1rem 0 0;">
                    <div class="d-flex align-items-center gap-3">
                        <div class="d-flex align-items-center justify-content-center rounded-circle"
                             style="width:40px;height:40px;background:rgba(255,255,255,0.12);">
                            <i class="fas fa-calendar-plus" style="color:#7ecfc3;font-size:1rem;"></i>
                        </div>
                        <div>
                            <h5 class="modal-title mb-0 fw-semibold text-white" id="bookingModalLabel"
                                style="letter-spacing:-0.02em;font-size:1.1rem;">
                                {{ __('messages.book_asset') }}
                            </h5>
                        </div>
                    </div>
                    <button type="button"
                            class="d-flex align-items-center justify-content-center rounded-circle border-0"
                            data-bs-dismiss="modal"
                            style="width:32px;height:32px;background:rgba(255,255,255,0.12);color:#fff;font-size:1rem;line-height:1;cursor:pointer;">
                        <i class="fas fa-times"></i>
                    </button>
                </div>

                {{-- ── Body ── --}}
                <div class="modal-body px-5 py-4" style="background:#fff;">

                    {{-- Divider label --}}
                    <div class="d-flex align-items-center gap-2 mb-3">
                        <i class="fas fa-clock" style="color:#4c9183;font-size:0.85rem;"></i>
                        <span class="fw-semibold text-uppercase"
                              style="font-size:0.7rem;letter-spacing:0.1em;color:#154269;">
                            {{ __('messages.desired_book_time') }}
                        </span>
                        <div class="flex-grow-1" style="height:1px;background:#e5eaf0;"></div>
                    </div>

                    {{-- Date + Time row --}}
                    <div class="row g-3 mb-1">
                        <div class="col-md-4">
                            <label class="form-label d-flex align-items-center gap-1 mb-1"
                                   style="font-size:0.75rem;color:#5a6a7e;font-weight:500;">
                                <i class="fas fa-calendar-alt" style="color:#4c9183;font-size:0.7rem;"></i>
                                {{ __('messages.date') }}
                            </label>
                            <input type="date" name="date" required
                                   min="{{ date('Y-m-d') }}"
                                   class="form-control reservel-input"
                                   style="border-radius:0.5rem;border:1.5px solid #d1dbe8;font-size:0.875rem;padding:0.5rem 0.75rem;color:#154269;transition:border-color .2s,box-shadow .2s;">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label d-flex align-items-center gap-1 mb-1"
                                   style="font-size:0.75rem;color:#5a6a7e;font-weight:500;">
                                <i class="fas fa-play-circle" style="color:#4c9183;font-size:0.7rem;"></i>
                                {{ __('messages.start_time') }}
                            </label>
                            <input type="text" id="start_time_only" class="form-control reservel-input"
                                   placeholder="HH:MM" maxlength="5" required
                                   style="border-radius:0.5rem;border:1.5px solid #d1dbe8;font-size:0.875rem;padding:0.5rem 0.75rem;color:#154269;letter-spacing:0.05em;transition:border-color .2s,box-shadow .2s;">
                            <div id="start_time_error" class="mt-1 d-flex align-items-center gap-1"
                                 style="display:none!important;font-size:0.72rem;color:#dc3545;">
                                <i class="fas fa-exclamation-circle"></i>
                                <span></span>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label d-flex align-items-center gap-1 mb-1"
                                   style="font-size:0.75rem;color:#5a6a7e;font-weight:500;">
                                <i class="fas fa-stop-circle" style="color:#4c9183;font-size:0.7rem;"></i>
                                {{ __('messages.end_time') }}
                            </label>
                            <input type="text" id="end_time_only" class="form-control reservel-input"
                                   placeholder="HH:MM" maxlength="5" required
                                   style="border-radius:0.5rem;border:1.5px solid #d1dbe8;font-size:0.875rem;padding:0.5rem 0.75rem;color:#154269;letter-spacing:0.05em;transition:border-color .2s,box-shadow .2s;">
                            <div id="end_time_error" class="mt-1 d-flex align-items-center gap-1"
                                 style="display:none!important;font-size:0.72rem;color:#dc3545;">
                                <i class="fas fa-exclamation-circle"></i>
                                <span></span>
                            </div>
                        </div>
                    </div>
                    <p class="mb-4" style="font-size:0.7rem;color:#8fa0b3;">
                        <i class="fas fa-info-circle me-1" style="color:#4c9183;"></i>
                        {{ __('messages.allowed_range') }}
                    </p>

                    {{-- Room section --}}
                    <div class="d-flex align-items-center gap-2 mb-3">
                        <i class="fas fa-door-open" style="color:#4c9183;font-size:0.85rem;"></i>
                        <span class="fw-semibold text-uppercase"
                              style="font-size:0.7rem;letter-spacing:0.1em;color:#154269;">
                            {{ __('messages.select_room') }}
                        </span>
                        <div class="flex-grow-1" style="height:1px;background:#e5eaf0;"></div>
                    </div>

                    <div class="mb-4">
                        <select name="asset_ids[]" id="roomSelect"
                                class="form-select reservel-input"
                                style="border-radius:0.5rem;border:1.5px solid #d1dbe8;font-size:0.875rem;padding:0.5rem 0.75rem;color:#154269;transition:border-color .2s,box-shadow .2s;">
                            <option value="" disabled selected>{{ __('messages.room_dropdown') }}</option>
                            @foreach (\App\Models\Asset::where('type', 'room')->where('status', 'available')->get() as $room)
                                <option value="{{ $room->id }}"
                                        data-image="{{ $room->image ?? '' }}"
                                        data-name="{{ $room->name }}">
                                    {{ $room->name }}
                                </option>
                            @endforeach
                        </select>

                        <div id="roomPreview" class="mt-2"></div>
                    </div>

                    {{-- Equipment section --}}
                    <div class="d-flex align-items-center gap-2 mb-3">
                        <i class="fas fa-laptop" style="color:#4c9183;font-size:0.85rem;"></i>
                        <span class="fw-semibold text-uppercase"
                              style="font-size:0.7rem;letter-spacing:0.1em;color:#154269;">
                            {{ __('messages.select_equipment_max') }}
                        </span>
                        <div class="flex-grow-1" style="height:1px;background:#e5eaf0;"></div>
                    </div>

                    <div id="equipmentValidationAlert"
                         class="d-none rounded-lg px-3 py-2 mb-2 d-flex align-items-center gap-2"
                         style="background:#fff1f2;border:1.5px solid #fecdd3;font-size:0.78rem;color:#b91c1c;border-radius:0.5rem;">
                        <i class="fas fa-exclamation-triangle" style="color:#dc2626;"></i>
                        <span id="equipmentValidationMessage"></span>
                    </div>

                    <div class="equipment-scroll-container"
                         style="max-height:220px;overflow-y:auto;border:1.5px solid #d1dbe8;border-radius:0.6rem;padding:6px;background:#fafcff;">
                        @foreach (\App\Models\Asset::where('type', 'equipment')->where('status', 'available')->get() as $item)
                            <label class="equipment-item d-flex align-items-center gap-3 px-3 py-2 rounded mb-1"
                                   for="equipment{{ $item->id }}"
                                   style="cursor:pointer;transition:background .15s;border-radius:0.4rem;">
                                <input class="form-check-input equipment-checkbox flex-shrink-0"
                                       type="checkbox"
                                       name="asset_ids[]"
                                       value="{{ $item->id }}"
                                       id="equipment{{ $item->id }}"
                                       style="width:1rem;height:1rem;accent-color:#4c9183;cursor:pointer;">
                                @if ($item->image)
                                    <img src="{{ $item->image }}" alt="{{ $item->name }}"
                                         class="rounded flex-shrink-0"
                                         style="width:36px;height:36px;object-fit:cover;">
                                @else
                                    <div class="rounded flex-shrink-0 d-flex align-items-center justify-content-center"
                                         style="width:36px;height:36px;background:#e8f4f2;">
                                        <i class="fas fa-laptop" style="color:#4c9183;font-size:0.8rem;"></i>
                                    </div>
                                @endif
                                <span style="font-size:0.875rem;color:#1e3a52;font-weight:500;">
                                    {{ $item->name }}
                                </span>
                            </label>
                        @endforeach
                    </div>

                </div>

                {{-- ── Footer ── --}}
                <div class="modal-footer border-0 px-5 py-3 gap-2"
                     style="background:#f8fafc;border-radius:0 0 1rem 1rem;">
                    <button type="button"
                            class="btn px-4 py-2"
                            data-bs-dismiss="modal"
                            style="border-radius:0.5rem;border:1.5px solid #c9d6e3;color:#5a6a7e;font-size:0.875rem;font-weight:500;background:transparent;transition:background .15s,color .15s;">
                        {{ __('messages.cancel') }}
                    </button>
                    <button type="submit"
                            class="btn px-4 py-2 d-flex align-items-center gap-2"
                            style="border-radius:0.5rem;background:#154269;color:#fff;font-size:0.875rem;font-weight:600;border:none;letter-spacing:0.01em;box-shadow:0 4px 14px rgba(21,66,105,0.35);transition:background .15s,box-shadow .15s;">
                        <i class="fas fa-calendar-check" style="font-size:0.8rem;"></i>
                        {{ __('messages.book_now') }}
                    </button>
                </div>

            </form>
        </div>
    </div>
</div>

@push('styles')
<style>
    /* Backdrop */
    #bookingModal.modal.show ~ .modal-backdrop {
        background: rgba(10, 25, 45, 0.65);
        backdrop-filter: blur(3px);
    }

    /* Focus ring on inputs */
    .reservel-input:focus {
        border-color: #4c9183 !important;
        box-shadow: 0 0 0 3px rgba(76, 145, 131, 0.18) !important;
        outline: none;
    }

    /* Invalid state */
    .is-invalid-time {
        border-color: #dc3545 !important;
        box-shadow: 0 0 0 3px rgba(220, 53, 69, 0.12) !important;
    }

    /* Equipment item hover */
    .equipment-item:hover {
        background: #edf7f5;
    }

    /* Equipment scrollbar */
    .equipment-scroll-container::-webkit-scrollbar {
        width: 5px;
    }
    .equipment-scroll-container::-webkit-scrollbar-track {
        background: #f0f4f8;
        border-radius: 10px;
    }
    .equipment-scroll-container::-webkit-scrollbar-thumb {
        background: #b0c8bf;
        border-radius: 10px;
    }

    /* Cancel button hover */
    .modal-footer .btn:first-child:hover {
        background: #f0f4f8;
        color: #154269;
    }

    /* Submit button hover */
    .modal-footer .btn:last-child:hover {
        background: #1a5280;
        box-shadow: 0 6px 18px rgba(21,66,105,0.4);
    }

    /* Error message visibility toggle — override inline display:none!important */
    .reservel-error-visible {
        display: flex !important;
    }

    /* Room preview */
    #roomPreview .room-card {
        border-radius: 0.5rem;
        border: 1.5px solid #d1e8e4;
        background: #f0faf8;
        padding: 0.6rem 0.9rem;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }
    #roomPreview .room-card span {
        font-weight: 600;
        color: #154269;
        font-size: 0.875rem;
    }
</style>
@endpush

@push('scripts')
<script>
    window.reservelTranslations = {
        format_error: "{{ __('messages.time_format_error') }}",
        range_error:  "{{ __('messages.time_range_error') }}",
        order_error:  "{{ __('messages.time_order_error') }}",
        max_items_error: "{{ __('messages.max_items_error') }}"
    };

    document.addEventListener('DOMContentLoaded', function () {

        // ── HH:MM auto-format ──────────────────────────────────────────────
        document.querySelectorAll('#start_time_only, #end_time_only').forEach(input => {
            input.addEventListener('input', function (e) {
                let val = e.target.value.replace(/\D/g, '');
                if (val.length >= 3) {
                    val = val.slice(0, 2) + ':' + val.slice(2, 4);
                }
                e.target.value = val;

                this.classList.remove('is-invalid-time');
                const errorDiv = document.getElementById(this.id + '_error');
                if (errorDiv) {
                    errorDiv.classList.remove('reservel-error-visible');
                }
            });
        });

        // ── Form submit validation ─────────────────────────────────────────
        const bookingForm = document.getElementById('bookingForm');
        if (bookingForm) {
            bookingForm.addEventListener('submit', function (e) {
                const date       = this.querySelector('input[name="date"]').value;
                const startInput = document.getElementById('start_time_only');
                const endInput   = document.getElementById('end_time_only');
                const startError = document.getElementById('start_time_error');
                const endError   = document.getElementById('end_time_error');

                let hasError = false;

                // Clear previous errors
                [startInput, endInput].forEach(i => i.classList.remove('is-invalid-time'));
                [startError, endError].forEach(err => {
                    err.classList.remove('reservel-error-visible');
                });

                const timeRegex = /^([01]\d|2[0-3]):([0-5]\d)$/;

                if (!timeRegex.test(startInput.value)) {
                    startError.querySelector('span').innerText = window.reservelTranslations.format_error;
                    startError.classList.add('reservel-error-visible');
                    startInput.classList.add('is-invalid-time');
                    hasError = true;
                }
                if (!timeRegex.test(endInput.value)) {
                    endError.querySelector('span').innerText = window.reservelTranslations.format_error;
                    endError.classList.add('reservel-error-visible');
                    endInput.classList.add('is-invalid-time');
                    hasError = true;
                }

                if (hasError) { e.preventDefault(); return; }

                const startHour = parseInt(startInput.value.split(':')[0]);
                const endHour   = parseInt(endInput.value.split(':')[0]);

                if (startHour < 7 || startHour >= 20) {
                    startError.querySelector('span').innerText = window.reservelTranslations.range_error;
                    startError.classList.add('reservel-error-visible');
                    startInput.classList.add('is-invalid-time');
                    hasError = true;
                }
                if (endHour < 7 || endHour > 20) {
                    endError.querySelector('span').innerText = window.reservelTranslations.range_error;
                    endError.classList.add('reservel-error-visible');
                    endInput.classList.add('is-invalid-time');
                    hasError = true;
                }

                if (!hasError && startInput.value >= endInput.value) {
                    endError.querySelector('span').innerText = window.reservelTranslations.order_error;
                    endError.classList.add('reservel-error-visible');
                    endInput.classList.add('is-invalid-time');
                    hasError = true;
                }

                if (hasError) {
                    e.preventDefault();
                } else {
                    const hStart = document.createElement('input');
                    hStart.type  = 'hidden';
                    hStart.name  = 'start_time';
                    hStart.value = `${date} ${startInput.value}:00`;
                    this.appendChild(hStart);

                    const hEnd  = document.createElement('input');
                    hEnd.type   = 'hidden';
                    hEnd.name   = 'end_time';
                    hEnd.value  = `${date} ${endInput.value}:00`;
                    this.appendChild(hEnd);
                }
            });
        }

        // ── Room preview ───────────────────────────────────────────────────
        const roomSelect = document.getElementById('roomSelect');
        if (roomSelect) {
            roomSelect.addEventListener('change', function () {
                const opt     = this.options[this.selectedIndex];
                const preview = document.getElementById('roomPreview');
                if (this.value) {
                    const img  = opt.getAttribute('data-image');
                    const name = opt.getAttribute('data-name');
                    preview.innerHTML = `
                        <div class="room-card">
                            ${img
                                ? `<img src="${img}" class="rounded flex-shrink-0" style="width:48px;height:48px;object-fit:cover;">`
                                : `<div class="rounded flex-shrink-0 d-flex align-items-center justify-content-center" style="width:48px;height:48px;background:#154269;"><i class="fas fa-building text-white"></i></div>`
                            }
                            <span>${name}</span>
                        </div>`;
                } else {
                    preview.innerHTML = '';
                }
            });
        }

        // ── Equipment max-5 guard ──────────────────────────────────────────
        document.querySelectorAll('.equipment-checkbox').forEach(cb => {
            cb.addEventListener('change', function () {
                if (document.querySelectorAll('.equipment-checkbox:checked').length > 5) {
                    this.checked = false;
                    const alertDiv = document.getElementById('equipmentValidationAlert');
                    const msgSpan  = document.getElementById('equipmentValidationMessage');
                    if (msgSpan) msgSpan.textContent = window.reservelTranslations.max_items_error;
                    if (alertDiv) {
                        alertDiv.classList.remove('d-none');
                        alertDiv.style.display = 'flex';
                        setTimeout(() => {
                            alertDiv.style.display = '';
                            alertDiv.classList.add('d-none');
                        }, 3000);
                    }
                }
            });
        });

    });
</script>
@endpush