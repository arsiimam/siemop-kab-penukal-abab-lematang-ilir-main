<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ActivityReport;
use App\Models\Institute;
use Auth;
use Illuminate\Http\Request;
use Validator;
use Pdf;

class ReportPembangunanController extends Controller
{
    protected $list = [];

    /**
     * index json
     */
    public function index_json(Request $request)
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

    /** 
     * process data
     */
    public function looping($val, $no)
    {
        $pagu_indikatif = '';

        if ($val->pagu_indikatif != null) $pagu_indikatif = number_format($val->pagu_indikatif);

        array_push($this->list, [
            $no,
            $val->title,
            $pagu_indikatif,
            $val->sumber_dana,
            // $val->type,
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
            // $val->contract_price != null ? number_format($val->contract_price) : '',
            $val->executor,
            $val->location,
        ]);
    }

    /**
     * generate pdf
     */
    public function generate_pdf(Request $request)
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
                ], 500);
            }

            $rows = ActivityReport::where('activityprogram_id', $request->program_id)
                ->where('parent_id', $request->subactivity_id)
                ->where('type', $request->type)
                ->get();

            foreach ($rows as $x => $val) {
                $this->looping_pdf($val, $x + 1);
            }

            $data = $this->list;
            $month = month_list();
            $month = $month[$request->bulan];
            $tahun = $request->tahun;

            $header = [
                'type' => $request->val_type,
                'institute' => $request->val_institute,
                'program' => $request->val_program,
                'activity' => $request->val_activity,
                'sub_activity' => $request->val_sub_activity,
            ];

            // set signature from institute
            $institute = Institute::find($request->institute_id);
            $sign_institute = $institute->name;
            if (file_exists($institute->paraf_image)) {
                $sign_image = $institute->paraf_image;
            } else {
                $sign_image = '';
            }
            $sign_name = $institute->head_of_institute;
            $sign_position = $institute->position;
            $sign_nip = $institute->nip;

            $signature = [
                'institute' => $sign_institute,
                'image' => $sign_image,
                'name' => $sign_name,
                'position' => $sign_position,
                'nip' => $sign_nip
            ];

            $pdf = Pdf::loadView('backend.report.pdf_report_pembangunan', compact('data', 'tahun', 'month', 'header', 'signature'))->setPaper('A4', 'landscape');
            return $pdf->download('report.pdf');
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error',
                'message' => $th->getMessage()
            ], 500);
        }
    }

    /**
     * looping for download pdf
     */
    public function looping_pdf($val, $no)
    {
        $pagu_indikatif = '';

        if ($val->pagu_indikatif != null) $pagu_indikatif = number_format($val->pagu_indikatif);

        array_push($this->list, [
            $no,
            $val->title,
            $pagu_indikatif,
            $val->sumber_dana,
            // $val->type,
            // $val->progress_pekerjaan,
            $val->contract_number,
            $val->contract_date,
            $val->contract_price != null ? number_format($val->contract_price) : '',
            $val->contract_duration,
            $val->target_progres,
            $val->progress_pekerjaan,
            $val->realisasi_progres,
            $val->documentation,
            // '',
            $val->ppk,
            $val->pptk,
            // $val->contract_price != null ? number_format($val->contract_price) : '',
            $val->executor,
            $val->location,
        ]);
    }
}
