<?php

namespace App\Http\Controllers\Acl;

use App\Http\Controllers\Controller;
use App\Http\Requests\Acl\RoleRequest;

use App\Models\Role;
use App\Models\PermissionGroup;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Yajra\DataTables\Facades\DataTables;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            
            $roles = Role::query();            
            $user = Auth::user();

            return DataTables::of($roles)
                ->addColumn('is_active', function ($role) {
                    return getStatusBadge($role->is_active);
                })
                ->addColumn('action', function ($role) use ($user) {
                $action = '<td><div class="overlay-edit">';

                    if ($user->can('Role Permissions Index')) {
                        $action .= '<a href="'.route('roles.getPermissions', $role->uuid).'" class="btn btn-icon btn-success"><i class="fas fa-key"></i></a>';
                    }

                    if ($user->can('Roles Update')) {
                        $action .= '<a href="'.route('roles.edit', $role->uuid).'" class="btn btn-icon btn-secondary"><i class="feather icon-edit-2"></i></a>';
                    }

                    if ($user->can('Roles Update Status')) {
                        $action .= '<a href="'.route('roles.updateStatus', $role->uuid).'" class="btn btn-icon '.($role->is_active == 1 ? "btn-danger" : "btn-success").' btn-status"><i class="feather '.($role->is_active == 1 ? "icon-eye-off" : "icon-eye").'"></i></a>';
                    }
                    if ($user->can('Roles Delete')) {    
                        $action .= '<a href="'.route('roles.destroy', $role->uuid).'" class="btn btn-icon btn-danger btn-delete"><i class="feather icon-trash-2"></i></a>';
                    }
                    $action .= '</div></td>';

                    return $action;
                    
                })
                ->editColumn('id', 'ID: {{$id}}')
                ->rawColumns(['is_active', 'action'])
                ->make(true);
        }
        
        return view('acl.roles.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        
        return view('acl.roles.create');

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\Acl\RoleRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(RoleRequest $request)
    {
        $roleData = $request->validated();

        Role::create($roleData);

        Session::flash('success', 'Role successfully created!');

        return redirect()->route('roles.index');
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
     * @param  \App\Models\Role $role
     * @return \Illuminate\Http\Response
     */
    public function edit(Role $role)
    {
        return view('acl.roles.edit', get_defined_vars());
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\Acl\RoleRequest  $request
     * @param  \App\Models\Role $role
     * @return \Illuminate\Http\Response
     */
    public function update(RoleRequest $request, Role $role) {

        $roleData = $request->validated();

        $role->update($roleData);

        Session::flash('success', 'Role successfully updated!');

        return redirect()->route('roles.index');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Role $role
     * @return \Illuminate\Http\Response
     */
    public function destroy(Role $role)
    {
        if ($role) {
            $role->delete();
            return $this->sendResponse(true, 'Role successfully deleted!');
        }

        return $this->sendResponse(false, 'Role not found!', [], 404);
    }

    /**
     * Update Status
     *
     * @param  \App\Models\Role $role
     * @return \Illuminate\Http\Response
     */
    public function updateStatus(Role $role) {

        if($role) {

            $role->is_active = !$role->is_active;
            $role->save();

            return $this->sendResponse(true, 'Role status successfully deleted!');
        }

        return $this->sendResponse(false, 'Role not found!', [], 404);
    }

    /**
     * Get role permissions.
     *
     * @param  \App\Models\Role $role
     * @return \Illuminate\Http\Response
     */
    public function getRolePermissions(Role $role)
    {
       $groups = PermissionGroup::with('permissions')->orderBy('ordering', 'asc')->get();
       return view('acl.roles.permissions', get_defined_vars());
    }

   /**
    * Update the specified resource in storage.
    *
    * @param  \Illuminate\Http\Request  $request
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */
   public function updateRolePermission($id, Request $request)
   {
       $permissions = $request->permissions;
       $role = Role::uuid($id)->firstOrFail();

       $role->syncPermissions($permissions);

       Session::flash('success', __('Permissions updated!'));
       return redirect()->route('roles.index');
   }
}
