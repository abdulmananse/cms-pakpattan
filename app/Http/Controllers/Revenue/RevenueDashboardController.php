<?php

namespace App\Http\Requests;
namespace App\Http\Controllers\Revenue;

use App\Enums\RevenueComplaintStatus;
use App\Http\Controllers\Controller;
use App\Models\RevenueComplaint;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RevenueDashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        // Check permission or Super Admin
        if (!$user->can('Land Revenue Matters Access') && !$user->can('Land Revenue Matters Dashboard') && !$user->can('Revenue Matters Access') && !$user->hasRole('Super Admin')) {
            abort(403, 'Unauthorized access to Land Revenue Matters.');
        }

        $assignedCount = RevenueComplaint::assigned($user->id)->count();
        $forwardedCount = RevenueComplaint::forwarded($user->id)->count();
        $myComplaintsCount = $assignedCount;
        $forwardedByMeCount = $forwardedCount;
        $closedCount = RevenueComplaint::accessibleTo($user)
            ->where('status', RevenueComplaintStatus::CLOSED)
            ->count();
        $totalAccessibleCount = RevenueComplaint::accessibleTo($user)->count();

        $recentComplaints = RevenueComplaint::accessibleTo($user)
            ->with(['creator', 'currentHolder'])
            ->latest()
            ->take(10)
            ->get();

        return view('revenue-matters.dashboard', compact(
            'assignedCount',
            'forwardedCount',
            'myComplaintsCount',
            'forwardedByMeCount',
            'closedCount',
            'totalAccessibleCount',
            'recentComplaints'
        ));
    }
}
