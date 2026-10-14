<div class="revenue-timeline">
    @forelse($complaint->logs as $log)
        <div class="card mb-3 bg-white shadow-sm border-start border-4 {{ match($log->action) {
            'created' => 'border-primary',
            'forwarded' => 'border-info',
            'sent_back' => 'border-warning',
            'comment_added' => 'border-secondary',
            'closed' => 'border-success',
            'reopened' => 'border-danger',
            default => 'border-dark',
        } }}">
            <div class="card-body p-3">
                <div class="d-flex justify-content-between align-items-center mb-2 flex-wrap gap-2">
                    <div class="d-flex align-items-center flex-wrap gap-1">
                        <span class="audit-badge {{ $log->actionBadgeClass() }} me-1">
                            {{ $log->actionLabel() }}
                        </span>
                        <strong class="audit-officer-name text-dark">{{ $log->user->official_label ?? 'System' }}</strong>
                        
                        @if($log->action === 'forwarded' && $log->toUser)
                            <span class="text-muted ms-1" style="font-size: 16px;">
                                <i class="feather icon-arrow-right text-primary"></i> Forwarded to <strong class="text-dark fw-semibold">{{ $log->toUser->official_label }}</strong>
                            </span>
                        @elseif($log->action === 'sent_back' && $log->toUser)
                            <span class="text-muted ms-1" style="font-size: 16px;">
                                <i class="feather icon-corner-up-left text-warning"></i> Sent back to <strong class="text-dark fw-semibold">{{ $log->toUser->official_label }}</strong>
                            </span>
                        @endif
                    </div>
                    <small class="audit-datetime">
                        <i class="feather icon-clock me-1"></i>{{ $log->created_at->format('d M, Y h:i A') }}
                    </small>
                </div>

                @if($log->comments)
                    <div class="p-3 bg-light rounded text-dark mb-2 audit-comment-box">
                        {{ $log->comments }}
                    </div>
                @endif

                @if($log->attachments->count() > 0)
                    <div class="mt-2">
                        <small class="text-muted fw-bold d-block mb-1" style="font-size: 15px;">
                            <i class="feather icon-paperclip me-1"></i>Attached Documents ({{ $log->attachments->count() }}):
                        </small>
                        <div class="d-flex flex-wrap gap-2">
                            @foreach($log->attachments as $att)
                                <a href="{{ asset('storage/' . $att->file_path) }}" target="_blank" 
                                   class="btn btn-sm btn-outline-secondary d-inline-flex align-items-center bg-white"
                                   style="font-size: 15px; padding: 0.4rem 0.75rem;">
                                    <i class="feather icon-file me-1"></i>
                                    <span class="fw-semibold">{{ Str::limit($att->original_name, 30) }}</span>
                                    @if($att->file_size)
                                        <small class="text-muted ms-1" style="font-size: 13.5px;">({{ $att->formattedSize() }})</small>
                                    @endif
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        </div>
    @empty
        <p class="text-muted text-center py-3" style="font-size: 16px;">No activity logs recorded yet.</p>
    @endforelse
</div>
