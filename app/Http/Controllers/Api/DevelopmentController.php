<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ActivityProgram;
use App\Models\ActivityReport;
use Auth;
use Illuminate\Http\Request;
use Validator;

class DevelopmentController extends Controller
{
    protected $list = [];
    protected $realization = 0;
    protected $no_parent = 'A';
    protected $no_child_1 = '';
    protected $no_child_2 = '';
    protected $active_button = 'true';

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /** list program by institute_id */
    public function programs_json(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'institute_id' => 'required',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => 'error',
                    'message' => $validator->errors()->all()
                ], 200);
            }

            $rows = ActivityProgram::selectRaw('id, title')
                ->where('institute_id', $request->institute_id)
                ->get()->toArray();

            $empty = ['id' => '', 'title' => ''];

            return response()->json([
                'status' => 200,
                'message' => 'List Data',
                'data' => (!empty($rows) ? array_merge([$empty], $rows) : []),
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error',
                'message' => $th->getMessage()
            ], 500);
        }
    }

    /** list activity */
    public function activity_json(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'program_id' => 'required',
                'tahun' => 'required',
                'bulan' => 'required',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => 'error',
                    'message' => $validator->errors()->all()
                ], 200);
            }

            $parent = ActivityReport::where('activityprogram_id', $request->program_id)
                ->where('month', $request->bulan)
                ->where('year', $request->tahun)
                ->first();

            if (!empty($parent)) {
                $rows = ActivityReport::selectRaw('id, title')
                    ->where('activityprogram_id', $request->program_id)
                    ->when($request->activity_id != null, function ($q) use ($request) {
                        $q->where('parent_id', $request->activity_id);
                    }, function ($q) use ($parent) {
                        $q->where('parent_id', $parent->id);
                    })
                    ->get()->toArray();
            } else {
                $rows = [];
            }

            $empty = ['id' => '', 'title' => ''];

            return response()->json([
                'status' => 200,
                'message' => 'List Data',
                'data' => (!empty($rows) ? array_merge([$empty], $rows) : []),
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error',
                'message' => $th->getMessage()
            ], 500);
        }
    }

    public function list_json(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'program_id' => 'required',
                'subactivity_id' => 'required',
                'type' => 'required' #fisik non fisik
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => 'error',
                    'message' => $validator->errors()->all(),
                    'data' => []
                ], 200);
            }

            $max_input_date = ENV('MAX_INPUT_DATE');
            $initial = date('Y-m-d', strtotime($request->year . '-' . $request->month . '-' . $max_input_date));
            $last = date('Y-m-d', strtotime('+1 month', strtotime($initial)));
            $today = date('Y-m-d');

            if (($today > $last or $today < $initial) and (!Auth::user()->can('Manage As Super Admin'))) {
                $this->active_button = false;
            }

            $rows = ActivityReport::where('activityprogram_id', $request->program_id)
                ->where('parent_id', $request->subactivity_id)
                ->where('type', $request->type)
                ->get();

            foreach ($rows as $x => $val) {
                $this->looping($val, $x + 1);
            }

            return response()->json([
                'status' => 200,
                'message' => 'List Data',
                'data' => $this->list,
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error',
                'message' => $th->getMessage()
            ], 500);
        }
    }

    public function looping($val, $no)
    {

        $title = '';
        $pagu_indikatif = '';

        $title = '<a href="#" class="text-underline modal-btn-xl" data-title="' . __('Edit') . '" data-url="' . route('development.edit', $val->id) . '" data-toggle="modal" data-backdrop="static">' . $val->title . '</button>';

        if (!$this->active_button) $title = $val->title;

        if ($val->pagu_indikatif != null) $pagu_indikatif = '<i>' . number_format($val->pagu_indikatif) . '</i>';


        $data1 = [];
        $data2 = [];
        if (Auth::user()->can('Delete Development')) {
            if (!$this->active_button) {
                $data1[] = '';
            } else {
                $data1[] = '<div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input delete_check" data-delete id="customCheck' . $val->id . '" value="' . $val->id . '">
                            <label class="custom-control-label" for="customCheck' . $val->id . '"></label>
                    </div>';
            }
        }

        array_push($data2, [
            $no,
            $title,
            $pagu_indikatif,
            $val->sumber_dana,
            $val->type,
            $val->contract_number,
            $val->contract_date,
            $val->contract_price != null ? number_format($val->contract_price) : '',
            $val->contract_duration,
            $val->target_progres,
            // $val->progress_pekerjaan,
            $val->realisasi_progres,
            $val->documentation != null ? '<a href="' . $val->documentation . '" class="text-underline" target="_blank">Lihat File</a>' : '-',
            $val->ppk,
            $val->pptk,
            $val->executor,
            $val->location,
        ]);

        array_push($this->list, array_merge($data1, array_reduce($data2, 'array_merge', array())));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'activityprogram_id' => 'required',
                'parent_id' => 'required',
                'title' => 'required',
                'pagu_indikatif' => 'required',
                'sumber_dana' => 'required',
                'target_progres' => 'required',
                // 'progress_pekerjaan' => 'required',
                'realisasi_progres' => 'required',
                'ppk' => 'required',
                'pptk' => 'required',
                'location' => 'required',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => 'error',
                    'message' => $validator->errors()->all()
                ], 200);
            }

            $dataInput = $request->all();

            if ($request->pagu_indikatif != null) $dataInput['pagu_indikatif'] = str_replace('.', '', $request->pagu_indikatif);
            if ($request->contract_price != null) $dataInput['contract_price'] = str_replace('.', '', $request->contract_price);

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
            $validator = Validator::make($request->all(), [
                'title' => 'required',
                'pagu_indikatif' => 'required',
                'target_progres' => 'required',
                // 'progress_pekerjaan' => 'required',
                'realisasi_progres' => 'required',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => 'error',
                    'message' => $validator->errors()->all()
                ], 200);
            }

            $activityReport = ActivityReport::find($id);

            $dataInput = $request->all();

            if ($request->pagu_indikatif != null) $dataInput['pagu_indikatif'] = str_replace('.', '', $request->pagu_indikatif);
            if ($request->realization != null) $dataInput['realization'] = str_replace('.', '', $request->realization);
            if ($request->contract_price != null) $dataInput['contract_price'] = str_replace('.', '', $request->contract_price);

            $old_realization = (int)$activityReport->realization ?? 0;
            if (isset($dataInput['realization'])) $new_realization = (int)$dataInput['realization'] ?? 0;
            else $new_realization = 0;

            if ($request->pagu_indikatif != null && $request->realization != null) {
                $dataInput['percentage'] = str_replace('.', '', $request->realization) / str_replace('.', '', $request->pagu_indikatif) * 100;
            } else {
                $dataInput['percentage'] = 0 / str_replace('.', '', $request->pagu_indikatif) * 100;
            }

            $old_pagu_indikatif = (int)$activityReport->pagu_indikatif ?? 0;
            $new_pagu_indikatif = (int)$dataInput['pagu_indikatif'] ?? 0;

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
     * Remove the specified resource from storage.
     */
    public function destroy(ActivityReport $development)
    {
        try {
            $development->delete();

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
            ActivityReport::whereIn('id', $request->id)->delete();

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
