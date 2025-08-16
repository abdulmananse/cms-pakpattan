<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\CategoryRequest;
use App\Http\Requests\ComplaintRequest;
use App\Models\Category;
use App\Models\Complaint;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Hash;

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

            $categories = Category::query();

            if(!$request->filled('order')) {
                $categories->orderBy('ordering', 'asc');
            }

            return Datatables::of($categories)
                ->addColumn('is_active', function ($category) {
                    return getStatusBadge($category->is_active);
                })
                ->addColumn('action', function ($category) {
                    $statusAction = '   <td>
                                            <div class="overlay-edit">
                                                <a href="'.route('categories.edit', $category->uuid).'" class="btn btn-icon btn-secondary"><i class="feather icon-edit-2"></i></a>
                                                <a href="'.route('categories.updateStatus', $category->uuid).'" class="btn btn-icon '.($category->is_active == 1 ? "btn-danger" : "btn-success").' btn-status"><i class="feather '.($category->is_active == 1 ? "icon-x-circle" : "icon-check-circle").'"></i></a>
                                                <a href="'.route('categories.destroy', $category->uuid).'" class="btn btn-icon btn-danger btn-delete"><i class="feather icon-trash-2"></i></a>
                                            </div>
                                        </td>';
                    return $statusAction;
                })
                ->rawColumns(['is_active', 'action'])
                ->make(true);

        }

        return view('categories.index');
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
        // dd($request->all());
        $userId = NULL;
        if (Auth::check()) {
            $userId = Auth::id();
        } else {
            $user = User::create([
                'name' => $request->name,
                'username' => $request->username,
                'mobile' => $request->mobile,
                'role' => 'Complainant',
                'email' => @$request->email,
                'address' => $request->address,
                'password' => Hash::make($request->password),
            ]);

            if($user) {
                $user->assignRole('Complainant');
                $userId = $user->id;
            }
        }

        $category = Category::find($request->category);

        $complaintData = $request->validated();

        $lastId = Complaint::max('id') + 1;
        $complaintNo = $category->code . str_pad($lastId, 4, '0', STR_PAD_LEFT);
        $complaintData['complaint_no'] = $complaintNo;
        $complaintData['category_id'] = $category->id;
        $complaintData['created_by'] = $userId;
        $complaintData['source'] = 'Online Form';

        if ($request->hasFile('attachment')) {
            $extension = $request->file('attachment')->getClientOriginalExtension();
            $fileName = $complaintNo . '.' . $extension;
            $request->file('attachment')->storeAs('complaints', $fileName, 'public');
            $complaintData['attachment'] = $fileName;
        }

        Complaint::create($complaintData);

        Session::flash('success', 'Complaint submitted successfully your complaint number is (' . $complaintNo . ')');

        return redirect()->route('complaint');
    }

    public function status()
    {
        return view('complaints.check-status', get_defined_vars());
    }

    public function checkStatus (Request $request) {
        
        $request->validate([
            'complaint_no' => 'required'
        ]);

        $complaintNo = $request->complaint_no;

        $complaint = Complaint::where('complaint_no', $request->complaint_no)->first();

        if($complaint) {
            if ($complaint->complaint_status == 0 && $complaint->approved_by == NULL) {
                return back()->withInput()->with('info', 'Your complaint is still pending approval. Please wait for the approval from the admin.');
            } elseif ($complaint->complaint_status == 0 && $complaint->approved_by != NULL && $complaint->assigned_by == NULL) {
                return back()->withInput()->with('info', 'Your complaint has been approved by the admin.');
            } elseif ($complaint->complaint_status == 0 && $complaint->approved_by != NULL && $complaint->department_id != NULL) {
                return back()->withInput()->with('info', 'Your complaint has been assigned to the concerned department.');
            } elseif ($complaint->complaint_status == 1) {
                return back()->withInput()->with('success', 'Your complaint has been successfully resolved.');
            } elseif ($complaint->complaint_status == 2) {
                return back()->withInput()->with('error', 'Your complaint is rejected.');
            }
        }

        return back()->withInput()->with('error', 'Complaint not found against this complaint no. (' . $complaintNo . ')');
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
