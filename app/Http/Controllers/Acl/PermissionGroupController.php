<?php

namespace App\Http\Controllers\Acl;

use App\Http\Controllers\Controller;
use App\Http\Requests\Acl\PermissionGroupRequest;
use App\Models\PermissionGroup;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Yajra\DataTables\Facades\DataTables;

class PermissionGroupController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        if($request->ajax()) {

            $groups = PermissionGroup::query();

            if(!$request->filled('order')) {
                $groups->orderBy('ordering', 'asc');
            }

            return Datatables::of($groups)
                ->addColumn('action', function ($group) {
                    $statusAction = '   <td>
                                            <div class="overlay-edit">
                                                <a href="'.route('permission-groups.edit', $group->uuid).'" class="btn btn-icon btn-secondary"><i class="feather icon-edit-2"></i></a>
                                                <a href="'.route('permission-groups.destroy', $group->uuid).'" class="btn btn-icon btn-danger btn-delete"><i class="feather icon-trash-2"></i></a>
                                            </div>
                                        </td>';
                    return $statusAction;
                })
                ->editColumn('id', 'ID: {{$id}}')
                ->make(true);

        }

        return view('acl.permission-groups.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('acl.permission-groups.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Request\Acl\PermissionGroupRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(PermissionGroupRequest $request)
    {
        PermissionGroup::create($request->validated());

        Session::flash('success', 'Group successfully created!');

        return redirect()->route('permission-groups.index');
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
     * @param  \App\Models\PermissionGroup $group
     * @return \Illuminate\Http\Response
     */
    public function edit(PermissionGroup $group)
    {
        return view('acl.permission-groups.edit', get_defined_vars());
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Request\Acl\PermissionGroupRequest  $request
     * @param  \App\Models\PermissionGroup $group
     * @return \Illuminate\Http\Response
     */
    public function update(PermissionGroupRequest $request, PermissionGroup $group) {

        $group->update($request->validated());

        Session::flash('success', 'Group successfully updated!');

        return redirect()->route('permission-groups.index');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\PermissionGroup $group
     * @return \Illuminate\Http\Response
     */
    public function destroy(PermissionGroup $group) {
        if($group) {
            $group->delete();
            return $this->sendResponse(true, 'Group successfully deleted!');
        }

        return $this->sendResponse(false, 'Group not found!', [], 404);

    }
}
