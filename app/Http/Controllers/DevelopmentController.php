<?php

namespace App\Http\Controllers;

use App\Models\ActivityProgram;
use App\Models\ActivityReport;
use App\Models\Institute;
use Illuminate\Http\Request;
use Auth;
use Validator;

class DevelopmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (Auth::user()->can('Manage Development')) {
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

            return view('backend.development.index', compact('institutes', 'activity_programs'));
        } else {
            return back()->with('error', 'Akses Ditolak.');
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        if (Auth::user()->can('Create Development')) {
            $validator = Validator::make($request->all(), [
                'program_id' => 'required',
                'subactivity_id' => 'required',
                'type' => 'required' #fisik non fisik
            ]);

            $max_input_date = ENV('MAX_INPUT_DATE');
            $initial = date('Y-m-d', strtotime($request->year . '-' . $request->month . '-' . $max_input_date));
            $last = date('Y-m-d', strtotime('+1 month', strtotime($initial)));
            $today = date('Y-m-d');
            $month = convert_month($request->month);
            $message = 'Batas input / edit program bulan ' . $month . ' dimulai dari ' . convert_format_date($initial) . ' hingga ' . convert_format_date($last);

            if (($today > $last or $today < $initial) and (!Auth::user()->can('Manage As Super Admin'))) {
                return response()->json([
                    'status' => 'error',
                    'message' => [$message],
                ], 200);
            }

            if ($validator->fails()) {
                return response()->json([
                    'status' => 'error',
                    'message' => $validator->errors()->all()
                ], 200);
            }

            return view('backend.development.add', compact('request'));
        } else {
            return response()->json(['status' => 'error', 'message' => __('Akses Ditolak')]);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ActivityReport $development)
    {
        if (Auth::user()->can('Edit Development')) {
            return view('backend.development.edit', compact('development'));
        } else {
            return response()->json(['status' => 'error', 'message' => __('Akses Ditolak')]);
        }
    }
}
