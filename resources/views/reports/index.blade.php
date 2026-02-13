<x-app-layout>
    <div class="fade-in">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="fw-bold text-primary">
                <i class="fas fa-exclamation-circle me-2"></i>{{ __('messages.reports') }}
            </h2>
        </div>

        <div class="row">
            @forelse($reports as $report)
                <div class="col-md-6 col-lg-4 mb-4">
                    <div class="card h-100 border-0 shadow-sm {{ $report->isPending() ? 'border-start border-danger border-3' : 'border-start border-success border-3' }}">
                        <div class="card-header {{ $report->isPending() ? 'bg-danger' : 'bg-success' }} text-white border-0">
                            <div class="d-flex justify-content-between align-items-center">
                                <h6 class="mb-0">
                                    <i class="fas fa-{{ $report->asset->type === 'room' ? 'building' : 'laptop' }} me-2"></i>
                                    {{ $report->asset->name }}
                                </h6>
                                <span class="badge bg-white {{ $report->isPending() ? 'text-danger' : 'text-success' }}">
                                    {{ __('messages.' . $report->status) }}
                                </span>
                            </div>
                        </div>
                        <div class="card-body">
                            <p class="card-text"><strong>{{ __('messages.problem') }}:</strong><br>{{ $report->problem_description }}</p>
                            
                            @if($report->possible_cause)
                                <p class="card-text small text-muted"><strong>{{ __('messages.possible_cause') }}:</strong><br>{{ $report->possible_cause }}</p>
                            @endif
                            
                            <p class="card-text small">
                                <i class="fas fa-user me-1"></i>{{ $report->user->name }}<br>
                                <i class="fas fa-calendar me-1"></i>{{ $report->created_at->format('d/m/Y H:i') }}
                            </p>
                            
                            <div class="d-flex gap-2">
                                @can('resolve', $report)
                                    @if($report->isPending())
                                        <button type="button" class="btn btn-success btn-sm flex-grow-1" onclick="confirmResolve({{ $report->id }}, '{{ $report->asset->name }}')">
                                            <i class="fas fa-check me-1"></i>{{ __('messages.mark_as_resolved') }}
                                        </button>
                                    @endif
                                @endcan
                                
                                @can('delete', $report)
                                    @if($report->isPending())
                                        <button type="button" class="btn btn-outline-danger btn-sm" onclick="confirmCancelReport({{ $report->id }}, '{{ $report->asset->name }}')">
                                            <i class="fas fa-times me-1"></i>{{ __('messages.cancel') }}
                                        </button>
                                    @endif
                                @endcan
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="alert alert-info text-center border-0">
                        <i class="fas fa-info-circle me-2"></i>
                        @if(auth()->user()->isTeacher())
                            {{ __('messages.no_reports_made') }}
                        @else
                            {{ __('messages.no_reports_found') }}
                        @endif
                    </div>
                </div>
            @endforelse
        </div>

        @if($reports->hasPages())
            <div class="mt-4 d-flex justify-content-center gap-2">
                @if($reports->onFirstPage())
                    <button class="btn btn-sm btn-outline-secondary" disabled>
                        <i class="fas fa-chevron-left me-1"></i>{{ __('messages.previous') ?? 'Previous' }}
                    </button>
                @else
                    <a href="{{ $reports->previousPageUrl() }}" class="btn btn-sm btn-outline-primary">
                        <i class="fas fa-chevron-left me-1"></i>{{ __('messages.previous') ?? 'Previous' }}
                    </a>
                @endif

                <span class="btn btn-sm btn-outline-secondary" disabled>
                    {{ $reports->currentPage() }} / {{ $reports->lastPage() }}
                </span>

                @if($reports->hasMorePages())
                    <a href="{{ $reports->nextPageUrl() }}" class="btn btn-sm btn-outline-primary">
                        {{ __('messages.next') ?? 'Next' }}<i class="fas fa-chevron-right ms-1"></i>
                    </a>
                @else
                    <button class="btn btn-sm btn-outline-secondary" disabled>
                        {{ __('messages.next') ?? 'Next' }}<i class="fas fa-chevron-right ms-1"></i>
                    </button>
                @endif
            </div>
        @endif
    </div>

    @if(!auth()->user()->isAdmin())
        <button class="btn btn-danger btn-lg rounded-circle position-fixed shadow-lg" style="bottom: 20px; right: 20px; width: 60px; height: 60px;" data-bs-toggle="modal" data-bs-target="#reportModal" title="{{ __('messages.report_problem') }}">
            <i class="fas fa-exclamation-triangle"></i>
        </button>

        @include('profile.partials.report-modal')
    @endif

    <form id="resolveReportForm" method="POST" style="display: none;">
        @csrf
    </form>

    <form id="cancelReportForm" method="POST" style="display: none;">
        @csrf
        @method('DELETE')
    </form>

    @push('scripts')
    <script>
    function confirmResolve(reportId, assetName) {
        const modal = document.createElement('div');
        modal.className = 'modal fade';
        modal.innerHTML = `
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content border-0 shadow">
                    <div class="modal-header bg-success text-white border-0">
                        <h5 class="modal-title"><i class="fas fa-check-circle me-2"></i>{{ __('messages.confirm_resolution') }}</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body text-center py-4">
                        <i class="fas fa-check-circle fa-3x text-success mb-3"></i>
                        <h6>{{ __('messages.mark_resolved_question') }}</h6>
                        <p class="text-muted mb-0">${assetName}</p>
                        <p class="small text-muted mt-2">{{ __('messages.resolve_warning') }}</p>
                    </div>
                    <div class="modal-footer border-0">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('messages.cancel') }}</button>
                        <button type="button" class="btn btn-success" onclick="resolveReport(${reportId})">
                            <i class="fas fa-check me-1"></i>{{ __('messages.resolve') }}
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

    function resolveReport(reportId) {
        const form = document.getElementById('resolveReportForm');
        form.action = '/reports/' + reportId + '/resolve';
        form.submit();
    }

    function confirmCancelReport(reportId, assetName) {
        const modal = document.createElement('div');
        modal.className = 'modal fade';
        modal.innerHTML = `
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content border-0 shadow">
                    <div class="modal-header bg-warning text-dark border-0">
                        <h5 class="modal-title"><i class="fas fa-exclamation-triangle me-2"></i>{{ __('messages.cancel_report') }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body text-center py-4">
                        <i class="fas fa-times-circle fa-3x text-warning mb-3"></i>
                        <h6>{{ __('messages.cancel_report_question') }}</h6>
                        <p class="text-muted mb-0">${assetName}</p>
                        <p class="small text-muted mt-2">{{ __('messages.cancel_report_warning') }}</p>
                    </div>
                    <div class="modal-footer border-0">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('messages.keep_report') }}</button>
                        <button type="button" class="btn btn-warning" onclick="cancelReport(${reportId})">
                            <i class="fas fa-times me-1"></i>{{ __('messages.cancel_report') }}
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

    function cancelReport(reportId) {
        const form = document.getElementById('cancelReportForm');
        form.action = '/reports/' + reportId;
        form.submit();
    }
    </script>
    @endpush
</x-app-layout>