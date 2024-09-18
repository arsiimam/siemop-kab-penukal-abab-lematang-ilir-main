<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ActivityReport;
use App\Models\User;
use Auth;
use Illuminate\Http\Request;
use Validator;

class ChildActivityReportController extends Controller
{

    protected $list = [];
    protected $realization = 0;
    protected $no_parent = 'A';
    protected $no_child_1 = '';
    protected $no_child_2 = '';
    protected $active_button = true;

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    public function list_json(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'report_id' => 'required',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => 'error',
                    'message' => $validator->errors()->all()
                ], 200);
            }

            $report_id = $request->report_id;

            $program = ActivityReport::find($request->report_id);

            $max_input_date = ENV('MAX_INPUT_DATE');
            $initial = date('Y-m-d', strtotime($program->year . '-' . $program->month . '-' . $max_input_date));
            $last = date('Y-m-d', strtotime('+1 month', strtotime($initial)));
            $today = date('Y-m-d');

            if (($today > $last or $today < $initial) and (!Auth::user()->can('Manage As Super Admin'))) {
                $this->active_button = false;
            }

            $proyek = new ActivityReport();
            $total_program = $proyek->child($report_id);
            $total_details = $proyek->details($report_id);

            $constraint = function ($query) use ($report_id) {
                $query->whereNull('parent_id')->where('id', $report_id);
            };

            $rows = ActivityReport::treeOf($constraint)
                ->whereNull('type')
                ->get()->toTree();

            foreach ($rows as $val) {
                $this->looping($val, $program->status);
            }

            return response()->json([
                'status' => 200,
                'message' => 'List Data',
                'data' => $this->list,
                'realization' => 'Rp. ' . number_format($rows[0]->realization),
                'total_program' => $total_program,
                'total_details' => $total_details,
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error',
                'message' => $th->getMessage()
            ], 500);
        }
    }

    public function looping($val, $status)
    {

        $title = '';
        $action = '';
        $realization = '';
        $pagu_indikatif = '';


        // if ($val->parent_id != null) {

        if ($status != 'done') {
            if ($val->depth == 0) {

                $no = '<b class="main_program">' . $this->no_parent++ . '.</b>';
                $this->no_child_1 = 'a';

                $title = '<b class="main_program">' . $val->program->title . '</b>';

                if ($val->realization != null) $realization = '<b>' . number_format($val->realization) . '</b>';
                if ($val->pagu_indikatif != null && $val->pagu_indikatif != 0) $pagu_indikatif = '<b>' . number_format($val->pagu_indikatif) . '</b>';
            } else if ($val->depth == 1) {
                $no = '<b class="sub_program">' . $this->no_child_1++ . '</b>';
                $this->no_child_2 = 1;

                if ($this->active_button)
                    $title = '<a href="#" class="text-underline modal-btn-xl sub_program" data-title="' . __('Edit Kegiatan') . '" data-url="' . route('description-of-activities.edit', $val->id) . '" data-toggle="modal" data-backdrop="static"><b>' . $val->title . '</b></button>';
                else $title = '<b>' . $val->title . '</b>';

                if ($val->realization != null) $realization = '<b>' . number_format($val->realization) . '</b>';
                if ($val->pagu_indikatif != null && $val->pagu_indikatif != 0) $pagu_indikatif = '<b>' . number_format($val->pagu_indikatif) . '</b>';

                /** action */
                if (Auth::user()->can('Create Activity Report') and $this->active_button) {
                    $action .= '<a href="#" class="modal-btn-xl tooltip-custom" data-title="' . __('Tambah Sub Kegiatan') . '" data-url="' . route('description-of-activities.create.details', $val->id) . '" data-toggle="modal" data-backdrop="static">
                            <i class="iconsminds-add icon-text-size"></i>
                            <span class="tooltiptext-top">Tambah Sub Kegiatan</span>
                            </a>';
                }
            } else {
                $no = '<small>' . $this->no_child_2++ . '</small>';

                $pixel = 10 * $val->depth;
                $nbsp = '<i style="margin-left: ' . $pixel . 'px;"></i> ';

                if ($this->active_button)
                    $title = '<a href="#" class="text-underline modal-btn-xl" data-title="' . __('Edit Sub Kegiatan') . '" data-url="' . route('description-of-activities.edit.details', $val->id) . '" data-toggle="modal" data-backdrop="static">' . $nbsp . $val->title . '</button>';
                else $title = $nbsp . $val->title;

                if ($val->realization != null) $realization = '<i>' . number_format($val->realization) . '</i>';
                if ($val->pagu_indikatif != null) $pagu_indikatif = '<i>' . number_format($val->pagu_indikatif) . '</i>';
            }
        } else {
            if ($val->depth == 0) {

                $no = '<b class="main_program">' . $this->no_parent++ . '.</b>';
                $this->no_child_1 = 'a';

                $title = '<b class="main_program">' . $val->program->title . '</b>';

                if ($val->realization != null) $realization = '<b>' . number_format($val->realization) . '</b>';
                if ($val->pagu_indikatif != null && $val->pagu_indikatif != 0) $pagu_indikatif = '<b>' . number_format($val->pagu_indikatif) . '</b>';
            } else if ($val->depth == 1) {
                $no = '<b class="sub_program">' . $this->no_child_1++ . '</b>';
                $this->no_child_2 = 1;

                $title = '<b class="sub_program">' . $val->title . '</b>';

                if ($val->realization != null) $realization = '<b>' . number_format($val->realization) . '</b>';
                if ($val->pagu_indikatif != null && $val->pagu_indikatif != 0) $pagu_indikatif = '<b>' . number_format($val->pagu_indikatif) . '</b>';
            } else {
                $no = '<small>' . $this->no_child_2++ . '</small>';

                $pixel = 10 * $val->depth;
                $nbsp = '<i style="margin-left: ' . $pixel . 'px;"></i> ';
                $title = $nbsp . $val->title;

                if ($val->realization != null) $realization = '<i>' . number_format($val->realization) . '</i>';
                if ($val->pagu_indikatif != null) $pagu_indikatif = '<i>' . number_format($val->pagu_indikatif) . '</i>';
            }
        }

        $data1 = [];
        $data2 = [];


        if ($status != 'done') {

            if (Auth::user()->can('Delete Activity Report')) {
                if ($val->depth != 0) {
                    $data1[] = '<div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input delete_check" data-delete id="customCheck' . $val->id . '" value="' . $val->id . '">
                                <label class="custom-control-label" for="customCheck' . $val->id . '"></label>
                        </div>';
                } else {
                    $data1[] = '';
                }
            }
            $data1[] = $action;
        }

        array_push($data2, [
            $no,
            $title,
            $pagu_indikatif,
            $val->target_kinerja,
            $val->target_fisik != null ? number_format($val->target_fisik) : '',
            $val->target_keuangan != null ? number_format($val->target_keuangan) : '',
            $val->fisik != null ? number_format($val->fisik) : '',
            $realization,
            ($val->percentage != null && $val->percentage != 0 ? round($val->percentage, 2) : ''),
            $val->ppk,
            $val->pptk,
            $val->location,
        ]);

        array_push($this->list, array_merge($data1, array_reduce($data2, 'array_merge', array())));
        // }

        if (count($val->children) == 0) {
            $this->realization += $val->realization;
        }

        foreach ($val->children as $child) {
            $this->looping($child, $status);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {

            $condition = [
                'activityprogram_id' => 'required',
                'title' => 'required',
                'pagu_indikatif' => 'required'
            ];

            if ($request->input_type == 'sub_activity') {
                $additional_condition = [
                    'fisik' => 'required',
                    'realization' => 'required',
                ];

                $condition = array_merge($condition, $additional_condition);
            }

            $validator = Validator::make($request->all(), $condition);

            if ($validator->fails()) {
                return response()->json([
                    'status' => 'error',
                    'message' => $validator->errors()->all()
                ], 200);
            }

            $dataInput = $request->all();

            unset($dataInput['input_type']);

            if ($request->pagu_indikatif != null) $dataInput['pagu_indikatif'] = str_replace('.', '', $request->pagu_indikatif);
            if ($request->realization != null) $dataInput['realization'] = str_replace('.', '', $request->realization);
            if ($request->contract_price != null) $dataInput['contract_price'] = str_replace('.', '', $request->contract_price);

            if (isset($dataInput['realization'])) $realization = (int)$dataInput['realization'] ?? 0;
            else $realization = 0;

            if ($request->input_type == 'sub_activity') $dataInput['percentage'] = str_replace('.', '', $request->realization) / str_replace('.', '', $request->pagu_indikatif) * 100;

            $pagu_indikatif = (int)$dataInput['pagu_indikatif'] ?? 0;

            if ($realization > $pagu_indikatif) {
                return response()->json([
                    'status' => 'error',
                    'message' => ['Realisasi Tidak Dapat Melebihi Pagu Indikatif']
                ], 200);
            }

            /** check max pagu indikatif in parent */
            $parent = ActivityReport::find($request->parent_id);
            $total_pagu_indikatif = ActivityReport::where('parent_id', $request->parent_id)->sum('pagu_indikatif');

            if (($pagu_indikatif + $total_pagu_indikatif) > $parent->pagu_indikatif) {
                $message = '';
                if ($request->input_type == 'sub_activity') $message = 'Total Pagu Indikatif Sub Kegiatan Melebihi Pagu Indikatif Kegiatan.';
                else $message = 'Total Pagu Indikatif Kegiatan Melebihi Pagu Indikatif Program.';
                return response()->json([
                    'status' => 'error',
                    'message' => [$message]
                ], 200);
            }

            $this->update_parent($request->parent_id, $realization, $pagu_indikatif);

            ActivityReport::create($dataInput);

            return response()->json([
                'status' => 'success',
                'message' => __('Data Berhasil Ditambahkan')
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error',
                'message' => $th->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        try {

            $condition = [
                'title' => 'required',
                'pagu_indikatif' => 'required'
            ];

            if ($request->input_type == 'sub_activity') {
                $additional_condition = [
                    'fisik' => 'required',
                    'realization' => 'required',
                ];

                $condition = array_merge($condition, $additional_condition);
            }

            $validator = Validator::make($request->all(), $condition);

            if ($validator->fails()) {
                return response()->json([
                    'status' => 'error',
                    'message' => $validator->errors()->all()
                ], 200);
            }

            $activityReport = ActivityReport::find($id);

            $dataInput = $request->all();

            unset($dataInput['input_type']);

            if ($request->pagu_indikatif != null) $dataInput['pagu_indikatif'] = str_replace('.', '', $request->pagu_indikatif);
            if ($request->realization != null) $dataInput['realization'] = str_replace('.', '', $request->realization);
            if ($request->contract_price != null) $dataInput['contract_price'] = str_replace('.', '', $request->contract_price);

            $old_realization = (int)$activityReport->realization ?? 0;
            if (isset($dataInput['realization'])) $new_realization = (int)$dataInput['realization'] ?? 0;
            else $new_realization = 0;

            if ($request->pagu_indikatif != null && $request->realization != null) {
                $dataInput['percentage'] = str_replace('.', '', $request->realization) / str_replace('.', '', $request->pagu_indikatif) * 100;
            } else {
                $dataInput['percentage'] = $activityReport->realization / str_replace('.', '', $request->pagu_indikatif) * 100;
            }

            $old_pagu_indikatif = (int)$activityReport->pagu_indikatif ?? 0;
            $new_pagu_indikatif = (int)$dataInput['pagu_indikatif'] ?? 0;

            if ($new_realization > $new_pagu_indikatif) {
                return response()->json([
                    'status' => 'error',
                    'message' => ['Realisasi Tidak Dapat Melebihi Pagu Indikatif']
                ], 200);
            }

            /** check max pagu indikatif in parent */
            $parent = ActivityReport::find($activityReport->parent_id);
            $total_pagu_indikatif = ActivityReport::where('parent_id', $activityReport->parent_id)->sum('pagu_indikatif');

            if ((($new_pagu_indikatif - $old_pagu_indikatif) + $total_pagu_indikatif) > $parent->pagu_indikatif) {
                $message = '';
                if ($request->input_type == 'sub_activity') $message = 'Total Pagu Indikatif Sub Kegiatan Melebihi Pagu Indikatif Kegiatan.';
                else $message = 'Total Pagu Indikatif Kegiatan Melebihi Pagu Indikatif Program.';
                return response()->json([
                    'status' => 'error',
                    'message' => [$message]
                ], 200);
            }

            if ($request->input_type == 'sub_activity') {
                $this->update_parent(
                    $activityReport->parent_id,
                    ($new_realization - $old_realization),
                    ($new_pagu_indikatif - $old_pagu_indikatif)
                );
            }

            $activityReport->update($dataInput);

            return response()->json([
                'status' => 'success',
                'message' => __('Data Berhasil Diperbarui')
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error',
                'message' => $th->getMessage()
            ], 500);
        }
    }

    /**
     * 24
     * 4
     * 1
     */
    public function update_parent($parent_id, $realization, $pagu_indikatif) #__, 5.000k, 5.000k
    {
        $report = ActivityReport::withTrashed()->find($parent_id);

        $value_realization = $report->realization + $realization;
        if ($value_realization <= 0) $value_realization = 0;

        $report->realization = $value_realization;
        if ($report->parent != null) {
            // $value_pagu_indikatif = $report->pagu_indikatif + $pagu_indikatif;
            $value_pagu_indikatif = $report->pagu_indikatif;
            if ($value_pagu_indikatif <= 0) $value_pagu_indikatif = 0;
            // $report->pagu_indikatif = $value_pagu_indikatif; #update pagu indikatif

            $report->percentage = $value_realization != 0 ? $value_realization / $value_pagu_indikatif * 100 : 0;
        } else {
            $report->percentage = $value_realization != 0 ? $value_realization / $report->pagu_indikatif * 100 : 0;
        }
        $report->save();

        $rows = ActivityReport::withTrashed()->where('parent_id', $report->parent_id)->first();

        if ($rows->parent_id != null) {
            $this->update_parent($rows->parent_id, $realization, $pagu_indikatif);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $activityReport = ActivityReport::find($id);

            $this->update_parent(
                $activityReport->parent_id,
                (-$activityReport->realization),
                (-$activityReport->pagu_indikatif)
            );
            $activityReport->pagu_indikatif = null;
            $activityReport->realization = null;
            $activityReport->save();

            $activityReport->delete();

            return response()->json([
                'status' => 'success',
                'message' => __('Data Berhasil Dihapus')
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error',
                'message' => $th->getMessage()
            ], 500);
        }
    }

    /** 
     * bulk remove
     */
    public function bulk_delete(Request $request)
    {
        try {

            foreach ($request->id as $val) {
                $activityReport = ActivityReport::withTrashed()->find($val);

                $this->update_parent(
                    $activityReport->parent_id,
                    (-$activityReport->realization),
                    (-$activityReport->pagu_indikatif)
                );

                $activityReport->pagu_indikatif = null;
                $activityReport->realization = null;
                $activityReport->deleted_at = now();
                $activityReport->save();
            }

            return response()->json([
                'status' => 'success',
                'message' => __('Berhasil Menghapus ' . count($request->id) . ' Data')
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error',
                'message' => $th->getMessage()
            ], 500);
        }
    }
}
