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

    /**
     * Show the Complaints Download page.
     */
    public function downloadView()
    {
        $columns = $this->getAllowedColumns();
        return view('reports.download', compact('columns'));
    }

    /**
     * Export complaints report to CSV based on selected columns and date filter.
     */
    public function downloadExport(Request $request)
    {
        $allowedColumnsMap = $this->getAllowedColumns();
        $allowedKeys = array_keys($allowedColumnsMap);

        $request->validate([
            'columns' => 'required|array|min:1',
            'columns.*' => 'string|in:' . implode(',', $allowedKeys),
            'from_date' => 'nullable|date_format:Y-m-d',
            'to_date' => 'nullable|date_format:Y-m-d|after_or_equal:from_date',
        ], [
            'columns.required' => 'Please select at least one column to include in the report.',
            'columns.min' => 'Please select at least one column to include in the report.',
            'columns.*.in' => 'One or more selected columns are invalid.',
            'to_date.after_or_equal' => 'The From Date must not be greater than the To Date.',
        ]);

        $selectedKeys = array_values(array_intersect($request->input('columns', []), $allowedKeys));

        if (empty($selectedKeys)) {
            return back()->with('error', 'Please select at least one valid column to export.');
        }

        $user = Auth::user();
        $query = Complaint::roleFilter($user);

        // Date range filter on complaint creation date (created_at)
        if ($request->filled('from_date')) {
            $query->whereDate('created_at', '>=', $request->from_date);
        }
        if ($request->filled('to_date')) {
            $query->whereDate('created_at', '<=', $request->to_date);
        }

        // Determine eager loading relations based on selected columns
        $relationsToLoad = [];
        foreach ($selectedKeys as $key) {
            if (isset($allowedColumnsMap[$key]['relation'])) {
                $relationsToLoad[] = $allowedColumnsMap[$key]['relation'];
            }
        }
        if (!empty($relationsToLoad)) {
            $query->with(array_unique($relationsToLoad));
        }

        $query->orderBy('id', 'desc');

        $fileName = 'complaints_report_' . date('Y-m-d_H-i-s') . '.csv';

        return response()->streamDownload(function () use ($query, $selectedKeys, $allowedColumnsMap) {
            $handle = fopen('php://output', 'w');

            // Write UTF-8 BOM for proper Excel compatibility
            fputs($handle, "\xEF\xBB\xBF");

            // Write Header Row
            $headers = [];
            foreach ($selectedKeys as $key) {
                $headers[] = $allowedColumnsMap[$key]['label'];
            }
            fputcsv($handle, $headers);

            // Chunk processing for memory efficiency
            $query->chunk(500, function ($complaints) use ($handle, $selectedKeys, $allowedColumnsMap) {
                foreach ($complaints as $complaint) {
                    $row = [];
                    foreach ($selectedKeys as $key) {
                        $callback = $allowedColumnsMap[$key]['callback'];
                        $row[] = $callback($complaint);
                    }
                    fputcsv($handle, $row);
                }
                if (function_exists('flush')) {
                    flush();
                }
            });

            fclose($handle);
        }, $fileName, [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Cache-Control' => 'no-cache, no-store, must-revalidate',
            'Pragma' => 'no-cache',
            'Expires' => '0',
        ]);
    }

    /**
     * Get server-side whitelist for allowed complaint export columns.
     */
    private function getAllowedColumns()
    {
        return [
            'complaint_id' => [
                'label' => 'Complaint ID',
                'callback' => fn($c) => $c->id,
            ],
            'complaint_no' => [
                'label' => 'Complaint No',
                'callback' => fn($c) => $c->complaint_no,
            ],
            'name' => [
                'label' => 'Name',
                'callback' => fn($c) => $c->name,
            ],
            'cnic' => [
                'label' => 'CNIC',
                'callback' => fn($c) => $c->cnic ? (function_exists('addDashesInCNIC') ? addDashesInCNIC($c->cnic) : $c->cnic) : '',
            ],
            'mobile' => [
                'label' => 'Mobile',
                'callback' => fn($c) => $c->mobile ? (function_exists('addDashInMobile') ? addDashInMobile($c->mobile) : $c->mobile) : '',
            ],
            'category' => [
                'label' => 'Category',
                'relation' => 'category',
                'callback' => fn($c) => $c->category?->name ?? '',
            ],
            'description' => [
                'label' => 'Description',
                'callback' => fn($c) => $c->description ?? '',
            ],
            'location' => [
                'label' => 'Location',
                'callback' => fn($c) => $c->location ?? '',
            ],
            'created_by' => [
                'label' => 'Created By',
                'relation' => 'complaint_by',
                'callback' => fn($c) => $c->complaint_by?->name ?? '',
            ],
            'assigned_at' => [
                'label' => 'Assigned At',
                'callback' => fn($c) => $c->assigned_at ? \Carbon\Carbon::parse($c->assigned_at)->format('Y-m-d H:i:s') : '',
            ],
            'assigned_to_dept' => [
                'label' => 'Assigned to Dept',
                'relation' => 'department',
                'callback' => fn($c) => $c->department?->name ?? '',
            ],
            'resolved_by' => [
                'label' => 'Resolved By',
                'relation' => 'resolved_user',
                'callback' => fn($c) => $c->resolved_user?->name ?? '',
            ],
            'resolution_remark' => [
                'label' => 'Resolution Remark',
                'callback' => fn($c) => $c->remarks ?? '',
            ],
            'reopened_by' => [
                'label' => 'Reopened By',
                'relation' => 'reopened_user',
                'callback' => fn($c) => $c->reopened_user?->name ?? '',
            ],
            'reopened_remarks' => [
                'label' => 'Reopened Remarks',
                'callback' => fn($c) => $c->reopened_remarks ?? '',
            ],
            'complaint_status' => [
                'label' => 'Complaint Status',
                'callback' => function($c) {
                    $status = $c->complaint_status;
                    $department = $c->department_id;
                    $assignedAt = $c->assigned_at ? \Carbon\Carbon::parse($c->assigned_at) : null;

                    if ($status == 0 && $department > 0) {
                        if ($assignedAt && $assignedAt->lt(\Carbon\Carbon::now()->subDays(5))) {
                            return 'Overdue';
                        }
                        return 'Assigned to Department';
                    } elseif ($status == 1) {
                        return 'Resolved';
                    } elseif ($status == 2) {
                        return 'Rejected';
                    } elseif ($status == 3) {
                        return 'Reopened';
                    }
                    return 'Pending';
                },
            ],
        ];
    }
}
