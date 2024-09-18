<?php

namespace App\Http\Controllers;

use App\Models\ActivityProgram;
use App\Models\Institute;
use Illuminate\Http\Request;
use Auth;

class ReportController extends Controller
{
    /**
     * index report
     * report non fisik
     */
    public function index()
    {
        if (Auth::user()->can('Manage Report')) {
            $institutes = Institute::all()->pluck('name', 'id');
            return view('backend.report.realisasi_fisik_report', compact('institutes'));
        } else {
            return back()->with('error', __('Akses Ditolak.'));
        }
    }

    /**
     * index report
     * report fisik
     */
    public function index2()
    {
        if (Auth::user()->can('Manage Report')) {

            $user = Auth::user();
            if (Auth::user()->can('Manage Any Activity Program')) {
                $institutes = Institute::all()->pluck('name', 'id');
                $activity_programs = [];
            } else {
                $institutes = [];
                $activity_programs = ActivityProgram::selectRaw('*')
                    ->where('institute_id', $user->institute_id)
                    ->get()->pluck('title', 'id');
            }

            return view('backend.report.pembangunan_report', compact('institutes', 'activity_programs'));
        } else {
            return back()->with('error', __('Akses Ditolak.'));
        }
    }
}
