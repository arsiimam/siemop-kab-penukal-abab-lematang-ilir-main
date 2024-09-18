<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Setting;
use Auth;
use Storage;
use Validator;

class SettingController extends Controller
{
    /**
     * index setting
     */
    public function index()
    {
        if (Auth::user()->can('Manage Setting')) {
            $rows = Setting::all();

            $row = array();
            foreach ($rows as $val) {
                $row[$val->setting_name] = $val;
            }

            return view('backend.setting.index', compact('row'));
        } else {
            return back()->with('error', __('Akses Ditolak'));
        }
    }

    /**
     * show store process
     */
    public function store(Request $request)
    {
        if (Auth::user()->can('Manage Setting')) {
            $post = $request->all();

            $rows = Setting::where('setting_type', $request->submit)->get();

            unset($post['_token']);
            unset($post['submit']);

            if (isset($request->max_input_date) || isset($request->ds_app_name) || isset($request->ds_app_desc)) {
                $input_env = [
                    'MAX_INPUT_DATE' => $request->max_input_date,
                    'DS_APP_NAME' => $request->ds_app_name,
                    'DS_APP_DESC' => $request->ds_app_desc,
                ];
                setEnvironmentValue($input_env);
            }

            $settings = array();
            foreach ($rows as $val) {
                $settings[$val->setting_name] = $val->setting_value;
            }

            foreach ($post as $key => $data) {
                if (in_array($key, array_keys($settings))) {
                    Setting::updateOrCreate(
                        ['setting_name' => $key],
                        ['setting_value' => $data]
                    );
                }
            }

            return back()->with('success', 'Data Berhasil Diperbarui');
        } else {
            return back()->with('error', __('Akses Ditolak'));
        }
    }

    /**
     * update picture
     */
    public function updatePict(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'file' => 'required|mimes:jpg,jpeg,png,webp',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validator->errors()->all()
            ]);
        }

        $image = $request->file('file')->store('uploads/banner');

        $setting             = Setting::find($request->id);

        if ($setting->setting_value != null) {
            Storage::delete($setting->setting_value);
        }

        $setting->setting_value      = $image;
        $setting->save();

        return response()->json(['success' => __('Data Berhasil Diunggah')]);
    }

    /** file recent */
    public function fileRecent($id)
    {
        $row = Setting::find($id);

        if ($row->setting_value != null) {
            if (file_exists($row->setting_value)) {
                $output =  '<ul class="gallery margin-top-10">
                            <li class="profile">
                                <img src="' . asset($row->setting_value) . '" width="100" />
                                <button type="button" class="btn-link remove_pict" data-id="' . $row->id . '"><i class="simple-icon-trash"></i></button>
                            </li>
                        </ul>';
            } else {
                $output =  '<ul class="gallery margin-top-10">
                        <li class="profile">
                            <img src="' . asset('img/image_coming_soon.jpeg') . '" width="100" />
                        </li>
                    </ul>';
            }
        } else {
            $output =  '<ul class="gallery margin-top-10">
                <li class="profile">
                    <img src="' . asset('img/image_coming_soon.jpeg') . '" width="100" />
                </li>
            </ul>';
        }

        return response()->json($output);
    }

    /** delete img */
    public function removeImg(Request $request)
    {
        $setting             = Setting::find($request->id);
        if ($setting->setting_value != null) {
            Storage::delete($setting->setting_value);
        }
        $setting->setting_value      = null;
        $setting->save();
    }
}
