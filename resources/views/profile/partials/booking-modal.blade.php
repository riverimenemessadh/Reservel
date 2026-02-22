<div class="modal fade" id="bookingModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form method="POST" action="{{ route('bookings.store') }}" id="bookingForm" novalidate>
                @csrf
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title"><i class="fas fa-calendar-plus me-2"></i>{{ __('messages.book_asset') }}
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-4">
                        <label class="form-label fw-bold"> {{ __('messages.desired_book_time') }}</label>
                        <div class="row g-2">
                            <div class="col-md-4">
                                <label class="form-label small text-muted">{{ __('messages.date') }}</label>
                                <input type="date" name="date" class="form-control" required
                                    min="{{ date('Y-m-d') }}" style="padding: 0.5rem;">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label small text-muted">{{ __('messages.start_time') }}</label>
                                <input type="text" id="start_time_only" class="form-control" placeholder="HH:MM"
                                    maxlength="5" required style="padding: 0.5rem;">
                                <div id="start_time_error" class="text-danger small mt-1" style="display:none;"></div>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label small text-muted">{{ __('messages.end_time') }}</label>
                                <input type="text" id="end_time_only" class="form-control" placeholder="HH:MM"
                                    maxlength="5" required style="padding: 0.5rem;">
                                <div id="end_time_error" class="text-danger small mt-1" style="display:none;"></div>
                            </div>
                        </div>
                        <small class="text-muted">{{ __('messages.allowed_range') }}</small>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">{{ __('messages.select_room') }}</label>
                        <select name="asset_ids[]" class="form-select" id="roomSelect">
                            <option value="" disabled selected>{{ __('messages.room_dropdown') }}</option>
                            @foreach (\App\Models\Asset::where('type', 'room')->where('status', 'available')->get() as $room)
                                <option value="{{ $room->id }}"
                                    data-image="{{ $room->image ? asset('storage/' . $room->image) : '' }}"
                                    data-name="{{ $room->name }}">
                                    {{ $room->name }}
                                </option>
                            @endforeach
                        </select>
                        <div id="roomPreview" class="mt-2"></div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">{{ __('messages.select_equipment_max') }}</label>
                        <div id="equipmentValidationAlert" class="alert alert-danger" style="display: none;">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            <span id="equipmentValidationMessage"></span>
                        </div>
                        <div class="equipment-scroll-container"
                            style="max-height: 250px; overflow-y: auto; border: 1px solid #dee2e6; border-radius: 6px; padding: 10px; background-color: #fff;">
                            @foreach (\App\Models\Asset::where('type', 'equipment')->where('status', 'available')->get() as $item)
                                <label class="d-flex align-items-center mb-2 p-2 rounded hover-bg-light"
                                    for="equipment{{ $item->id }}" style="cursor: pointer;">
                                    <input class="form-check-input equipment-checkbox me-3" type="checkbox"
                                        name="asset_ids[]" value="{{ $item->id }}"
                                        id="equipment{{ $item->id }}">
                                    @if ($item->image)
                                        <img src="{{ $item->image }}" alt="{{ $item->name }}"
                                            class="rounded me-2" style="width: 40px; height: 40px; object-fit: cover;">
                                    @else
                                        <div class="bg-light rounded me-2 d-flex align-items-center justify-content-center"
                                            style="width: 40px; height: 40px;">
                                            <i class="fas fa-laptop text-muted"></i>
                                        </div>
                                    @endif
                                    <span>{{ $item->name }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary"
                        data-bs-dismiss="modal">{{ __('messages.cancel') }}</button>
                    <button type="submit" class="btn btn-primary">{{ __('messages.book_now') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('styles')
    <style>
        .hover-bg-light:hover {
            background-color: var(--light-bg);
        }

        .is-invalid-time {
            border-color: var(--danger-color) !important;
        }

        .modal-content .fa-clock,
        .modal-content .fa-calendar-alt,
        .modal-content .fa-building,
        .modal-content .fa-laptop,
        .modal-content .fa-door-open {
            color: var(--primary-color) !important;
        }

        .modal-header.bg-primary i {
            color: var(--light-bg) !important;
        }
    </style>
@endpush

@push('scripts')
    <script>
        window.reservelTranslations = {
            format_error: "{{ __('messages.time_format_error') }}",
            range_error: "{{ __('messages.time_range_error') }}",
            order_error: "{{ __('messages.time_order_error') }}",
            max_items_error: "{{ __('messages.max_items_error') }}"
        };

        document.addEventListener('DOMContentLoaded', function() {


            document.querySelectorAll('#start_time_only, #end_time_only').forEach(input => {
                input.addEventListener('input', function(e) {
                    let val = e.target.value.replace(/\D/g, '');
                    if (val.length >= 3) {
                        val = val.slice(0, 2) + ':' + val.slice(2, 4);
                    }
                    e.target.value = val;

                    this.classList.remove('is-invalid-time');
                    const errorDiv = document.getElementById(this.id + '_error');
                    if (errorDiv) errorDiv.style.display = 'none';
                });
            });


            const bookingForm = document.getElementById('bookingForm');
            if (bookingForm) {
                bookingForm.addEventListener('submit', function(e) {
                    const date = this.querySelector('input[name="date"]').value;
                    const startInput = document.getElementById('start_time_only');
                    const endInput = document.getElementById('end_time_only');
                    const startError = document.getElementById('start_time_error');
                    const endError = document.getElementById('end_time_error');

                    let hasError = false;


                    [startInput, endInput].forEach(i => i.classList.remove('is-invalid-time'));
                    [startError, endError].forEach(err => err.style.display = 'none');

                    const timeRegex = /^([01]\d|2[0-3]):([0-5]\d)$/;


                    if (!timeRegex.test(startInput.value)) {
                        startError.innerText = window.reservelTranslations.format_error;
                        startError.style.display = 'block';
                        startInput.classList.add('is-invalid-time');
                        hasError = true;
                    }
                    if (!timeRegex.test(endInput.value)) {
                        endError.innerText = window.reservelTranslations.format_error;
                        endError.style.display = 'block';
                        endInput.classList.add('is-invalid-time');
                        hasError = true;
                    }

                    if (hasError) {
                        e.preventDefault();
                        return;
                    }

                    const startHour = parseInt(startInput.value.split(':')[0]);
                    const endHour = parseInt(endInput.value.split(':')[0]);


                    if (startHour < 7 || startHour >= 20) {
                        startError.innerText = window.reservelTranslations.range_error;
                        startError.style.display = 'block';
                        startInput.classList.add('is-invalid-time');
                        hasError = true;
                    }
                    if (endHour < 7 || endHour > 20) {
                        endError.innerText = window.reservelTranslations.range_error;
                        endError.style.display = 'block';
                        endInput.classList.add('is-invalid-time');
                        hasError = true;
                    }


                    if (!hasError && startInput.value >= endInput.value) {
                        endError.innerText = window.reservelTranslations.order_error;
                        endError.style.display = 'block';
                        endInput.classList.add('is-invalid-time');
                        hasError = true;
                    }

                    if (hasError) {
                        e.preventDefault();
                    } else {

                        const hStart = document.createElement('input');
                        hStart.type = 'hidden';
                        hStart.name = 'start_time';
                        hStart.value = `${date} ${startInput.value}:00`;
                        this.appendChild(hStart);

                        const hEnd = document.createElement('input');
                        hEnd.type = 'hidden';
                        hEnd.name = 'end_time';
                        hEnd.value = `${date} ${endInput.value}:00`;
                        this.appendChild(hEnd);
                    }
                });
            }


            const roomSelect = document.getElementById('roomSelect');
            if (roomSelect) {
                roomSelect.addEventListener('change', function() {
                    const opt = this.options[this.selectedIndex];
                    const preview = document.getElementById('roomPreview');
                    if (this.value) {
                        const img = opt.getAttribute('data-image');
                        const name = opt.getAttribute('data-name');
                        preview.innerHTML = `
                        <div class="d-flex align-items-center p-2 bg-light rounded border">
                            ${img ? `<img src="${img}" class="rounded me-2" style="width: 60px; height: 60px; object-fit: cover;">` 
                                  : `<div class="bg-secondary rounded me-2 d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;"><i class="fas fa-building fa-2x text-white"></i></div>`}
                            <span class="fw-bold">${name}</span>
                        </div>`;
                    } else {
                        preview.innerHTML = '';
                    }
                });
            }


            document.querySelectorAll('.equipment-checkbox').forEach(cb => {
                cb.addEventListener('change', function() {
                    if (document.querySelectorAll('.equipment-checkbox:checked').length > 5) {
                        this.checked = false;
                        const alertDiv = document.getElementById('equipmentValidationAlert');
                        const msgSpan = document.getElementById('equipmentValidationMessage');
                        if (msgSpan) msgSpan.textContent = window.reservelTranslations
                            .max_items_error;
                        if (alertDiv) {
                            alertDiv.style.display = 'block';
                            setTimeout(() => alertDiv.style.display = 'none', 3000);
                        }
                    }
                });
            });
        });
    </script>
@endpush
