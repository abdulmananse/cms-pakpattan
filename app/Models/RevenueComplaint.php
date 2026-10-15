<?php

namespace App\Models;

use App\Enums\RevenueComplaintStatus;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class RevenueComplaint extends Model
{
    protected $fillable = [
        'uuid',
        'complaint_number',
        'citizen_name',
        'father_husband_name',
        'cnic',
        'contact_number',
        'address',
        'tehsil',
        'subject',
        'description',
        'status',
        'creator_id',
        'current_holder_id',
        'custody_stack',
        'closed_by',
        'closed_at',
        'closing_comments',
        'reopened_by',
        'reopened_at',
        'reopening_reason',
    ];

    protected $casts = [
        'custody_stack' => 'array',
        'status' => RevenueComplaintStatus::class,
        'closed_at' => 'datetime',
        'reopened_at' => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function (self $model) {
            if (empty($model->uuid)) {
                $model->uuid = (string) Str::uuid();
            }

            if (empty($model->complaint_number)) {
                $model->complaint_number = RevenueComplaintSequence::getNextComplaintNumber();
            }

            if (empty($model->status)) {
                $model->status = RevenueComplaintStatus::OPEN;
            }

            if (!empty($model->creator_id)) {
                if (empty($model->current_holder_id)) {
                    $model->current_holder_id = $model->creator_id;
                }
                if (empty($model->custody_stack)) {
                    $model->custody_stack = [(int) $model->creator_id];
                }
            }
        });
    }

    public function getRouteKeyName(): string
    {
        return 'uuid';
    }

    public function scopeUuid(Builder $query, string $uuid): Builder
    {
        return $query->where('uuid', $uuid);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'creator_id');
    }

    public function currentHolder()
    {
        return $this->belongsTo(User::class, 'current_holder_id');
    }

    public function closedBy()
    {
        return $this->belongsTo(User::class, 'closed_by');
    }

    public function reopenedBy()
    {
        return $this->belongsTo(User::class, 'reopened_by');
    }

    public function logs()
    {
        return $this->hasMany(RevenueComplaintLog::class)->orderBy('created_at', 'asc');
    }

    public function latestLog()
    {
        return $this->hasOne(RevenueComplaintLog::class)->latestOfMany();
    }

    public function attachments()
    {
        return $this->hasMany(RevenueComplaintAttachment::class)->orderBy('created_at', 'asc');
    }

    /**
     * Scope for complaints assigned to / currently held by the user (active workload inbox).
     */
    public function scopeAssigned(Builder $query, int $userId): Builder
    {
        return $query->where('current_holder_id', $userId)
            ->where('status', '!=', RevenueComplaintStatus::CLOSED);
    }

    /**
     * Alias for scopeAssigned for backwards compatibility.
     */
    public function scopeMyComplaints(Builder $query, int $userId): Builder
    {
        return $this->scopeAssigned($query, $userId);
    }

    /**
     * Scope for complaints that the user has forwarded to another officer.
     * Historical forwarding remains visible even if subsequent forwarding or send-back occurred.
     */
    public function scopeForwarded(Builder $query, int $userId): Builder
    {
        return $query->whereHas('logs', function ($q) use ($userId) {
            $q->where('action', 'forwarded')
              ->where(function ($sub) use ($userId) {
                  $sub->where('from_user_id', $userId)
                      ->orWhere('user_id', $userId);
              });
        });
    }

    /**
     * Alias for scopeForwarded for backwards compatibility.
     */
    public function scopeForwardedByMe(Builder $query, int $userId): Builder
    {
        return $this->scopeForwarded($query, $userId);
    }

    /**
     * Get the latest forwarding log created by the specified user.
     */
    public function latestForwardLogBy(int $userId): ?RevenueComplaintLog
    {
        return $this->logs
            ->where('action', 'forwarded')
            ->filter(fn($l) => (int) $l->from_user_id === $userId || (int) $l->user_id === $userId)
            ->last();
    }

    /**
     * Scope for complaints where the user participated or created, but is not currently holding or is closed.
     */
    public function scopeHistory(Builder $query, int $userId): Builder
    {
        return $query->where(function ($q) use ($userId) {
            $q->where('creator_id', $userId)
              ->orWhereHas('logs', function ($logQuery) use ($userId) {
                  $logQuery->where('user_id', $userId)
                           ->orWhere('from_user_id', $userId)
                           ->orWhere('to_user_id', $userId);
              });
        })->where(function ($q) use ($userId) {
            $q->where('status', RevenueComplaintStatus::CLOSED)
              ->orWhere('current_holder_id', '!=', $userId);
        });
    }

    /**
     * Scope for all complaints accessible to a user based on role and participation.
     */
    public function scopeAccessibleTo(Builder $query, User $user): Builder
    {
        if ($user->hasRole('Super Admin') 
            || $user->can('Land Revenue Matters View All') 
            || $user->can('Revenue Matters View All')) {
            return $query;
        }

        return $query->where(function ($q) use ($user) {
            $q->where('creator_id', $user->id)
              ->orWhere('current_holder_id', $user->id)
              ->orWhereHas('logs', function ($logQuery) use ($user) {
                  $logQuery->where('user_id', $user->id)
                           ->orWhere('from_user_id', $user->id)
                           ->orWhere('to_user_id', $user->id);
              });
        });
    }

    /**
     * Determine whether the given user can forward this complaint.
     */
    public function canBeForwardedBy(User $user): bool
    {
        if ($this->status === RevenueComplaintStatus::CLOSED) {
            return false;
        }

        return (int) $this->current_holder_id === (int) $user->id
            && ($user->can('Land Revenue Matters Forward') || $user->can('Revenue Matters Forward'));
    }

    /**
     * Determine whether the given user can send back this complaint.
     */
    public function canBeSentBackBy(User $user): bool
    {
        if ($this->status === RevenueComplaintStatus::CLOSED) {
            return false;
        }

        $stack = $this->custody_stack ?? [];

        return (int) $this->current_holder_id === (int) $user->id
            && count($stack) > 1
            && ($user->can('Land Revenue Matters Send Back') || $user->can('Revenue Matters Send Back'));
    }

    /**
     * Determine whether the given user can close this complaint.
     */
    public function canBeClosedBy(User $user): bool
    {
        if ($this->status === RevenueComplaintStatus::CLOSED) {
            return false;
        }

        if ($user->hasRole('Super Admin') 
            || $user->can('Land Revenue Matters Resolve') 
            || $user->can('Revenue Matters Resolve')) {
            return true;
        }

        return (int) $this->creator_id === (int) $user->id
            && (int) $this->current_holder_id === (int) $user->id;
    }

    /**
     * Determine whether the given user can reopen this complaint.
     */
    public function canBeReopenedBy(User $user): bool
    {
        if ($this->status !== RevenueComplaintStatus::CLOSED) {
            return false;
        }

        if ($user->hasRole('Super Admin') 
            || $user->can('Land Revenue Matters Reopen') 
            || $user->can('Revenue Matters Reopen')) {
            return true;
        }

        return (int) $this->creator_id === (int) $user->id;
    }

    /**
     * Determine whether the given user can comment on this complaint.
     */
    public function canBeCommentedBy(User $user): bool
    {
        if ($this->status === RevenueComplaintStatus::CLOSED) {
            return false;
        }

        $hasCommentPerm = $user->can('Land Revenue Matters Comment') 
            || $user->can('Revenue Matters Comment');

        if ($user->hasRole('Super Admin') 
            || $user->can('Land Revenue Matters View All') 
            || $user->can('Revenue Matters View All')) {
            return $hasCommentPerm;
        }

        return ((int) $this->current_holder_id === (int) $user->id || (int) $this->creator_id === (int) $user->id)
            && $hasCommentPerm;
    }

    /**
     * Get the previous holder in the custody stack.
     */
    public function getPreviousHolderId(): ?int
    {
        $stack = $this->custody_stack ?? [];
        $count = count($stack);

        if ($count > 1) {
            return (int) $stack[$count - 2];
        }

        return null;
    }

    public function getPreviousHolder(): ?User
    {
        $prevId = $this->getPreviousHolderId();
        return $prevId ? User::find($prevId) : null;
    }

    /**
     * Check if complaint is overdue (> 7 days without resolution).
     */
    public function isOverdue(int $daysThreshold = 7): bool
    {
        if ($this->status === RevenueComplaintStatus::CLOSED) {
            return false;
        }

        return $this->created_at ? $this->created_at->lt(now()->subDays($daysThreshold)) : false;
    }

    /**
     * Check if complaint was previously closed and reopened.
     */
    public function isReopened(): bool
    {
        return $this->status !== RevenueComplaintStatus::CLOSED && !empty($this->reopened_at);
    }
}
