<?php

namespace App\Http\Controllers\Revenue;

use App\Enums\RevenueComplaintStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\Revenue\StoreRevenueComplaintRequest;
use App\Models\RevenueComplaint;
use App\Models\RevenueComplaintAttachment;
use App\Models\RevenueComplaintLog;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Models\Role;

class RevenueComplaintController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        if (!$user->can('Land Revenue Matters Access') && !$user->can('Revenue Matters Access') && !$user->hasRole('Super Admin')) {
            abort(403, 'Unauthorized access to Land Revenue Matters.');
        }

        $tab = $request->get('tab', 'assigned');
        if ($tab === 'my') {
            $tab = 'assigned';
        }

        $canViewAll = $user->hasRole('Super Admin') 
            || $user->can('Land Revenue Matters View All') 
            || $user->can('Revenue Matters View All');

        if ($tab === 'all') {
            if (!$canViewAll) {
                abort(403, 'Unauthorized. You do not have permission to view all Land Revenue complaints.');
            }
            $query = RevenueComplaint::query();
        } elseif ($tab === 'forwarded') {
            $query = RevenueComplaint::forwarded($user->id);
        } else {
            $tab = 'assigned';
            $query = RevenueComplaint::assigned($user->id);
        }

        $status = $request->get('status');
        $tehsil = $request->get('tehsil');
        $createdBy = $request->get('created_by');
        $currentHolder = $request->get('current_holder');
        $search = $request->get('search');

        if ($status && in_array($status, ['open', 'in_process', 'closed'])) {
            $query->where('status', $status);
        }

        if ($tehsil) {
            $query->where('tehsil', $tehsil);
        }

        if ($createdBy) {
            $query->where('creator_id', $createdBy);
        }

        if ($currentHolder) {
            $query->where('current_holder_id', $currentHolder);
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('complaint_number', 'like', "%{$search}%")
                  ->orWhere('citizen_name', 'like', "%{$search}%")
                  ->orWhere('cnic', 'like', "%{$search}%")
                  ->orWhere('contact_number', 'like', "%{$search}%")
                  ->orWhere('subject', 'like', "%{$search}%")
                  ->orWhere('address', 'like', "%{$search}%");
            });
        }

        $complaints = $query->with(['creator', 'currentHolder', 'latestLog', 'logs.toUser'])
            ->latest()
            ->paginate(15)
            ->withQueryString();

        $counts = [
            'assigned' => RevenueComplaint::assigned($user->id)->count(),
            'forwarded' => RevenueComplaint::forwarded($user->id)->count(),
            'all' => $canViewAll ? RevenueComplaint::count() : 0,
        ];

        // Tehsils for filter dropdown
        $distinctTehsils = RevenueComplaint::distinct()
            ->whereNotNull('tehsil')
            ->where('tehsil', '!=', '')
            ->pluck('tehsil');
        $tehsils = collect(['Pakpattan', 'Arifwala'])
            ->merge($distinctTehsils)
            ->unique()
            ->filter()
            ->values();

        // Revenue officers for created_by and current_holder filters
        $officers = User::where(function ($q) {
                $q->whereHas('roles', function ($roleQuery) {
                    $roleQuery->whereIn('name', ['Revenue Department', 'Revenue Department Admin', 'Super Admin']);
                })
                ->orWhereIn('id', RevenueComplaint::select('creator_id')->distinct())
                ->orWhereIn('id', RevenueComplaint::select('current_holder_id')->distinct());
            })
            ->orderBy('name')
            ->get();

        return view('revenue-matters.index', compact('complaints', 'tab', 'counts', 'canViewAll', 'tehsils', 'officers'));
    }

    public function search(Request $request)
    {
        $user = Auth::user();
        if (!$user->can('Land Revenue Matters Access') && !$user->can('Land Revenue Matters Search') && !$user->can('Revenue Matters Access') && !$user->hasRole('Super Admin')) {
            abort(403, 'Unauthorized access to Land Revenue Matters.');
        }

        $canViewAll = $user->hasRole('Super Admin') 
            || $user->can('Land Revenue Matters View All') 
            || $user->can('Revenue Matters View All');

        $query = $canViewAll ? RevenueComplaint::query() : RevenueComplaint::accessibleTo($user);

        $hasSearch = false;

        if ($complaintNumber = trim((string) $request->get('complaint_number', ''))) {
            $query->where('complaint_number', 'like', "%{$complaintNumber}%");
            $hasSearch = true;
        }

        if ($citizenName = trim((string) $request->get('citizen_name', ''))) {
            $query->where('citizen_name', 'like', "%{$citizenName}%");
            $hasSearch = true;
        }

        if ($fatherHusband = trim((string) $request->get('father_husband_name', ''))) {
            $query->where('father_husband_name', 'like', "%{$fatherHusband}%");
            $hasSearch = true;
        }

        if ($cnic = trim((string) $request->get('cnic', ''))) {
            $query->where('cnic', 'like', "%{$cnic}%");
            $hasSearch = true;
        }

        if ($contactNumber = trim((string) $request->get('contact_number', ''))) {
            $query->where('contact_number', 'like', "%{$contactNumber}%");
            $hasSearch = true;
        }

        if ($tehsil = trim((string) $request->get('tehsil', ''))) {
            $query->where('tehsil', 'like', "%{$tehsil}%");
            $hasSearch = true;
        }

        if ($address = trim((string) $request->get('address', ''))) {
            $query->where('address', 'like', "%{$address}%");
            $hasSearch = true;
        }

        if ($subject = trim((string) $request->get('subject', ''))) {
            $query->where('subject', 'like', "%{$subject}%");
            $hasSearch = true;
        }

        if ($status = $request->get('status')) {
            if (in_array($status, ['open', 'in_process', 'closed'])) {
                $query->where('status', $status);
                $hasSearch = true;
            }
        }

        $complaints = $hasSearch 
            ? $query->with(['creator', 'currentHolder'])->latest()->paginate(15)->withQueryString()
            : null;

        return view('revenue-matters.search', compact('complaints', 'hasSearch', 'canViewAll'));
    }

    public function create()
    {
        $user = Auth::user();
        if (!$user->can('Land Revenue Matters Create') && !$user->can('Revenue Matters Create') && !$user->hasRole('Super Admin')) {
            abort(403, 'Unauthorized to lodge revenue complaints.');
        }

        return view('revenue-matters.create');
    }

    public function store(StoreRevenueComplaintRequest $request)
    {
        $user = Auth::user();

        $complaint = DB::transaction(function () use ($request, $user) {
            $complaint = RevenueComplaint::create([
                'citizen_name' => $request->citizen_name,
                'father_husband_name' => $request->father_husband_name,
                'cnic' => $request->cnic,
                'contact_number' => $request->contact_number,
                'address' => $request->address,
                'tehsil' => $request->tehsil,
                'subject' => $request->subject,
                'description' => $request->description,
                'status' => RevenueComplaintStatus::OPEN,
                'creator_id' => $user->id,
                'current_holder_id' => $user->id,
                'custody_stack' => [$user->id],
            ]);

            $log = RevenueComplaintLog::create([
                'revenue_complaint_id' => $complaint->id,
                'user_id' => $user->id,
                'action' => 'created',
                'comments' => 'Complaint registered in Revenue Department Matters.',
            ]);

            if ($request->hasFile('attachments')) {
                foreach ($request->file('attachments') as $file) {
                    $path = $file->store('revenue-matters/complaints', 'public');
                    RevenueComplaintAttachment::create([
                        'revenue_complaint_id' => $complaint->id,
                        'revenue_complaint_log_id' => $log->id,
                        'user_id' => $user->id,
                        'file_path' => $path,
                        'original_name' => $file->getClientOriginalName(),
                        'file_size' => $file->getSize(),
                        'mime_type' => $file->getMimeType(),
                        'attachment_type' => 'initial',
                    ]);
                }
            }

            return $complaint;
        });

        $complaint->refresh();

        return redirect()->route('revenue-matters.complaints.show', $complaint)
            ->with('success', "Revenue complaint {$complaint->complaint_number} lodged successfully.");
    }

    public function show(RevenueComplaint $revenue_complaint)
    {
        $user = Auth::user();

        if ($user->cannot('view', $revenue_complaint)) {
            abort(403, 'You do not have permission to view this Revenue complaint.');
        }

        $revenue_complaint->load([
            'creator',
            'currentHolder',
            'closedBy',
            'reopenedBy',
            'logs.user',
            'logs.fromUser',
            'logs.toUser',
            'logs.attachments',
            'attachments.user',
        ]);

        // Potential forward recipients: users with Revenue Department or Super Admin role, except current holder
        $potentialRecipients = User::where('id', '!=', $revenue_complaint->current_holder_id)
            ->where(function ($q) {
                $q->whereHas('roles', function ($roleQuery) {
                    $roleQuery->whereIn('name', ['Revenue Department', 'Revenue Department Admin', 'Super Admin']);
                });
            })
            ->orderBy('name')
            ->get();

        $previousHolder = $revenue_complaint->getPreviousHolder();

        return view('revenue-matters.show', [
            'complaint' => $revenue_complaint,
            'potentialRecipients' => $potentialRecipients,
            'previousHolder' => $previousHolder,
        ]);
    }
}
