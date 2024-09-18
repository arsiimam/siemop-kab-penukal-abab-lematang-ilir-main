<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Institute;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Storage;
use Validator;

class InstituteController extends Controller
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
                'name' => 'required',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => 'error',
                    'message' => $validator->errors()->all()
                ], 200);
            }

            $dataInput = $request->all();
            unset($dataInput['image'], $dataInput['name_file']);

            if (isset($request->image)) {
                $signature = $this->postImage($request->image);
                $dataInput['paraf_image'] = $signature;
            }

            $dataInput['signature_status'] = 1;

            Institute::create($dataInput);

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
    public function update(Request $request, Institute $institute)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => 'error',
                    'message' => $validator->errors()->all()
                ], 200);
            }

            $dataInput = $request->all();
            unset($dataInput['image'], $dataInput['name_file']);

            if (isset($request->image)) {
                if (file_exists($institute->paraf_image)) {
                    Storage::delete($institute->paraf_image);
                }
                $dataInput['paraf_image'] = $this->postImage($request->image);
            }

            $institute->update($dataInput);

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
    public function destroy(Institute $institute)
    {
        try {
            if (file_exists($institute->paraf_image)) {
                Storage::delete($institute->paraf_image);
            }
            $institute->delete();

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
            Institute::whereIn('id', $request->id)->delete();

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

    /** 
     * upload images
     * only pdf 
     */
    public function postImage($base64)
    {
        $base64 = Crypt::decryptString($base64);

        $folderPath = "uploads/paraf/";
        if (!Storage::exists($folderPath)) {
            Storage::makeDirectory($folderPath, 0777, true, true);
        }

        $base64Image = explode(";base64,", $base64);
        $explodeImage = explode("image/", $base64Image[0]);
        $imageType = $explodeImage[1];
        $image_base64 = base64_decode($base64Image[1]);
        $file = $folderPath . date('YmdHis') . '.' . $imageType;
        file_put_contents($file, $image_base64);

        return $file;
    }
}
