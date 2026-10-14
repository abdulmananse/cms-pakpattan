<?php

namespace App\Http\Controllers\Revenue;

use App\Enums\RevenueComplaintStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\Revenue\AddRevenueCommentRequest;
use App\Http\Requests\Revenue\CloseRevenueComplaintRequest;
use App\Http\Requests\Revenue\ForwardRevenueComplaintRequest;
use App\Http\Requests\Revenue\ReopenRevenueComplaintRequest;
use App\Http\Requests\Revenue\SendBackRevenueComplaintRequest;
use App\Models\RevenueComplaint;
use App\Models\RevenueComplaintAttachment;
use App\Models\RevenueComplaintLog;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class RevenueComplaintActionController extends Controller
{
    /**
     * Forward the complaint to another authorized Revenue officer.
     */
    public function forward(ForwardRevenueComplaintRequest $request, RevenueComplaint $revenue_complaint)
    {
        $user = Auth::user();
        $recipient = User::findOrFail($request->recipient_id);

        DB::transaction(function () use ($request, $revenue_complaint, $user, $recipient) {
            $stack = $revenue_complaint->custody_stack ?? [];
            $stack[] = (int) $recipient->id;

            $revenue_complaint->custody_stack = $stack;
            $revenue_complaint->current_holder_id = $recipient->id;
            $revenue_complaint->status = RevenueComplaintStatus::IN_PROCESS;
            $revenue_complaint->save();

            $log = RevenueComplaintLog::create([
                'revenue_complaint_id' => $revenue_complaint->id,
                'user_id' => $user->id,
                'action' => 'forwarded',
                'from_user_id' => $user->id,
                'to_user_id' => $recipient->id,
                'comments' => $request->instructions,
            ]);

            if ($request->hasFile('attachments')) {
                foreach ($request->file('attachments') as $file) {
                    $path = $file->store('revenue-matters/forwarding', 'public');
                    RevenueComplaintAttachment::create([
                        'revenue_complaint_id' => $revenue_complaint->id,
                        'revenue_complaint_log_id' => $log->id,
                        'user_id' => $user->id,
                        'file_path' => $path,
                        'original_name' => $file->getClientOriginalName(),
                        'file_size' => $file->getSize(),
                        'mime_type' => $file->getMimeType(),
                        'attachment_type' => 'forwarding',
                    ]);
                }
            }
        });

        return redirect()->route('revenue-matters.complaints.show', $revenue_complaint)
            ->with('success', "Complaint forwarded to {$recipient->official_label} successfully.");
    }

    /**
     * Send the complaint back to the previous holder in the custody stack.
     */
    public function sendBack(SendBackRevenueComplaintRequest $request, RevenueComplaint $revenue_complaint)
    {
        $user = Auth::user();
        $stack = $revenue_complaint->custody_stack ?? [];

        if (count($stack) <= 1) {
            return redirect()->route('revenue-matters.complaints.show', $revenue_complaint)
                ->with('error', 'Cannot send back: You are the root creator of this complaint.');
        }

        DB::transaction(function () use ($request, $revenue_complaint, $user, $stack) {
            // Pop current holder from custody stack
            array_pop($stack);
            $previousHolderId = (int) end($stack);

            $revenue_complaint->custody_stack = array_values($stack);
            $revenue_complaint->current_holder_id = $previousHolderId;
            $revenue_complaint->status = RevenueComplaintStatus::IN_PROCESS;
            $revenue_complaint->save();

            $log = RevenueComplaintLog::create([
                'revenue_complaint_id' => $revenue_complaint->id,
                'user_id' => $user->id,
                'action' => 'sent_back',
                'from_user_id' => $user->id,
                'to_user_id' => $previousHolderId,
                'comments' => $request->report,
            ]);

            if ($request->hasFile('attachments')) {
                foreach ($request->file('attachments') as $file) {
                    $path = $file->store('revenue-matters/send-back', 'public');
                    RevenueComplaintAttachment::create([
                        'revenue_complaint_id' => $revenue_complaint->id,
                        'revenue_complaint_log_id' => $log->id,
                        'user_id' => $user->id,
                        'file_path' => $path,
                        'original_name' => $file->getClientOriginalName(),
                        'file_size' => $file->getSize(),
                        'mime_type' => $file->getMimeType(),
                        'attachment_type' => 'send_back',
                    ]);
                }
            }
        });

        $prevUser = User::find($revenue_complaint->current_holder_id);
        $prevLabel = $prevUser ? $prevUser->official_label : 'previous holder';

        return redirect()->route('revenue-matters.complaints.show', $revenue_complaint)
            ->with('success', "Complaint sent back to {$prevLabel} successfully.");
    }

    /**
     * Add a progress note or comment to the complaint.
     */
    public function addComment(AddRevenueCommentRequest $request, RevenueComplaint $revenue_complaint)
    {
        $user = Auth::user();

        DB::transaction(function () use ($request, $revenue_complaint, $user) {
            $log = RevenueComplaintLog::create([
                'revenue_complaint_id' => $revenue_complaint->id,
                'user_id' => $user->id,
                'action' => 'comment_added',
                'comments' => $request->comment,
            ]);

            if ($request->hasFile('attachments')) {
                foreach ($request->file('attachments') as $file) {
                    $path = $file->store('revenue-matters/comments', 'public');
                    RevenueComplaintAttachment::create([
                        'revenue_complaint_id' => $revenue_complaint->id,
                        'revenue_complaint_log_id' => $log->id,
                        'user_id' => $user->id,
                        'file_path' => $path,
                        'original_name' => $file->getClientOriginalName(),
                        'file_size' => $file->getSize(),
                        'mime_type' => $file->getMimeType(),
                        'attachment_type' => 'comment',
                    ]);
                }
            }
        });

        return redirect()->route('revenue-matters.complaints.show', $revenue_complaint)
            ->with('success', 'Progress comment recorded successfully.');
    }

    /**
     * Close the complaint.
     */
    public function close(CloseRevenueComplaintRequest $request, RevenueComplaint $revenue_complaint)
    {
        $user = Auth::user();

        DB::transaction(function () use ($request, $revenue_complaint, $user) {
            $revenue_complaint->status = RevenueComplaintStatus::CLOSED;
            $revenue_complaint->closed_by = $user->id;
            $revenue_complaint->closed_at = now();
            $revenue_complaint->closing_comments = $request->closing_comments;
            $revenue_complaint->save();

            $log = RevenueComplaintLog::create([
                'revenue_complaint_id' => $revenue_complaint->id,
                'user_id' => $user->id,
                'action' => 'closed',
                'comments' => $request->closing_comments,
            ]);

            if ($request->hasFile('attachment')) {
                $file = $request->file('attachment');
                $path = $file->store('revenue-matters/closing', 'public');
                RevenueComplaintAttachment::create([
                    'revenue_complaint_id' => $revenue_complaint->id,
                    'revenue_complaint_log_id' => $log->id,
                    'user_id' => $user->id,
                    'file_path' => $path,
                    'original_name' => $file->getClientOriginalName(),
                    'file_size' => $file->getSize(),
                    'mime_type' => $file->getMimeType(),
                    'attachment_type' => 'closing',
                ]);
            }
        });

        return redirect()->route('revenue-matters.complaints.show', $revenue_complaint)
            ->with('success', "Complaint {$revenue_complaint->complaint_number} closed successfully.");
    }

    /**
     * Reopen a closed complaint.
     */
    public function reopen(ReopenRevenueComplaintRequest $request, RevenueComplaint $revenue_complaint)
    {
        $user = Auth::user();

        DB::transaction(function () use ($request, $revenue_complaint, $user) {
            $revenue_complaint->status = RevenueComplaintStatus::IN_PROCESS;
            $revenue_complaint->reopened_by = $user->id;
            $revenue_complaint->reopened_at = now();
            $revenue_complaint->reopening_reason = $request->reopening_reason;
            $revenue_complaint->current_holder_id = $user->id;
            // Start a new active custody stack with the reopening user (Amendment 5)
            $revenue_complaint->custody_stack = [$user->id];
            $revenue_complaint->save();

            RevenueComplaintLog::create([
                'revenue_complaint_id' => $revenue_complaint->id,
                'user_id' => $user->id,
                'action' => 'reopened',
                'comments' => $request->reopening_reason,
            ]);
        });

        return redirect()->route('revenue-matters.complaints.show', $revenue_complaint)
            ->with('success', "Complaint {$revenue_complaint->complaint_number} reopened successfully.");
    }
}
