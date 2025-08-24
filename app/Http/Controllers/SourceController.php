<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\CategoryRequest;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Yajra\DataTables\Facades\DataTables;

class SourceController extends Controller
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
