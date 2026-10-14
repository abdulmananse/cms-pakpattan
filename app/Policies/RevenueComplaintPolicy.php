<?php

namespace App\Policies;

use App\Models\RevenueComplaint;
use App\Models\User;

class RevenueComplaintPolicy
{
    /**
     * Determine whether the user can view any revenue complaints.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('Land Revenue Matters Access') || $user->can('Revenue Matters Access');
    }

    /**
     * Determine whether the user can view the specific complaint.
     */
    public function view(User $user, RevenueComplaint $complaint): bool
    {
        if ($user->hasRole('Super Admin') 
            || $user->can('Land Revenue Matters View All') 
            || $user->can('Revenue Matters View All')) {
            return true;
        }

        if ((int) $complaint->creator_id === (int) $user->id || (int) $complaint->current_holder_id === (int) $user->id) {
            return true;
        }

        return $complaint->logs()
            ->where(function ($q) use ($user) {
                $q->where('user_id', $user->id)
                  ->orWhere('from_user_id', $user->id)
                  ->orWhere('to_user_id', $user->id);
            })->exists();
    }

    /**
     * Determine whether the user can create a complaint.
     */
    public function create(User $user): bool
    {
        return $user->can('Land Revenue Matters Create') || $user->can('Revenue Matters Create');
    }

    /**
     * Determine whether the user can forward the complaint.
     */
    public function forward(User $user, RevenueComplaint $complaint): bool
    {
        return $complaint->canBeForwardedBy($user);
    }

    /**
     * Determine whether the user can send back the complaint.
     */
    public function sendBack(User $user, RevenueComplaint $complaint): bool
    {
        return $complaint->canBeSentBackBy($user);
    }

    /**
     * Determine whether the user can close the complaint.
     */
    public function close(User $user, RevenueComplaint $complaint): bool
    {
        return $complaint->canBeClosedBy($user);
    }

    /**
     * Determine whether the user can reopen the complaint.
     */
    public function reopen(User $user, RevenueComplaint $complaint): bool
    {
        return $complaint->canBeReopenedBy($user);
    }

    /**
     * Determine whether the user can comment on the complaint.
     */
    public function comment(User $user, RevenueComplaint $complaint): bool
    {
        return $complaint->canBeCommentedBy($user);
    }
}
