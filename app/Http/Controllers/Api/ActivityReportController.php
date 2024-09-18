<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ActivityReport;
use App\Models\User;
use Auth;
use Illuminate\Http\Request;
use Validator;

class ActivityReportController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'activityprogram_id' => 'required',
                'year' => 'required',
                'month' => 'required',
                'pagu_indikatif' => 'required',
                'target_kinerja' => 'required',
            ]);

            $lastRecord = [];

            $max_input_date = ENV('MAX_INPUT_DATE');
            if ($request->year != '' and $request->month != '' and $max_input_date != '') {

                $initial = date('Y-m-d', strtotime($request->year . '-' . $request->month . '-' . $max_input_date));
                $last = date('Y-m-d', strtotime('+1 month', strtotime($initial)));
                $today = date('Y-m-d');
                $month = convert_month($request->month);
                $message = 'Batas input / edit program bulan ' . $month . ' dimulai dari ' . convert_format_date($initial) . ' hingga ' . convert_format_date($last);

                if (($today > $last or $today < $initial) and (!Auth::user()->can('Manage As Super Admin'))) {
                    return response()->json([
                        'status' => 'error',
                        'message' => [$message]
                    ], 200);
                }

                $prevMonth = date('Y-m', strtotime('-1 month', strtotime($initial)));
                $prevMonth = explode('-', $prevMonth);
                $convertYear = $prevMonth[0];
                $convertMonth = $prevMonth[1];

                $record = ActivityReport::where('activityprogram_id', $request->activityprogram_id)->where('year', $convertYear)->where('month', $convertMonth)->first();

                if (isset($record->id)) {
                    $constraint = function ($query) use ($record) {
                        $query->whereNull('parent_id')->where('id', $record->id);
                    };

                    $lastRecord = ActivityReport::treeOf($constraint)
                        ->get()->toTree()->toArray();

                    $lastRecord = $lastRecord[0];
                }
            }

            // return response()->json([
            //     'status' => 'error',
            //     'message' => $lastRecord
            // ], 500);

            if ($validator->fails()) {
                return response()->json([
                    'status' => 'error',
                    'message' => $validator->errors()->all()
                ], 200);
            }

            $dataInput = $request->all();

            if (ActivityReport::where('activityprogram_id', $request->activityprogram_id)->where('month', $request->month)->where('year', $request->year)->exists()) {
                // post with the same slug already exists
                return response()->json([
                    'status' => 'error',
                    'message' => ['Program sudah tersedia di periode yang dipilih']
                ], 200);
            } else {
                if (isset($request->user_id)) {
                    $user = User::find($request->user_id);
                } else {
                    $user = Auth::user();
                }

                $dataInput['institute_id'] = $user->institute_id;
                $dataInput['user_id'] = $user->id;
                $dataInput['pagu_indikatif'] = str_replace('.', '', $request->pagu_indikatif);


                if (count($lastRecord) > 0) {

                    if ($dataInput['pagu_indikatif'] < $lastRecord['pagu_indikatif']) {
                        return response()->json([
                            'status' => 'error',
                            'message' => ['Pagu indikatif tidak sesuai atau kurang dari laporan bulan sebelumnya !']
                        ], 200);
                    }

                    $dataInput['realization'] = $lastRecord['realization'];
                    $dataInput['percentage'] = $lastRecord['realization'] / $dataInput['pagu_indikatif'] * 100;
                }

                $parent = ActivityReport::create($dataInput);
                $id = $parent->id;

                /** save detail report */
                if (count($lastRecord) > 0) {
                    foreach ($lastRecord['children'] as $child) {
                        /** process saved child */
                        $input = $this->unset($child);
                        $input['parent_id'] = $id;

                        $save = ActivityReport::create($input);
                        $parent_id = $save->id;

                        /** loop child */
                        $this->saveChild($child['children'], $parent_id);
                    }
                }

                return response()->json([
                    'status' => 'success',
                    'message' => __('Data Berhasil Ditambahkan')
                ], 200);
            }
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error',
                'message' => $th->getMessage()
            ], 500);
        }
    }

    /** 
     * unset
     */
    public function unset($input)
    {
        unset(
            $input['id'],
            $input['parent_id'],
            $input['depth'],
            $input['path'],
            $input['children'],
            $input['created_at'],
            $input['updated_at'],
            $input['deleted_at']
        );

        return $input;
    }

    /** 
     *  @param Request $request
     */
    public function saveChild($child, $parent_id)
    {
        foreach ($child as $row) {
            $input = $this->unset($row);
            $input['parent_id'] = $parent_id;

            $save = ActivityReport::create($input);
            $sub_parent_id = $save->id;

            $this->saveChild($row['children'], $sub_parent_id);
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
    public function update(Request $request, ActivityReport $activityReport)
    {
        try {
            $validator = Validator::make($request->all(), [
                'year' => 'required',
                'month' => 'required',
                'pagu_indikatif' => 'required',
                'target_kinerja' => 'required',
            ]);

            $max_input_date = ENV('MAX_INPUT_DATE');
            if ($request->year != '' and $request->month != '' and $max_input_date != '') {
                $initial = date('Y-m-d', strtotime($request->year . '-' . $request->month . '-' . $max_input_date));
                $last = date('Y-m-d', strtotime('+1 month', strtotime($initial)));
                $today = date('Y-m-d');
                $month = convert_month($request->month);
                $message = 'Batas input / edit program bulan ' . $month . ' dimulai dari ' . convert_format_date($initial) . ' hingga ' . convert_format_date($last);

                if (($today > $last or $today < $initial) and (!Auth::user()->can('Manage As Super Admin'))) {
                    return response()->json([
                        'status' => 'error',
                        'message' => [$message]
                    ], 200);
                }
            }

            if ($validator->fails()) {
                return response()->json([
                    'status' => 'error',
                    'message' => $validator->errors()->all()
                ], 200);
            }

            $dataInput = $request->all();

            unset($dataInput['activityprogram_id']);

            $dataInput['pagu_indikatif'] = str_replace('.', '', $request->pagu_indikatif);

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
     * update status
     */
    public function update_status(Request $request, ActivityReport $activityReport)
    {
        try {

            if ($activityReport->status == 'done') {
                $dataInput['status'] = null;
            } else {
                $dataInput['status'] = 'done';
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
     * Remove the specified resource from storage.
     */
    public function destroy(ActivityReport $activityReport)
    {
        try {
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
