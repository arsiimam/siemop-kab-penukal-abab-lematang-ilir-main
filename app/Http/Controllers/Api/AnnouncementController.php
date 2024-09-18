<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use Illuminate\Http\Request;
use Validator;

class AnnouncementController extends Controller
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
                'title' => 'required',
                'start_date' => 'required',
                'end_date' => 'required',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => 'error',
                    'message' => $validator->errors()->all()
                ], 200);
            }

            $dataInput = $request->all();

            Announcement::create($dataInput);

            return response()->json([
                'status' => 'success',
                'message' => __('Data Berhasil Ditambahkan'),
                'count' => get_announcement()
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
    public function update(Request $request, Announcement $announcement)
    {
        try {
            $validator = Validator::make($request->all(), [
                'title' => 'required',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => 'error',
                    'message' => $validator->errors()->all()
                ], 200);
            }

            $dataInput = $request->all();

            $announcement->update($dataInput);

            return response()->json([
                'status' => 'success',
                'message' => __('Data Berhasil Diperbarui'),
                'count' => get_announcement()
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
    public function destroy(Announcement $announcement)
    {
        try {
            $announcement->delete();

            return response()->json([
                'status' => 'success',
                'message' => __('Data Berhasil Dihapus'),
                'count' => get_announcement()
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error',
                'message' => $th->getMessage()
            ], 500);
        }
    }

    /**
     * remove multiple
     */
    public function bulk_delete(Request $request)
    {
        try {
            Announcement::whereIn('id', $request->id)->delete();

            return response()->json([
                'status' => 'success',
                'message' => __('Berhasil Menghapus ' . count($request->id) . ' Data'),
                'count' => get_announcement()
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error',
                'message' => $th->getMessage()
            ], 500);
        }
    }
}
