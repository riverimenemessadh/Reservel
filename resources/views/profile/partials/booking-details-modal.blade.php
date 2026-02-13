<div class="modal fade" id="bookingDetailsModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">
                    <i class="fas fa-info-circle me-2"></i>{{ __('messages.booking_details') }}
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div id="bookingDetailsContent">
                    <div class="text-center">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">{{ __('messages.loading') }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
    <script>
        function showBookingDetails(bookings, userName, startTime, endTime, canCancel, bookingId) {
            const bookingsArray = typeof bookings === 'string' ? JSON.parse(bookings) : bookings;

            let html = '<div class="mb-3">';
            html += '<h6 class="text-muted"><i class="fas fa-user me-2"></i>{{ __("messages.booked_by") }}: ' + userName + '</h6>';
            html += '<p class="text-muted mb-0"><i class="fas fa-clock me-2"></i>' + startTime + ' - ' + endTime + '</p></div>';
            html += '<hr>';
            html += '<div class="row">';

            bookingsArray.forEach(function (booking) {
                const asset = booking.asset;
                const isRoom = asset.type === 'room';
                const icon = isRoom ? 'fa-building' : 'fa-laptop';
                const badgeColor = isRoom ? 'primary' : 'info';

                html += '<div class="col-md-6 mb-3">';
                html += '<div class="card h-100 border-0 shadow-sm cursor-pointer hover-card-lift" style="transition: all 0.3s ease;" onclick="showAssetModal(' + asset.id + ')">';

                if (asset.image) {
                    html += '<img src="/storage/' + asset.image + '" class="card-img-top" style="height: 150px; object-fit: cover; cursor: pointer;">';
                } else {
                    html += '<div class="bg-light d-flex align-items-center justify-content-center" style="height: 150px;">';
                    html += '<i class="fas ' + icon + ' fa-3x text-muted"></i>';
                    html += '</div>';
                }

                html += '<div class="card-body">';
                html += '<h6 class="card-title"><i class="fas ' + icon + ' me-2"></i>' + asset.name + '</h6>';
                html += '<span class="badge bg-' + badgeColor + '">' + (isRoom ? '{{ __("messages.room") }}' : '{{ __("messages.equipment_singular") }}') + '</span>';
                html += '</div></div></div>';
            });

            html += '</div>';

            if (canCancel) {
                html += '<div class="mt-3">';
                html += '<button type="button" class="btn btn-danger w-100" onclick="confirmCancelBooking(' + bookingId + ', \'' + userName.replace(/'/g, "\\'") + '\')">';
                html += '<i class="fas fa-times me-2"></i>{{ __("messages.cancel_this_booking") }}';
                html += '</button></div>';
            }

            document.getElementById('bookingDetailsContent').innerHTML = html;

            const modal = new bootstrap.Modal(document.getElementById('bookingDetailsModal'));
            modal.show();
        }

        function openImageLightbox(imageUrl) {
            const lightboxHtml = `
                <div id="imageLightbox" class="position-fixed top-0 start-0 w-100 h-100 d-flex align-items-center justify-content-center" 
                     style="background: rgba(0,0,0,0.9); z-index: 9999; cursor: pointer;"
                     onclick="this.remove()">
                    <img src="${imageUrl}" class="img-fluid" style="max-width: 90%; max-height: 90%; border-radius: 8px;">
                    <button class="btn btn-light position-absolute top-0 end-0 m-3" onclick="document.getElementById('imageLightbox').remove()">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            `;
            document.body.insertAdjacentHTML('beforeend', lightboxHtml);
        }

        function confirmCancelBooking(bookingId, bookingName) {
            const modal = document.createElement('div');
            modal.className = 'modal fade';
            modal.setAttribute('tabindex', '-1');
            modal.innerHTML = `
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content border-0 shadow-lg">
                        <div class="modal-header bg-danger text-white border-0">
                            <h5 class="modal-title">
                                <i class="fas fa-exclamation-triangle me-2"></i>{{ __('messages.cancel_booking') }}
                            </h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body text-center py-4">
                            <i class="fas fa-calendar-times fa-4x text-danger mb-3"></i>
                            <h5 class="mb-3">{{ __('messages.are_you_sure_cancel_booking') }}</h5>
                            <p class="text-muted mb-1"><strong>${bookingName}</strong></p>
                            <p class="small text-muted">{{ __('messages.action_cannot_be_undone') }}</p>
                        </div>
                        <div class="modal-footer border-0 justify-content-center">
                            <button type="button" class="btn btn-secondary px-4" data-bs-dismiss="modal">
                                <i class="fas fa-times me-2"></i>{{ __('messages.no_keep') }}
                            </button>
                            <button type="button" class="btn btn-danger px-4" onclick="cancelBooking(${bookingId})">
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

        function cancelBooking(bookingId) {
            const form = document.getElementById('cancelBookingForm');
            form.action = '/bookings/' + bookingId;
            form.submit();
        }

        function showAssetModal(assetId) {
            fetch('/assets/' + assetId)
                .then(response => response.text())
                .then(html => {
                    const parser = new DOMParser();
                    const doc = parser.parseFromString(html, 'text/html');
                    const content = doc.querySelector('.card');
                    
                    if (content) {
                        document.getElementById('assetModalContent').innerHTML = content.outerHTML;
                        
                        const images = document.querySelectorAll('#assetModalContent img');
                        images.forEach(img => {
                            img.style.cursor = 'pointer';
                            img.onclick = function() {
                                openImageLightbox(this.src);
                            };
                        });
                    }
                    
                    const modal = new bootstrap.Modal(document.getElementById('assetModal'));
                    modal.show();
                });
        }
    </script>
@endpush

<div class="modal fade" id="assetModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title"><i class="fas fa-info-circle me-2"></i>{{ __('messages.asset_details') }}</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="assetModalContent">
                <div class="text-center">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">{{ __('messages.loading') }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>