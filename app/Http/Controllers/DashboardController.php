<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Complaint;
use App\Models\Department;
use App\Models\Source;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        if($user->role == 'Complainant') {
            $complaints = Complaint::where('created_by', Auth::id())->orderBy('updated_at', 'desc')->get();
            return view('home', get_defined_vars());
        }

        $summary = Complaint::selectRaw("
            COUNT(*) as total,
            SUM(CASE WHEN complaint_status = 0 THEN 1 ELSE 0 END) as pending,
            SUM(CASE WHEN complaint_status = 1 THEN 1 ELSE 0 END) as resolved,
            SUM(CASE WHEN complaint_status = 2 THEN 1 ELSE 0 END) as rejected
        ")
        ->roleFilter($user)
        ->filter($request);
        
        $summary = $summary->first();

        if ($user->can('Department Complaint Charts')) {
            $departments = Department::withCount('pending_complaints', 'resolved_complaints')->get();
        }
        if ($user->can('Source Complaint Charts')) {
            $sources = Source::withCount('pending_complaints', 'resolved_complaints')->get();
        }

        return view('dashboard', get_defined_vars());
    }

}
