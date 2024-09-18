<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Spatie\Permission\Models\Permission;
use DB;
use App\DataTables\PermissionsDataTable;
use Spatie\Permission\Models\Role;
use Auth;

class PermissionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(PermissionsDataTable $dataTable)
    {
        if (Auth::user()->can('Manage Permission')) {
            $roles = Role::all();
            return $dataTable->render('backend.permissions.index', compact('roles'));
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
        if (Auth::user()->can('Create Permission')) {
            $roles = Role::all();
            return view('backend.permissions.add')->with('roles', $roles);
        } else {
            return response()->json(['error' => __('Akses Ditolak.')], 401);
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
        if (Auth::user()->can('Create Permission')) {
            $this->validate($request, [
                'name' => 'required|unique:permissions,name',
            ]);

            $permission                = new Permission();
            $permission->name          = $request->name;
            $permission->guard_name    = 'web';

            $roles = $request['roles'];

            $permission->save();

            if (!empty($request['roles'])) {
                foreach ($roles as $role) {
                    $r          = Role::where('id', '=', $role)->firstOrFail();
                    $permission = Permission::where('name', '=', $request->name)->first();
                    $r->givePermissionTo($permission);
                }
            }

            return redirect()->route('permissions.index')
                ->with('success', __('Sukses Menambahkan Perizinan ' . $permission->name));
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
        // 
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Permission $permission)
    {
        if (Auth::user()->can('Edit Permission')) {
            return view('backend.permissions.edit', compact('permission'));
        } else {
            return response()->json(['error' => __('Akses Ditolak.')], 401);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Permission $permission)
    {
        if (Auth::user()->can('Edit Permission')) {
            $this->validate($request, [
                'name' => 'required'
            ]);

            $permission->name = $request->input('name');
            $permission->save();

            return redirect()->route('permissions.index')
                ->with('success', __('Sukses Memperbarui Perizinan ' . $permission->name));
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
    public function destroy(Permission $permission)
    {
        if (Auth::user('Delete Permission')) {
            $permission->delete();
            return redirect()->route('permissions.index')
                ->with('success', 'Data Berhasil Dihapu');
        } else {
            return back()->with('error', __('Akses Ditolak'));
        }
    }
}
