<?php

namespace App\Http\Controllers;

use App\Models\ActivityProgram;
use App\Models\ActivityReport;
use App\Models\Announcement;
use App\Models\Call;
use App\Models\Customer;
use App\Models\Institute;
use App\Models\Transaction;
use App\Models\User;
use App\Models\UserLog;
use App\Models\Voucher;
use DateTime;
use Illuminate\Http\Request;
use Auth;

use function PHPSTORM_META\type;

class HomeController extends Controller
{
    /**
     * show manage / index page
     */
    public function index()
    {
        if (Auth::user()->can('Manage As Super Admin')) {
            $institutes = Institute::count();
            $users = User::count();
            $programs = ActivityProgram::count();
            $proyek = ActivityReport::where('month', date('m'))->where('year', date('Y'))->where('parent_id', null)->count();

            $realisasi_list = ActivityReport::selectRaw('sum(realization) as total, institutes.name')
                ->rightJoin('institutes', 'activity_reports.institute_id', '=', 'institutes.id')
                ->where('parent_id', null)
                ->where('month', date('m'))
                ->where('year', date('Y'))
                ->groupBy('institutes.id')
                ->orderBY('total', 'desc')
                ->limit(5)
                ->get();

            $realization = array();
            foreach ($realisasi_list as $val) {
                $realization['labels'][] = $val->name;
                $realization['data'][] = $val->total ?? 0;
            }

            if (isset($realisasi_list)) {
                $realization['labels'] = [];
                $realization['data'] = [];
            }

            /** parsing json */
            $realization = [
                'labels' => json_encode($realization['labels']),
                'data' => json_encode($realization['data'])
            ];

            $table_realization = ActivityReport::where('parent_id', null)->where('month', date('m'))->where('year', date('Y'))->get();

            $list_institutes = ActivityReport::selectRaw('institutes.name as dinas, sum(if(status = "done", 1, 0)) as done, count(activity_reports.id) as total')
                ->join('institutes', 'activity_reports.institute_id', '=', 'institutes.id')
                ->where('month', date('m'))->where('year', date('Y'))
                ->groupBy('institute_id')
                ->get();

            /** announcement */
            $announcement = Announcement::where('end_date', '>=', date('Y-m-d'))->get();

            /** user_logs */
            $logs = UserLog::selectRaw('user_logs.*, users.name as user')
                ->join('users', 'user_logs.user_id', '=', 'users.id')
                ->orderBy('user_logs.id', 'desc')->limit(50)->get();

            return view('backend.dashboard.index', compact(
                'institutes',
                'users',
                'programs',
                'proyek',
                'realization',
                'table_realization',
                'list_institutes',
                'announcement',
                'logs'
            ));
        } else {
            $institutes = Institute::count();
            $users = User::count();
            $programs = ActivityProgram::where('institute_id', Auth::user()->institute_id)->count();
            $proyek = ActivityReport::where('month', date('m'))->where('year', date('Y'))->where('parent_id', null)->where('institute_id', Auth::user()->institute_id)->count();

            $realisasi_list = ActivityReport::selectRaw('sum(realization) as total, institutes.name, month')
                ->rightJoin('institutes', 'activity_reports.institute_id', '=', 'institutes.id')
                ->where('parent_id', null)
                // ->where('month', date('m'))
                ->where('year', date('Y'))
                ->where('institute_id', Auth::user()->institute_id)
                ->groupBy('month')
                ->get()->pluck('total', 'month');

            $realization = array();
            $list_month = month_list();
            for ($i = 1; $i <= 12; $i++) {
                $month = date('m', strtotime(date('d-' . $i . '-Y')));

                if (isset($realisasi_list[$month])) {
                    $realization['data'][] = (int)$realisasi_list[$month];
                } else {
                    $realization['data'][] = 0;
                }
                $realization['labels'][] = $list_month[$month];
            }

            // return $realization;
            // foreach ($realisasi_list as $val) {
            //     $realization['labels'][] = $val->name;
            //     $realization['data'][] = $val->total ?? 0;
            // }

            if (isset($realisasi_list)) {
                $realization['labels'] = [];
                $realization['data'] = [];
            }

            /** parsing json */
            $realization = [
                'labels' => json_encode($realization['labels']),
                'data' => json_encode($realization['data'])
            ];

            $table_realization = ActivityReport::where('parent_id', null)
                ->where('institute_id', Auth::user()->institute_id)
                ->where('month', date('m'))
                ->where('year', date('Y'))
                ->get();

            /** announcement */
            $announcement = Announcement::where(function ($q) {
                $q->where('institute_id', null)
                    ->orWhere('institute_id', Auth::user()->institute_id);
            })
                // ->where('end_date', '>=', date('Y-m-d'))
                ->orderBy('end_date', 'desc')
                ->get();

            return view('backend.dashboard.index2', compact(
                'institutes',
                'users',
                'programs',
                'proyek',
                'realization',
                'table_realization',
                'announcement'
            ));
        }
    }
}
