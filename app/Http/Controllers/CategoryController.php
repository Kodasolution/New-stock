<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use SDamian\LaravelManPagination\Pagination;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if (!auth()->user()->can(config('permission.name.caisse.view'))) {
            return view('admin.auth.forbidden');
        }
        $data = Validator::make($request->all(), [
            "name"        => ["nullable", "string"],
            // "value"        => ["nullable", "string"],

        ])->validated();
        $category = Category::query();
        if (array_key_exists('name', $data)) {
            $category= Category::where("name", 'LIKE', "%" . $data["name"] . "%");
            // dd($data);
        }
        $total = $category->count();
        $pagination = new Pagination(['options_select' => config('man-pagination.options_select')]);
        $pagination->paginate($total);
        $limit = $pagination->limit();
        $offset = $pagination->offset();
        $category = $category->skip($offset)->take($limit)->orderBy("id", "desc")->paginate($limit);
        // $category = Category::orderBy('id', 'desc')->paginate(5);
        return view('content.categories.list', compact('category', "pagination"));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $category = Category::all();
        return view('content.categories.create', compact('category'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|unique:categories',
            'status_cat' => 'boolean',
            'parent_id' => 'nullable|exists:categories,id',
        ]);

        $category = new Category([
            'name' => $request->input('name'),
            'status_cat' => $request->input('status_cat'),
            'parent_id' => $request->input('parent_id'),
        ]);

        $category->save();

        return redirect()->route('category.index')->with('success', 'Catégorie créée avec succès.');
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
        $categories = Category::all();
        $category = Category::findOrFail($id);
        return view('content.categories.edit', compact('categories', 'category'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // dd($request->all());
        $data = $request->validate([
            'name' => [
                'required',
                'string',
                Rule::unique('categories', 'name')->where(function ($query) use ($request, $id) {
                    return $query->where('parent_id', $request->input('parent_id'))
                        ->where('id', '!=', $id); // Exclut la catégorie actuelle lors de la validation de l'unicité
                }),
            ],
            'parent_id' => 'nullable|exists:categories,id',
            "status_cat" => "nullable",
        ]);
        // dd($data);


        DB::beginTransaction();
        try {
            $category = Category::findOrFail($id);

            $category->update([
                "name" => $request->name,
                "parent_id" => $request->parent_id,
                "status_cat" => $request->status_cat,
            ]);

            DB::commit();
            return redirect()->route('category.index')->with('success', 'Product updated successfully');
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
            $category = Category::findOrFail($id);

            // Supprimer la catégorie
            $category->delete();

            DB::commit();

            return redirect()->route('category.index')->with('success', 'Category deleted successfully');
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
                if ($data["value"]  && $data['type'] === "name") {

                    $query->where("name", 'LIKE', "%" . $data["value"] . "%");
                }
                if ($data["value"] && $data['type'] === "status") {

                    $query->where("status_cat", $data["value"]);
                }
            }
        }
        return $query;
    }
}
