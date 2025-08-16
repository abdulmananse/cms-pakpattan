<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\DepartmentRequest;
use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Yajra\DataTables\Facades\DataTables;

class DepartmentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        if($request->ajax()) {

            $departments = Department::query();

            if(!$request->filled('order')) {
                $departments->orderBy('ordering', 'asc');
            }

            return Datatables::of($departments)
                ->addColumn('is_active', function ($department) {
                    return getStatusBadge($department->is_active);
                })
                ->addColumn('action', function ($department) {
                    $statusAction = '   <td>
                                            <div class="overlay-edit">
                                                <a href="'.route('departments.edit', $department->uuid).'" class="btn btn-icon btn-secondary"><i class="feather icon-edit-2"></i></a>
                                                <a href="'.route('departments.updateStatus', $department->uuid).'" class="btn btn-icon '.($department->is_active == 1 ? "btn-danger" : "btn-success").' btn-status"><i class="feather '.($department->is_active == 1 ? "icon-x-circle" : "icon-check-circle").'"></i></a>
                                                <a href="'.route('departments.destroy', $department->uuid).'" class="btn btn-icon btn-danger btn-delete"><i class="feather icon-trash-2"></i></a>
                                            </div>
                                        </td>';
                    return $statusAction;
                })
                ->rawColumns(['is_active', 'action'])
                ->make(true);

        }

        return view('departments.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('departments.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Request\DepartmentRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(DepartmentRequest $request)
    {
        Department::create($request->validated());

        Session::flash('success', 'Department successfully created!');

        return redirect()->route('departments.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Department $department
     * @return \Illuminate\Http\Response
     */
    public function edit(Department $department)
    {
        return view('departments.edit', get_defined_vars());
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Request\DepartmentRequest  $request
     * @param  \App\Models\Department $department
     * @return \Illuminate\Http\Response
     */
    public function update(DepartmentRequest $request, Department $department) {

        $department->update($request->validated());

        Session::flash('success', 'Department successfully updated!');

        return redirect()->route('departments.index');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Department $department
     * @return \Illuminate\Http\Response
     */
    public function destroy(Department $department) {
        if($department) {
            $department->delete();
            return $this->sendResponse(true, 'Department successfully deleted!');
        }

        return $this->sendResponse(false, 'Department not found!', [], 404);
    }

    /**
     * Update Status
     *
     * @param  \App\Models\Department $department
     * @return \Illuminate\Http\Response
     */
    public function updateStatus(Department $department) {

        if($department) {
            $department->is_active = !$department->is_active;
            $department->save();

            return $this->sendResponse(true, 'Department status successfully updated!');
        }

        return $this->sendResponse(false, 'Department not found!', [], 404);
    }
}
