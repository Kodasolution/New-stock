<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use SDamian\LaravelManPagination\Pagination;

class ProductController extends Controller
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
            "type"        => ["nullable", "string"],
            "value"        => ["nullable", "string"],

        ])->validated();
        $products = Product::query();
        if (count($data) > 0) {
            if ($data["type"] !== null) {
                if ($data["value"] && $data['type'] === "name") {
                    $products->where("name", 'LIKE', "%" . $data["value"] . "%");
                }
                if ($data["value"] && $data['type'] === "category") {
                    $products->join('categories', 'products.category_id', 'categories.id')
                       ->select('products.*','categories.name as cat_name')
                        ->where("categories.name", 'LIKE', "%" . $data["value"] . "%");
                }
            }
        }
        $total = $products->count();
        $pagination = new Pagination(['options_select' => config('man-pagination.options_select')]);
        $pagination->paginate($total);
        $limit = $pagination->limit();
        $offset = $pagination->offset();
        $products = $products->skip($offset)->take($limit)->orderBy("products.id", "desc")->paginate($limit);
        $category = Product::all();
        return view('content.products.list', compact('products', 'category', 'pagination'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $category = Category::all();
        return view('content.products.create', compact('category'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // dd($request->all());
        $data = $request->validate([
            'name' => [
                'required',
                'string',
                Rule::unique('products')->where(function ($query) use ($request) {
                    return $query->where('category_id', $request->input('categorie_id'));
                }),
            ],
            'category_id' => 'required|exists:categories,id',
            'unit_mesure' => 'required',
            'quantity' => 'nullable',
            'status_pro' => 'boolean',
        ]);
        $quantity = $request->input('quantity', 0);
        // dd($data);
        $product = new Product([
            'name' => $request->input('name'),
            'category_id' => $request->input('category_id'),
            'unit_mesure' => $request->input('unit_mesure'),
            'quantity' => $quantity,
            'status_pro' => $request->input('status_pro'),
        ]);
        // dd($product);
        $product->save();

        return redirect()->route('product.index')->with('success', 'Produit créé avec succès.');
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
        $products = Product::findOrFail($id);
        $category = Category::all();
        return view('content.products.edit', compact('products', 'category'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // dd($request->all());
        $data = $request->validate([
            "name" => [
                'required',
                'string',
                Rule::unique('products')->where(function ($query) use ($request) {
                    return $query->where('category_id', $request->input('categorie_id'));
                }),
            ],
            "category_id" => 'required|exists:categories,id',
            "unit_mesure" => "required",
            "status_pro" => "nullable",
            "quantity" => "nullable",
        ]);

        $quantity = $request->input('quantity', 0);
        // dd($data);
        DB::beginTransaction();
        try {
            $products = Product::findOrFail($id);

            $products->update([
                "name" => $request->name,
                "category_id" => $request->category_id,
                "unit_mesure" => $request->unit_mesure,
                "status_pro" => $request->status_pro,
                'quantity' => $quantity,
            ]);

            DB::commit();
            return redirect()->route('product.index')->with('success', 'Product updated successfully');
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
    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $product = Product::findOrFail($id);
            $product->delete();
            DB::commit();
            return redirect()->route('product.index')->with('success', 'Product deleted successfully');
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
