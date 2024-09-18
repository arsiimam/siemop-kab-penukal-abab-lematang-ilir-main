<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ActivityProgram;
use Illuminate\Http\Request;
use Validator;

class ActivityProgramController extends Controller
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
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => 'error',
                    'message' => $validator->errors()->all()
                ], 200);
            }

            $dataInput = $request->all();

            ActivityProgram::create($dataInput);

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
    public function update(Request $request, ActivityProgram $activityProgram)
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

            $activityProgram->update($dataInput);

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
    public function destroy(ActivityProgram $activityProgram)
    {
        try {
            $activityProgram->delete();

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
     * Multiple delete
     */
    public function bulk_delete(Request $request)
    {
        try {
            ActivityProgram::whereIn('id', $request->id)->delete();

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
