<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\User;
use App\Models\Expenses;
use App\Models\Ingredient;
use Illuminate\Http\Request;
use App\Models\DepenseIngredient;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use SDamian\LaravelManPagination\Pagination;

class ExpensesController extends Controller
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
            "user"        => ["nullable", "string"],
            "title"        => ["nullable", "string"],
            "date"        => ["nullable", "string"],
            ])->validated();

            $depenses = Expenses::query();
            $depenses = $this->search($data, $depenses);
            // dd($purchases->get());
            $total = $depenses->count();
            $pagination = new Pagination(['options_select' => config('man-pagination.options_select')]);
            $pagination->paginate($total);
            $limit = $pagination->limit();
            $offset = $pagination->offset();
            $depenses = $depenses->skip($offset)->take($limit)->orderBy("depenses.id", "desc")->paginate($limit);
    
        // $depenses = Expenses::orderBy('id', 'desc')->paginate(10);
        $users = User::all();
        return view('content.depenses.list', compact('depenses','users','pagination'));
    }

    public function list(Request $request)
    {
        if (!auth()->user()->can(config('permission.name.caisse.view'))) {
            return view('admin.auth.forbidden');
        }
        $data = Validator::make($request->all(), [
            "user"        => ["nullable", "string"],
            "title"        => ["nullable", "string"],
            "date"        => ["nullable", "string"],
            ])->validated();

            $depenses = Expenses::query()->where("type","ingredients");
            $depenses = $this->search($data, $depenses);
            // dd($purchases->get());
            $total = $depenses->count();
            $pagination = new Pagination(['options_select' => config('man-pagination.options_select')]);
            $pagination->paginate($total);
            $limit = $pagination->limit();
            $offset = $pagination->offset();
            $depenses = $depenses->skip($offset)->take($limit)->orderBy("depenses.id", "desc")->paginate($limit);

        // $depenses = Expenses::orderBy('id', 'desc')->paginate(10);
        $users = User::all();
        return view('content.depenses.index', compact('depenses','users','pagination'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $users = User::all();
        $ingredients = Ingredient::all();
        return view('content.depenses.create', compact('users', 'ingredients'));
    }

    public function add()
    {
        $users = User::all();
        $ingredients = Ingredient::all();
        return view('content.depenses.add', compact('users', 'ingredients'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // dd($request->all());
        $data = $request->validate([
            // 'type' => 'required',
            'user_id' => 'required',
            'amount' => 'required|numeric',
            'title' => 'required',
            'date_creation' => 'required',
        ]);
        DB::beginTransaction();
        try {
            $depenses = Expenses::create([
                'user_id' => $request->user_id,
                'amount' => $request->amount,
                'title' => $request->title,
                'date_creation' => $request->date_creation,
                'type' => 'autres',
            ]);
            // dd($depenses['amount']);
  
            // dd($depenses);

            // if ($data['type'] === 'ingredients' && $request->has('ingredients')) {
            //     $ingredients = $request->input('ingredients');
            //     foreach ($ingredients as $ingredient) {
            //         $depenseIngredient = new DepenseIngredient();
            //         $depenseIngredient->depense_id = $depenses->id;
            //         $depenseIngredient->ingredient_id = $ingredient;
            //         $depenseIngredient->save();
            //         // dd($depenseIngredient);
            //     }  
            // }

            DB::commit();

            return redirect()->route('expenses.index')
                ->with('success', 'Expense saved with success');
        } catch (\Exception $e) {
            DB::rollBack();
            dd([
                $e->getLine(),
                $e->getMessage()
            ]); 

            return redirect()->back()->with('error', 'Une erreur est survenue lors de la création de la depense.');
        }
    }


    public function save(Request $request)
    {
        $validatedData = $request->validate([
            'user_id' => 'required',
            'date_creation' => 'required',
            'group_a.*.ingredient_id' => 'required',
            'group_a.*.prix_unitaire' => 'required',
        ]);

        $user = $request->input('user_id');
        $date_creation = $request->input('date_creation');
        $error = null;
        $montant_tot = 0; // Initialiser le montant total à zéro

        DB::beginTransaction();

        try {
            $depenses = Expenses::create([
                "title" => 'Condiments',
                "user_id" => $user,
                "date_creation" => $date_creation,
                'type' => 'ingredients',
                "amount" => $montant_tot,
            ]);

            foreach ($request->group_a as $item) {
                $ingredient_id = $item['ingredient_id'];
                $prix_unitaire = $item['prix_unitaire'];

                $ingredients = DepenseIngredient::create([
                    "depense_id" => $depenses->id,
                    "prix_unitaire"  => $prix_unitaire,
                    "ingredient_id"  => $ingredient_id,
                    "date_creation" => $date_creation,
                ]);

                // Ajouter le prix unitaire à la somme totale
                $montant_tot += $prix_unitaire;
            }

            // Mettre à jour le montant total dans l'instance Expenses
            $depenses->update(['amount' => $montant_tot]);
            // dd($ingredients);
            DB::commit();
            return redirect()->route('expenses.list')
                ->with('success', 'Expense saved with success'); 
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'error' => [
                    'line' => $e->getLine(),
                    'file' => $e->getFile(),
                    'message' => $e->getMessage()
                ]
            ], 500); // Retourner une réponse JSON avec les détails de l'erreur et le code d'état 500
        }
        return redirect()->back()->with('error', 'Une erreur est survenue lors de la création de la depense.');
    }


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $depenses = Expenses::findOrFail($id);

        $users = User::all();
        $ingredients = Ingredient::all();
        $depenseIngredients = DepenseIngredient::all();
        return view('content.depenses.show', compact('depenses','users', 'ingredients', 'depenseIngredients'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $depenses = Expenses::findOrFail($id);
        $users = User::all();
        $ingredients = Ingredient::all();
        $selectedIngredients = $depenses->ingredients->pluck('id')->toArray(); // Récupérer les IDs des ingrédients liés à la dépense
        return view('content.depenses.edit', compact('depenses','users', 'ingredients', 'selectedIngredients'));
    }

    public function editDepenseIngredient(string $id)
    {
        $depenseIngredients = DepenseIngredient::findOrFail($id);
        $users = User::all();
        $ingredients = Ingredient::all();
        $depenses = Expenses::all();
        return view('content.depenses.edit2', compact('depenses','users', 'ingredients', 'depenseIngredients'));
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        // dd($request->all());
        $data = $request->validate([
            // 'type' => 'required',
            'user_id' => 'required',
            'amount' => 'required',
            'title' => 'required',
            'date_creation' => 'required',
        ]);

        DB::beginTransaction();

        try {
            $depense = Expenses::findOrFail($id);
            $depense->user_id = $request->input('user_id');
            $depense->amount = $request->input('amount');
            $depense->title = $request->input('title');
            $depense->date_creation = $request->input('date_creation');
            $depense->type = 'autres';
            $depense->save();

            // Supprimer les anciennes relations entre les dépenses et les ingrédients
            // DepenseIngredient::where('depense_id', $depense->id)->delete();

            // if ($data['type'] === 'ingredients' && $request->has('ingredients')) {
            //     $ingredients = array_unique($request->input('ingredients'));

            //     // dd($ingredients);
            //     foreach ($ingredients as $ingredient) {
            //         $depenseIngredient = new DepenseIngredient();
            //         $depenseIngredient->depense_id = $depense->id;
            //         $depenseIngredient->ingredient_id = $ingredient;
            //         $depenseIngredient->save();
            //         // dd($depenseIngredient);
            //     }
            // }

            DB::commit();

            return redirect()->route('expenses.index')->with('success', 'Expense updated successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'An error occurred while updating the expense.');
        }
    }

    public function update2(Request $request, $id)
    {
      
        DB::beginTransaction();

        try {

            $depenseIngredients = DepenseIngredient::findOrFail($id);
            
            $depenses = Expenses::where('id', $depenseIngredients->depense_id)->first();

            $last_price = $depenseIngredients->prix_unitaire;

            $depense_amount = $depenses->amount - $last_price;

            $depenseIngredients->prix_unitaire = $request->input('prix_unitaire');
            $depenseIngredients->save();

            $depenses->amount = $depense_amount + $depenseIngredients->prix_unitaire;
            $depenses->save();


            DB::commit();
            return redirect()->route('expenses.list', $depenses->id)
                ->with('success', 'Expense saved with success'); // Retourner une réponse JSON avec les données et le code d'état 200
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'error' => [
                    'line' => $e->getLine(),
                    'file' => $e->getFile(),
                    'message' => $e->getMessage()
                ]
            ], 500); // Retourner une réponse JSON avec les détails de l'erreur et le code d'état 500
        }
        return redirect()->back()->with('error', 'Une erreur est survenue lors de la création de la depense.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        DB::beginTransaction();

        try {
            $depense = Expenses::findOrFail($id);

            // Supprimer les relations avec les ingrédients
            $depenseIngredients = DepenseIngredient::where('depense_id', $depense->id);

            if($depenseIngredients){
                $depenseIngredients->delete();
            }
            // Supprimer la dépense elle-même
            $depense->delete();

            DB::commit();

            return redirect()->route('expenses.index')->with('success', 'Expense deleted successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'An error occurred while deleting the expense.');
        }
    }

    

    public function destroy2($id)
    {
        DB::beginTransaction();

        try {
            $depenseIngredients = DepenseIngredient::findOrFail($id);
            $depenses = Expenses::where('id', $depenseIngredients->depense_id)->first();

            $depense_amount = $depenses->amount - $depenseIngredients->prix_unitaire;
            $depenses->amount = $depense_amount;
            $depenses->save();

            $depenseIngredients->delete();

            DB::commit();

            return redirect()->route('expenses.list', $depenses->id)
                ->with('success', 'Expense deleted with success');
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'error' => [
                    'line' => $e->getLine(),
                    'file' => $e->getFile(),
                    'message' => $e->getMessage()
                ]
            ], 500);
        }
    }
    public function search($data, $query)
    {
        if (count($data) > 0) {
            if (array_key_exists('user',$data) && $user = $data["user"]) {
                $query
                    ->where('depenses.user_id', $user);
            }
            if (array_key_exists('title',$data) && $product = $data["title"] ) {
                $query
                    ->where("depenses.title", 'LIKE', "%" . $product . "%");
            }
            if ($date = $data["date"]) {
                $query->where("depenses.date_creation", $date);
            }
        }
        return $query;
    }
}
