<x-admin-layout>
    @include('revenue-matters.partials.theme-styles')

    <div class="pcoded-main-container revenue-report-container">
        <div class="pcoded-content">

            @php
                $sectionTitle = match ($tab) {
                    'forwarded' => 'Forwarded Complaints',
                    'all' => 'All Land Revenue Complaints',
                    default => 'Assigned Complaints',
                };
            @endphp

            <!-- [ breadcrumb ] start -->
            <x-breadcrumb :title="$sectionTitle" :breadcrumbs="[
                ['name' => 'Land Revenue Matters', 'allow' => true, 'link' => route('revenue-matters.dashboard')],
                ['name' => $sectionTitle, 'allow' => true, 'link' => '#'],
            ]" :button="(auth()->user()->can('Land Revenue Matters Create') || auth()->user()->can('Revenue Matters Create')) ? ['name' => 'Lodge New Complaint', 'allow' => true, 'link' => route('revenue-matters.complaints.create')] : null" />
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

            <!-- Search and Filter Bar -->
            <div class="card revenue-filter-card mb-3 shadow-sm">
                <div class="card-body py-3 px-3">
                    @if($tab === 'all')
                        {{-- All Complaints Comprehensive Monitoring Filter Form --}}
                        <form method="GET" action="{{ route('revenue-matters.complaints.index') }}" class="row g-2 align-items-center">
                            <input type="hidden" name="tab" value="all">
                            
                            <div class="col-xl-3 col-lg-3 col-md-6 col-12">
                                <div class="input-group">
                                    <span class="input-group-text"><i class="feather icon-search"></i></span>
                                    <input type="text" name="search" class="form-control" 
                                           placeholder="Search #, citizen, CNIC, phone, subject, address..." 
                                           value="{{ request('search') }}">
                                </div>
                            </div>

                            <div class="col-xl-2 col-lg-2 col-md-3 col-6">
                                <select name="status" class="form-select">
                                    <option value="">-- All Statuses --</option>
                                    <option value="open" {{ request('status') === 'open' ? 'selected' : '' }}>Open</option>
                                    <option value="in_process" {{ request('status') === 'in_process' ? 'selected' : '' }}>In Process</option>
                                    <option value="closed" {{ request('status') === 'closed' ? 'selected' : '' }}>Resolved</option>
                                </select>
                            </div>

                            <div class="col-xl-2 col-lg-2 col-md-3 col-6">
                                <select name="tehsil" class="form-select">
                                    <option value="">-- All Tehsils --</option>
                                    @foreach($tehsils as $t)
                                        <option value="{{ $t }}" {{ request('tehsil') === $t ? 'selected' : '' }}>{{ $t }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-xl-2 col-lg-2 col-md-6 col-6">
                                <select name="created_by" class="form-select">
                                    <option value="">-- Created By --</option>
                                    @foreach($officers as $officer)
                                        <option value="{{ $officer->id }}" {{ (string) request('created_by') === (string) $officer->id ? 'selected' : '' }}>
                                            {{ $officer->official_label }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-xl-2 col-lg-2 col-md-6 col-6">
                                <select name="current_holder" class="form-select">
                                    <option value="">-- Current Holder --</option>
                                    @foreach($officers as $officer)
                                        <option value="{{ $officer->id }}" {{ (string) request('current_holder') === (string) $officer->id ? 'selected' : '' }}>
                                            {{ $officer->official_label }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-xl-1 col-lg-1 col-md-12 col-12 d-flex gap-1">
                                <button type="submit" class="btn btn-primary flex-fill d-flex align-items-center justify-content-center" title="Apply Filters">
                                    <i class="feather icon-filter"></i>
                                    <span class="d-none d-md-inline ms-1 d-lg-none">Filter</span>
                                </button>
                                <a href="{{ route('revenue-matters.complaints.index', ['tab' => 'all']) }}" class="btn btn-outline-secondary d-flex align-items-center justify-content-center" title="Reset Filters">
                                    <i class="feather icon-rotate-ccw"></i>
                                </a>
                            </div>
                        </form>
                    @else
                        {{-- Assigned / Forwarded Filter Form --}}
                        <form method="GET" action="{{ route('revenue-matters.complaints.index') }}" class="row g-2 align-items-center">
                            <input type="hidden" name="tab" value="{{ $tab }}">
                            
                            <div class="col-md-5">
                                <div class="input-group">
                                    <span class="input-group-text"><i class="feather icon-search"></i></span>
                                    <input type="text" name="search" class="form-control" 
                                           placeholder="Search complaint #, citizen name, CNIC, phone, or subject..." 
                                           value="{{ request('search') }}">
                                </div>
                            </div>

                            <div class="col-md-3">
                                <select name="status" class="form-select">
                                    <option value="">-- All Statuses --</option>
                                    <option value="open" {{ request('status') === 'open' ? 'selected' : '' }}>Open</option>
                                    <option value="in_process" {{ request('status') === 'in_process' ? 'selected' : '' }}>In Process</option>
                                    <option value="closed" {{ request('status') === 'closed' ? 'selected' : '' }}>Closed</option>
                                </select>
                            </div>

                            <div class="col-md-2">
                                <select name="tehsil" class="form-select">
                                    <option value="">-- All Tehsils --</option>
                                    @foreach($tehsils as $t)
                                        <option value="{{ $t }}" {{ request('tehsil') === $t ? 'selected' : '' }}>{{ $t }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-2 d-flex gap-2">
                                <button type="submit" class="btn btn-primary">
                                    <i class="feather icon-filter me-1"></i> Filter
                                </button>
                                <a href="{{ route('revenue-matters.complaints.index', ['tab' => $tab]) }}" class="btn btn-outline-secondary">
                                    Reset
                                </a>
                            </div>
                        </form>
                    @endif
                </div>
            </div>

            @if($tab === 'all')
                {{-- Compact Officer Color Legend (All Complaints Report) --}}
                <div class="card officer-legend-card mb-3 shadow-sm">
                    <div class="card-body py-2 px-3">
                        <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                            <div class="d-flex align-items-center text-muted small fw-bold text-uppercase" style="letter-spacing: 0.5px;">
                                <i class="feather icon-users text-primary me-1"></i> Officer Color Legend
                                <span class="badge bg-light text-secondary border ms-2" style="font-size: 0.7rem; font-weight: 500; text-transform: none;">
                                    Row pastel background & left border indicate Current Holder
                                </span>
                            </div>
                            @if(request('current_holder'))
                                <div>
                                    <a href="{{ route('revenue-matters.complaints.index', array_merge(request()->except(['page', 'current_holder']), ['tab' => 'all'])) }}" class="badge bg-secondary text-white text-decoration-none py-1 px-2" style="font-size: 0.72rem;">
                                        <i class="feather icon-x me-1"></i> Clear officer filter
                                    </a>
                                </div>
                            @endif
                        </div>
                        <div class="d-flex align-items-center flex-wrap gap-2 mt-2">
                            @foreach($officers as $officer)
                                @php
                                    $officerTheme = \App\Services\Revenue\OfficerColorService::getThemeForUser($officer->id);
                                    $initials = \App\Services\Revenue\OfficerColorService::getInitials($officer->name);
                                    $isActiveFilter = (string) request('current_holder') === (string) $officer->id;
                                @endphp
                                <a href="{{ route('revenue-matters.complaints.index', array_merge(request()->except('page'), ['tab' => 'all', 'current_holder' => $isActiveFilter ? null : $officer->id])) }}"
                                   class="officer-legend-item {{ $isActiveFilter ? 'active-legend' : '' }}"
                                   title="Click to {{ $isActiveFilter ? 'clear filter' : 'filter complaints held by '.$officer->official_label }}"
                                   style="background-color: {{ $officerTheme['chip_bg'] }}; border: 1.5px solid {{ $isActiveFilter ? $officerTheme['border'] : $officerTheme['chip_border'] }}; color: {{ $officerTheme['chip_text'] }};">
                                    <span class="officer-legend-avatar" style="background-color: {{ $officerTheme['border'] }}; color: #ffffff;">
                                        {{ $initials }}
                                    </span>
                                    <span class="officer-legend-name fw-semibold">{{ $officer->official_label }}</span>
                                    @if($isActiveFilter)
                                        <i class="feather icon-check-circle ms-1" style="font-size: 0.75rem;"></i>
                                    @endif
                                </a>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif

            <!-- Complaints Table Card -->
            <div class="card report-table-card shadow-sm">
                <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
                    <div>
                        <h5 class="mb-1 text-dark fw-bold">
                            @if($tab === 'forwarded')
                                <i class="feather icon-send text-warning me-1"></i> Forwarded Complaints
                                <span class="badge bg-warning text-dark ms-1">{{ $counts['forwarded'] }}</span>
                            @elseif($tab === 'all')
                                <i class="feather icon-list text-primary me-1"></i> All Land Revenue Complaints (Monitoring & Oversight)
                                <span class="badge bg-primary ms-1">{{ $counts['all'] }}</span>
                            @else
                                <i class="feather icon-inbox text-primary me-1"></i> Assigned Complaints (Active Workload)
                                <span class="badge bg-primary ms-1">{{ $counts['assigned'] }}</span>
                            @endif
                        </h5>
                        <small class="text-muted">
                            @if($tab === 'forwarded')
                                Complaints forwarded by you to other officers (historical forwarding remains visible).
                            @elseif($tab === 'all')
                                Consolidated department-wide complaint register for Revenue Admin monitoring across all officers.
                            @else
                                Complaints currently in your custody awaiting review or action.
                            @endif
                        </small>
                    </div>
                    <div>
                        <a href="{{ route('revenue-matters.complaints.search') }}" class="btn btn-sm btn-outline-secondary">
                            <i class="feather icon-search me-1"></i> Advanced Search
                        </a>
                    </div>
                </div>
                <div class="card-body p-0 table-responsive-custom">
                    @if($tab === 'forwarded')
                        {{-- Forwarded View (with specific Forwarded To and Forwarded Date columns) --}}
                        <table class="table report-table table-hover align-middle mb-0">
                            <thead>
                                <tr>
                                    <th>Complaint #</th>
                                    <th>Citizen Name</th>
                                    <th>Subject</th>
                                    <th>Forwarded To</th>
                                    <th>Forwarded Date/Time</th>
                                    <th>Current Holder</th>
                                    <th>Status</th>
                                    <th class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($complaints as $complaint)
                                    @php
                                        $fLog = $complaint->latestForwardLogBy(auth()->id());
                                    @endphp
                                    <tr>
                                        <td>
                                            <a href="{{ route('revenue-matters.complaints.show', $complaint) }}" class="complaint-no-link">
                                                <i class="feather icon-hash text-muted small me-1"></i>{{ $complaint->complaint_number }}
                                            </a>
                                        </td>
                                        <td>
                                            <span class="fw-bold text-dark">{{ $complaint->citizen_name }}</span>
                                            @if($complaint->father_husband_name)
                                                <br><small class="text-muted"><span class="text-secondary">S/O, W/O:</span> {{ $complaint->father_husband_name }}</small>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="text-dark" title="{{ $complaint->subject }}">
                                                {{ Str::limit($complaint->subject, 35) }}
                                            </span>
                                        </td>
                                        <td>
                                            <strong class="text-dark">
                                                {{ $fLog?->toUser?->official_label ?? '—' }}
                                            </strong>
                                        </td>
                                        <td>
                                            <small class="text-muted">
                                                <i class="feather icon-clock me-1"></i>{{ $fLog?->created_at?->format('d M, Y h:i A') ?? '—' }}
                                            </small>
                                        </td>
                                        <td>
                                            <span class="badge bg-light text-dark border">
                                                {{ $complaint->currentHolder->official_label ?? 'Unassigned' }}
                                            </span>
                                            @if((int) $complaint->current_holder_id === (int) auth()->id())
                                                <span class="badge bg-success text-white ms-1" style="font-size: 0.7rem;">In your custody</span>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="badge {{ $complaint->status->badgeClass() }}">
                                                {{ $complaint->status->label() }}
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            <a href="{{ route('revenue-matters.complaints.show', $complaint) }}" class="btn btn-sm btn-primary px-3 shadow-sm rounded-pill">
                                                <i class="feather icon-eye me-1"></i> View
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center py-5 text-muted">
                                            <i class="feather icon-send display-4 text-black-50 mb-2"></i>
                                            <p class="mb-0 fw-semibold">No forwarded complaints found.</p>
                                            <small>Complaints you forward to other officers will appear here.</small>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    @elseif($tab === 'all')
                        {{-- Dedicated All Complaints Monitoring View (Revenue Admin Oversight) --}}
                        <table class="table report-table align-middle mb-0">
                            <thead>
                                <tr>
                                    <th>Complaint #</th>
                                    <th>Citizen Name</th>
                                    <th>Tehsil</th>
                                    <th>Subject</th>
                                    <th>Created By</th>
                                    <th>Current Holder</th>
                                    <th>Status</th>
                                    <th>Created Date</th>
                                    <th>Last Activity</th>
                                    <th class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($complaints as $complaint)
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
                                                <br><small class="text-muted"><span class="text-secondary">S/O, W/O:</span> {{ $complaint->father_husband_name }}</small>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="badge bg-white text-dark border px-2 py-1">
                                                <i class="feather icon-map-pin text-primary me-1"></i>{{ $complaint->tehsil ?? '—' }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="text-dark fw-medium" title="{{ $complaint->subject }}">
                                                {{ Str::limit($complaint->subject, 32) }}
                                            </span>
                                            @if($complaint->address)
                                                <br><small class="text-muted"><i class="feather icon-map-pin me-1 text-secondary"></i>{{ Str::limit($complaint->address, 25) }}</small>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="text-dark fw-semibold small">{{ $complaint->creator->official_label ?? '—' }}</span>
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
                                            @if((int) $complaint->current_holder_id === (int) auth()->id())
                                                <span class="badge bg-success text-white ms-1 align-middle" style="font-size: 0.68rem; font-weight: 600;">In your custody</span>
                                            @endif
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
                                            <span class="text-dark fw-semibold small d-block">{{ $complaint->created_at->format('d M, Y') }}</span>
                                            <small class="text-muted" style="font-size: 0.72rem;">{{ $complaint->created_at->diffForHumans() }}</small>
                                        </td>
                                        <td>
                                            @php
                                                $lastLog = $complaint->latestLog ?? $complaint->logs->last();
                                            @endphp
                                            @if($lastLog)
                                                <div class="last-activity-time text-nowrap">
                                                    <i class="feather icon-clock me-1 text-muted"></i>{{ $lastLog->created_at->format('d M, Y h:i A') }}
                                                </div>
                                                <div class="last-activity-desc text-nowrap">
                                                    {{ $lastLog->actionLabel() }}
                                                </div>
                                            @else
                                                <div class="last-activity-time text-nowrap">
                                                    <i class="feather icon-clock me-1 text-muted"></i>{{ $complaint->updated_at->format('d M, Y h:i A') }}
                                                </div>
                                                <div class="last-activity-desc text-nowrap">
                                                    Last Updated
                                                </div>
                                            @endif
                                        </td>
                                        <td class="text-center text-nowrap">
                                            <a href="{{ route('revenue-matters.complaints.show', $complaint) }}" class="btn btn-sm btn-primary px-3 shadow-sm rounded-pill">
                                                <i class="feather icon-eye me-1"></i> View
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="10" class="text-center py-5 text-muted">
                                            <i class="feather icon-inbox display-4 text-black-50 mb-2"></i>
                                            <p class="mb-0 fw-semibold">No Land Revenue complaints found.</p>
                                            <small>Try clearing or broadening your search criteria.</small>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    @else
                        {{-- Assigned View --}}
                        <table class="table report-table table-hover align-middle mb-0">
                            <thead>
                                <tr>
                                    <th>Complaint #</th>
                                    <th>Citizen Name</th>
                                    <th>CNIC / Contact</th>
                                    <th>Tehsil</th>
                                    <th>Subject</th>
                                    <th>Original Creator</th>
                                    <th>Status</th>
                                    <th>Date</th>
                                    <th class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($complaints as $complaint)
                                    <tr>
                                        <td>
                                            <a href="{{ route('revenue-matters.complaints.show', $complaint) }}" class="complaint-no-link">
                                                <i class="feather icon-hash text-muted small me-1"></i>{{ $complaint->complaint_number }}
                                            </a>
                                        </td>
                                        <td>
                                            <span class="fw-bold text-dark">{{ $complaint->citizen_name }}</span>
                                            @if($complaint->father_husband_name)
                                                <br><small class="text-muted"><span class="text-secondary">S/O, W/O:</span> {{ $complaint->father_husband_name }}</small>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="fw-semibold text-dark">{{ $complaint->cnic ?? '—' }}</div>
                                            <small class="text-muted">{{ $complaint->contact_number ?? '—' }}</small>
                                        </td>
                                        <td>
                                            <span class="badge bg-light text-dark border px-2 py-1">
                                                <i class="feather icon-map-pin text-primary me-1"></i>{{ $complaint->tehsil ?? '—' }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="text-dark" title="{{ $complaint->subject }}">
                                                {{ Str::limit($complaint->subject, 35) }}
                                            </span>
                                        </td>
                                        <td>
                                            <strong class="text-dark">{{ $complaint->creator->official_label ?? '—' }}</strong>
                                        </td>
                                        <td>
                                            <span class="badge {{ $complaint->status->badgeClass() }}">
                                                {{ $complaint->status->label() }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="text-dark fw-medium">{{ $complaint->created_at->format('d M, Y') }}</span>
                                        </td>
                                        <td class="text-center">
                                            <a href="{{ route('revenue-matters.complaints.show', $complaint) }}" class="btn btn-sm btn-primary px-3 shadow-sm rounded-pill">
                                                <i class="feather icon-eye me-1"></i> View
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="9" class="text-center py-5 text-muted">
                                            <i class="feather icon-inbox display-4 text-black-50 mb-2"></i>
                                            <p class="mb-0 fw-semibold">No complaints assigned to you.</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    @endif
                </div>

                @if($complaints->hasPages())
                    <div class="card-footer bg-white d-flex justify-content-between align-items-center flex-wrap gap-2 py-3 px-3 border-top">
                        <div class="text-muted small">
                            Showing {{ $complaints->firstItem() }} to {{ $complaints->lastItem() }} of {{ $complaints->total() }} records
                        </div>
                        <div>
                            {{ $complaints->links() }}
                        </div>
                    </div>
                @endif
            </div>

        </div>
    </div>
</x-admin-layout>
