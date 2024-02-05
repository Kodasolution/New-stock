<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Caisse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use SDamian\LaravelManPagination\Pagination;

class CaisseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // $date=Carbon::parse(2)->month;
        // $date->month(11);
        // dd( $date);
        if (!auth()->user()->can(config('permission.name.caisse.view'))) {
            return view('admin.auth.forbidden');
        }
        $data = Validator::make($request->all(), [
            "user"        => ["nullable", "string"],
            "date"        => ["nullable", "string"],
            "caisse"        => ["nullable", "string"],

        ])->validated();
        $caisses = Caisse::query();
        $caisses = $this->search($data, $caisses);
        $total = $caisses->count();
        $pagination = new Pagination(['options_select' => config('man-pagination.options_select')]);
        $pagination->paginate($total);
        $limit = $pagination->limit();
        $offset = $pagination->offset();
        $caisses = $caisses->skip($offset)->take($limit)->orderBy("caisses.id", "desc")->paginate($limit);

        // $caisses = Caisse::orderBy('id', 'desc')->paginate(10);
        return view('content.caisse.list', compact('caisses','pagination'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $users = User::all();
        return view('content.caisse.create', compact('users'));
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
            'type' => 'required',
            'date_creation' => 'required',
        ]);
        // dd($data);

        DB::beginTransaction();

        try {

            $caisses = new Caisse([
                'user_id' => $request->input('user_id'),
                'montant' => $request->input('montant'),
                'type' => $request->input('type'),
                'date_creation' => $request->input('date_creation'),
            ]);
            // dd($depenses['montant']);
            $caisses->save();
            // dd($caisses);

            DB::commit();

            return redirect()->route('caisse.index')
                ->with('success', 'Money saved with success');
        } catch (\Exception $e) {
            dd($e);
            DB::rollBack();

            return redirect()->back()->with('error', 'Une erreur est survenue lors de la création.');
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
        $caisses = Caisse::findOrFail($id);
        $users = User::all();
        return view('content.caisse.edit', compact('caisses', 'users'));
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
            'type' => 'required',
            'date_creation' => 'required',
        ]);
        DB::beginTransaction();

        try {
            $caisses = Caisse::findOrFail($id);

            $caisses->user_id = $request->input('user_id');
            $caisses->montant = $request->input('montant');
            $caisses->date_creation = $request->input('date_creation');
            $caisses->type = $request->input('type');

            $caisses->save();

            DB::commit();

            return redirect()->route('caisse.index')
                ->with('success', 'mis à jour avec succès');
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()->with('error', 'Une erreur est survenue lors de la mise à jour.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        DB::beginTransaction();

        try {
            $caisse = Caisse::findOrFail($id);

            // Supprimer la catégorie
            $caisse->delete();

            DB::commit();

            return redirect()->route('caisse.index')->with('success', 'Caiise entry deleted successfully');
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
            if ($client = $data["user"]) {
                $cli = User::orWhere(DB::raw("concat(firstName, ' ',lastName)"), 'LIKE', "%" . $data["user"] . "%")
                    ->orWhere(DB::raw("concat(lastName, ' ',firstName)"), 'LIKE', "%" . $data["user"] . "%")
                    ->orWhere("email", 'LIKE', "%" . $data["user"] . "%")->get()->pluck("id")->toArray();
                $query->whereIn("user_id", $cli);
            }
            if ($type  = $data["caisse"]) {
                $query
                    ->where("type", $data["caisse"]);
            }
            if ($date  = $data["date"]) {
                $query->where("date_creation", $data["date"]);
            }
            
        }
        return $query;
    }
}
