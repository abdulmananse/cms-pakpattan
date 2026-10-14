<x-admin-layout>
    @include('revenue-matters.partials.theme-styles')

    <div class="pcoded-main-container">
        <div class="pcoded-content">

            <!-- [ breadcrumb ] start -->
            <x-breadcrumb title="Search Land Revenue Matters" :breadcrumbs="[
                ['name' => 'Land Revenue Matters', 'allow' => true, 'link' => route('revenue-matters.dashboard')],
                ['name' => 'Search', 'allow' => true, 'link' => '#'],
            ]" />
            <!-- [ breadcrumb ] end -->

            <!-- Search Form Card -->
            <div class="card mb-4 shadow-sm border-0 revenue-filter-card">
                <div class="card-header bg-light">
                    <h5 class="mb-0 text-dark">
                        <i class="feather icon-search me-1"></i> Search Land Revenue Complaints
                    </h5>
                    <small class="text-muted">Search and filter complaints across citizen details, location, and matter subjects.</small>
                </div>
                <div class="card-body">
                    <form method="GET" action="{{ route('revenue-matters.complaints.search') }}">
                        <div class="row g-3">
                            <div class="col-md-3">
                                <label for="complaint_number" class="form-label fw-bold">Complaint #</label>
                                <input type="text" name="complaint_number" id="complaint_number" class="form-control" 
                                       placeholder="e.g. REV-YYYY-XXXXX" value="{{ request('complaint_number') }}">
                            </div>

                            <div class="col-md-3">
                                <label for="citizen_name" class="form-label fw-bold">Citizen Name</label>
                                <input type="text" name="citizen_name" id="citizen_name" class="form-control" 
                                       placeholder="Citizen name" value="{{ request('citizen_name') }}">
                            </div>

                            <div class="col-md-3">
                                <label for="father_husband_name" class="form-label fw-bold">Father / Husband</label>
                                <input type="text" name="father_husband_name" id="father_husband_name" class="form-control" 
                                       placeholder="Father or husband name" value="{{ request('father_husband_name') }}">
                            </div>

                            <div class="col-md-3">
                                <label for="cnic" class="form-label fw-bold">CNIC</label>
                                <input type="text" name="cnic" id="cnic" class="form-control" 
                                       placeholder="35202-xxxxxxx-x" value="{{ request('cnic') }}">
                            </div>

                            <div class="col-md-3">
                                <label for="contact_number" class="form-label fw-bold">Contact Number</label>
                                <input type="text" name="contact_number" id="contact_number" class="form-control" 
                                       placeholder="Phone number" value="{{ request('contact_number') }}">
                            </div>

                            <div class="col-md-3">
                                <label for="tehsil" class="form-label fw-bold">Tehsil</label>
                                <input type="text" name="tehsil" id="tehsil" class="form-control" 
                                       placeholder="Tehsil name" value="{{ request('tehsil') }}">
                            </div>

                            <div class="col-md-3">
                                <label for="address" class="form-label fw-bold">Address / Mauza</label>
                                <input type="text" name="address" id="address" class="form-control" 
                                       placeholder="Village, Mauza or address" value="{{ request('address') }}">
                            </div>

                            <div class="col-md-3">
                                <label for="status" class="form-label fw-bold">Status</label>
                                <select name="status" id="status" class="form-select">
                                    <option value="">-- All Statuses --</option>
                                    <option value="open" {{ request('status') === 'open' ? 'selected' : '' }}>Open</option>
                                    <option value="in_process" {{ request('status') === 'in_process' ? 'selected' : '' }}>In Process</option>
                                    <option value="closed" {{ request('status') === 'closed' ? 'selected' : '' }}>Closed</option>
                                </select>
                            </div>

                            <div class="col-md-12">
                                <label for="subject" class="form-label fw-bold">Subject / Matter Description</label>
                                <input type="text" name="subject" id="subject" class="form-control" 
                                       placeholder="Keywords from complaint subject or matter details" value="{{ request('subject') }}">
                            </div>
                        </div>

                        <div class="d-flex justify-content-end gap-2 mt-4 pt-2 border-top">
                            <a href="{{ route('revenue-matters.complaints.search') }}" class="btn btn-outline-secondary revenue-form-btn">
                                <i class="feather icon-rotate-ccw me-1"></i> Clear Filters
                            </a>
                            <button type="submit" class="btn btn-primary revenue-form-btn">
                                <i class="feather icon-search me-1"></i> Search Complaints
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Search Results Card -->
            <div class="card shadow-sm border-0 report-table-card">
                <div class="card-header bg-light d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 text-dark fw-bold" style="font-size: 17px;">
                        <i class="feather icon-list me-1"></i> Search Results
                        @if($hasSearch && $complaints)
                            <span class="badge bg-primary ms-1" style="font-size: 14px;">{{ $complaints->total() }}</span>
                        @endif
                    </h5>
                    @if($hasSearch && $complaints)
                        <small class="text-muted" style="font-size: 14px;">Found {{ $complaints->total() }} matching records</small>
                    @endif
                </div>
                <div class="card-body p-0 table-responsive-custom">
                    @if($hasSearch)
                        <table class="table table-hover align-middle mb-0 report-table">
                            <thead class="table-light">
                                <tr>
                                    <th>Complaint #</th>
                                    <th>Citizen Name</th>
                                    <th>CNIC / Contact</th>
                                    <th>Tehsil / Mauza</th>
                                    <th>Subject</th>
                                    <th>Current Holder</th>
                                    <th>Status</th>
                                    <th>Date</th>
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
                                            <div class="fw-semibold text-dark">{{ $complaint->cnic ?? '—' }}</div>
                                            <small class="text-muted">{{ $complaint->contact_number ?? '—' }}</small>
                                        </td>
                                        <td>
                                            <span class="badge bg-white text-dark border px-2 py-1" style="font-size: 13.5px;">
                                                <i class="feather icon-map-pin text-primary me-1"></i>{{ $complaint->tehsil ?? '—' }}
                                            </span>
                                            @if($complaint->address)
                                                <br><small class="text-muted" title="{{ $complaint->address }}">{{ Str::limit($complaint->address, 25) }}</small>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="text-dark fw-medium" title="{{ $complaint->subject }}">
                                                {{ Str::limit($complaint->subject, 35) }}
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
                                            <span class="text-dark fw-semibold d-block" style="font-size: 14px;">{{ $complaint->created_at->format('d M, Y') }}</span>
                                            <small class="text-muted" style="font-size: 13px;">{{ $complaint->created_at->diffForHumans() }}</small>
                                        </td>
                                        <td class="text-center text-nowrap">
                                            <a href="{{ route('revenue-matters.complaints.show', $complaint) }}" class="btn btn-primary px-3 shadow-sm rounded-pill" style="font-size: 14px; padding: 0.35rem 0.85rem;">
                                                <i class="feather icon-eye me-1"></i> View
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="9" class="text-center py-5 text-muted">
                                            <i class="feather icon-slash display-4 text-black-50 mb-2"></i>
                                            <p class="mb-0 fw-semibold">No Land Revenue complaints found matching your search criteria.</p>
                                            <small>Try broadening your search terms or clearing specific filters.</small>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    @else
                        <div class="text-center py-5 text-muted">
                            <i class="feather icon-search display-4 text-black-50 mb-3"></i>
                            <p class="mb-1 fw-semibold">Enter your search criteria above to locate Land Revenue complaints.</p>
                            <small>You can search by complaint number, citizen identity, phone, tehsil, mauza, subject, or status.</small>
                        </div>
                    @endif
                </div>

                @if($hasSearch && $complaints && $complaints->hasPages())
                    <div class="card-footer d-flex justify-content-between align-items-center">
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
