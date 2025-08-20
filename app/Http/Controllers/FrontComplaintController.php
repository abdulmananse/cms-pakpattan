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
use Illuminate\Support\Facades\Hash;

class FrontComplaintController extends Controller
{
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categories = Category::active()->orderBy('ordering')->pluck('name', 'id');
        return view('front-complaints.create', get_defined_vars());
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
        $complaintData['name'] = $request->name;
        $complaintData['cnic'] = $request->username;
        $complaintData['mobile'] = $request->mobile;
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
        return view('front-complaints.check-status', get_defined_vars());
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
}
