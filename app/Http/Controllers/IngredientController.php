<?php

namespace App\Http\Controllers;

use App\Models\Ingredient;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use SDamian\LaravelManPagination\Pagination;

class IngredientController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request  $request)
    {
         if (!auth()->user()->can(config('permission.name.caisse.view'))) {
            return view('admin.auth.forbidden');
        }
        $data = Validator::make($request->all(), [
            "name"        => ["nullable", "string"]
        ])->validated();
        // dd($data);
        $ingredients = Ingredient::query();
        if (array_key_exists('name',$data)) {
            $ingredients->where("name", 'LIKE', "%" . $data["name"] . "%");
        }
        $total = $ingredients->count();
        $pagination = new Pagination(['options_select' => config('man-pagination.options_select')]);
        $pagination->paginate($total);
        $limit = $pagination->limit();
        $offset = $pagination->offset();
        $ingredients = $ingredients->skip($offset)->take($limit)->orderBy("id", "desc")->paginate($limit);

        // $ingredients = Ingredient::orderBy('id', 'desc')->paginate(15);
        return view('content.ingredients.list', compact('ingredients','pagination'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $ingredients = Ingredient::all();
        return view('content.ingredients.create', compact('ingredients'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|unique:ingredients',
        ]);

        $ingredients = new Ingredient([
            'name' => $request->input('name'),
        ]);

        $ingredients->save();

        return redirect()->route('product.ingredient.index')->with('success', 'Ingredient créée avec succès.');
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
        $ingredients = Ingredient::findOrFail($id);
        return view('content.ingredients.edit', compact('ingredients'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // dd($request->all());
        $data = $request->validate([
            'name' => 'required|string|unique:ingredients,name,'.$id,
            ]);

        // dd($data);
        

        DB::beginTransaction();
        try {
            $ingredients = Ingredient::findOrFail($id);

            $ingredients->update([
                "name" => $request->name,
            ]);

            DB::commit();
            return redirect()->route('product.ingredient.index')->with('success', 'Ingredient updated successfully');
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
            $ingredients = Ingredient::findOrFail($id);

            // Supprimer la catégorie
            $ingredients->delete();

            DB::commit();

            return redirect()->route('ingredient.index')->with('success', 'Ingredient deleted successfully');
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
