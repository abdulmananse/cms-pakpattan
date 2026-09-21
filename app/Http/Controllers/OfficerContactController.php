<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\OfficerContactRequest;
use App\Models\ContactCategory;
use App\Models\Department;
use App\Models\OfficerContact;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Yajra\DataTables\Facades\DataTables;

class OfficerContactController extends Controller
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
            $query = OfficerContact::with(['department', 'contactCategory']);

            // Filters
            if ($request->filled('department_id')) {
                $query->where('department_id', $request->department_id);
            }

            if ($request->filled('contact_category_id')) {
                $query->where('contact_category_id', $request->contact_category_id);
            }

            if ($request->filled('lifecycle_status')) {
                $query->where('lifecycle_status', $request->lifecycle_status);
            }

            if ($request->filled('is_pcm') && $request->is_pcm !== 'all') {
                $query->where('is_pcm', (bool) $request->is_pcm);
            }

            if ($request->filled('is_favorite') && $request->is_favorite !== 'all') {
                $query->where('is_favorite', (bool) $request->is_favorite);
            }

            // Global search custom enhancement
            if ($request->filled('search.value')) {
                $search = $request->input('search.value');
                $query->where(function ($q) use ($search) {
                    $q->where('officer_name', 'like', "%{$search}%")
                        ->orWhere('designation', 'like', "%{$search}%")
                        ->orWhere('primary_mobile', 'like', "%{$search}%")
                        ->orWhere('alternate_phone', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%")
                        ->orWhere('office_establishment', 'like', "%{$search}%")
                        ->orWhereHas('department', function ($d) use ($search) {
                            $d->where('name', 'like', "%{$search}%");
                        })
                        ->orWhereHas('contactCategory', function ($c) use ($search) {
                            $c->where('name', 'like', "%{$search}%");
                        });
                });
            }

            return DataTables::of($query)
                ->addColumn('photo', function ($officer) {
                    return '<img src="' . e($officer->photo_url) . '" alt="' . e($officer->officer_name) . '" class="rounded-circle" width="42" height="42" style="object-fit:cover; border: 2px solid #e2e8f0;" />';
                })
                ->addColumn('officer_name', function ($officer) {
                    $star = $officer->is_favorite ? ' <i class="fas fa-star text-warning" title="Favorite"></i>' : '';
                    return '<a href="' . route('officer-contacts.show', $officer->uuid) . '" class="text-primary font-weight-bold">' . e($officer->officer_name) . '</a>' . $star;
                })
                ->addColumn('department', function ($officer) {
                    return $officer->department ? e($officer->department->name) : '<span class="text-muted">-</span>';
                })
                ->addColumn('category', function ($officer) {
                    return $officer->contactCategory ? '<span class="badge bg-light text-dark border">' . e($officer->contactCategory->name) . '</span>' : '<span class="text-muted">-</span>';
                })
                ->addColumn('primary_mobile', function ($officer) {
                    return '<a href="tel:' . e($officer->primary_mobile) . '" class="text-dark"><i class="feather icon-phone me-1 text-success"></i>' . e($officer->primary_mobile) . '</a>';
                })
                ->addColumn('lifecycle_status', function ($officer) {
                    $isActive = strtolower($officer->lifecycle_status) === 'active';
                    $badgeClass = $isActive ? 'bg-success' : 'bg-danger';
                    return '<span class="badge ' . $badgeClass . '">' . e($officer->lifecycle_status) . '</span>';
                })
                ->addColumn('is_pcm', function ($officer) {
                    if ($officer->is_pcm) {
                        return '<a href="' . route('officer-contacts.togglePcm', $officer->uuid) . '" class="badge bg-success btn-pcm-toggle" title="Click to toggle PCM status">PCM</a>';
                    }
                    return '<a href="' . route('officer-contacts.togglePcm', $officer->uuid) . '" class="badge bg-secondary btn-pcm-toggle" title="Click to toggle PCM status">Non-PCM</a>';
                })
                ->addColumn('is_favorite', function ($officer) {
                    if ($officer->is_favorite) {
                        return '<a href="' . route('officer-contacts.toggleFavorite', $officer->uuid) . '" class="btn-fav-toggle text-warning" title="Marked as Favorite (Click to remove)"><i class="fas fa-star fa-lg"></i></a>';
                    }
                    return '<a href="' . route('officer-contacts.toggleFavorite', $officer->uuid) . '" class="btn-fav-toggle text-muted" title="Not Favorite (Click to mark)"><i class="far fa-star fa-lg"></i></a>';
                })
                ->addColumn('action', function ($officer) {
                    $statusIcon = strtolower($officer->lifecycle_status) === 'active' ? 'icon-x-circle' : 'icon-check-circle';
                    $statusClass = strtolower($officer->lifecycle_status) === 'active' ? 'btn-danger' : 'btn-success';

                    return '<td>
                                <div class="overlay-edit">
                                    <a href="' . route('officer-contacts.show', $officer->uuid) . '" class="btn btn-icon btn-info" title="View Details"><i class="feather icon-eye"></i></a>
                                    <a href="' . route('officer-contacts.edit', $officer->uuid) . '" class="btn btn-icon btn-secondary" title="Edit"><i class="feather icon-edit-2"></i></a>
                                    <a href="' . route('officer-contacts.updateStatus', $officer->uuid) . '" class="btn btn-icon ' . $statusClass . ' btn-status" title="Toggle Active/Inactive"><i class="feather ' . $statusIcon . '"></i></a>
                                    <a href="' . route('officer-contacts.destroy', $officer->uuid) . '" class="btn btn-icon btn-danger btn-delete" title="Delete"><i class="feather icon-trash-2"></i></a>
                                </div>
                            </td>';
                })
                ->rawColumns(['photo', 'officer_name', 'department', 'category', 'primary_mobile', 'lifecycle_status', 'is_pcm', 'is_favorite', 'action'])
                ->make(true);
        }

        $departments = getActiveDepartments();
        $categories = getActiveContactCategories();

        return view('officer-contacts.index', compact('departments', 'categories'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $departments = getActiveDepartments();
        $categories = getActiveContactCategories();
        $officer = null;

        return view('officer-contacts.create', compact('departments', 'categories', 'officer'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\OfficerContactRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(OfficerContactRequest $request)
    {
        $data = $request->validated();

        if ($request->hasFile('photo')) {
            $extension = $request->file('photo')->getClientOriginalExtension();
            $fileName = 'officer_' . time() . '_' . Str::random(8) . '.' . $extension;
            $request->file('photo')->storeAs('officers', $fileName, 'public');
            $data['photo'] = $fileName;
        }

        OfficerContact::create($data);

        Session::flash('success', 'Officer contact successfully created!');

        return redirect()->route('officer-contacts.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\OfficerContact  $OfficerContact
     * @return \Illuminate\Http\Response
     */
    public function show(OfficerContact $officerContact)
    {
        $officerContact->load(['department', 'contactCategory']);

        return view('officer-contacts.show', compact('officerContact'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\OfficerContact  $OfficerContact
     * @return \Illuminate\Http\Response
     */
    public function edit(OfficerContact $officerContact)
    {
        $departments = getActiveDepartments();
        $categories = getActiveContactCategories();
        $officer = $officerContact;

        return view('officer-contacts.edit', compact('departments', 'categories', 'officer', 'officerContact'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\OfficerContactRequest  $request
     * @param  \App\Models\OfficerContact  $OfficerContact
     * @return \Illuminate\Http\Response
     */
    public function update(OfficerContactRequest $request, OfficerContact $OfficerContact)
    {
        $data = $request->validated();

        if ($request->hasFile('photo')) {
            // Delete old photo if exists
            if ($OfficerContact->photo && Storage::disk('public')->exists('officers/' . $OfficerContact->photo)) {
                Storage::disk('public')->delete('officers/' . $OfficerContact->photo);
            }

            $extension = $request->file('photo')->getClientOriginalExtension();
            $fileName = 'officer_' . time() . '_' . Str::random(8) . '.' . $extension;
            $request->file('photo')->storeAs('officers', $fileName, 'public');
            $data['photo'] = $fileName;
        }

        $OfficerContact->update($data);

        Session::flash('success', 'Officer contact successfully updated!');

        return redirect()->route('officer-contacts.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\OfficerContact  $OfficerContact
     * @return \Illuminate\Http\Response
     */
    public function destroy(OfficerContact $OfficerContact)
    {
        if ($OfficerContact) {
            if ($OfficerContact->photo && Storage::disk('public')->exists('officers/' . $OfficerContact->photo)) {
                Storage::disk('public')->delete('officers/' . $OfficerContact->photo);
            }

            $OfficerContact->delete();

            return $this->sendResponse(true, 'Officer contact record successfully deleted!');
        }

        return $this->sendResponse(false, 'Officer record not found!', [], 404);
    }

    /**
     * Toggle PCM status via quick AJAX.
     *
     * @param  \App\Models\OfficerContact  $OfficerContact
     * @return \Illuminate\Http\Response
     */
    public function togglePcm(OfficerContact $OfficerContact)
    {
        if ($OfficerContact) {
            $OfficerContact->is_pcm = !$OfficerContact->is_pcm;
            $OfficerContact->save();

            $status = $OfficerContact->is_pcm ? 'marked as Price Control Magistrate (PCM)' : 'unmarked as Price Control Magistrate (PCM)';

            return $this->sendResponse(true, 'Officer successfully ' . $status . '!');
        }

        return $this->sendResponse(false, 'Officer record not found!', [], 404);
    }

    /**
     * Toggle Favorite status via quick AJAX.
     *
     * @param  \App\Models\OfficerContact  $OfficerContact
     * @return \Illuminate\Http\Response
     */
    public function toggleFavorite(OfficerContact $OfficerContact)
    {
        if ($OfficerContact) {
            $OfficerContact->is_favorite = !$OfficerContact->is_favorite;
            $OfficerContact->save();

            $status = $OfficerContact->is_favorite ? 'marked as Favorite' : 'removed from Favorites';

            return $this->sendResponse(true, 'Officer successfully ' . $status . '!');
        }

        return $this->sendResponse(false, 'Officer record not found!', [], 404);
    }

    /**
     * Update Status
     *
     * @param  \App\Models\OfficerContact  $OfficerContact
     * @return \Illuminate\Http\Response
     */
    public function updateStatus(OfficerContact $OfficerContact)
    {
        if ($OfficerContact) {
            $OfficerContact->lifecycle_status = (strtolower($OfficerContact->lifecycle_status) === 'active') ? 'Inactive' : 'Active';
            $OfficerContact->save();

            return $this->sendResponse(true, 'Officer lifecycle status updated successfully!');
        }

        return $this->sendResponse(false, 'Officer record not found!', [], 404);
    }
}
