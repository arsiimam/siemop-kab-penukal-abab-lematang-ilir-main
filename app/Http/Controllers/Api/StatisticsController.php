<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ActivityReport;
use App\Models\Institute;
use Illuminate\Http\Request;

class StatisticsController extends Controller
{
    /**
     * index json
     */
    public function index_json(Request $request)
    {
        try {
            # TAHUN VALUE
            if ($request->tahun != '') {
                $tahun = $request->tahun;
            } else {
                $tahun = date('Y');
            }

            # BULAN VALUE
            if ($request->bulan != '') {
                $bulan = $request->bulan;
            } else {
                $bulan = date('m');
            }

            $insitute = Institute::all();

            $rows = ActivityReport::selectRaw('institutes.name as dinas, institute_id, month, year, sum(realization) as total_realization, (sum(percentage) / count(activity_reports.id)) as sum_percentage, count(activity_reports.id) as count_programs')
                ->join('institutes', 'activity_reports.institute_id', '=', 'institutes.id')
                ->where(function ($q) use ($tahun, $bulan) {
                    $q->where('parent_id', null)
                        ->where('month', $bulan)
                        ->where('year', $tahun);
                })
                ->groupBy('institute_id')
                ->get();

            $data = [];
            $month_list = month_list();
            $month = $month_list[$bulan];

            foreach ($rows as $i => $row) {

                $data[$row->institute_id] = [
                    'programs' => $row->count_programs,
                    'percentage' => round($row->sum_percentage, 2),
                    'realization' => number_format($row->total_realization ?? 0)
                ];
            }

            $data_arr = [];
            foreach ($insitute as $i => $row) {
                if (isset($data[$row->id])) {
                    array_push($data_arr, [
                        'institute' => $row->name,
                        'month' => $month,
                        'year' => $tahun,
                        'programs' => $data[$row->id]['programs'],
                        'percentage' => $data[$row->id]['percentage'],
                        'realization' => $data[$row->id]['realization']
                    ]);
                } else {
                    array_push($data_arr, [
                        'institute' => $row->name,
                        'month' => $month,
                        'year' => $tahun,
                        'programs' => 0,
                        'percentage' => 0,
                        'realization' => 0
                    ]);
                }
            }

            $keys = array_column(
                $data_arr,
                'percentage'
            );

            if ($request->order == 'asc') {
                array_multisort($keys, SORT_ASC, $data_arr);
            } else {
                array_multisort($keys, SORT_DESC, $data_arr);
            }

            $data_final = [];
            foreach ($data_arr as $i => $row) {
                array_push($data_final, array_merge(['no' => $i + 1], $row));
            }

            return response()->json([
                'status' => 200,
                'message' => 'List Data',
                'data' => $data_final,
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error',
                'message' => $th->getMessage()
            ], 500);
        }
    }
}
