<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Commande;
use App\Models\Mouvement;
use App\Models\Fournisseur;
use Illuminate\Http\Request;
use App\Models\DetailCommande;
use App\Models\ProductMouvement;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use SDamian\LaravelManPagination\Pagination;

class CommandeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request  $request)
    {
        if (!auth()->user()->can(config('permission.name.caisser.view'))) {
            return view('admin.auth.forbidden');
        }
        $data = Validator::make($request->all(), [
            "fournisseur"        => ["nullable", "string"],
            "product"        => ["nullable", "string"],
            "status"        => ["nullable", "string"],

        ])->validated();
        $purchases = Commande::query()->join('fournisseurs', 'commandes.fournisseur_id', 'fournisseurs.id')
        ->join('detail_commandes', 'commandes.id', 'detail_commandes.commande_id');
        $purchases = $this->search($data, $purchases);
        // dd($purchases->get());
        $total = $purchases->count();
        $pagination = new Pagination(['options_select' => config('man-pagination.options_select')]);
        $pagination->paginate($total);
        $limit = $pagination->limit();
        $offset = $pagination->offset();
        $purchases = $purchases->skip($offset)->take($limit)->orderBy("commandes.id", "desc")->paginate($limit);




        // $purchases = Commande::orderBy('id', 'desc')->paginate(15);
        $providers = Fournisseur::all();

        $detailsCommandes = DetailCommande::all();
        return view('content.purchase.list', compact('purchases', 'detailsCommandes', 'providers','pagination'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $purchases = Commande::all();
        $detailsCommandes = Commande::all();
        $providers = Fournisseur::all();
        $products = Product::all();
        return view('content.purchase.create', compact('purchases', 'detailsCommandes', 'providers', 'products'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // dd($request->all());
        $data = $request->validate([
            'date_commande' => 'required|date',
            'fournisseur_id' => 'required|exists:fournisseurs,id',
            'status_co' => 'boolean',
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
            'unit_price' => 'required|numeric|min:0.01',
        ]);

        DB::beginTransaction();

        try {
            // Génération automatique du numéro de commande
            $numero_commande = 'CMD-' . date('Ymd') . '-' . rand(1000, 9999);

            // Calcul du prix total
            $total_price = $request->input('quantity') * $request->input('unit_price');

            $commande = new Commande([
                'numero_commande' => $numero_commande,
                'date_commande' => $request->input('date_commande'),
                'fournisseur_id' => $request->input('fournisseur_id'),
                'status_co' => $request->input('status_co'),
            ]);


            $commande->save();

            $detailCommande = new DetailCommande([
                'commande_id' => $commande->id,
                'product_id' => $request->input('product_id'),
                'quantity' => $request->input('quantity'),
                'unit_price' => $request->input('unit_price'),
                'total_price' => $total_price, // Utilisation du prix total calculé
            ]);

            $detailCommande->save();
            // dd($detailCommande);

            $user = Auth::user();

            if ($commande->status_co == '1') {

                $mouvements = new Mouvement([
                    'referenceMov' => $numero_commande,
                    'date_flux' => $request->input('date_commande'),
                    'user_id' => $user->id,
                    'typeMouv' => 'input',
                ]);

                $mouvements->save();

                $productMouvements = new ProductMouvement([
                    'mouvement_id' => $mouvements->id,
                    'product_id' => $request->input('product_id'),
                    'quantity' => $request->input('quantity'),
                    'price_un' => $request->input('unit_price'),
                    'price_tot' => $total_price,
                    'date_flux' => $request->input('date_commande'),
                ]);

                $productMouvements->save();

                $product = Product::find($request->input('product_id'));

                $product->quantity += $request->input('quantity');

                $product->save();
            }

            DB::commit();

            return redirect()->route('purchase.index')
                ->with('success', 'Commande créée avec succès');
        } catch (\Exception $e) {
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
        $purchases = Commande::findOrFail($id);
        $detailsCommandes = Commande::all();
        $providers = Fournisseur::all();
        $products = Product::all();
        return view('content.purchase.edit', compact('purchases', 'detailsCommandes', 'providers', 'products'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'date_commande' => 'required|date',
            'fournisseur_id' => 'required|exists:fournisseurs,id',
            'status_co' => 'boolean',
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
            'unit_price' => 'required|numeric|min:0.01',
        ]);
        // dd($data);

        DB::beginTransaction();

        try {
            $commande = Commande::findOrFail($id);

            $commande->date_commande = $request->input('date_commande');
            $commande->fournisseur_id = $request->input('fournisseur_id');
            $commande->status_co = $request->input('status_co');

            $commande->save();

            $detailCommande = DetailCommande::where('commande_id', $commande->id)->first();

            $last_quantity = $detailCommande->quantity;
            // dd($commande->status_co);

            $detailCommande->product_id = $request->input('product_id');
            $detailCommande->quantity = $request->input('quantity');
            $detailCommande->unit_price = $request->input('unit_price');

            $total_price = $request->input('quantity') * $request->input('unit_price');
            $detailCommande->total_price = $total_price;

            // Save the updated detail command
            $detailCommande->save();
            // dd($detailCommande->quantity);
            $user = Auth::user();
            
            if ($commande->status_co == '1') {

                $mouvements = Mouvement::where('referenceMov', $commande->numero_commande)->first();
                
                if($commande) {
                    $mouvements->date_flux = $request->input('date_commande');
                    $mouvements->user_id = $user->id;
                    $mouvements->save();
                    // dd($mouvements);
        
                    $productMouvements = ProductMouvement::where('mouvement_id', $mouvements->id)->first();
        
                    $productMouvements->product_id = $request->input('product_id');
                    $productMouvements->quantity = $request->input('quantity');
                    $productMouvements->price_un = $request->input('unit_price');
                    $productMouvements->price_tot = $total_price;
                    $productMouvements->date_flux = $request->input('date_commande');
                    
                    $productMouvements->save();
                    // dd($productMouvements);
                    
                    $product = Product::find($request->input('product_id'));
        
                    $product_quantity = $product->quantity- $last_quantity + $request->input('quantity');
                    $product->quantity = $product_quantity;
        
                    $product->save();
                    
                } else {
                    
                        $mouvements = new Mouvement([
                        'referenceMov' => $commande->numero_commande,
                        'date_flux' => $request->input('date_commande'),
                        'user_id' => $user->id,
                        'typeMouv' => 'input',
                    ]);
    
                    $mouvements->save();
    
                    $productMouvements = new ProductMouvement([
                        'mouvement_id' => $mouvements->id,
                        'product_id' => $request->input('product_id'),
                        'quantity' => $request->input('quantity'),
                        'price_un' => $request->input('unit_price'),
                        'price_tot' => $total_price,
                        'date_flux' => $request->input('date_commande'),
                    ]);
    
                    $productMouvements->save();
    
                    $product = Product::find($request->input('product_id'));
    
                    $product->quantity += $request->input('quantity');
    
                    $product->save();
                }
                
            } else {
                
                $mouvement = Mouvement::where('referenceMov', $commande->numero_commande)->first();
                
                if($mouvement) {
                    
                    $productMouvement = ProductMouvement::where('mouvement_id', $mouvement->id)->first();

                    $product = Product::find($request->input('product_id'));
                    $product_quantity =  $product->quantity - $last_quantity;
                    $product->quantity = $product_quantity;
                    $product->save();
                    // dd($product);
                    $productMouvement->delete();
                    
                    $mouvement->delete();
                }
                
            }
            DB::commit();

            return redirect()->route('purchase.index')
                ->with('success', 'Commande mise à jour avec succès');
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()->with('error', 'Une erreur est survenue lors de la mise à jour de la commande.');
        }
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        DB::beginTransaction();

        try {
            $commande = Commande::findOrFail($id);

            $detailCommande = DetailCommande::where('commande_id', $commande->id)->first();

            // Supprimer les détails de la commande
            $detailCommande->delete();

            if ($commande->status_co == '1') {
                // Supprimer les mouvements et les entrées associées
                $mouvement = Mouvement::where('referenceMov', $commande->numero_commande)->first();
                if ($mouvement) {
                    $mouvement->delete();
                }
                $productMouvement = ProductMouvement::where('mouvement_id', $mouvement->id)->first();
                if ($productMouvement) {
                    $productMouvement->delete();
                }
                $product = Product::find($detailCommande->product_id);
                $product->quantity -= $detailCommande->quantity;
                $product->save();
            }

            // Supprimer la commande
            $commande->delete();

            DB::commit();

            return redirect()->route('purchase.index')
                ->with('success', 'Commande supprimée avec succès');
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()->with('error', 'Une erreur est survenue lors de la suppression de la commande.');
        }
    }

    public function search($data, $query)
    {
        if (count($data) > 0) {
            if ($fournisseur = $data["fournisseur"]) {
                $query
                    ->where('commandes.fournisseur_id', (int)$fournisseur);
            }
            if ($product = $data["product"]) {
                $query
                    ->join('products', 'detail_commandes.product_id', 'products.id')
                    ->select('products.name','fournisseurs.*','commandes.*')
                    ->where("products.name", 'LIKE', "%" . $product . "%");
            }
            if ($status = $data["status"]) {
                if ($status == "delivery") {
                    $query->where("status_co", 1);
                } elseif ($status == "undelivery") {
                    $query->where("status_co", 0);
                }
            }
        }
        return $query;
    }
}
