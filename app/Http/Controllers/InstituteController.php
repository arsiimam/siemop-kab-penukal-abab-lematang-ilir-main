<?php

namespace App\Http\Controllers;

use App\DataTables\InstituteDataTable;
use App\Models\Institute;
use Illuminate\Http\Request;
use Auth;

class InstituteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(InstituteDataTable $dataTable)
    {
        if (Auth::user()->can('Manage Institute')) {
            return $dataTable->render('backend.institute.index');
        } else {
            return back()->with('error', __('Akses Ditolak.'));
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        if (Auth::user()->can('Create Institute')) {
            return view('backend.institute.add');
        } else {
            return response()->json(['status' => 'error', 'message' => __('Akses Ditolak')]);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Institute $institute)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Institute $institute)
    {
        if (Auth::user()->can('Edit Institute')) {
            return view('backend.institute.edit', compact('institute'));
        } else {
            return response()->json(['status' => 'error', 'message' => __('Akses Ditolak')]);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Institute $institute)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Institute $institute)
    {
        //
    }
}
