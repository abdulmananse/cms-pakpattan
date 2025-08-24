<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\CategoryRequest;
use App\Http\Requests\ComplaintRequest;
use App\Models\Category;
use App\Models\Complaint;
use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Yajra\DataTables\Facades\DataTables;

class ComplaintController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        if($request->ajax()) {

            $user = Auth::user();
            $complaints = Complaint::with('category', 'department', 'complaint_by')
                            ->roleFilter($user)
                            ->filter($request);

            if(!$request->filled('order')) {
                $complaints->orderBy('updated_at', 'desc');
            }

            return Datatables::of($complaints)
                ->addColumn('complaint_status', function ($complaint) {
                    return getComplaintStatusBadge($complaint);
                })
                ->addColumn('action', function ($complaint) use ($user) {
                    $action = '<td><div class="overlay-edit">';

                    if ($user->can('Complaints Show')) {
                        $action .= '<a href="'.route('complaints.show', $complaint->uuid).'" class="btn btn-icon btn-secondary"><i class="feather icon-eye"></i></a>';
                    }
                    $action .= '</div></td>';

                    return $action;
                    
                })
                ->rawColumns(['complaint_status', 'action'])
                ->make(true);
        }

        return view('complaints.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categories = Category::active()->orderBy('ordering')->pluck('name', 'id');
        return view('complaints.create', get_defined_vars());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Request\ComplaintRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ComplaintRequest $request)
    {
        $userId = Auth::id();
        $category = Category::find($request->category);

        $complaintData = $request->validated();

        $lastId = Complaint::max('id') + 1;
        $complaintNo = $category->code . str_pad($lastId, 4, '0', STR_PAD_LEFT);
        $complaintData['cnic'] = $request->username;
        $complaintData['category_id'] = $category->id;
        $complaintData['complaint_no'] = $complaintNo;
        $complaintData['created_by'] = $userId;

        if ($request->hasFile('attachment')) {
            $extension = $request->file('attachment')->getClientOriginalExtension();
            $fileName = $complaintNo . '.' . $extension;
            $request->file('attachment')->storeAs('complaints', $fileName, 'public');
            $complaintData['attachment'] = $fileName;
        }

        Complaint::create($complaintData);

        Session::flash('success', 'Complaint submitted successfully your complaint number is (' . $complaintNo . ')');

        return redirect()->route('complaints.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Complaint $complaint
     * @return \Illuminate\Http\Response
     */
    public function show(Complaint $complaint)
    {
        $user = Auth::user();
        $departments = Department::active()->pluck('name', 'id');
        return view('complaints.show', get_defined_vars());
    }

    /**
     * Assign complaint to department
     *
     * @param  \App\Http\Request\Request  $request
     * @param  \App\Models\Complaint $complaint
     * @return \Illuminate\Http\Response
     */
    public function assigned(Request $request, Complaint $complaint) {


        if ($request->filled('department_id')) {
            $complaint->department_id = $request->department_id;
            $complaint->assigned_by = Auth::id();
            $complaint->save();

            Session::flash('success', 'Complaint successfully assigned!');
            return redirect()->route('complaints.index');
        }

        Session::flash('error', 'Complaint not assigned!');
        return redirect()->back();
    }
    
    /**
     * Reject Complaint
     *
     * @param  \App\Models\Complaint $complaint
     * @return \Illuminate\Http\Response
     */
    public function rejected(Complaint $complaint) {

        $complaint->complaint_status = 2;
        $complaint->rejected_by = Auth::id();
        $complaint->save();

        return ['status' => true];
    }
    
    /**
     * Reject Complaint
     *
     * @param  \App\Http\Request\Request  $request
     * @param  \App\Models\Complaint $complaint
     * @return \Illuminate\Http\Response
     */
    public function resolved(Request $request, Complaint $complaint) {

        if ($request->hasFile('attachment')) {
            $extension = $request->file('attachment')->getClientOriginalExtension();
            $fileName = $complaint->complaint_no . '_r.' . $extension;
            $request->file('attachment')->storeAs('complaints', $fileName, 'public');
            $complaint->resolved_attachment = $fileName;
        }

        $complaint->complaint_status = 1;
        $complaint->remarks = $request->remarks;
        $complaint->resolved_by = Auth::id();
        $complaint->save();

        Session::flash('success', 'Complaint successfully resolved!');
        return redirect()->route('complaints.index');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Category $category
     * @return \Illuminate\Http\Response
     */
    public function edit(Category $category)
    {
        return view('categories.edit', get_defined_vars());
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Request\CategoryRequest  $request
     * @param  \App\Models\Category $category
     * @return \Illuminate\Http\Response
     */
    public function update(CategoryRequest $request, Category $category) {

        $category->update($request->validated());

        Session::flash('success', 'Category successfully updated!');

        return redirect()->route('categories.index');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Category $category
     * @return \Illuminate\Http\Response
     */
    public function destroy(Category $category) {
        if($category) {
            $category->delete();
            return $this->sendResponse(true, 'Category successfully deleted!');
        }

        return $this->sendResponse(false, 'Category not found!', [], 404);
    }

    /**
     * Update Status
     *
     * @param  \App\Models\Category $category
     * @return \Illuminate\Http\Response
     */
    public function updateStatus(Category $category) {

        if($category) {
            $category->is_active = !$category->is_active;
            $category->save();

            return $this->sendResponse(true, 'Category status successfully updated!');
        }

        return $this->sendResponse(false, 'Category not found!', [], 404);
    }
}
