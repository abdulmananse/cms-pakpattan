<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\SourceRequest;
use App\Models\Source;
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

            $sources = Source::query();

            if(!$request->filled('order')) {
                $sources->orderBy('ordering', 'asc');
            }

            return Datatables::of($sources)
                ->addColumn('is_active', function ($source) {
                    return getStatusBadge($source->is_active);
                })
                ->addColumn('action', function ($source) {
                    $statusAction = '   <td>
                                            <div class="overlay-edit">
                                                <a href="'.route('sources.edit', $source->uuid).'" class="btn btn-icon btn-secondary"><i class="feather icon-edit-2"></i></a>
                                                <a href="'.route('sources.updateStatus', $source->uuid).'" class="btn btn-icon '.($source->is_active == 1 ? "btn-danger" : "btn-success").' btn-status"><i class="feather '.($source->is_active == 1 ? "icon-x-circle" : "icon-check-circle").'"></i></a>
                                                <a href="'.route('sources.destroy', $source->uuid).'" class="btn btn-icon btn-danger btn-delete"><i class="feather icon-trash-2"></i></a>
                                            </div>
                                        </td>';
                    return $statusAction;
                })
                ->rawColumns(['is_active', 'action'])
                ->make(true);

        }

        return view('sources.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('sources.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Request\SourceRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(SourceRequest $request)
    {
        Source::create($request->validated());

        Session::flash('success', 'Source successfully added!');

        return redirect()->route('sources.index');
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
     * @param  \App\Models\Source $source
     * @return \Illuminate\Http\Response
     */
    public function edit(Source $source)
    {
        return view('sources.edit', get_defined_vars());
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Request\SourceRequest  $request
     * @param  \App\Models\Source $source
     * @return \Illuminate\Http\Response
     */
    public function update(SourceRequest $request, Source $source) {

        $source->update($request->validated());

        Session::flash('success', 'Source successfully updated!');

        return redirect()->route('sources.index');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Source $source
     * @return \Illuminate\Http\Response
     */
    public function destroy(Source $source) {
        if($source) {
            $source->delete();
            return $this->sendResponse(true, 'Source successfully deleted!');
        }

        return $this->sendResponse(false, 'Source not found!', [], 404);
    }

    /**
     * Update Status
     *
     * @param  \App\Models\Source $source
     * @return \Illuminate\Http\Response
     */
    public function updateStatus(Source $source) {

        if($source) {
            $source->is_active = !$source->is_active;
            $source->save();

            return $this->sendResponse(true, 'Source status successfully updated!');
        }

        return $this->sendResponse(false, 'Source not found!', [], 404);
    }
}
