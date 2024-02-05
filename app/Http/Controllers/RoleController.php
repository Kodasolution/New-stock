<?php

namespace App\Http\Controllers;

use App\Models\Module;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use App\Http\Controllers\Controller;
use App\Classes\Services\RoleService;
use Spatie\Permission\Models\Permission;

class RoleController extends Controller
{
    private RoleService $roleService;

    public function __construct()
    {
        $this->roleService = new RoleService();
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $roles = $this->roleService->Index($request);
        $permission = Permission::get()->pluck("name", "id")->toArray();
        $modules = Module::get()->pluck("name", "id")->toArray();
        return view("content.role.index", compact("roles","permission","modules"));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $modules = Module::get()->pluck("name", "id")->toArray();
        $permission = Permission::get()->pluck("name", "id")->toArray();
        return view("content.role.create", compact("modules", "permission"));

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $this->roleService->DataValidation($request, "post");
        if ($data->fails()) {
            return back()->withInput()->withErrors($data);
        }
        $role = $this->roleService->Create($request);
        return redirect()->route("user.role.index")->with("success", "Role successfully created.");
    }

    /**
     * Display the specified resource.
     */
    public function show(Role $role)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Role $role)
    {
        $modules = Module::get()->pluck("name", "id")->toArray();
        $permission = $role->permissions->pluck("name", "id")->toArray();
        $permissionAll = Permission::get()->pluck("name", "id")->toArray();
        // dd($permission);
        return view("content.role.edit", compact("role","modules","permission","permissionAll"));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Role $role)
    {
        // dd($request->all());
        $data = $this->roleService->DataValidation($request, "patch", $role);
        if ($data->fails()) {
            return back()->withInput()->withErrors($data, "err_" . $role->id)->with("err", $role->id);
        }
        $role = $this->roleService->Update($request, $role);
        return redirect()->route("user.role.index")->with("success", "Role ($role->name) successfully updated.");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Role $role)
    {
        $name = $role->name;
        $this->roleService->Delete($role);
        return redirect()->route("user.role.index")->with("success", "Role ($role->name) successfully updated.");
    }
}
