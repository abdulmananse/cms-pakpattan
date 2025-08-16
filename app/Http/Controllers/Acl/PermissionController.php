<?php

namespace App\Http\Controllers\Acl;

use App\Http\Controllers\Controller;
use App\Http\Requests\Acl\PermissionRequest;
use App\Models\Permission;
use App\Models\PermissionGroup;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Yajra\DataTables\Facades\DataTables;

class PermissionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        if($request->ajax()) {

            $permissions = Permission::query();

            return Datatables::of($permissions)
                ->addColumn('action', function ($permission) {
                    $statusAction = '   <td>
                                            <div class="overlay-edit">
                                                <a href="'.route('permissions.edit', $permission->uuid).'" class="btn btn-icon btn-secondary"><i class="feather icon-edit-2"></i></a>
                                                <a href="'.route('permissions.destroy', $permission->uuid).'" class="btn btn-icon btn-danger btn-delete"><i class="feather icon-trash-2"></i></a>
                                            </div>
                                        </td>';
                    return $statusAction;
                })
                ->editColumn('id', 'ID: {{$id}}')
                ->make(true);

        }

        return view('acl.permissions.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $groups = PermissionGroup::pluck('name', 'id');
        return view('acl.permissions.create', get_defined_vars());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Request\Acl\PermissionRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(PermissionRequest $request)
    {
        $permissionData = $request->validated();
        Permission::create($permissionData);

        Session::flash('success', 'Permission successfully created!');

        return redirect()->route('permissions.create');
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
     * @param  \App\Models\Permission $permission
     * @return \Illuminate\Http\Response
     */
    public function edit(Permission $permission)
    {
        $groups = PermissionGroup::pluck('name', 'id');
        return view('acl.permissions.edit', get_defined_vars());
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Acl\Request  $request
     * @param  \App\Models\Permission $permission
     * @return \Illuminate\Http\Response
     */
    public function update(PermissionRequest $request, Permission $permission) {

        $permissionData = $request->validated();
        $permission->update($permissionData);

        Session::flash('success', 'Permission successfully updated!');

        return redirect()->route('permissions.index');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {


        if($id) {

            Permission::where('uuid', '=', $id)->delete();

            return $this->sendResponse(true, 'Permission successfully deleted!');
        }

        return $this->sendResponse(false, 'Permission not found!', [], 404);

    }
}
