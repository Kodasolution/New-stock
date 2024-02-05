<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Product;
use App\Models\Mouvement;
use Illuminate\Http\Request;
use App\Models\ProductMouvement;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use SDamian\LaravelManPagination\Pagination;

class MouvementController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if (!auth()->user()->can(config('permission.name.product.view'))) {
            return view('admin.auth.forbidden');
        }
        $data = Validator::make($request->all(), [
            "flux_number"        => ["nullable", "string"],
            "product"        => ["nullable", "string"],
            "date"        => ["nullable", "string"],

        ])->validated();
        $mouvements = Mouvement::query()->join('product_mouvements', 'mouvements.id', 'product_mouvements.mouvement_id')
            ->join('products', 'product_mouvements.product_id', 'products.id')->where('typeMouv', 'input')
            ->select('product_mouvements.*', 'mouvements.*', 'products.name');
        $mouvements = $this->search($data, $mouvements);
        // dd($purchases->get());
        $total = $mouvements->count();
        $pagination = new Pagination(['options_select' => config('man-pagination.options_select')]);
        $pagination->paginate($total);
        $limit = $pagination->limit();
        $offset = $pagination->offset();
        $mouvements = $mouvements->skip($offset)->take($limit)->orderBy("mouvements.id", "desc")->paginate($limit);
        $productMouvements = ProductMouvement::all();
        return view('content.inventory.input.list', compact('mouvements', 'productMouvements', 'pagination'));
    }

    public function indexOutput(Request $request)
    {
        if (!auth()->user()->can(config('permission.name.product.view'))) {
            return view('admin.auth.forbidden');
        }
        $data = Validator::make($request->all(), [
            "flux_number"        => ["nullable", "string"],
            "product"        => ["nullable", "string"],
            "date"        => ["nullable", "string"],

        ])->validated();
        $mouvements = Mouvement::query()->join('product_mouvements', 'mouvements.id', 'product_mouvements.mouvement_id')
            ->join('products', 'product_mouvements.product_id', 'products.id')->where('typeMouv', 'output')
            ->select('product_mouvements.*', 'mouvements.*', 'products.name');
            $mouvements = $this->search($data, $mouvements);
        // dd($purchases->get());
        $total = $mouvements->count();
        $pagination = new Pagination(['options_select' => config('man-pagination.options_select')]);
        $pagination->paginate($total);
        $limit = $pagination->limit();
        $offset = $pagination->offset();
        $mouvements = $mouvements->skip($offset)->take($limit)->orderBy("mouvements.id", "desc")->paginate($limit);

        // $mouvements = Mouvement::where('typeMouv', 'output')
        //     ->orderBy('id', 'desc')
        //     ->paginate(10);
        $productMouvements = ProductMouvement::all();
        return view('content.inventory.output.list', compact('mouvements', 'productMouvements','pagination'));
    }

    public function indexReturn(Request  $request)
    {
        if (!auth()->user()->can(config('permission.name.product.view'))) {
            return view('admin.auth.forbidden');
        }
        $data = Validator::make($request->all(), [
            "flux_number"        => ["nullable", "string"],
            "product"        => ["nullable", "string"],
            "date"        => ["nullable", "string"],

        ])->validated();
        $mouvements = Mouvement::query()->join('product_mouvements', 'mouvements.id', 'product_mouvements.mouvement_id')
            ->join('products', 'product_mouvements.product_id', 'products.id')->where('typeMouv', 'return')
            ->select('product_mouvements.*', 'mouvements.*', 'products.name');
            $mouvements = $this->search($data, $mouvements);
        // dd($purchases->get());
        $total = $mouvements->count();
        $pagination = new Pagination(['options_select' => config('man-pagination.options_select')]);
        $pagination->paginate($total);
        $limit = $pagination->limit();
        $offset = $pagination->offset();
        $mouvements = $mouvements->skip($offset)->take($limit)->orderBy("mouvements.id", "desc")->paginate($limit);

        // $mouvements = Mouvement::where('typeMouv', 'return')
        //     ->orderBy('id', 'desc')
        //     ->paginate(10);
        $productMouvements = ProductMouvement::all();
        return view('content.inventory.return.list', compact('mouvements', 'productMouvements','pagination'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $mouvements = Mouvement::all();
        $productMouvements = ProductMouvement::all();
        $users = User::all();
        $products = Product::all();
        return view('content.inventory.input.create', compact('mouvements', 'productMouvements', 'products', 'users'));
    }

    public function output()
    {
        $mouvements = Mouvement::all();
        $products = Product::all();
        $productMouvements = ProductMouvement::all();
        $users = User::all();
        return view('content.inventory.output.add', compact('mouvements', 'products', 'productMouvements', 'users'));
    }

    public function retour()
    {
        $mouvements = Mouvement::all();
        $products = Product::all();
        $productMouvements = ProductMouvement::all();
        $users = User::all();
        return view('content.inventory.return.add', compact('mouvements', 'products', 'productMouvements', 'users'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'date_flux' => 'required',
            'user_id' => 'required',
            'product_id' => 'required',
            'quantity' => 'required',
            'price_un' => 'required',
        ]);
        // dd($data);

        DB::beginTransaction();

        try {
            // Génération automatique du numéro de commande
            $reference_mouv = 'MOV-E-' . date('Ymd') . '-' . rand(1000, 9999);

            // Récupérez le produit associé au mouvement
            $product = Product::find($request->input('product_id'));
            // Calcul du prix total
            $total_price = $request->input('quantity') * $request->input('price_un');

            $mouvements = new Mouvement([
                'referenceMov' => $reference_mouv,
                'date_flux' => $request->input('date_flux'),
                'user_id' => $request->input('user_id'),
                'typeMouv' => 'input',
            ]);
            // dd($mouvements);
            $mouvements->save();
            // dd($mouvements);

            $productMouvements = new ProductMouvement([
                'mouvement_id' => $mouvements->id,
                'product_id' => $request->input('product_id'),
                'quantity' => $request->input('quantity'),
                'price_un' => $request->input('price_un'),
                'price_tot' => $total_price,
                'date_flux' => $request->input('date_flux'),
            ]);
            // dd($productMouvements);
            $productMouvements->save();

            // Mettez à jour la quantité totale dans le stock
            $product_quantity = $product->quantity + $request->input('quantity');
            $product->quantity = $product_quantity;
            // dd($product->quantity);
            
            $product->save();

            DB::commit();

            return redirect()->route('inventory.index')
                ->with('success', 'Flux de stock créé avec succès');
        } catch (\Exception $e) {
            dd($e); // Log or display the exception message and stack trace
            DB::rollBack();

            return redirect()->back()->with('error', 'Une erreur est survenue lors de la création du flux de stock.');
        }
    }

 
        public function storeOutput(Request $request)
        {
            $data = $request->validate([
                'date_flux' => 'required',
                'user_id' => 'required',
                'product_id' => 'required',
                'quantity' => 'required',
            ]);
            DB::beginTransaction();
            try {
                // Génération automatique du numéro de commande
                $reference_mouv = 'MOV-O-' . date('Ymd') . '-' . rand(1000, 9999);
    
                // Récupérez le produit associé au mouvement
                $product = Product::find($request->input('product_id'));
                // Vérifiez si la quantité en stock est suffisante et que la quantité demandée est valide
                if ($product->quantity <= 0 || $request->input('quantity') <= 0 || $request->input('quantity') > $product->quantity) {
                    // La quantité en stock est insuffisante, retournez une erreur
                    return redirect()->back()->with('error', 'La quantité en stock est insuffisante.');
                }
    
                // Calcul du prix total
                $total_price = $request->input('quantity') * $product->getLastPrice();
                $mouvements = new Mouvement([
                    'referenceMov' => $reference_mouv,
                    'date_flux' => $request->input('date_flux'),
                    'user_id' => $request->input('user_id'),
                    'typeMouv' => 'output',
                ]);
                // dd($mouvements);
    
                $mouvements->save();
    
                $productMouvements = new ProductMouvement([
                    'mouvement_id' => $mouvements->id,
                    'product_id' => $request->input('product_id'),
                    'quantity' => $request->input('quantity'),
                    'price_un' => $product->getLastPrice(),
                    'price_tot' => $total_price,
                    'date_flux' => $request->input('date_flux'),
                ]);
    
                // dd($productMouvements);
    
                $productMouvements->save();
    
                // Mettez à jour la quantité totale dans le stock
                $product->quantity -= $request->input('quantity');
    
                $product->save();
    
                DB::commit();
    
                return redirect()->route('inventory.sorties')
                    ->with('success', 'Flux de stock créé avec succès');
            } catch (\Exception $e) {
                dd($e); // Log or display the exception message and stack trace
                DB::rollBack();
    
                return redirect()->back()->with('error', 'Une erreur est survenue lors de la création du flux de stock.');
            }
        }

    public function StoreRetour(Request $request)
    {
        // dd($request->all());
        $data = $request->validate([
            'date_flux' => 'required',
            'user_id' => 'required',
            'product_id' => 'required',
            'quantity' => 'required',
            "price_un" => 'nullable',
        ]);
        // dd($data);

        DB::beginTransaction();

        try {
            // Génération automatique du numéro de commande
            $reference_mouv = 'MOV-R-' . date('Ymd') . '-' . rand(1000, 9999);

            // Récupérez le produit associé au mouvement
            $product = Product::find($request->input('product_id'));

            // Calcul du prix total
            $total_price = $request->input('quantity') *  $request->input('price_un');

            $mouvements = new Mouvement([
                'referenceMov' => $reference_mouv,
                'date_flux' => $request->input('date_flux'),
                'user_id' => $request->input('user_id'),
                'typeMouv' => 'return',
            ]);
            // dd($mouvements);
            $mouvements->save();
            // dd($mouvements);

            $productMouvements = new ProductMouvement([
                'mouvement_id' => $mouvements->id,
                'product_id' => $request->input('product_id'),
                'quantity' => $request->input('quantity'),
                'price_un' =>  $product->getLastPrice(),
                'price_tot' => $total_price,
                'date_flux' => $request->input('date_flux'),
            ]);
            // dd($productMouvements);
            $productMouvements->save();

            // Mettez à jour la quantité totale dans le stock
            $product->quantity += $request->input('quantity');

            $product->save();

            DB::commit();

            return redirect()->route('inventory.return')
                ->with('success', 'Flux de stock retourné avec succès');
        } catch (\Exception $e) {
            dd($e); // Log or display the exception message and stack trace
            DB::rollBack();

            return redirect()->back()->with('error', 'Une erreur est survenue lors de la création du flux de stock.');
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
        $mouvements = Mouvement::findOrFail($id);
        $products = Product::all();
        $productMouvements = ProductMouvement::all();
        $users = User::all();
        return view('content.inventory.input.edit', compact('mouvements', 'products', 'productMouvements', 'users'));
    }

    public function editOutput(string $id)
    {
        $mouvements = Mouvement::findOrFail($id);
        $products = Product::all();
        $productMouvements = ProductMouvement::all();
        $users = User::all();
        return view('content.inventory.output.edit', compact('mouvements', 'products', 'productMouvements', 'users'));
    }

    public function editReturn(string $id)
    {
        $mouvements = Mouvement::findOrFail($id);
        $products = Product::all();
        $productMouvements = ProductMouvement::all();
        $users = User::all();
        return view('content.inventory.return.edit', compact('mouvements', 'products', 'productMouvements', 'users'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'date_flux' => 'required',
            'user_id' => 'required',
            'product_id' => 'required',
            'quantity' => 'required',
            'price_un' => 'required',
        ]);

        DB::beginTransaction();

        try {
            $mouvement = Mouvement::findOrFail($id);

            // Mettez à jour les propriétés de l'objet mouvement
            $mouvement->date_flux = $request->input('date_flux');
            $mouvement->user_id = $request->input('user_id');

            $product = Product::find($request->input('product_id'));
            $total_price = $request->input('quantity') * $request->input('price_un');

            $productMouvement = ProductMouvement::where('mouvement_id', $id)->first();
            $previousQuantity = $productMouvement->quantity;

            $productMouvement->product_id = $request->input('product_id');
            $productMouvement->quantity = $request->input('quantity');
            $productMouvement->price_un = $request->input('price_un');
            $productMouvement->price_tot = $total_price;
            $productMouvement->date_flux = $request->input('date_flux');

            // Mettez à jour la quantité totale dans le stock

            $newQuantity = $request->input('quantity');

            $product->quantity = $product->quantity - $previousQuantity + $newQuantity;

            $mouvement->save();
            $productMouvement->save();
            $product->save();

            DB::commit();

            return redirect()->route('inventory.index')
                ->with('success', 'Flux de stock mis à jour avec succès');
        } catch (\Exception $e) {
            dd($e); // Log or display the exception message and stack trace
            DB::rollBack();

            return redirect()->back()->with('error', 'Une erreur est survenue lors de la mise à jour du flux de stock.');
        }
    }

    public function updateOutput(Request $request, $id)
    {
        $data = $request->validate([
            'date_flux' => 'required',
            'user_id' => 'required',
            'product_id' => 'required',
            'quantity' => 'required',
            'price_un' => 'nullable',
        ]);
        // dd($data);

        DB::beginTransaction();

        try {
            $mouvement = Mouvement::findOrFail($id);

            // Mettez à jour les propriétés de l'objet mouvement
            $mouvement->date_flux = $request->input('date_flux');
            $mouvement->user_id = $request->input('user_id');

            $product = Product::find($request->input('product_id'));
            $total_price = $request->input('quantity') * $request->input('price_un');

            $productMouvement = ProductMouvement::where('mouvement_id', $id)->first();
            $previousQuantity = $productMouvement->quantity;

            $productMouvement->product_id = $request->input('product_id');
            $productMouvement->quantity = $request->input('quantity');
            $productMouvement->price_un = $request->input('price_un');
            $productMouvement->price_tot = $total_price;
            $productMouvement->date_flux = $request->input('date_flux');

            // Mettez à jour la quantité totale dans le stock

            $newQuantity = $request->input('quantity');

            $product->quantity = $product->quantity + $previousQuantity - $newQuantity;

            $mouvement->save();
            $productMouvement->save();
            $product->save();

            DB::commit();

            return redirect()->route('inventory.sorties')
                ->with('success', 'Flux de stock mis à jour avec succès');
        } catch (\Exception $e) {
            dd($e); // Log or display the exception message and stack trace
            DB::rollBack();

            return redirect()->back()->with('error', 'Une erreur est survenue lors de la mise à jour du flux de stock.');
        }
    }

    public function updateReturn(Request $request, $id)
    {
        $data = $request->validate([
            'date_flux' => 'required',
            'user_id' => 'required',
            'product_id' => 'required',
            'quantity' => 'required',
            'price_un' => 'nullable',
        ]);
        // dd($data);

        DB::beginTransaction();

        try {
            $mouvement = Mouvement::findOrFail($id);

            // Mettez à jour les propriétés de l'objet mouvement
            $mouvement->date_flux = $request->input('date_flux');
            $mouvement->user_id = $request->input('user_id');

            $product = Product::find($request->input('product_id'));
            $total_price = $request->input('quantity') * $request->input('price_un');

            $productMouvement = ProductMouvement::where('mouvement_id', $id)->first();
            $previousQuantity = $productMouvement->quantity;

            $productMouvement->product_id = $request->input('product_id');
            $productMouvement->quantity = $request->input('quantity');
            $productMouvement->price_un = $request->input('price_un');
            $productMouvement->price_tot = $total_price;
            $productMouvement->date_flux = $request->input('date_flux');

            // Mettez à jour la quantité totale dans le stock

            $newQuantity = $request->input('quantity');

            $product->quantity = $product->quantity - $previousQuantity + $newQuantity;

            $mouvement->save();
            $productMouvement->save();
            $product->save();

            DB::commit();

            return redirect()->route('inventory.return')
                ->with('success', 'Flux de stock mis à jour avec succès');
        } catch (\Exception $e) {
            dd($e); // Log or display the exception message and stack trace
            DB::rollBack();

            return redirect()->back()->with('error', 'Une erreur est survenue lors de la mise à jour du flux de stock.');
        }
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        DB::beginTransaction();
    
        try {
            $mouvement = Mouvement::findOrFail($id);
            $productMouvement = ProductMouvement::where('mouvement_id', $id)->first();
            $product = Product::find($productMouvement->product_id);
            if($mouvement->typeMouv == "output" || $mouvement->typeMouv == "return"){
                $product->quantity += $productMouvement->quantity;
                
            }else{
                $product->quantity -= $productMouvement->quantity;

            }
            $mouvement->delete();
            $productMouvement->delete();
            $product->save();
            DB::commit();
    
            return redirect()->route('inventory.index')
                ->with('success', 'Stock movement deleted successfully');
        } catch (\Exception $e) {
            dd($e); // Log or display the exception message and stack trace
            DB::rollBack();
    
            return redirect()->back()->with('error', 'An error occurred while deleting the stock movement.');
        }
    }

    public function search($data, $query)
    {
        if (count($data) > 0) {
            if ($flux_number = $data["flux_number"]) {
                $query
                    ->where('mouvements.referenceMov', $flux_number);
            }
            if ($product = $data["product"]) {
                $query
                    ->where("products.name", 'LIKE', "%" . $product . "%");
            }
            if ($date = $data["date"]) {
                $query->where("mouvements.date_flux", $date);
            }
        }
        return $query;
    }
}
