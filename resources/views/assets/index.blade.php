<x-app-layout>
    <div class="row fade-in">

        {{-- ── Page Header ── --}}
        <div class="col-12 mb-4 d-flex justify-content-between align-items-center flex-wrap gap-3">
            <h2 class="fw-bold text-primary mb-0">
                <i class="fas fa-box me-2"></i>{{ __('messages.assets') }}
            </h2>
            @can('create', App\Models\Asset::class)
                <a href="{{ route('assets.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus me-2"></i>{{ __('messages.add_asset') }}
                </a>
            @endcan
        </div>

        {{-- ── Rooms Section ── --}}
        <div class="col-12 mb-3">
            <h4 class="fw-bold text-primary border-bottom border-2 pb-2 d-inline-block">
                <i class="fas fa-door-open me-2"></i>{{ __('messages.rooms') }}
            </h4>
        </div>

        @forelse($rooms as $item)
            <div class="col-md-6 col-lg-4 mb-4">
                <div class="card h-100 border-0 shadow-sm hover-card-lift">
                    <div class="cursor-pointer" onclick="window.location.href='{{ route('assets.show', $item) }}'">
                        @if ($item->image)
                            <img src="{{ $item->image }}" alt="{{ $item->name }}"
                                style="height: 200px; object-fit: cover; width: 100%; border-top-left-radius: 12px; border-top-right-radius: 12px;">
                        @else
                            <div class="bg-light d-flex align-items-center justify-content-center"
                                style="height: 200px; border-top-left-radius: 12px; border-top-right-radius: 12px;">
                                <i class="fas fa-door-open fa-4x text-muted"></i>
                            </div>
                        @endif
                        <div class="card-body pb-0">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <h5 class="card-title mb-0 text-primary fw-bold">{{ $item->name }}</h5>
                                <span class="status-badge status-{{ $item->status }}">
                                    {{ __('messages.' . $item->status) }}
                                </span>
                            </div>
                            <p class="card-text text-muted small mb-3">
                                {{ Str::limit($item->description, 80) }}
                            </p>
                        </div>
                    </div>
                    <div class="card-footer bg-white border-0 pt-0 pb-3">
                        <div class="d-flex gap-2">
                            @can('update', $item)
                                <a href="{{ route('assets.edit', $item) }}" class="btn btn-sm btn-outline-primary"
                                    title="{{ __('messages.edit') }}">
                                    <i class="fas fa-edit"></i>
                                </a>
                            @endcan
                            @can('delete', $item)
                                <button type="button" class="btn btn-sm btn-outline-danger"
                                    title="{{ __('messages.delete') }}"
                                    onclick="confirmDeleteAsset({{ $item->id }}, '{{ addslashes($item->name) }}')">
                                    <i class="fas fa-trash"></i>
                                </button>
                            @endcan
                            <button type="button" class="btn btn-sm btn-outline-warning"
                                title="{{ __('messages.report_now') }}"
                                onclick="openReportModal({{ $item->id }}, '{{ $item->status }}')"
                                {{ $item->status === 'in_repair' ? 'disabled' : '' }}>
                                <i class="fas fa-exclamation-triangle"></i>
                            </button>
                            <button type="button" class="btn btn-sm btn-primary"
                                title="{{ __('messages.book_now') }}"
                                onclick="quickBook({{ $item->id }}, '{{ $item->type }}')">
                                <i class="fas fa-calendar-plus"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12 text-center py-4 mb-3">
                <p class="text-muted">{{ __('messages.no_assets_available', ['type' => __('messages.room')]) }}</p>
            </div>
        @endforelse

        {{-- ── Equipment Section ── --}}
        <div class="col-12 mb-3 mt-2">
            <h4 class="fw-bold text-primary border-bottom border-2 pb-2 d-inline-block">
                <i class="fas fa-laptop me-2"></i>{{ __('messages.equipment') }}
            </h4>
        </div>

        @forelse($equipment as $item)
            <div class="col-md-6 col-lg-4 mb-4">
                <div class="card h-100 border-0 shadow-sm hover-card-lift">
                    <div class="cursor-pointer" onclick="window.location.href='{{ route('assets.show', $item) }}'">
                        @if ($item->image)
                            <img src="{{ $item->image }}" alt="{{ $item->name }}"
                                style="height: 200px; object-fit: cover; width: 100%; border-top-left-radius: 12px; border-top-right-radius: 12px;">
                        @else
                            <div class="bg-light d-flex align-items-center justify-content-center"
                                style="height: 200px; border-top-left-radius: 12px; border-top-right-radius: 12px;">
                                <i class="fas fa-laptop fa-4x text-muted"></i>
                            </div>
                        @endif
                        <div class="card-body pb-0">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <h5 class="card-title mb-0 text-primary fw-bold">{{ $item->name }}</h5>
                                <span class="status-badge status-{{ $item->status }}">
                                    {{ __('messages.' . $item->status) }}
                                </span>
                            </div>
                            <p class="card-text text-muted small mb-3">
                                {{ Str::limit($item->description, 80) }}
                            </p>
                        </div>
                    </div>
                    <div class="card-footer bg-white border-0 pt-0 pb-3">
                        <div class="d-flex gap-2">
                            @can('update', $item)
                                <a href="{{ route('assets.edit', $item) }}" class="btn btn-sm btn-outline-primary"
                                    title="{{ __('messages.edit') }}">
                                    <i class="fas fa-edit"></i>
                                </a>
                            @endcan
                            @can('delete', $item)
                                <button type="button" class="btn btn-sm btn-outline-danger"
                                    title="{{ __('messages.delete') }}"
                                    onclick="confirmDeleteAsset({{ $item->id }}, '{{ addslashes($item->name) }}')">
                                    <i class="fas fa-trash"></i>
                                </button>
                            @endcan
                            <button type="button" class="btn btn-sm btn-outline-warning"
                                title="{{ __('messages.report_now') }}"
                                onclick="openReportModal({{ $item->id }}, '{{ $item->status }}')"
                                {{ $item->status === 'in_repair' ? 'disabled' : '' }}>
                                <i class="fas fa-exclamation-triangle"></i>
                            </button>
                            <button type="button" class="btn btn-sm btn-primary"
                                title="{{ __('messages.book_now') }}"
                                onclick="quickBook({{ $item->id }}, '{{ $item->type }}')">
                                <i class="fas fa-calendar-plus"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12 text-center py-4">
                <p class="text-muted">{{ __('messages.no_assets_available', ['type' => __('messages.equipment')]) }}</p>
            </div>
        @endforelse
    </div>

    @include('profile.partials.booking-modal')
    @include('profile.partials.report-modal')

    <form id="deleteAssetForm" method="POST" style="display: none;">
        @csrf
        @method('DELETE')
    </form>

    @push('styles')
    <style>
        .cursor-pointer { cursor: pointer; }
        .hover-card-lift {
            transition: all 0.3s ease;
            border: 1px solid rgba(0,0,0,0.05);
        }
        .hover-card-lift:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(21,66,105,0.2) !important;
        }
    </style>
    @endpush

    @push('scripts')
    <script>
        function openReportModal(assetId, assetStatus) {
            if (assetStatus === 'in_repair') {
                const html = `
                    <div class="alert alert-warning alert-dismissible fade show"
                         role="alert"
                         style="position:fixed;top:80px;right:20px;z-index:9999;max-width:400px;">
                        <i class="fas fa-exclamation-circle me-2"></i>
                        <strong>{{ __('messages.asset_already_reported') }}</strong>
                        <p class="mb-0 mt-2">{{ __('messages.asset_in_repair_message') }}</p>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>`;
                document.body.insertAdjacentHTML('beforeend', html);
                setTimeout(() => {
                    const alert = document.querySelector('[role="alert"]');
                    if (alert) alert.querySelector('.btn-close')?.click();
                }, 5000);
                return;
            }
            const modal = new bootstrap.Modal(document.getElementById('reportModal'));
            document.getElementById('reportAsset').value = assetId;
            document.getElementById('reportAsset').dispatchEvent(new Event('change'));
            modal.show();
        }

        function quickBook(assetId, assetType) {
            const modal = new bootstrap.Modal(document.getElementById('bookingModal'));
            document.getElementById('bookingForm').reset();
            if (assetType === 'room') {
                const roomSelect = document.getElementById('roomSelect');
                roomSelect.value = assetId;
                roomSelect.dispatchEvent(new Event('change'));
            } else {
                const checkbox = document.querySelector(`.equipment-checkbox[value="${assetId}"]`);
                if (checkbox) {
                    checkbox.checked = true;
                    checkbox.dispatchEvent(new Event('change'));
                }
            }
            modal.show();
        }

        function confirmDeleteAsset(assetId, assetName) {
            const existing = document.getElementById('deleteAssetModal');
            if (existing) existing.remove();

            const html = `
                <div class="modal fade" id="deleteAssetModal" tabindex="-1">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content border-0 shadow-lg">
                            <div class="modal-header bg-danger text-white border-0">
                                <h5 class="modal-title">
                                    <i class="fas fa-exclamation-triangle me-2"></i>{{ __('messages.delete_asset') }}
                                </h5>
                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body text-center py-4">
                                <i class="fas fa-trash-alt fa-4x text-danger mb-3"></i>
                                <h5 class="mb-3">{{ __('messages.are_you_sure_delete') }}</h5>
                                <p class="text-muted mb-1"><strong>${assetName}</strong></p>
                            </div>
                            <div class="modal-footer border-0 justify-content-center">
                                <button type="button" class="btn btn-secondary px-4" data-bs-dismiss="modal">
                                    {{ __('messages.cancel') }}
                                </button>
                                <button type="button" class="btn btn-danger px-4" onclick="executeDelete(${assetId})">
                                    {{ __('messages.yes_delete') }}
                                </button>
                            </div>
                        </div>
                    </div>
                </div>`;
            document.body.insertAdjacentHTML('beforeend', html);
            new bootstrap.Modal(document.getElementById('deleteAssetModal')).show();
        }

        function executeDelete(assetId) {
            const form = document.getElementById('deleteAssetForm');
            form.action = '/assets/' + assetId;
            form.submit();
        }
    </script>
    @endpush
</x-app-layout>