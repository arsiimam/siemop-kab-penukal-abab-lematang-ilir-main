<?php

namespace App\Http\Controllers;

use Auth;
use Illuminate\Http\Request;

class StatisticsController extends Controller
{
    /**
     * function index
     */
    public function index()
    {
        if (Auth::user()->can('Manage Statistics')) {
            return view('backend.statistics.index');
        } else {
            return back()->with('error', __('Akses Ditolak.'));
        }
    }
}
