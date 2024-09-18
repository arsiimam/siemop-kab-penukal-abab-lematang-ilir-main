<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;

class DropzoneController extends Controller
{
    /** 
     * post single image
     */
    public function singlePost(Request $request)
    {
        $mime = getimagesize($request->file('file'));

        $encode = 'data:' . $mime['mime'] . ';base64,' . base64_encode(file_get_contents($request->file('file')));

        $size = $request->file('file')->getSize();

        if ($size >= 1048576) {
            $size = number_format($request->file('file')->getSize() / 1048576, 2) . ' MB';
        } else {
            $size = number_format($request->file('file')->getSize() / 1000, 1) . ' KB';
        }

        return response()->json([
            'status' => 'success',
            'file' => Crypt::encryptString($encode),
            'title' => $request->file->getClientOriginalName(),
            'size' => $size
        ]);
    }
}
