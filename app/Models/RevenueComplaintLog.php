<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RevenueComplaintLog extends Model
{
    protected $fillable = [
        'revenue_complaint_id',
        'user_id',
        'action',
        'from_user_id',
        'to_user_id',
        'comments',
        'metadata',
    ];

    protected $casts = [
        'metadata' => 'array',
    ];

    public function complaint()
    {
        return $this->belongsTo(RevenueComplaint::class, 'revenue_complaint_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function fromUser()
    {
        return $this->belongsTo(User::class, 'from_user_id');
    }

    public function toUser()
    {
        return $this->belongsTo(User::class, 'to_user_id');
    }

    public function attachments()
    {
        return $this->hasMany(RevenueComplaintAttachment::class, 'revenue_complaint_log_id');
    }

    public function actionLabel(): string
    {
        return match ($this->action) {
            'created' => 'Complaint Lodged',
            'forwarded' => 'Forwarded',
            'sent_back' => 'Sent Back',
            'comment_added' => 'Progress Comment',
            'closed' => 'Resolved',
            'reopened' => 'Reopened',
            default => ucfirst(str_replace('_', ' ', $this->action)),
        };
    }

    public function actionBadgeClass(): string
    {
        return match ($this->action) {
            'created' => 'audit-badge-created',
            'forwarded' => 'audit-badge-forwarded',
            'sent_back' => 'audit-badge-sent-back',
            'comment_added' => 'audit-badge-comment',
            'closed' => 'audit-badge-closed',
            'reopened' => 'audit-badge-reopened',
            default => 'audit-badge-default',
        };
    }
}
