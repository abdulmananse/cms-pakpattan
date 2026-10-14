<x-admin-layout>
    @include('revenue-matters.partials.theme-styles')

    <div class="pcoded-main-container revenue-dashboard-container">
        <div class="pcoded-content">

            <!-- [ breadcrumb ] start -->
            <x-breadcrumb title="Land Revenue Matters - Dashboard" :breadcrumbs="[
                ['name' => 'Land Revenue Matters', 'allow' => true, 'link' => route('revenue-matters.dashboard')],
                ['name' => 'Dashboard', 'allow' => true, 'link' => '#'],
            ]" />
            <!-- [ breadcrumb ] end -->

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="feather icon-check-circle me-1"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="feather icon-alert-triangle me-1"></i> {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <!-- Metric Cards (Highest Priority: 17-18px label, 30-36px number, 14-15px desc, 24-28px icon) -->
            <div class="row g-3 mb-4">
                {{-- Assigned / Inbox Card --}}
                <div class="col-sm-6 col-xl-3">
                    <div class="card stat-card stat-card-blue shadow-sm h-100">
                        <div class="card-body p-3 d-flex flex-column justify-content-between">
                            <div class="d-flex align-items-start justify-content-between">
                                <div>
                                    <div class="stat-card-title">Assigned (Inbox)</div>
                                    <div class="stat-card-value">{{ $assignedCount }}</div>
                                    <div class="stat-card-desc">Currently in your custody</div>
                                </div>
                                <div class="stat-icon-wrapper">
                                    <i class="feather icon-inbox"></i>
                                </div>
                            </div>
                            <div class="mt-3">
                                <a href="{{ route('revenue-matters.complaints.index', ['tab' => 'assigned']) }}" class="stat-link">
                                    View Inbox <i class="feather icon-arrow-right"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Forwarded Card --}}
                <div class="col-sm-6 col-xl-3">
                    <div class="card stat-card stat-card-amber shadow-sm h-100">
                        <div class="card-body p-3 d-flex flex-column justify-content-between">
                            <div class="d-flex align-items-start justify-content-between">
                                <div>
                                    <div class="stat-card-title">Forwarded</div>
                                    <div class="stat-card-value">{{ $forwardedCount }}</div>
                                    <div class="stat-card-desc">Forwarded to other officers</div>
                                </div>
                                <div class="stat-icon-wrapper">
                                    <i class="feather icon-send"></i>
                                </div>
                            </div>
                            <div class="mt-3">
                                <a href="{{ route('revenue-matters.complaints.index', ['tab' => 'forwarded']) }}" class="stat-link">
                                    Track Forwarded <i class="feather icon-arrow-right"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Resolved / Closed Card --}}
                <div class="col-sm-6 col-xl-3">
                    <div class="card stat-card stat-card-emerald shadow-sm h-100">
                        <div class="card-body p-3 d-flex flex-column justify-content-between">
                            <div class="d-flex align-items-start justify-content-between">
                                <div>
                                    <div class="stat-card-title">Resolved / Closed</div>
                                    <div class="stat-card-value">{{ $closedCount }}</div>
                                    <div class="stat-card-desc">Completed matters</div>
                                </div>
                                <div class="stat-icon-wrapper">
                                    <i class="feather icon-check-circle"></i>
                                </div>
                            </div>
                            <div class="mt-3">
                                <a href="{{ route('revenue-matters.complaints.search', ['status' => 'closed']) }}" class="stat-link">
                                    Search Resolved <i class="feather icon-arrow-right"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Total Purview Card --}}
                <div class="col-sm-6 col-xl-3">
                    <div class="card stat-card stat-card-indigo shadow-sm h-100">
                        <div class="card-body p-3 d-flex flex-column justify-content-between">
                            <div class="d-flex align-items-start justify-content-between">
                                <div>
                                    <div class="stat-card-title">Total Purview</div>
                                    <div class="stat-card-value">{{ $totalAccessibleCount }}</div>
                                    <div class="stat-card-desc">Matters in your purview</div>
                                </div>
                                <div class="stat-icon-wrapper">
                                    <i class="feather icon-folder"></i>
                                </div>
                            </div>
                            <div class="mt-3">
                                @canany(['Land Revenue Matters Create', 'Revenue Matters Create'])
                                    <a href="{{ route('revenue-matters.complaints.create') }}" class="btn-create-stat">
                                        <i class="feather icon-plus me-1"></i> Lodge New Complaint
                                    </a>
                                @else
                                    <a href="{{ route('revenue-matters.complaints.search') }}" class="stat-link">
                                        Search Records <i class="feather icon-arrow-right"></i>
                                    </a>
                                @endcan
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Complaints (Table headings 15-16px bold, content 15-16px, numbers 16px, holder 15-16px, badges 14px) -->
            <div class="row">
                <div class="col-xl-12">
                    <div class="card report-table-card shadow-sm">
                        <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
                            <div>
                                <h5 class="mb-1 text-dark fw-bold" style="font-size: 18px;">
                                    <i class="feather icon-clock text-primary me-2"></i>Recent Land Revenue Matters
                                </h5>
                                <small class="text-muted" style="font-size: 14.5px;">Latest 10 complaints lodged or active in your jurisdiction</small>
                            </div>
                            <div class="d-flex gap-2">
                                <a href="{{ route('revenue-matters.complaints.search') }}" class="btn btn-outline-secondary" style="font-size: 15px; font-weight: 500; padding: 0.45rem 1rem;">
                                    <i class="feather icon-search me-1"></i> Search
                                </a>
                                <a href="{{ route('revenue-matters.complaints.index', ['tab' => 'assigned']) }}" class="btn btn-primary" style="font-size: 15px; font-weight: 600; padding: 0.45rem 1rem;">
                                    <i class="feather icon-inbox me-1"></i> Go to Inbox
                                </a>
                            </div>
                        </div>
                        <div class="card-body p-0 table-responsive-custom">
                            <table class="table report-table align-middle mb-0">
                                <thead>
                                    <tr>
                                        <th>Complaint #</th>
                                        <th>Citizen</th>
                                        <th>CNIC</th>
                                        <th>Subject</th>
                                        <th>Current Holder</th>
                                        <th>Status</th>
                                        <th>Date</th>
                                        <th class="text-center">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($recentComplaints as $complaint)
                                        @php
                                            $holderId = $complaint->current_holder_id;
                                            $theme = \App\Services\Revenue\OfficerColorService::getThemeForUser($holderId);
                                            $initials = $complaint->currentHolder ? \App\Services\Revenue\OfficerColorService::getInitials($complaint->currentHolder->name) : '—';
                                            $isClosed = $complaint->status === \App\Enums\RevenueComplaintStatus::CLOSED;
                                            $isReopened = !$isClosed && $complaint->isReopened();
                                            $isOverdue = !$isClosed && $complaint->isOverdue();
                                        @endphp
                                        <tr class="table-officer-row" style="--row-officer-bg: {{ $theme['bg'] }}; --row-officer-border: {{ $theme['border'] }};">
                                            <td>
                                                <a href="{{ route('revenue-matters.complaints.show', $complaint) }}" class="complaint-no-link">
                                                    <i class="feather icon-hash text-muted small me-1"></i>{{ $complaint->complaint_number }}
                                                </a>
                                            </td>
                                            <td>
                                                <span class="fw-bold text-dark">{{ $complaint->citizen_name }}</span>
                                                @if($complaint->father_husband_name)
                                                    <br><small class="text-muted"><span class="text-secondary">S/O, D/O, W/O:</span> {{ $complaint->father_husband_name }}</small>
                                                @endif
                                            </td>
                                            <td>
                                                <span class="fw-semibold text-dark">{{ $complaint->cnic ?? '—' }}</span>
                                                @if($complaint->contact_number)
                                                    <br><small class="text-muted">{{ $complaint->contact_number }}</small>
                                                @endif
                                            </td>
                                            <td>
                                                <span class="text-dark fw-medium" title="{{ $complaint->subject }}">
                                                    {{ Str::limit($complaint->subject, 36) }}
                                                </span>
                                            </td>
                                            <td>
                                                <div class="officer-holder-pill" style="background-color: {{ $theme['chip_bg'] }}; border: 1px solid {{ $theme['chip_border'] }}; color: {{ $theme['chip_text'] }};" title="Current Holder: {{ $complaint->currentHolder->official_label ?? 'Unassigned' }}">
                                                    <span class="officer-holder-avatar" style="background-color: {{ $theme['border'] }}; color: #ffffff;">
                                                        {{ $initials }}
                                                    </span>
                                                    <span class="officer-holder-label">
                                                        {{ $complaint->currentHolder->official_label ?? 'Unassigned' }}
                                                    </span>
                                                </div>
                                            </td>
                                            <td>
                                                @if($isClosed)
                                                    <span class="badge status-badge-resolved">Resolved</span>
                                                @elseif($isReopened)
                                                    <span class="badge status-badge-reopened">Reopened</span>
                                                @elseif($complaint->status === \App\Enums\RevenueComplaintStatus::OPEN)
                                                    <span class="badge status-badge-open">Open</span>
                                                @else
                                                    <span class="badge status-badge-in-process">In Process</span>
                                                @endif

                                                @if($isOverdue)
                                                    <span class="badge status-badge-overdue ms-1" title="Pending for over 7 days">Overdue</span>
                                                @endif
                                            </td>
                                            <td>
                                                <span class="text-dark fw-semibold d-block">{{ $complaint->created_at->format('d M, Y') }}</span>
                                                <small class="text-muted" style="font-size: 13.5px;">{{ $complaint->created_at->diffForHumans() }}</small>
                                            </td>
                                            <td class="text-center text-nowrap">
                                                <a href="{{ route('revenue-matters.complaints.show', $complaint) }}" class="btn btn-primary px-3 shadow-sm rounded-pill" style="font-size: 15px; font-weight: 600;">
                                                    <i class="feather icon-eye me-1"></i> View
                                                </a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="8" class="text-center py-5 text-muted" style="font-size: 15.5px;">
                                                <i class="feather icon-inbox display-4 text-black-50 mb-2"></i>
                                                <p class="mb-0 fw-semibold">No Land Revenue Matters found.</p>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-admin-layout>
