<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use DB;
use App\DataTables\RolesDataTable;
use Auth;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(RolesDataTable $dataTable)
    {
        if (Auth::user()->can('Manage Role')) {
            return $dataTable->render('backend.roles.index');
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
        if (Auth::user()->can('Create Role')) {
            $permissions = Permission::all()->pluck('name', 'id')->toArray();
            return view('backend.roles.add', compact('permissions'));
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
        if (Auth::user()->can('Create Role')) {
            $this->validate($request, [
                'name' => 'required|unique:roles,name',
                'permissions' => 'required',
            ]);

            $role = Role::create(['name' => $request->input('name')]);
            $role->syncPermissions($request->input('permissions'));

            return redirect()->route('roles.index')
                ->with('success', __('Data Berhasil Ditambahkan'));
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
        $role = Role::find($id);
        $rolePermissions = Permission::join("role_has_permissions", "role_has_permissions.permission_id", "=", "permissions.id")
            ->where("role_has_permissions.role_id", $id)
            ->get();

        return view('backend.roles.show', compact('role', 'rolePermissions'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if (Auth::user()->can('Edit Role')) {
            $role = Role::find($id);
            $permissions = Permission::all()->pluck('name', 'id')->toArray();
            $rolePermissions = DB::table("role_has_permissions")->where("role_has_permissions.role_id", $id)
                ->pluck('role_has_permissions.permission_id', 'role_has_permissions.permission_id')
                ->all();

            return view('backend.roles.edit', compact('role', 'permissions', 'rolePermissions'));
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
    public function update(Request $request, $id)
    {
        if (Auth::user()->can('Edit Role')) {
            $this->validate($request, [
                'name' => 'required',
                'permissions' => 'required',
            ]);

            $role = Role::find($id);
            $role->name = $request->input('name');
            $role->save();

            $role->syncPermissions($request->input('permissions'));

            return redirect()->route('roles.index')
                ->with('success', __('Data Berhasil Diperbarui'));
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
    public function destroy(Role $role)
    {
        if (Auth::user('Delete Role')) {
            $role->delete();
            return redirect()->route('roles.index')
                ->with('success', 'Data Berhasil Dihapus');
        } else {
            return back()->with('error', __('Akses Ditolak'));
        }
    }
}
