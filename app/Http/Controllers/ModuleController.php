<?php

namespace App\Http\Controllers;

use App\Models\Module;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use App\Classes\Services\ModuleService;
use Illuminate\Support\Facades\Validator;

class ModuleController extends Controller
{
    private ModuleService $moduleService;

    public function __construct()
    {
        $this->moduleService = new ModuleService();
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $modules = $this->moduleService->Index($request);
        return view("content.module.index", compact("modules"));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $this->moduleService->DataValidation($request, "post");
        if ($data->fails()) {
            return back()->withInput()->withErrors($data);
        }
        $module = $this->moduleService->Create($request);
        return redirect()->route("user.module.index")->with("success", "Module successfully created.");
    }

    /**
     * Display the specified resource.
     */
    public function show(Module $module)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Module $module)
    {
        return view('content.module.edit', compact("module"));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Module $module)
    {
        $data = $this->moduleService->DataValidation($request, "patch", $module);
        if ($data->fails()) {
            return back()->withInput()->withErrors($data, "err_" . $module->id)->with("err", $module->id);
        }
        $module = $this->moduleService->Update($request, $module);
        return redirect()->route("user.module.index")->with("success", "Module ($module->name) successfully updated.");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Module $module)
    {
        $name = $module->name;
        $this->moduleService->Delete($module);
        return redirect()->route("user.module.index")->with("success", "Module ($module->name) successfully deleted.");
    }
}
