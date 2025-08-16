<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Setting;

use Illuminate\Support\Facades\Session;

class SettingController extends Controller
{
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($page = 'index')
    {
        $settings = Setting::pluck('value', 'key');
        return view('settings.' . $page, get_defined_vars());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $settings = $request->except('_token');

        foreach($settings as $key => $value) {
            Setting::updateOrCreate(['key' => $key], ['value' => $value]);
        }
        

        Session::flash('success', __('Setting successfully updated!'));
        return redirect()->route('settings.index');
    }
}
