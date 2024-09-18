<?php

namespace App\Http\Controllers;

use App\DataTables\ActivityProgramDataTable;
use App\Models\ActivityProgram;
use App\Models\Institute;
use Illuminate\Http\Request;
use Auth;

class ActivityProgramController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(ActivityProgramDataTable $dataTable)
    {
        if (Auth::user()->can('Manage Activity Program')) {
            return $dataTable->render('backend.activity_program.index');
        } else {
            return back()->with('error', __('Akses Ditolak.'));
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        if (Auth::user()->can('Create Activity Program')) {
            $institutes = Institute::all()->pluck('name', 'id');
            return view('backend.activity_program.add', compact('institutes'));
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
    public function show(ActivityProgram $activityProgram)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ActivityProgram $activityProgram)
    {
        if (Auth::user()->can('Edit Activity Program')) {
            $institutes = Institute::all()->pluck('name', 'id');
            return view('backend.activity_program.edit', compact('activityProgram', 'institutes'));
        } else {
            return response()->json(['status' => 'error', 'message' => __('Akses Ditolak')]);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ActivityProgram $activityProgram)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ActivityProgram $activityProgram)
    {
        //
    }
}
