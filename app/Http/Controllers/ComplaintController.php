<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\CategoryRequest;
use App\Models\Category;
use App\Models\Complaint;
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
            $complaints = Complaint::query();

            if(!$request->filled('order')) {
                $complaints->orderBy('updated_at', 'desc');
            }

            return Datatables::of($complaints)
                ->addColumn('complaint_status', function ($complaint) {
                    return getComplaintStatusBadge($complaint->complaint_status);
                })
                ->addColumn('action', function ($complaint) use ($user) {
                    $action = '<td><div class="overlay-edit">';

                    // if ($user->can('Complaints Show')) {
                    //     $action .= '<a href="'.route('users.edit', $user->uuid).'" class="btn btn-icon btn-secondary"><i class="feather icon-edit-2"></i></a>';
                    // }
                    $action .= '</div></td>';

                    return $action;
                    
                })
                ->rawColumns(['is_active', 'action'])
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
        return view('categories.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Request\CategoryRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CategoryRequest $request)
    {
        Category::create($request->validated());

        Session::flash('success', 'Category successfully created!');

        return redirect()->route('categories.index');
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
