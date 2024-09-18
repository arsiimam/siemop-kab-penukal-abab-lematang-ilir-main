<?php

namespace App\Http\Controllers;

use App\DataTables\ActivityReportDataTable;
use App\DataTables\DetailsActivityDataTable;
use App\Models\ActivityProgram;
use App\Models\ActivityReport;
use App\Models\Institute;
use Auth;
use Illuminate\Http\Request;

class DescriptionOfActivitiesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($activityreport_id)
    {
        if (Auth::user()->can('Manage Activity Report')) {
            $proyek = ActivityReport::find($activityreport_id);

            if (empty($proyek)) {
                return back()->with('error', __('Data Tidak Ditemukan.'));
            }

            $max_input_date = ENV('MAX_INPUT_DATE');
            $initial = date('Y-m-d', strtotime($proyek->year . '-' . $proyek->month . '-' . $max_input_date));
            $last = date('Y-m-d', strtotime('+1 month', strtotime($initial)));
            $today = date('Y-m-d');
            $month = convert_month($proyek->month);
            $message = 'Batas input / edit program bulan ' . $month . ' dimulai dari ' . convert_format_date($initial) . ' hingga ' . convert_format_date($last);

            return view('backend.activity_report.description.index', compact('activityreport_id', 'proyek', 'message'));
        } else {
            return back()->with('error', __('Akses Ditolak.'));
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create($activityreport_id)
    {
        if (Auth::user()->can('Create Activity Report')) {
            $proyek = ActivityReport::find($activityreport_id);

            $max_input_date = ENV('MAX_INPUT_DATE');
            $initial = date('Y-m-d', strtotime($proyek->year . '-' . $proyek->month . '-' . $max_input_date));
            $last = date('Y-m-d', strtotime('+1 month', strtotime($initial)));
            $today = date('Y-m-d');
            $month = convert_month($proyek->month);
            $message = 'Batas input / edit program bulan ' . $month . ' dimulai dari ' . convert_format_date($initial) . ' hingga ' . convert_format_date($last);

            if (($today > $last or $today < $initial) and (!Auth::user()->can('Manage As Super Admin'))) {
                return response()->json([
                    'status' => 'error',
                    'message' => [$message]
                ], 200);
            }

            return view('backend.activity_report.description.add', compact('proyek'));
        } else {
            return response()->json(['status' => 'error', 'message' => __('Akses Ditolak')]);
        }
    }

    /**
     * Show function for create details
     */
    public function create_details($parent_id)
    {
        if (Auth::user()->can('Create Activity Report')) {
            $proyek = ActivityReport::find($parent_id);
            return view('backend.activity_report.details.add', compact('proyek'));
        } else {
            return response()->json(['status' => 'error', 'message' => __('Akses Ditolak')]);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        if (Auth::user()->can('Edit Activity Report')) {
            $activityReport = ActivityReport::find($id);

            $parent = ActivityReport::find($activityReport->parent_id);

            $max_input_date = ENV('MAX_INPUT_DATE');
            $initial = date('Y-m-d', strtotime($parent->year . '-' . $parent->month . '-' . $max_input_date));
            $last = date('Y-m-d', strtotime('+1 month', strtotime($initial)));
            $today = date('Y-m-d');
            $month = convert_month($parent->month);
            $message = 'Batas input / edit program bulan ' . $month . ' dimulai dari ' . convert_format_date($initial) . ' hingga ' . convert_format_date($last);

            if (($today > $last or $today < $initial) and (!Auth::user()->can('Manage As Super Admin'))) {
                return response()->json([
                    'status' => 'error',
                    'message' => [$message]
                ], 200);
            }
            return view('backend.activity_report.description.edit', compact('activityReport'));
        } else {
            return response()->json(['status' => 'error', 'message' => __('Akses Ditolak')]);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit_details($id)
    {
        if (Auth::user()->can('Edit Activity Report')) {
            $activityReport = ActivityReport::find($id);
            return view('backend.activity_report.details.edit', compact('activityReport'));
        } else {
            return response()->json(['status' => 'error', 'message' => __('Akses Ditolak')]);
        }
    }
}
