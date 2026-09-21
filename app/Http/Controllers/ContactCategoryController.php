<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\ContactCategoryRequest;
use App\Models\ContactCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Yajra\DataTables\Facades\DataTables;

class ContactCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $categories = ContactCategory::withCount('officerContacts');

            return DataTables::of($categories)
                ->addColumn('officers_count', function ($category) {
                    return '<span class="badge bg-info text-white">' . $category->officer_contacts_count . ' Officers</span>';
                })
                ->addColumn('is_active', function ($category) {
                    return getStatusBadge($category->is_active);
                })
                ->addColumn('action', function ($category) {
                    $statusIcon = $category->is_active == 1 ? 'icon-x-circle' : 'icon-check-circle';
                    $statusClass = $category->is_active == 1 ? 'btn-danger' : 'btn-success';

                    return '<td>
                                <div class="overlay-edit">
                                    <a href="' . route('contact-categories.show', $category->uuid) . '" class="btn btn-icon btn-info" title="View"><i class="feather icon-eye"></i></a>
                                    <a href="' . route('contact-categories.edit', $category->uuid) . '" class="btn btn-icon btn-secondary" title="Edit"><i class="feather icon-edit-2"></i></a>
                                    <a href="' . route('contact-categories.updateStatus', $category->uuid) . '" class="btn btn-icon ' . $statusClass . ' btn-status" title="Toggle Status"><i class="feather ' . $statusIcon . '"></i></a>
                                    <a href="' . route('contact-categories.destroy', $category->uuid) . '" class="btn btn-icon btn-danger btn-delete" title="Delete"><i class="feather icon-trash-2"></i></a>
                                </div>
                            </td>';
                })
                ->rawColumns(['officers_count', 'is_active', 'action'])
                ->make(true);
        }

        return view('contact-categories.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('contact-categories.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\ContactCategoryRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ContactCategoryRequest $request)
    {
        ContactCategory::create($request->validated());

        Session::flash('success', 'Contact category successfully created!');

        return redirect()->route('contact-categories.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ContactCategory  $contactCategory
     * @return \Illuminate\Http\Response
     */
    public function show(ContactCategory $contactCategory)
    {
        $contactCategory->load(['officerContacts.department']);

        return view('contact-categories.show', compact('contactCategory'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ContactCategory  $contactCategory
     * @return \Illuminate\Http\Response
     */
    public function edit(ContactCategory $contactCategory)
    {
        return view('contact-categories.edit', compact('contactCategory'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\ContactCategoryRequest  $request
     * @param  \App\Models\ContactCategory  $contactCategory
     * @return \Illuminate\Http\Response
     */
    public function update(ContactCategoryRequest $request, ContactCategory $contactCategory)
    {
        $contactCategory->update($request->validated());

        Session::flash('success', 'Contact category successfully updated!');

        return redirect()->route('contact-categories.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ContactCategory  $contactCategory
     * @return \Illuminate\Http\Response
     */
    public function destroy(ContactCategory $contactCategory)
    {
        if ($contactCategory) {
            $assignedCount = $contactCategory->officerContacts()->count();
            if ($assignedCount > 0) {
                return $this->sendResponse(
                    false,
                    'Cannot delete category because it is currently assigned to ' . $assignedCount . ' officer contact record(s). Please reassign or delete the associated officers first.',
                    [],
                    422
                );
            }

            $contactCategory->delete();

            return $this->sendResponse(true, 'Contact category successfully deleted!');
        }

        return $this->sendResponse(false, 'Contact category not found!', [], 404);
    }

    /**
     * Update Status
     *
     * @param  \App\Models\ContactCategory  $contactCategory
     * @return \Illuminate\Http\Response
     */
    public function updateStatus(ContactCategory $contactCategory)
    {
        if ($contactCategory) {
            $contactCategory->is_active = $contactCategory->is_active == 1 ? 0 : 1;
            $contactCategory->save();

            return $this->sendResponse(true, 'Contact category status successfully updated!');
        }

        return $this->sendResponse(false, 'Contact category not found!', [], 404);
    }
}
