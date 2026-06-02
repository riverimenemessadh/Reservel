{{-- Primary Booking Details Modal --}}
<div class="modal fade" id="bookingDetailsModal" tabindex="-1" aria-labelledby="bookingDetailsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content border-0 overflow-hidden" style="border-radius: 1rem; box-shadow: 0 25px 60px rgba(0,0,0,0.25);">

            {{-- Header --}}
            <div class="modal-header border-0 px-4 py-3" style="background-color: #154269;">
                <div class="d-flex align-items-center gap-2">
                    <div class="d-flex align-items-center justify-content-center rounded-circle"
                         style="width:36px; height:36px; background: rgba(255,255,255,0.15);">
                        <i class="fas fa-calendar-check" style="color:#fff; font-size:0.9rem;"></i>
                    </div>
                    <h5 class="modal-title mb-0 fw-semibold" id="bookingDetailsModalLabel"
                        style="color:#fff; font-size:1.05rem; letter-spacing:0.01em;">
                        {{ __('messages.booking_details') }}
                    </h5>
                </div>
                <button type="button"
                        class="btn border-0 px-2 py-1 d-flex align-items-center gap-1"
                        data-bs-dismiss="modal"
                        style="color: rgba(255,255,255,0.75); background: rgba(255,255,255,0.1); border-radius: 0.5rem; font-size: 0.8rem; transition: background 0.2s;">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            {{-- Body --}}
            <div class="modal-body p-0" style="background:#fff;">
                <div id="bookingDetailsContent">
                    {{-- Loading state --}}
                    <div class="d-flex flex-column align-items-center justify-content-center py-5">
                        <div class="spinner-border" role="status" style="color:#154269; width:2rem; height:2rem; border-width:0.2em;">
                            <span class="visually-hidden">{{ __('messages.loading') }}</span>
                        </div>
                        <p class="mt-3 mb-0 small" style="color:#6b7280;">{{ __('messages.loading') }}</p>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

{{-- Secondary Asset Detail Modal --}}
<div class="modal fade" id="assetModal" tabindex="-1" aria-labelledby="assetModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content border-0 overflow-hidden" style="border-radius: 1rem; box-shadow: 0 25px 60px rgba(0,0,0,0.25);">

            {{-- Header --}}
            <div class="modal-header border-0 px-4 py-3" style="background-color: #4c9183;">
                <div class="d-flex align-items-center gap-2">
                    <div class="d-flex align-items-center justify-content-center rounded-circle"
                         style="width:36px; height:36px; background: rgba(255,255,255,0.15);">
                        <i class="fas fa-info-circle" style="color:#fff; font-size:0.9rem;"></i>
                    </div>
                    <h5 class="modal-title mb-0 fw-semibold" id="assetModalLabel"
                        style="color:#fff; font-size:1.05rem; letter-spacing:0.01em;">
                        {{ __('messages.asset_details') }}
                    </h5>
                </div>
                <button type="button"
                        class="btn border-0 px-2 py-1 d-flex align-items-center gap-1"
                        data-bs-dismiss="modal"
                        style="color: rgba(255,255,255,0.75); background: rgba(255,255,255,0.1); border-radius: 0.5rem; font-size: 0.8rem; transition: background 0.2s;">
                    <i class="fas fa-arrow-left me-1"></i>
                    <span style="font-size:0.78rem;">{{ __('messages.back') }}</span>
                </button>
            </div>

            {{-- Body --}}
            <div class="modal-body p-4" id="assetModalContent" style="background:#fff;">
                <div class="d-flex flex-column align-items-center justify-content-center py-5">
                    <div class="spinner-border" role="status" style="color:#4c9183; width:2rem; height:2rem; border-width:0.2em;">
                        <span class="visually-hidden">{{ __('messages.loading') }}</span>
                    </div>
                    <p class="mt-3 mb-0 small" style="color:#6b7280;">{{ __('messages.loading') }}</p>
                </div>
            </div>

        </div>
    </div>
</div>

@push('scripts')
<style>
    /* ── Booking Details Modal Styles ── */
    .bm-meta-pill {
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
        padding: 0.28rem 0.75rem;
        border-radius: 999px;
        font-size: 0.8rem;
        font-weight: 500;
        letter-spacing: 0.01em;
    }
    .bm-asset-row {
        display: flex;
        align-items: center;
        gap: 1rem;
        padding: 0.85rem 1.25rem;
        border: 1.5px solid #e5e7eb;
        border-radius: 0.75rem;
        cursor: pointer;
        transition: border-color 0.18s, box-shadow 0.18s, background 0.18s;
        background: #fafafa;
        margin-bottom: 0.6rem;
        text-decoration: none;
    }
    .bm-asset-row:hover {
        border-color: #154269;
        background: #f0f5fb;
        box-shadow: 0 2px 12px rgba(21,66,105,0.1);
    }
    .bm-asset-icon {
        width: 44px;
        height: 44px;
        border-radius: 0.6rem;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        font-size: 1.1rem;
    }
    .bm-asset-icon.room {
        background: #eff6ff;
        color: #154269;
    }
    .bm-asset-icon.equipment {
        background: #f0fdf4;
        color: #166534;
    }
    .bm-asset-thumb {
        width: 48px;
        height: 48px;
        border-radius: 0.5rem;
        object-fit: cover;
        flex-shrink: 0;
    }
    .bm-badge-room {
        background: #eff6ff;
        color: #154269;
        font-size: 0.7rem;
        padding: 0.18rem 0.55rem;
        border-radius: 999px;
        font-weight: 600;
        letter-spacing: 0.02em;
        border: 1px solid #bfdbfe;
    }
    .bm-badge-equip {
        background: #f0fdf4;
        color: #166534;
        font-size: 0.7rem;
        padding: 0.18rem 0.55rem;
        border-radius: 999px;
        font-weight: 600;
        letter-spacing: 0.02em;
        border: 1px solid #bbf7d0;
    }
    .bm-cancel-btn {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        width: 100%;
        padding: 0.7rem 1.5rem;
        background: #ae2e3c;
        color: #fff;
        border: none;
        border-radius: 0.65rem;
        font-size: 0.9rem;
        font-weight: 600;
        letter-spacing: 0.02em;
        cursor: pointer;
        transition: background 0.18s, box-shadow 0.18s;
    }
    .bm-cancel-btn:hover {
        background: #921f2c;
        box-shadow: 0 4px 16px rgba(174,46,60,0.3);
    }
    #bookingDetailsModal .modal-backdrop,
    #assetModal .modal-backdrop {
        backdrop-filter: blur(2px);
    }
</style>

<script>
    // ── CANONICAL LOCATION for showBookingDetails, confirmCancelBooking, cancelBooking, showAssetModal ──

    /**
     * showBookingDetails — CANONICAL
     * Opens the primary booking details modal with user/time info and asset rows.
     */
    function showBookingDetails(bookings, userName, startTime, endTime, canCancel, bookingId) {
        const bookingsArray = typeof bookings === 'string' ? JSON.parse(bookings) : bookings;

        // Stash for showAssetModal to use without a fetch
        window._reservelBookings = bookingsArray;

        let html = '';

        // ── Meta bar: user + time pill ──
        html += '<div style="background:#f8fafc; border-bottom:1.5px solid #e5e7eb; padding:1rem 1.5rem;">';
        html += '<div class="d-flex flex-wrap align-items-center gap-2">';
        html += '<span class="bm-meta-pill" style="background:#eff6ff; color:#154269; border:1px solid #bfdbfe;">';
        html += '<i class="fas fa-user" style="font-size:0.75rem;"></i>' + userName;
        html += '</span>';
        html += '<span class="bm-meta-pill" style="background:#f0fdf4; color:#166534; border:1px solid #bbf7d0;">';
        html += '<i class="fas fa-clock" style="font-size:0.75rem;"></i>' + startTime + '&nbsp;&ndash;&nbsp;' + endTime;
        html += '</span>';
        html += '</div>';
        html += '</div>';

        // ── Asset list ──
        html += '<div style="padding:1.25rem 1.5rem 0.5rem;">';
        html += '<p class="mb-2" style="font-size:0.72rem; font-weight:700; text-transform:uppercase; letter-spacing:0.08em; color:#9ca3af;">{{ __("messages.booked_assets") }}</p>';

        bookingsArray.forEach(function (booking) {
            const asset = booking.asset;
            const isRoom = asset.type === 'room';
            const icon = isRoom ? 'fa-building' : 'fa-laptop';
            const typeLabel = isRoom ? '{{ __("messages.room") }}' : '{{ __("messages.equipment_singular") }}';
            const badgeClass = isRoom ? 'bm-badge-room' : 'bm-badge-equip';
            const iconClass = isRoom ? 'room' : 'equipment';

            html += '<div class="bm-asset-row" onclick="showAssetModal(' + asset.id + ')">';

            // Thumbnail or icon
            if (asset.image) {
                html += '<img src="' + asset.image + '" alt="" class="bm-asset-thumb">';
            } else {
                html += '<div class="bm-asset-icon ' + iconClass + '">';
                html += '<i class="fas ' + icon + '"></i>';
                html += '</div>';
            }

            // Name + badge
            html += '<div class="flex-grow-1 min-width-0">';
            html += '<p class="mb-1 fw-semibold text-truncate" style="color:#1e293b; font-size:0.92rem;">' + asset.name + '</p>';
            html += '<span class="' + badgeClass + '">' + typeLabel + '</span>';
            html += '</div>';

            // Chevron
            html += '<i class="fas fa-chevron-right" style="color:#9ca3af; font-size:0.75rem; flex-shrink:0;"></i>';
            html += '</div>';
        });

        html += '</div>';

        // ── Cancel button ──
        if (canCancel) {
            html += '<div style="padding:0.75rem 1.5rem 1.25rem;">';
            html += '<button class="bm-cancel-btn" onclick="confirmCancelBooking(' + bookingId + ', \'' + userName.replace(/'/g, "\\'") + '\')">';
            html += '<i class="fas fa-ban"></i>{{ __("messages.cancel_this_booking") }}';
            html += '</button>';
            html += '</div>';
        } else {
            html += '<div style="height:1.25rem;"></div>';
        }

        document.getElementById('bookingDetailsContent').innerHTML = html;

        const modal = new bootstrap.Modal(document.getElementById('bookingDetailsModal'));
        modal.show();
    }

    /**
     * openImageLightbox — opens a full-screen overlay for an image.
     */
    function openImageLightbox(imageUrl) {
        const lightboxHtml = `
            <div id="imageLightbox"
                 class="position-fixed top-0 start-0 w-100 h-100 d-flex align-items-center justify-content-center"
                 style="background:rgba(0,0,0,0.9); z-index:9999; cursor:pointer;"
                 onclick="this.remove()">
                <img src="${imageUrl}" class="img-fluid"
                     style="max-width:90%; max-height:90%; border-radius:8px;">
                <button class="btn btn-light position-absolute top-0 end-0 m-3"
                        onclick="document.getElementById('imageLightbox').remove()">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        `;
        document.body.insertAdjacentHTML('beforeend', lightboxHtml);
    }

    /**
     * confirmCancelBooking — CANONICAL
     * Shows a Bootstrap confirmation modal before cancelling a booking.
     */
    function confirmCancelBooking(bookingId, bookingName) {
        const modal = document.createElement('div');
        modal.className = 'modal fade';
        modal.setAttribute('tabindex', '-1');
        modal.innerHTML = `
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content border-0 overflow-hidden"
                     style="border-radius:1rem; box-shadow:0 25px 60px rgba(0,0,0,0.25);">
                    <div class="modal-header border-0 px-4 py-3" style="background:#ae2e3c;">
                        <div class="d-flex align-items-center gap-2">
                            <div class="d-flex align-items-center justify-content-center rounded-circle"
                                 style="width:36px; height:36px; background:rgba(255,255,255,0.15);">
                                <i class="fas fa-exclamation-triangle" style="color:#fff; font-size:0.9rem;"></i>
                            </div>
                            <h5 class="modal-title mb-0 fw-semibold" style="color:#fff; font-size:1.05rem;">
                                {{ __('messages.cancel_booking') }}
                            </h5>
                        </div>
                        <button type="button"
                                class="btn border-0 px-2 py-1"
                                data-bs-dismiss="modal"
                                style="color:rgba(255,255,255,0.75); background:rgba(255,255,255,0.1); border-radius:0.5rem;">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                    <div class="modal-body text-center py-4 px-4">
                        <div class="d-flex align-items-center justify-content-center mb-3"
                             style="width:64px; height:64px; border-radius:50%; background:#fef2f2; margin:0 auto;">
                            <i class="fas fa-calendar-times fa-2x" style="color:#ae2e3c;"></i>
                        </div>
                        <h5 class="mb-2 fw-semibold" style="color:#1e293b; font-size:1rem;">
                            {{ __('messages.are_you_sure_cancel_booking') }}
                        </h5>
                        <p class="mb-1 fw-semibold" style="color:#374151; font-size:0.9rem;">${bookingName}</p>
                        <p class="mb-0 small" style="color:#9ca3af;">{{ __('messages.action_cannot_be_undone') }}</p>
                    </div>
                    <div class="modal-footer border-0 justify-content-center gap-2 pb-4">
                        <button type="button"
                                class="btn px-4 py-2"
                                data-bs-dismiss="modal"
                                style="border:1.5px solid #d1d5db; color:#374151; border-radius:0.6rem; font-size:0.88rem; background:#fff; transition:background 0.15s;">
                            <i class="fas fa-arrow-left me-2"></i>{{ __('messages.no_keep') }}
                        </button>
                        <button type="button"
                                class="btn px-4 py-2"
                                onclick="cancelBooking(${bookingId})"
                                style="background:#ae2e3c; color:#fff; border:none; border-radius:0.6rem; font-size:0.88rem; font-weight:600; transition:background 0.15s;">
                            <i class="fas fa-check me-2"></i>{{ __('messages.yes_cancel') }}
                        </button>
                    </div>
                </div>
            </div>
        `;
        document.body.appendChild(modal);
        const bsModal = new bootstrap.Modal(modal);
        bsModal.show();
        modal.addEventListener('hidden.bs.modal', () => modal.remove());
    }

    /**
     * cancelBooking — CANONICAL
     * Submits the cancelBookingForm (defined in parent view) for the given booking ID.
     */
    function cancelBooking(bookingId) {
        const form = document.getElementById('cancelBookingForm');
        form.action = '/bookings/' + bookingId;
        form.submit();
    }

    /**
     * showAssetModal — CANONICAL
     * Displays asset details using data already present in the bookings array
     * passed to showBookingDetails. No fetch required.
     *
     * Call signature: showAssetModal(assetId) — assetId is matched against
     * window._reservelBookings which is set by showBookingDetails.
     */
    function showAssetModal(assetId) {
        const bookings = window._reservelBookings || [];
        const booking  = bookings.find(b => b.asset && b.asset.id == assetId);
        const asset    = booking ? booking.asset : null;

        const container = document.getElementById('assetModalContent');

        if (!asset) {
            container.innerHTML = `
                <div class="d-flex flex-column align-items-center justify-content-center py-5 text-muted">
                    <i class="fas fa-exclamation-circle fa-2x mb-3" style="color:#9ca3af;"></i>
                    <p class="mb-0 small">{{ __('messages.unknown') }}</p>
                </div>`;
            new bootstrap.Modal(document.getElementById('assetModal')).show();
            return;
        }

        const isRoom     = asset.type === 'room';
        const icon       = isRoom ? 'fa-building' : 'fa-laptop';
        const iconColor  = isRoom ? '#154269' : '#4c9183';
        const iconBg     = isRoom ? 'rgba(21,66,105,0.1)' : 'rgba(76,145,131,0.1)';
        const typeLabel  = isRoom ? '{{ __("messages.room") }}' : '{{ __("messages.equipment_singular") }}';
        const statusKey  = asset.status || 'unknown';
        const statusLabels = {
            available: '{{ __("messages.available") }}',
            in_use:    '{{ __("messages.in_use") }}',
            in_repair: '{{ __("messages.in_repair") }}',
        };
        const statusColors = {
            available: { bg: '#dcfce7', color: '#15803d' },
            in_use:    { bg: '#fef9c3', color: '#a16207' },
            in_repair: { bg: '#fee2e2', color: '#b91c1c' },
        };
        const sc = statusColors[statusKey] || { bg: '#f3f4f6', color: '#6b7280' };

        let html = '';

        // Image or icon banner
        if (asset.image) {
            html += `<div style="text-align:center; padding:1.25rem 1.5rem 0;">
                        <img src="${asset.image}" alt=""
                             style="max-height:180px; max-width:100%; border-radius:0.75rem; object-fit:cover; cursor:pointer;"
                             onclick="openImageLightbox('${asset.image}')">
                     </div>`;
        } else {
            html += `<div class="d-flex align-items-center justify-content-center"
                         style="height:100px; background:${iconBg}; margin:1.25rem 1.5rem 0; border-radius:0.75rem;">
                        <i class="fas ${icon} fa-3x" style="color:${iconColor};"></i>
                     </div>`;
        }

        // Name + type + status
        html += `<div style="padding:1.25rem 1.5rem 0;">
                    <h5 style="font-weight:700; color:#1e293b; font-size:1.05rem; margin-bottom:0.5rem;">${asset.name}</h5>
                    <div class="d-flex align-items-center gap-2 flex-wrap mb-3">
                        <span style="font-size:0.75rem; font-weight:600; padding:0.2rem 0.65rem; border-radius:999px;
                                     background:${iconBg}; color:${iconColor};">
                            <i class="fas ${icon} me-1"></i>${typeLabel}
                        </span>
                        <span style="font-size:0.75rem; font-weight:600; padding:0.2rem 0.65rem; border-radius:999px;
                                     background:${sc.bg}; color:${sc.color};">
                            ${statusLabels[statusKey] || statusKey}
                        </span>
                    </div>`;

        if (asset.description) {
            html += `<p style="font-size:0.875rem; color:#64748b; margin-bottom:0;">${asset.description}</p>`;
        }

        html += `</div><div style="height:1.25rem;"></div>`;

        container.innerHTML = html;
        new bootstrap.Modal(document.getElementById('assetModal')).show();
    }
</script>
@endpush