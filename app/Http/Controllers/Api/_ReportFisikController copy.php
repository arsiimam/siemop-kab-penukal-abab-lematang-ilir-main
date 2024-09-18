<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ActivityReport;
use Auth;
use Illuminate\Http\Request;
use Validator;
use Pdf;

class ReportFisikController extends Controller
{
    protected $list = [];
    protected $no_dinas = 1;
    protected $no_parent = 'A';
    protected $no_child_1 = '';
    protected $no_child_2 = '';
    protected $dinas = [];

    /**
     * index json
     */
    public function index_json(Request $request)
    {
        try {
            # DINAS VALUE
            if ($request->dinas_id != '') {
                $dinas_id = $request->dinas_id;
            } else {
                if (Auth::user()->can('Manage Any Report')) {
                    $dinas_id = '';
                } else {
                    $dinas_id = Auth::user()->institute_id;
                }
            }

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

            $constraint = function ($query) use ($tahun, $bulan, $dinas_id) {
                $query->whereNull('parent_id')
                    ->where('year', $tahun)
                    ->where('month', $bulan)
                    ->when($dinas_id != '', function ($q) use ($dinas_id) {
                        $q->where('institute_id', $dinas_id);
                    });
            };

            $rows = ActivityReport::treeOf($constraint)
                ->orderBy('institute_id', 'asc')
                ->whereNull('type')
                ->get()->toTree();

            // return $rows;

            foreach ($rows as $val) {
                $this->looping($val);
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
    public function looping($val)
    {
        $title = '';
        $realization = '';
        $pagu_indikatif = '';
        $no = '';
        $dinas_name = $val->institute->name;

        if (!in_array($dinas_name, $this->dinas) && $val->institute_id != null) {
            # RECORD NOT FOUND

            array_push($this->dinas, $dinas_name);

            array_push($this->list, [
                '<b class="font-italic header_dinas">' . $this->no_dinas++ . '</b>',
                '<div class="text-center"><b>' . $dinas_name . '</b></div>',
                '',
                '',
                '',
                '',
                '',
                '',
            ]);

            $this->no_parent = 'A';
        }

        if ($val->depth == 0) {

            $no = '<b class="main_program">' . $this->no_parent++ . '.</b>';
            $this->no_child_1 = 'a';

            $title = '<b>' . $val->program->title . '</b>';

            if ($val->realization != null) $realization = '<b>' . number_format($val->realization) . '</b>';
            if ($val->pagu_indikatif != null && $val->pagu_indikatif != 0) $pagu_indikatif = '<b>' . number_format($val->pagu_indikatif) . '</b>';
        } elseif ($val->depth == 1) {

            $no = '<b class="sub_program">' . $this->no_child_1++ . '</b>';
            $this->no_child_2 = 1;

            $pixel = 15 * $val->depth;
            $nbsp = '<i style="margin-left: ' . $pixel . 'px;"></i> ';

            $title = '<div style="margin-left: ' . $pixel . 'px; font-weight: bold;">' . $val->title . '</div>';

            if ($val->realization != null) $realization = '<b>' . number_format($val->realization) . '</b>';
            if ($val->pagu_indikatif != null && $val->pagu_indikatif != 0) $pagu_indikatif = '<b>' . number_format($val->pagu_indikatif) . '</b>';
        } else {

            $no = '<small>' . $this->no_child_2++ . '</small>';

            $pixel = 15 * $val->depth;
            $nbsp = '<i style="margin-left: ' . $pixel . 'px;"></i> ';
            $title = '<div style="margin-left: ' . $pixel . 'px;">' . $val->title . '</div>';

            if ($val->realization != null) $realization = '<i>' . number_format($val->realization) . '</i>';
            if ($val->pagu_indikatif != null) $pagu_indikatif = '<i>' . number_format($val->pagu_indikatif) . '</i>';
        }

        array_push($this->list, [
            $no,
            $title,
            $val->location,
            $val->ppk,
            $val->pptk,
            $val->executor,
            $pagu_indikatif,
            $realization,
        ]);

        foreach ($val->children as $child) {
            $this->looping($child);
        }
    }

    /**
     * generate pdf
     */
    public function generate_pdf(Request $request)
    {
        try {
            # DINAS VALUE
            if ($request->dinas_id != '') {
                $dinas_id = $request->dinas_id;
            } else {
                if (Auth::user()->can('Manage Any Report')) {
                    $dinas_id = '';
                } else {
                    $dinas_id = Auth::user()->institute_id;
                }
            }

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

            $constraint = function ($query) use ($tahun, $bulan, $dinas_id) {
                $query->whereNull('parent_id')
                    ->where('year', $tahun)
                    ->where('month', $bulan)
                    ->when($dinas_id != '', function ($q) use ($dinas_id) {
                        $q->where('institute_id', $dinas_id);
                    });
            };

            $rows = ActivityReport::treeOf($constraint)
                ->orderBy('institute_id', 'asc')
                ->get()->toTree();

            foreach ($rows as $val) {
                $this->looping_pdf($val);
            }

            $data = $this->list;
            $month = month_list();
            $month = $month[$bulan];

            $pdf = Pdf::loadView('backend.report.pdf_report_fisik', compact('data', 'tahun', 'month'))->setPaper('A4', 'landscape');
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
    public function looping_pdf($val)
    {
        $title = '';
        $realization = '';
        $pagu_indikatif = '';
        $no = '';
        $dinas_name = $val->institute->name;
        $type = '';

        if (!in_array($dinas_name, $this->dinas) && $val->institute_id != null) {
            # RECORD NOT FOUND

            array_push($this->dinas, $dinas_name);

            array_push($this->list, [
                '<b class="font-italic">' . $this->no_dinas++ . '</b>',
                '<div class="text-center"><b>' . $dinas_name . '</b></div>',
                '',
                '',
                '',
                '',
                '',
                '',
                '',
            ]);

            $this->no_parent = 'A';
        }

        if ($val->depth == 0) {

            $no = '<b>' . $this->no_parent++ . '.</b>';
            $this->no_child_1 = 'a';

            $title = '<b>' . $val->program->title . '</b>';

            if ($val->realization != null) $realization = '<b>' . number_format($val->realization) . '</b>';
            if ($val->pagu_indikatif != null && $val->pagu_indikatif != 0) $pagu_indikatif = '<b>' . number_format($val->pagu_indikatif) . '</b>';

            $type = 'parent';
        } elseif ($val->depth == 1) {

            $no = '<b>' . $this->no_child_1++ . '</b>';
            $this->no_child_2 = 1;

            $pixel = 10 * $val->depth;
            $title = '<div style="margin-left: ' . $pixel . 'px; font-weight: bold;">' . $val->title . '</div>';

            if ($val->realization != null) $realization = '<b>' . number_format($val->realization) . '</b>';
            if ($val->pagu_indikatif != null && $val->pagu_indikatif != 0) $pagu_indikatif = '<b>' . number_format($val->pagu_indikatif) . '</b>';

            $type = 'child';
        } else {

            $no = '<small>' . $this->no_child_2++ . '</small>';

            $pixel = 10 * $val->depth;
            $title = '<div style="margin-left: ' . $pixel . 'px;">' . $val->title . '</div>';

            if ($val->realization != null) $realization = '<i>' . number_format($val->realization) . '</i>';
            if ($val->pagu_indikatif != null) $pagu_indikatif = '<i>' . number_format($val->pagu_indikatif) . '</i>';
        }

        array_push($this->list, [
            $no,
            $title,
            $val->location,
            $val->ppk,
            $val->pptk,
            $val->executor,
            $pagu_indikatif,
            $realization,
            $type
        ]);

        foreach ($val->children as $child) {
            $this->looping_pdf($child);
        }
    }
}
