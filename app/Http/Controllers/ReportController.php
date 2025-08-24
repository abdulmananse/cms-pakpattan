<?php

namespace App\Http\Controllers;

use App\Models\Complaint;
use App\Models\Department;
use App\Models\Source;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    
    public function pendingComplaints(Request $request)
    {
        $departments = getActiveDepartments();
        $sources = getActiveSources();

        $data = Complaint::selectRaw('source_id, department_id, COUNT(*) as total')
            ->where('complaint_status', 0)
            ->whereNotNull('department_id')
            ->groupBy('source_id', 'department_id')
            ->get()
            ->groupBy('source_id');

        // dd($data->toArray());

        return view('reports.pending-complaints', get_defined_vars());
    }
    
    public function resolvedComplaints(Request $request)
    {
        $departments = getActiveDepartments();
        $sources = getActiveSources();

        $data = Complaint::selectRaw('source_id, department_id, COUNT(*) as total')
            ->where('complaint_status', 1)
            ->whereNotNull('department_id')
            ->groupBy('source_id', 'department_id')
            ->get()
            ->groupBy('source_id');

        return view('reports.resolved-complaints', get_defined_vars());
    }
}
