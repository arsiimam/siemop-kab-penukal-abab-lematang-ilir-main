<?php

namespace App\Http\Controllers;

use App\DataTables\ActivityReportDataTable;
use App\Models\ActivityProgram;
use App\Models\ActivityReport;
use App\Models\Institute;
use Auth;
use Illuminate\Http\Request;

class ActivityReportController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(ActivityReportDataTable $dataTable)
    {
        if (Auth::user()->can('Manage Activity Report')) {
            $institutes = Institute::all()->pluck('name', 'id');
            return $dataTable->with([
                'month' => date('m'),
                'year' => date('Y')
            ])->render('backend.activity_report.index', compact('institutes'));
        } else {
            return back()->with('error', __('Akses Ditolak.'));
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        if (Auth::user()->can('Create Activity Report')) {
            if (Auth::user()->can('Manage Any Activity Report')) {
                $programs = ActivityProgram::all()->pluck('title', 'id');
            } else {
                $programs = ActivityProgram::where('institute_id', Auth::user()->institute_id)->get()->pluck('title', 'id');
            }
            return view('backend.activity_report.add', compact('programs'));
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
    public function show(ActivityReport $activityReport)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ActivityReport $activityReport)
    {
        if (Auth::user()->can('Edit Activity Report')) {
            if (Auth::user()->can('Manage Any Activity Report')) {
                $programs = ActivityProgram::all()->pluck('title', 'id');
            } else {
                $programs = ActivityProgram::where('institute_id', Auth::user()->institute_id)->get()->pluck('title', 'id');
            }
            return view('backend.activity_report.edit', compact('programs', 'activityReport'));
        } else {
            return response()->json(['status' => 'error', 'message' => __('Akses Ditolak')]);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ActivityReport $activityReport)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ActivityReport $activityReport)
    {
        //
    }
}
