<x-admin-layout>
    @include('revenue-matters.partials.theme-styles')

    <div class="pcoded-main-container">
        <div class="pcoded-content">

            <!-- [ Merged Header & Custody Card ] start -->
            <div class="card mb-3 shadow-sm" style="background-color: #edf5fc; border: 1px solid #d2e3f3;">
                <div class="card-body p-3">
                    <!-- Title & Breadcrumb -->
                    <div class="d-flex flex-wrap justify-content-between align-items-center mb-2">
                        <div>
                            <h5 class="mb-1 complaint-main-heading">
                                Land Revenue Complaint {{ $complaint->complaint_number }}
                            </h5>
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb mb-0 py-0">
                                    <li class="breadcrumb-item"><a href="{{ auth()->check() && auth()->user()->isRevenueOnly() ? route('revenue-matters.dashboard') : route('dashboard') }}" class="text-decoration-none"><i class="feather icon-home"></i></a></li>
                                    <li class="breadcrumb-item"><a href="{{ route('revenue-matters.dashboard') }}" class="text-decoration-none">Land Revenue Matters</a></li>
                                    <li class="breadcrumb-item"><a href="{{ route('revenue-matters.complaints.index') }}" class="text-decoration-none">Complaints</a></li>
                                    <li class="breadcrumb-item active text-muted" aria-current="page">{{ $complaint->complaint_number }}</li>
                                </ol>
                            </nav>
                        </div>
                        <div>
                            <a href="{{ route('revenue-matters.complaints.index') }}" class="btn btn-outline-secondary bg-white fw-semibold" style="font-size: 15.5px; padding: 0.5rem 1rem;">
                                <i class="feather icon-arrow-left me-1"></i> Back to List
                            </a>
                        </div>
                    </div>

                    <hr class="my-2 text-muted opacity-25">

                    <!-- Custody Grid: Current Holder, Status, Original Creator -->
                    <div class="row g-3 align-items-center pt-1">
                        <div class="col-md-5">
                            <span class="text-uppercase text-muted fw-bold d-block" style="font-size: 15px; letter-spacing: 0.5px;">Current Holder</span>
                            <span class="fw-bold text-primary" style="font-size: 18px;">
                                {{ $complaint->currentHolder->official_label ?? 'Unassigned' }}
                            </span>
                            @if((int) $complaint->current_holder_id === (int) auth()->id())
                                <span class="custody-badge-active ms-1 align-middle">
                                    <i class="feather icon-check-circle me-1"></i> Currently in your custody
                                </span>
                            @endif
                        </div>

                        <div class="col-md-3">
                            <span class="text-uppercase text-muted fw-bold d-block" style="font-size: 15px; letter-spacing: 0.5px;">Status</span>
                            @php
                                $statusBadgeClass = match($complaint->status->value) {
                                    'open' => 'status-badge-open',
                                    'in_process' => 'status-badge-in-process',
                                    'closed' => 'status-badge-resolved',
                                    default => 'status-badge-in-process',
                                };
                                if ($complaint->isReopened()) {
                                    $statusBadgeClass = 'status-badge-reopened';
                                } elseif ($complaint->isOverdue()) {
                                    $statusBadgeClass = 'status-badge-overdue';
                                }
                            @endphp
                            <span class="badge {{ $statusBadgeClass }} align-middle">
                                {{ $complaint->status === \App\Enums\RevenueComplaintStatus::CLOSED ? 'Resolved' : $complaint->status->label() }}
                            </span>
                            @if($complaint->status === \App\Enums\RevenueComplaintStatus::CLOSED && $complaint->closedBy)
                                <small class="text-muted d-block mt-1 date-time-text">
                                    Resolved by {{ $complaint->closedBy->official_label }}
                                </small>
                            @endif
                        </div>

                        <div class="col-md-4">
                            <span class="text-uppercase text-muted fw-bold d-block" style="font-size: 15px; letter-spacing: 0.5px;">Original Creator</span>
                            <strong class="text-dark d-block" style="font-size: 18px;">{{ $complaint->creator->official_label ?? '—' }}</strong>
                            <span class="text-muted d-block date-time-text">{{ $complaint->created_at->format('d M, Y h:i A') }}</span>
                        </div>
                    </div>
                </div>
            </div>
            <!-- [ Merged Header & Custody Card ] end -->

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="feather icon-check-circle me-1"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="feather icon-alert-circle me-1"></i> {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if ($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div class="row">
                <!-- Complaint Details Column -->
                <div class="col-lg-6 mb-4">
                    <div class="card h-100 shadow-sm" style="background-color: #fdfaf3; border: 1px solid #ebdcc6;">
                        <div class="card-header d-flex justify-content-between align-items-center" style="background-color: #f7f0e1; border-bottom: 1px solid #ebdcc6;">
                            <h5 class="mb-0 text-dark fw-bold" style="font-size: 20px;"><i class="feather icon-info me-1"></i> Citizen & Matter Details</h5>
                        </div>
                        <div class="card-body" style="background-color: #fdfaf3;">
                            <table class="table table-bordered mb-0 bg-white details-table">
                                <tbody>
                                    <tr>
                                        <th class="table-light w-35 fw-bold text-dark" style="background-color: #f8fafc;">Complaint #</th>
                                        <td class="fw-bold text-primary bg-white">{{ $complaint->complaint_number }}</td>
                                    </tr>
                                    <tr>
                                        <th class="table-light fw-bold text-dark" style="background-color: #f8fafc;">Citizen Name</th>
                                        <td class="bg-white">{{ $complaint->citizen_name }}</td>
                                    </tr>
                                    <tr>
                                        <th class="table-light fw-bold text-dark" style="background-color: #f8fafc;">Father / Husband</th>
                                        <td class="bg-white">{{ $complaint->father_husband_name ?? '—' }}</td>
                                    </tr>
                                    <tr>
                                        <th class="table-light fw-bold text-dark" style="background-color: #f8fafc;">CNIC</th>
                                        <td class="bg-white">{{ $complaint->cnic ?? '—' }}</td>
                                    </tr>
                                    <tr>
                                        <th class="table-light fw-bold text-dark" style="background-color: #f8fafc;">Contact Number</th>
                                        <td class="bg-white">{{ $complaint->contact_number ?? '—' }}</td>
                                    </tr>
                                    <tr>
                                        <th class="table-light fw-bold text-dark" style="background-color: #f8fafc;">Tehsil</th>
                                        <td class="bg-white">{{ $complaint->tehsil ?? '—' }}</td>
                                    </tr>
                                    <tr>
                                        <th class="table-light fw-bold text-dark" style="background-color: #f8fafc;">Address / Mauza</th>
                                        <td class="bg-white">{{ $complaint->address ?? '—' }}</td>
                                    </tr>
                                    <tr>
                                        <th class="table-light fw-bold text-dark" style="background-color: #f8fafc;">Subject</th>
                                        <td class="fw-bold text-dark bg-white">{{ $complaint->subject }}</td>
                                    </tr>
                                    <tr>
                                        <th class="table-light fw-bold text-dark" style="background-color: #f8fafc;">Description</th>
                                        <td class="bg-white" style="white-space: pre-wrap; line-height: 1.65; font-size: 17px;">{{ $complaint->description }}</td>
                                    </tr>
                                </tbody>
                            </table>

                            <!-- Initial Attachments -->
                            @php
                                $initialAttachments = $complaint->attachments->where('attachment_type', 'initial');
                            @endphp
                            @if($initialAttachments->count() > 0)
                                <div class="mt-3">
                                    <h6 class="fw-bold text-dark mb-2" style="font-size: 17.5px;">
                                        <i class="feather icon-paperclip me-1"></i> Initial Complaint Attachments
                                    </h6>
                                    <div class="d-flex flex-wrap gap-2">
                                        @foreach($initialAttachments as $att)
                                            <a href="{{ asset('storage/' . $att->file_path) }}" target="_blank" 
                                               class="btn btn-outline-secondary bg-white d-inline-flex align-items-center"
                                               style="font-size: 15.5px; padding: 0.45rem 0.85rem;">
                                                <i class="feather icon-file me-1"></i>
                                                <span class="fw-semibold">{{ Str::limit($att->original_name, 30) }}</span>
                                                @if($att->file_size)
                                                    <small class="text-muted ms-1" style="font-size: 14px;">({{ $att->formattedSize() }})</small>
                                                @endif
                                            </a>
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            @if($complaint->closing_comments)
                                <div class="mt-3 p-3 bg-white border-start border-success border-4 rounded shadow-sm">
                                    <h6 class="text-success fw-bold mb-2" style="font-size: 17.5px;">
                                        <i class="feather icon-check-circle me-1"></i> Final Resolution Comments
                                    </h6>
                                    <p class="mb-0 text-dark" style="font-size: 17px; line-height: 1.6;">{{ $complaint->closing_comments }}</p>
                                    @php
                                        $closingAtt = $complaint->attachments->where('attachment_type', 'closing')->first();
                                    @endphp
                                    @if($closingAtt)
                                        <div class="mt-2">
                                            <a href="{{ asset('storage/' . $closingAtt->file_path) }}" target="_blank" 
                                               class="btn btn-outline-success bg-white d-inline-flex align-items-center"
                                               style="font-size: 15.5px; padding: 0.45rem 0.85rem;">
                                                <i class="feather icon-download me-1"></i> <span class="fw-semibold">{{ $closingAtt->original_name }}</span>
                                            </a>
                                        </div>
                                    @endif
                                </div>
                            @endif

                            <!-- Workflow Action Buttons -->
                            @php
                                $canForward = $complaint->canBeForwardedBy(auth()->user());
                                $canSendBack = $complaint->canBeSentBackBy(auth()->user());
                                $canComment = $complaint->canBeCommentedBy(auth()->user());
                                $canClose = $complaint->canBeClosedBy(auth()->user());
                                $canReopen = $complaint->canBeReopenedBy(auth()->user());
                            @endphp

                            @if($canForward || $canSendBack || $canComment || $canClose || $canReopen)
                                <hr class="my-4 text-muted opacity-25">
                                <div class="d-flex flex-wrap justify-content-center align-items-center gap-3 pt-1 pb-1">
                                    @if($canForward)
                                        <button type="button" class="btn btn-warning text-dark workflow-btn shadow-sm" data-bs-toggle="modal" data-bs-target="#forwardModal">
                                            <i class="feather icon-send me-1"></i> Forward Complaint
                                        </button>
                                    @endif

                                    @if($canSendBack)
                                        <button type="button" class="btn workflow-btn shadow-sm" style="background-color: #f1f5f9; color: #1e293b; border: 1.5px solid #cbd5e1;" data-bs-toggle="modal" data-bs-target="#sendBackModal">
                                            <i class="feather icon-corner-up-left me-1"></i> Send Back to Previous Officer
                                        </button>
                                    @endif

                                    @if($canComment)
                                        <button type="button" class="btn btn-outline-primary bg-white text-primary workflow-btn shadow-sm" data-bs-toggle="modal" data-bs-target="#commentModal">
                                            <i class="feather icon-message-square me-1"></i> Add Progress Comment
                                        </button>
                                    @endif

                                    @if($canClose)
                                        <button type="button" class="btn workflow-btn shadow-sm" style="background-color: #ecfdf5; color: #065f46; border: 1.5px solid #34d399;" data-bs-toggle="modal" data-bs-target="#closeModal">
                                            <i class="feather icon-check-circle me-1"></i> Resolve Complaint
                                        </button>
                                    @endif

                                    @if($canReopen)
                                        <button type="button" class="btn workflow-btn shadow-sm" style="background-color: #fef2f2; color: #991b1b; border: 1.5px solid #f87171;" data-bs-toggle="modal" data-bs-target="#reopenModal">
                                            <i class="feather icon-refresh-cw me-1"></i> Reopen Complaint
                                        </button>
                                    @endif
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Timeline / Audit Trail Column -->
                <div class="col-lg-6 mb-4">
                    <div class="card h-100 shadow-sm" style="background-color: #eef2f5; border: 1px solid #d2dce6;">
                        <div class="card-header d-flex justify-content-between align-items-center" style="background-color: #e2e8f0; border-bottom: 1px solid #d2dce6;">
                            <h5 class="mb-0 text-dark fw-bold" style="font-size: 20px;"><i class="feather icon-activity me-1"></i> Complete Audit Trail</h5>
                            <small class="text-muted fw-semibold" style="font-size: 15px;">{{ $complaint->logs->count() }} Events</small>
                        </div>
                        <div class="card-body" style="background-color: #eef2f5;">
                            @include('revenue-matters.partials.timeline')
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <!-- Modals -->

    <!-- Forward Modal -->
    @if($canForward)
        <div class="modal fade" id="forwardModal" tabindex="-1" aria-labelledby="forwardModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <form action="{{ route('revenue-matters.complaints.forward', $complaint) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="modal-header bg-info text-white">
                            <h5 class="modal-title text-white fw-bold" id="forwardModalLabel" style="font-size: 18px;">
                                <i class="feather icon-send me-1"></i> Forward Land Revenue Complaint
                            </h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="recipient_id" class="form-label fw-bold">Select Authorized Revenue Officer <span class="text-danger">*</span></label>
                                <select name="recipient_id" id="recipient_id" class="form-select" required>
                                    <option value="">-- Choose Revenue Officer --</option>
                                    @foreach($potentialRecipients as $officer)
                                        <option value="{{ $officer->id }}">{{ $officer->official_label }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="instructions" class="form-label fw-bold">Instructions / Forwarding Remarks <span class="text-danger">*</span></label>
                                <textarea name="instructions" id="instructions" class="form-control" rows="4" 
                                          placeholder="Enter forwarding instructions for the officer..." required></textarea>
                            </div>

                            <div class="mb-3">
                                <label for="forward_attachments" class="form-label fw-bold">Attach Documents (Optional)</label>
                                <input type="file" name="attachments[]" id="forward_attachments" class="form-control" multiple accept=".pdf,.jpg,.jpeg,.png,.doc,.docx">
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary revenue-form-btn" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-info text-white revenue-form-btn">
                                <i class="feather icon-send me-1"></i> Forward
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif

    <!-- Send Back Modal -->
    @if($canSendBack)
        <div class="modal fade" id="sendBackModal" tabindex="-1" aria-labelledby="sendBackModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <form action="{{ route('revenue-matters.complaints.sendBack', $complaint) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="modal-header bg-warning text-dark">
                            <h5 class="modal-title fw-bold" id="sendBackModalLabel" style="font-size: 18px;">
                                <i class="feather icon-corner-up-left me-1"></i> Send Back Land Revenue Complaint
                            </h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="alert alert-info py-2" style="font-size: 14.5px;">
                                <i class="feather icon-info me-1"></i> This complaint will be sent back to:
                                <strong>{{ $previousHolder ? $previousHolder->official_label : 'Previous Holder' }}</strong>
                            </div>

                            <div class="mb-3">
                                <label for="report" class="form-label fw-bold">Report / Findings / Reason <span class="text-danger">*</span></label>
                                <textarea name="report" id="report" class="form-control" rows="4" 
                                          placeholder="Detail your findings, inquiry report, or reason for sending back..." required></textarea>
                            </div>

                            <div class="mb-3">
                                <label for="sendback_attachments" class="form-label fw-bold">Attach Inquiry Report / Evidence (Optional)</label>
                                <input type="file" name="attachments[]" id="sendback_attachments" class="form-control" multiple accept=".pdf,.jpg,.jpeg,.png,.doc,.docx">
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary revenue-form-btn" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-warning text-dark revenue-form-btn">
                                <i class="feather icon-corner-up-left me-1"></i> Send Back
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif

    <!-- Progress Comment Modal -->
    @if($canComment)
        <div class="modal fade" id="commentModal" tabindex="-1" aria-labelledby="commentModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <form action="{{ route('revenue-matters.complaints.comment', $complaint) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="modal-header bg-secondary text-white">
                            <h5 class="modal-title text-white fw-bold" id="commentModalLabel" style="font-size: 18px;">
                                <i class="feather icon-message-square me-1"></i> Add Progress Comment
                            </h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="comment" class="form-label fw-bold">Comment / Progress Note <span class="text-danger">*</span></label>
                                <textarea name="comment" id="comment" class="form-control" rows="4" 
                                          placeholder="Enter internal progress note..." required></textarea>
                            </div>

                            <div class="mb-3">
                                <label for="comment_attachments" class="form-label fw-bold">Attach Documents (Optional)</label>
                                <input type="file" name="attachments[]" id="comment_attachments" class="form-control" multiple accept=".pdf,.jpg,.jpeg,.png,.doc,.docx">
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary revenue-form-btn" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-primary revenue-form-btn">
                                <i class="feather icon-save me-1"></i> Record Comment
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif

    <!-- Close Modal -->
    @if($canClose)
        <div class="modal fade" id="closeModal" tabindex="-1" aria-labelledby="closeModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <form action="{{ route('revenue-matters.complaints.close', $complaint) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="modal-header bg-success text-white">
                            <h5 class="modal-title text-white fw-bold" id="closeModalLabel" style="font-size: 18px;">
                                <i class="feather icon-check-circle me-1"></i> Resolve Land Revenue Complaint
                            </h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="alert alert-info py-2" style="font-size: 14.5px;">
                                <i class="feather icon-check-circle me-1"></i> Resolving this complaint marks the matter as completed and final.
                            </div>

                            <div class="mb-3">
                                <label for="closing_comments" class="form-label fw-bold">Resolution Comments / Final Order <span class="text-danger">*</span></label>
                                <textarea name="closing_comments" id="closing_comments" class="form-control" rows="4" 
                                          placeholder="Enter comprehensive resolution remarks or order details..." required></textarea>
                            </div>

                            <div class="mb-3">
                                <label for="close_attachment" class="form-label fw-bold">Final Resolution Order / File (Optional)</label>
                                <input type="file" name="attachment" id="close_attachment" class="form-control" accept=".pdf,.jpg,.jpeg,.png,.doc,.docx">
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary revenue-form-btn" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-success revenue-form-btn">
                                <i class="feather icon-check-circle me-1"></i> Confirm Resolve
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif

    <!-- Reopen Modal -->
    @if($canReopen)
        <div class="modal fade" id="reopenModal" tabindex="-1" aria-labelledby="reopenModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <form action="{{ route('revenue-matters.complaints.reopen', $complaint) }}" method="POST">
                        @csrf
                        <div class="modal-header bg-danger text-white">
                            <h5 class="modal-title text-white fw-bold" id="reopenModalLabel" style="font-size: 18px;">
                                <i class="feather icon-refresh-cw me-1"></i> Reopen Land Revenue Complaint
                            </h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="alert alert-info py-2" style="font-size: 14.5px;">
                                <i class="feather icon-info me-1"></i> Reopening will change the complaint status to <strong>In Process</strong> and place custody with you.
                            </div>

                            <div class="mb-3">
                                <label for="reopening_reason" class="form-label fw-bold">Reason for Reopening <span class="text-danger">*</span></label>
                                <textarea name="reopening_reason" id="reopening_reason" class="form-control" rows="4" 
                                          placeholder="Specify reason for reopening this matter..." required></textarea>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary revenue-form-btn" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-danger revenue-form-btn">
                                <i class="feather icon-refresh-cw me-1"></i> Reopen Complaint
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif

</x-admin-layout>
