<?php

namespace App\Http\Controllers;

use App\Classes\Services\RapportService;
use Illuminate\Http\Request;

class RapportController extends Controller
{
    private RapportService $rapportService;

    public function __construct()
    {
        $this->rapportService = new RapportService();
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $rapports = 0;
        $rapports = $this->rapportService->search($request);
        // dd($rapports);
        // if (key_exists('status', $rapports)) {
        //     // dd($rapports['msg']);
        //     return redirect()->back();
        // } else {
            return view('content.rapport.index', compact('rapports'));
        
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
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
