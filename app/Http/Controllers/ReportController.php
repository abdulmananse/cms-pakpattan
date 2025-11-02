<?php

namespace App\Http\Controllers;

use App\Models\Complaint;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    
    public function charts(Request $request)
    {
        $user = Auth::user();
        $summary = Complaint::selectRaw("
            COUNT(*) as total,
            COALESCE(SUM(CASE WHEN complaint_status = 0 AND (department_id IS NULL OR created_at >= DATE_SUB(NOW(), INTERVAL 5 DAY)) THEN 1 ELSE 0 END), 0) as fresh,
            COALESCE(SUM(CASE WHEN complaint_status = 0 AND department_id > 0 AND assigned_at < DATE_SUB(NOW(), INTERVAL 5 DAY) THEN 1 ELSE 0 END), 0) as overdue,
            COALESCE(SUM(CASE WHEN complaint_status = 0 THEN 1 ELSE 0 END), 0) as pending,
            COALESCE(SUM(CASE WHEN complaint_status = 1 THEN 1 ELSE 0 END), 0) as resolved,
            COALESCE(SUM(CASE WHEN complaint_status = 2 THEN 1 ELSE 0 END), 0) as rejected,
            COALESCE(SUM(CASE WHEN complaint_status = 3 THEN 1 ELSE 0 END), 0) as reopen
        ")
        ->roleFilter($user)
        ->filter($request)
        ->first();

        $subTitle = '';
        if ($request->filled('date')) {
            $date = explode(',', $request->date);
            $startDate = $date[0];
            $endDate = $date[1];

            $subTitle = date('jS M', strtotime($startDate)) . ' to ' . date('jS M Y', strtotime($endDate));
        }

        $categories = Complaint::select('category_id', DB::raw('count(*) as total'))
                        ->roleFilter($user)
                        ->filter($request)
                        ->groupBy('category_id')
                        ->with('category:id,name')
                        ->get()
                        ->map(function ($row) {
                            return [
                                'name' => $row->category->name ?? 'Unknown',
                                'total'    => $row->total,
                                'color' => sprintf('#%06X', mt_rand(0, 0xFFFFFF)),
                            ];
                        })
                        ->sortByDesc('total') // sort by total descending
                        ->values();

        $sources = Complaint::select('source_id', DB::raw('count(*) as total'))
                        ->roleFilter($user)
                        ->filter($request)
                        ->groupBy('source_id')
                        ->with('source:id,name')
                        ->get()
                        ->map(function ($row) {
                            return [
                                'name' => $row->source->name ?? 'Unknown',
                                'total'    => $row->total,
                            ];
                        });

        // dd($sources);

        return view('reports.charts', get_defined_vars());
    }
    
    public function summary(Request $request)
    {
        $departments = getActiveDepartments();
        $sources = getActiveSources();

        $complaints = Complaint::selectRaw('department_id, source_id, COUNT(*) as total')
            ->whereNotNull('department_id')
            ->whereNotNull('source_id')
            ->filter($request)
            ->groupBy('department_id', 'source_id')
            ->get();
        
        $sourceIds = $complaints->pluck('source_id');
        $departmentIds = $complaints->pluck('department_id');
        
        $data = $complaints->groupBy('department_id');

        return view('reports.summary', get_defined_vars());
    }

    public function pendingComplaints(Request $request)
    {
        $departments = getActiveDepartments();
        $sources = getActiveSources();

        $complaints = Complaint::selectRaw('source_id, department_id, COUNT(*) as total')
            ->where('complaint_status', 0)
            ->whereNotNull('department_id')
            ->groupBy('source_id', 'department_id')
            ->get();

        $departmentIds = $complaints->pluck('department_id');
        $data = $complaints->groupBy('source_id');

        return view('reports.pending-complaints', get_defined_vars());
    }
    
    public function resolvedComplaints(Request $request)
    {
        $departments = getActiveDepartments();
        $sources = getActiveSources();

        $complaints = Complaint::selectRaw('source_id, department_id, COUNT(*) as total')
            ->where('complaint_status', 1)
            ->whereNotNull('department_id')
            ->groupBy('source_id', 'department_id')
            ->get();

        $departmentIds = $complaints->pluck('department_id');
        $data = $complaints->groupBy('source_id');

        return view('reports.resolved-complaints', get_defined_vars());
    }
}
