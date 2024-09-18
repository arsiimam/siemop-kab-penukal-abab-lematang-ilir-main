<?php

namespace App\Http\Controllers;

use App\DataTables\AnnouncementDataTable;
use App\Models\Announcement;
use App\Models\Institute;
use Auth;
use Illuminate\Http\Request;

use function Termwind\render;

class AnnouncementController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, AnnouncementDataTable $dataTable)
    {
        if (Auth::user()->can('Manage Announcement')) {
            if (isset($request->action)) {
                Announcement::where(function ($q) {
                    $q->where('institute_id', Auth::user()->institute_id)
                        ->orWhere('institute_id', null);
                })->where('readable', 0)->update(['readable' => 1]);
            }
            return $dataTable->render('backend.announcement.index');
        } else {
            return back()->with('error', __('Akses Ditolak.'));
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        if (Auth::user()->can('Create Announcement')) {
            $institutes = Institute::all()->pluck('name', 'id');
            return view('backend.announcement.add', compact('institutes'));
        } else {
            return back()->with('error', __('Akses Ditolak.'));
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
    public function show(Announcement $announcement)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Announcement $announcement)
    {
        if (Auth::user()->can('Edit Announcement')) {
            $institutes = Institute::all()->pluck('name', 'id');
            return view('backend.announcement.edit', compact('institutes', 'announcement'));
        } else {
            return back()->with('error', __('Akses Ditolak.'));
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Announcement $announcement)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Announcement $announcement)
    {
        //
    }
}
