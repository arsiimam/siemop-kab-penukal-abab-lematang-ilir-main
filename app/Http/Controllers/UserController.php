<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use Spatie\Permission\Models\Role;
use DB;
use Hash;
use Illuminate\Support\Arr;
use App\DataTables\UsersDataTable;
use App\Models\Institute;
use Auth;
use Illuminate\Support\Facades\Crypt;
use Storage;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(UsersDataTable $dataTable)
    {
        if (Auth::user()->can('Manage User')) {
            return $dataTable->render('backend.users.index');
        } else {
            return back()->with('error', __('Akses Ditolak'));
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (Auth::user()->can('Create User')) {
            $roles = Role::pluck('name', 'id')->all();
            $institute = Institute::pluck('name', 'id')->all();
            return view('backend.users.add', compact('roles', 'institute'));
        } else {
            return back()->with('error', __('Akses Ditolak'));
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (Auth::user()->can('Create User')) {
            $this->validate($request, [
                'name' => 'required',
                'username' => 'required|unique:users,username',
                'email' => 'required|email|unique:users,email',
                'password' => 'required|same:confirm-password',
                'role_id' => 'required',
                'institute_id' => 'required',
                'is_active' => 'required'
            ]);

            $input = $request->all();
            $input['password'] = Hash::make($input['password']);

            $user = User::create($input);
            $user->assignRole($request->input('role_id'));

            return redirect()->route('users.index')
                ->with('success', 'Data Berhasil Ditambahkan');
        } else {
            return back()->with('error', __('Akses Ditolak'));
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = User::find($id);
        return view('users.show', compact('user'));
    }

    /**
     * Display Profile
     */
    public function profile()
    {
        if (Auth::user()) {
            $user = Auth::user();
            return view('backend.users.profile', compact('user'));
        } else {
            return back()->with('error', __('Akses Ditolak'));
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        if (Auth::user()->can('Edit User')) {
            $roles = Role::pluck('name', 'id')->all();
            $userRole = $user->roles->pluck('name', 'name')->all();
            $institute = Institute::pluck('name', 'id')->all();

            return view('backend.users.edit', compact('user', 'roles', 'userRole', 'institute'));
        } else {
            return back()->with('error', __('Akses Ditolak'));
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        if (Auth::user()->can('Edit User')) {
            $this->validate($request, [
                'name' => 'required',
                'username' => 'required|unique:users,username,' . $user->id,
                'email' => 'required|email|unique:users,email,' . $user->id,
                'role_id' => 'required',
                'institute_id' => 'required',
                'is_active' => 'required'
            ]);

            $input = $request->all();
            if (!empty($input['password'])) {
                $this->validate($request, [
                    'password' => 'same:confirm-password'
                ]);

                $input['password'] = Hash::make($input['password']);
            } else {
                $input = Arr::except($input, array('password'));
            }

            /** upload avatar */
            if (isset($request->image)) {
                if ($user->avatar != null) Storage::delete($user->avatar);
                $input['avatar'] = $this->postImage($request->image, $request->name_file);
            }

            $user->update($input);
            DB::table('model_has_roles')->where('model_id', $user->id)->delete();

            $user->assignRole($request->input('role_id'));

            // return redirect()->route('users.index')
            //     ->with('success', 'User updated successfully');

            return back()->with('success', 'Data Berhasil Diperbarui');
        } else {
            return back()->with('error', __('Akses Ditolak'));
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        if (Auth::user()->can('Delete User')) {
            $user->delete();
            return redirect()->route('users.index')
                ->with('success', 'Data Berhasil Dihapus');
        } else {
            return back()->with('error', __('Akses Ditolak'));
        }
    }

    /** 
     * upload images / file
     */
    public function postImage($base64, $filename)
    {
        $base64 = Crypt::decryptString($base64);

        $folderPath = "uploads/avatar/";

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
