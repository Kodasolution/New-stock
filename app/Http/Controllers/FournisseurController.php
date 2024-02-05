<?php

namespace App\Http\Controllers;

use App\Models\Fournisseur;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use SDamian\LaravelManPagination\Pagination;

class FournisseurController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if (!auth()->user()->can(config('permission.name.user.view'))) {
            return view('admin.auth.forbidden');
        }
        $data = Validator::make($request->all(), [
            "name"        => ["nullable", "string"]
        ])->validated();
        // dd($data);
        $providers = Fournisseur::query();
        if (array_key_exists('name', $data)) {
            $providers->orWhere(DB::raw("concat(firstName, ' ',lastName)"), 'LIKE', "%" . $data['name'] . "%")
                ->orWhere(DB::raw("concat(lastName, ' ',firstName)"), 'LIKE', "%" . $data['name'] . "%")
                ->orWhere("email", 'LIKE', "%" . $data['name'] . "%");
        }
        $total = $providers->count();
        $pagination = new Pagination(['options_select' => config('man-pagination.options_select')]);
        $pagination->paginate($total);
        $limit = $pagination->limit();
        $offset = $pagination->offset();
        $providers = $providers->skip($offset)->take($limit)->orderBy("id", "desc")->paginate($limit);
        // $providers = Fournisseur::orderBy('id', 'desc')->paginate(15);
        return view('content.providers.list', compact('providers','pagination'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('content.providers.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'firstName' => 'required',
            'lastName' => 'required',
            'phone' => 'required',
            'email' => 'nullable',
            'adress' => 'nullable',
            'status' => 'nullable',
        ]);
        // dd($data);
        $providers = new Fournisseur([
            'firstName' => $request->input('firstName'),
            'lastName' => $request->input('lastName'),
            'phone' => $request->input('phone'),
            'email' => $request->input('email'),
            'adress' => $request->input('adress'),
            'status' => $request->input('status'),
        ]);
        $providers->save();

        return redirect()->route('provider.index')->with('success', 'Fournisseur créé avec succès.');
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
        $providers = Fournisseur::findOrFail($id);
        return view('content.providers.edit', compact('providers'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $data = $request->validate([
            "firstName" => "required",
            "lastName" => "required",
            "phone" => "required",
            "adress" => "nullable",
            "email" => "nullable",
            "status" => "nullable",
        ]);

        DB::beginTransaction();
        try {
            $providers = Fournisseur::findOrFail($id);

            $providers->update([
                "firstName" => $request->firstName,
                "lastName" => $request->lastName,
                "phone" => $request->phone,
                "email" => $request->email,
                "adress" => $request->adress,
                "status" => $request->status,
            ]);

            DB::commit();
            return redirect()->route('provider.index')->with('success', 'Provider updated successfully');
        } catch (\Throwable $e) {
            DB::rollBack();
            dd(
                $e->getMessage(),
                $e->getLine(),
                $e->getFile()
            );
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        DB::beginTransaction();

        try {
            $fournisseur = Provider::findOrFail($id);

            // Supprimer la catégorie
            $fournisseur->delete();

            DB::commit();

            return redirect()->route('provider.index')->with('success', 'Provider deleted successfully');
        } catch (\Throwable $e) {
            DB::rollBack();
            dd(
                $e->getMessage(),
                $e->getLine(),
                $e->getFile()
            );
        }
    }
}
