<?php

namespace App\Http\Controllers;


use Illuminate\Support\Facades\Session;
use Yajra\DataTables\Facades\DataTables;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\ProfileRequest;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\Department;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        if ($request->ajax()) {

            $users = User::where('id', '!=', 1);
            $authUser = Auth::user();

            if ($request->filled('order')) {
                $column = $request->columns[$request->order[0]['column']]['data'];
                $orderBy = $request->order[0]['dir'];
                if ($column == 'is_active') {
                    $users->orderBy($column, $orderBy);
                } 
            } else {
                $users->orderBy('updated_at', 'desc');
            }

            return Datatables::of($users)
                ->addColumn('role', function ($user) {
                    return $user->getRoleNames()->first();
                })
                ->addColumn('is_active', function ($user) {
                    return getStatusBadge($user->is_active);
                })
                ->addColumn('action', function ($user) use ($authUser) {
                    $action = '<td><div class="overlay-edit">';

                    if ($authUser->can('Users Update')) {
                        $action .= '<a href="'.route('users.edit', $user->uuid).'" class="btn btn-icon btn-secondary"><i class="feather icon-edit-2"></i></a>';
                    }
                    if ($authUser->can('Users Update Status')) {
                        $action .= '<a href="'.route('users.updateStatus', $user->uuid).'" class="btn btn-icon '.($user->is_active == 1 ? "btn-danger" : "btn-success").' btn-status"><i class="feather '.($user->is_active == 1 ? "icon-x-circle" : "icon-check-circle").'"></i></a>';
                    }
                    if ($authUser->can('Users Delete')) {    
                        $action .= '<a href="'.route('users.destroy', $user->uuid).'" class="btn btn-icon btn-danger btn-delete"><i class="feather icon-trash-2"></i></a>';
                    }
                    $action .= '</div></td>';

                    return $action;
                    
                })
                ->rawColumns(['is_active', 'action'])
                ->make(true);
        }
        
        return view('users.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $roles = Role::where('id', '!=', 1)->active()->pluck('name', 'id');
        $departments = getActiveDepartments();
        $sources = getActiveSources();
        $selectedDepartments = null;

        return view('users.create', get_defined_vars());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreUserRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreUserRequest $request) {
        
        $userData = $request->validated();
        if ($request->filled('mobile')) {
            $userData['mobile'] = str_replace('-', '', $request->mobile);
        }
        $userData['password'] = Hash::make($request->password);
        
        $role = Role::find($request->role);

        if ($role) {
            $userData['role'] = $role->name;
        }

        $user = User::create($userData);

        if($user && $role) {
            $user->assignRole($role);
        }
        
        if ($request->filled('department_ids')) {
            $user->departments()->sync($request->input('department_ids', []));
        }

        Session::flash('success', 'User successfully created');

        return redirect()->route('users.index');
        
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user) {

        $roles = Role::where('id', '!=', 1)->active()->pluck('name', 'id');

        $role = $user->roles->first();
        if ($role) {
            $user->role = $role->id;
        } else {
            $user->role = '';
        }
        
        $departments = getActiveDepartments();
        $sources = getActiveSources();
        
        $selectedDepartments = $user->departments->pluck('id')->toArray();
       
        return view('users.edit', get_defined_vars());
        
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateUserRequest  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateUserRequest $request, User $user)
    {
        $userData = $request->validated();

        $role = Role::find($request->role);

        if ($role) {
            $userData['role'] = $role->name;
            $user->syncRoles($role);
        }
        
        if ($request->filled('mobile')) {
            $userData['mobile'] = str_replace('-', '', $request->mobile);
        }

        if ($request->filled('password')) {
            $userData['password'] = Hash::make($request->password);
            DB::table('sessions')->where('user_id', $user->id)->delete();
        } else {
            unset($userData['password']);
        }

        $user->update($userData);

        $user->departments()->sync($request->input('department_ids', []));

        Session::flash('success', 'User successfully updated');
        
        return redirect()->route('users.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        if ($user) {
            $user->delete();
            return $this->sendResponse(true, 'User deleted successfully.');
        }

        return $this->sendResponse(false, 'User not found.', [], 404);
    }

    /**
     * Update Status
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function updateStatus(User $user)
    {
        if ($user) {
            $user->is_active = !$user->is_active;
            $user->save();
            return $this->sendResponse(true, 'User status updated successfully.');
        }

        return $this->sendResponse(false, 'User not found.', [], 404);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function profile()
    {
        $user = Auth::user();
        return view('users.profile', get_defined_vars());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function editProfile()
    {
        $user = Auth::user();

        return view('users.update-profile', get_defined_vars());
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\ProfileRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function updateProfile(ProfileRequest $request)
    {

        $user = User::find(Auth::id());

        $userData = $request->validated();
        if ($request->filled('mobile')) {
            $userData['mobile'] = str_replace('-', '', $request->mobile);
        }

        $user->update($userData);

        Session::flash('success', 'User profile successfully updated');

        return redirect()->route('profile');
    }
    
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function changePassword()
    {
        $user = Auth::user();
        return view('users.change-password', get_defined_vars());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreUserRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function updatePassword(Request $request) {
        
        $request->validate([
            'password' => ['required', 'confirmed', Rules\Password::defaults()]
        ]);

        User::where('id', Auth::id())
            ->update(['password' => Hash::make($request->password)]);

        Session::flash('success', 'Password successfully updated');

        return redirect()->route('profile');
    }
}
