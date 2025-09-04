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
use Illuminate\Support\Facades\DB;
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
            $user = Auth::user();
            $userId = $user->id;
            $name = $user->name;
            $username = $user->username;
            $mobile = $user->mobile;
        } else {
            $name = $request->name;
            $username = $request->username;
            $password = generatePassword();
            $mobile = $request->mobile;

            $user = User::create([
                'name' => $request->name,
                'username' => $username,
                'mobile' => $mobile,
                'role' => 'Complainant',
                'email' => @$request->email,
                'address' => $request->address,
                'password' => Hash::make($password),
            ]);

            if($user) {
                $user->assignRole('Complainant');
                $userId = $user->id;
            }
        }

        $category = Category::find($request->category);

        $complaintData = $request->validated();

        $lastId = Complaint::max('id') + 1;
        $complaintNo = $category->code .'-'. str_pad($lastId, 3, '0', STR_PAD_LEFT);
        $complaintData['complaint_no'] = $complaintNo;
        $complaintData['name'] = $name;
        $complaintData['cnic'] = $username;
        $complaintData['mobile'] = $mobile;
        $complaintData['category_id'] = $category->id;
        $complaintData['created_by'] = $userId;
        $complaintData['source_id'] = 1;
        $complaintData['complaint_at'] = date('Y-m-d H:i:s');

        if ($request->hasFile('attachment')) {
            $extension = $request->file('attachment')->getClientOriginalExtension();
            $fileName = $complaintNo . '.' . $extension;
            $request->file('attachment')->storeAs('complaints', $fileName, 'public');
            $complaintData['attachment'] = $fileName;
        }

        $complaint = Complaint::create($complaintData);

        if (Auth::check()) {
            $message = "Your complaint has been registered successfully. Your complaint number is: <b>" . $complaintNo . "</b>";
        } else {
            $message = "Your complaint has been registered successfully. Your account has also been created. <br/> Your complaint number is: <b>" . $complaintNo . "</b> <br/> Your username is: <b>" . $username . "</b> <br/> Your password is: <b>" . $password . "</b> <br/> Please change your password after login.";
        }

        if ($complaint) {
            complaintLog($complaint, 'creation');
        }
        
        Session::flash('success', $message);
        return redirect()->route('complaint');
    }

    public function checkStatus (Request $request) {
        
        $request->validate([
            'complaint_no' => 'required'
        ]);

        $complaintNo = $request->complaint_no;

        $complaint = Complaint::where('complaint_no', $request->complaint_no)->first();

        if($complaint) {
            if ($complaint->complaint_status == 0 && $complaint->approved_by == NULL) {
                $message = 'Your complaint is still pending approval. Please wait for the approval from the admin.';
            } elseif ($complaint->complaint_status == 0 && $complaint->approved_by != NULL && $complaint->assigned_by == NULL) {
                $message = 'Your complaint has been approved by the admin.';
            } elseif ($complaint->complaint_status == 0 && $complaint->approved_by != NULL && $complaint->department_id != NULL) {
                $message = 'Your complaint has been assigned to the concerned department.';
            } elseif ($complaint->complaint_status == 1) {
                $message = 'Your complaint has been successfully resolved.';
            } elseif ($complaint->complaint_status == 2) {
                $message = 'Your complaint is rejected.';
            } else {
                $message = 'Your complaint is under process.';
            }
        } else {
            $message = 'Complaint not found against this complaint no. (' . $complaintNo . ')';
        }

        DB::table('search_logs')->insert([
            'complaint_no' => $complaintNo,
            'response' => $message,
            'user_id' => @Auth::id(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return response()->json(['message' => $message]);
    }
}
