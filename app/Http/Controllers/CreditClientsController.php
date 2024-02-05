<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\CreditClient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CreditClientsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $credits = CreditClient::orderBy('id', 'desc')->paginate(10);
        return view('content.credit.list', compact('credits'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $clients = Client::all();
        return view('content.credit.create', compact('clients'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // dd($request->all());
        $data = $request->validate([
            'client_id' => 'required',
            'status' => 'boolean',
            'montant' => 'required',
            'date_credit' => 'required',
            'description' => 'required',
        ]);
        // dd($data);

        DB::beginTransaction();

        try {

            $credits = new CreditClient([
                'client_id' => $request->input('client_id'),
                'montant' => $request->input('montant'),
                'date_credit' => $request->input('date_credit'),
                'description' => $request->input('description'),
                'status' => $request->input('status'),
            ]);

            $credits->save();
            // dd($credits);

            DB::commit();

            return redirect()->route('client-credit.index')
                ->with('success', 'Credit saved with success');
        } catch (\Exception $e) {
            dd($e);
            DB::rollBack();

            return redirect()->back()->with('error', 'Une erreur est survenue lors de la création de la commande.');
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
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $credits = CreditClient::findOrFail($id);
        $clients = Client::all();
        return view('content.credit.edit', compact('credits','clients'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // dd($request->all());
        $data = $request->validate([
            'client_id' => 'required',
            'status' => 'boolean',
            'montant' => 'required',
            'date_credit' => 'required',
            'description' => 'required',
        ]);
// dd($data);
        DB::beginTransaction();

        try {
            $credits = CreditClient::findOrFail($id);

            $credits->client_id = $request->input('client_id');
            $credits->montant = $request->input('montant');
            $credits->date_credit = $request->input('date_credit');
            $credits->description = $request->input('description');
            $credits->status = $request->input('status');

            $credits->save();

            DB::commit();

            return redirect()->route('client-credit.index')
                ->with('success', 'Crédit mis à jour avec succès');
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()->with('error', 'Une erreur est survenue lors de la mise à jour du credit.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
