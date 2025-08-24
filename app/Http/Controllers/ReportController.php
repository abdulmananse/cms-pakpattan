<?php

namespace App\Http\Controllers;

use App\Models\Complaint;
use App\Models\Department;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    
    public function pendingComplaints(Request $request)
    {
        $departments = Department::all();
        $sources = complaintSources();

        $data = Complaint::selectRaw('source, department_id, COUNT(*) as total')
            ->where('complaint_status', 0)
            ->whereNotNull('department_id')
            ->groupBy('source', 'department_id')
            ->get()
            ->groupBy('source');

        // dd($data->toArray());

        return view('reports.pending-complaints', get_defined_vars());
    }
    
    public function resolvedComplaints(Request $request)
    {
        $departments = Department::all();
        $sources = complaintSources();

        $data = Complaint::selectRaw('source, department_id, COUNT(*) as total')
            ->where('complaint_status', 1)
            ->whereNotNull('department_id')
            ->groupBy('source', 'department_id')
            ->get()
            ->groupBy('source');

        return view('reports.resolved-complaints', get_defined_vars());
    }
}
