<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Salaire;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use SDamian\LaravelManPagination\Pagination;

class SalaireController extends Controller
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
            "type"        => ["nullable", "string"],
            "value"        => ["nullable", "string"],

        ])->validated();
        $salaires = Salaire::query();
        $salaires = $this->search($data, $salaires);
        $total = $salaires->count();
        $pagination = new Pagination(['options_select' => config('man-pagination.options_select')]);
        $pagination->paginate($total);
        $limit = $pagination->limit();
        $offset = $pagination->offset();
        $salaires = $salaires->skip($offset)->take($limit)->orderBy("id", "desc")->paginate($limit);
        // dd()
        // $salaires = Salaire::orderBy('id', 'desc')->paginate(10);
        return view('content.salaires.list', compact('salaires', 'pagination'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $users = User::all();
        return view('content.salaires.create', compact('users'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // dd($request->all());
        $data = $request->validate([
            'user_id' => 'required',
            'montant' => 'required',
            'title' => 'required',
            'date_in' => 'required',
        ]);
        // dd($data);

        DB::beginTransaction();

        try {

            $salaires = new Salaire([
                'user_id' => $request->input('user_id'),
                'montant' => $request->input('montant'),
                'title' => $request->input('title'),
                'date_in' => $request->input('date_in'),
            ]);

            $salaires->save();
            // dd($credits);

            DB::commit();

            return redirect()->route('user.salaire.index')
                ->with('success', 'Salary saved with success');
        } catch (\Exception $e) {
            DB::rollBack();
            dd([$e->getFile(),$e->getMessage(),$e->getLine()]);

            return redirect()->back()->with('error', 'Une erreur est survenue lors de la création du salaire.');
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
        $salaires = Salaire::findOrFail($id);
        $users = User::all();
        return view('content.salaires.edit', compact('salaires', 'users'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // dd($request->all());
        $data = $request->validate([
            'user_id' => 'required',
            'montant' => 'required',
            'title' => 'required',
            'date_in' => 'required',
        ]);
        DB::beginTransaction();

        try {
            $salaires = Salaire::findOrFail($id);

            $salaires->user_id = $request->input('user_id');
            $salaires->montant = $request->input('montant');
            $salaires->date_in = $request->input('date_in');
            $salaires->title = $request->input('title');

            $salaires->save();

            DB::commit();

            return redirect()->route('user.salaire.index')
                ->with('success', 'Salaire mis à jour avec succès');
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()->with('error', 'Une erreur est survenue lors de la mise à jour du salaire.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        DB::beginTransaction();

        try {
            $salaires = Salaire::findOrFail($id);

            // Supprimer la catégorie
            $salaires->delete();

            DB::commit();

            return redirect()->route('user.salaire.index')->with('success', 'Salary deleted successfully');
        } catch (\Throwable $e) {
            DB::rollBack();
            dd(
                $e->getMessage(),
                $e->getLine(),
                $e->getFile()
            );
        }
    }

    public function search($data, $query)
    {
        if (count($data) > 0) {
            if ($data["type"] !== null) {
                if ($client = $data["value"]  && $data['type'] === "name") {
                    $cli = User::orWhere(DB::raw("concat(firstName, ' ',lastName)"), 'LIKE', "%" . $data["value"]. "%")
                        ->orWhere(DB::raw("concat(lastName, ' ',firstName)"), 'LIKE', "%" .$data["value"] . "%")
                        ->orWhere("email", 'LIKE', "%" . $data["value"] . "%")->get()->pluck("id")->toArray();
                    $query->whereIn("user_id", $cli);
                }
                if ($data["value"]  && $data['type'] === "title") {

                    $query->where("title", 'LIKE', "%" . $data["value"] . "%");
                }
                if ($data["value"] && $data['type'] === "montant") {

                    $query->where("montant", $data["value"]);
                }
            }
        }
        return $query;

    }
}
