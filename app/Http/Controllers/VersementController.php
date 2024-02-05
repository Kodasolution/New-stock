<?php

namespace App\Http\Controllers;

use App\Models\Dette;
use App\Models\Salaire;
use App\Models\Versement;
use Illuminate\Http\Request;
use App\Models\RemboursementDette;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use SDamian\LaravelManPagination\Pagination;

class VersementController extends Controller
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
            "user"        => ["nullable", "string"],
            "date"        => ["nullable", "string"],
            "title"        => ["nullable", "string"],

        ])->validated();
        $versements = Versement::query()->join('salaires', 'versements.salaire_id', 'salaires.id')->select('versements.*', 'salaires.id as idSalaire');
        $versements = $this->search($data, $versements);
        $total = $versements->count();
        $pagination = new Pagination(['options_select' => config('man-pagination.options_select')]);
        $pagination->paginate($total);
        $limit = $pagination->limit();
        $offset = $pagination->offset();
        $versements = $versements->skip($offset)->take($limit)->orderBy("versements.id", "desc")->paginate($limit);
        $salaires = Salaire::all();
        // dd($salaires);
        // $versements = Versement::orderBy('id', 'desc')->paginate(10);
        return view('content.versement.list', compact('versements', 'pagination', 'salaires'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $salaires = Salaire::all();
        return view('content.versement.create', compact('salaires'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // dd($request->all());
        $data = $request->validate([
            'salaire_id' => 'required',
            'montant_verse' => 'required',
            'date_verse' => 'required',
        ]);
        // dd($data);

        DB::beginTransaction();

        try {
            $employee = Salaire::where('id', $request->input('salaire_id'))->first();
            if ($employee == null) {
                return redirect()->back()->with('error', 'Pas employe trouve');
            }
            $dettes = Dette::where('user_id', $employee->user->id)->first();
            if ($dettes != null) {
                $rest = $request->montant_verse - $dettes->montant_total;
            } else {
                $rest = $request->montant_verse;
            }
            if ($dettes) {
                $versements = Versement::create([
                    'salaire_id' => $request->salaire_id,
                    'montant_verse' => $rest,
                    'date_verse' => $request->date_verse,
                ]);
                if ($dettes->montant_total < $request->input('montant_verse')) {
                    $dettes->montant_total = 0;
                    $dettes->status = 0;
                    $dettes->save();
                } else {
                    $dettes->montant_total = $dettes->montant_total - $request->input('montant_verse');
                    $dettes->status = 1;
                    $dettes->save();
                }

                $remboursements = new RemboursementDette([
                    'dette_id' => $dettes->id,
                    'montant_rembourse' => $request->input('montant_verse') - $versements->montant_verse,
                    'date_rembourse' => $request->input('date_verse'),
                ]);
                $remboursements->save();
                // dd($remboursements);
            }

            $versements = new Versement([
                'salaire_id' => $request->input('salaire_id'),
                'montant_verse' => $request->input('montant_verse'),
                'date_verse' => $request->input('date_verse'),
            ]);

            $versements->save();
            // dd($versements);

            DB::commit();

            return redirect()->route('expenses.versement.index')
                ->with('success', 'Salary payment saved with success');
        } catch (\Exception $e) {
            dd($e);
            DB::rollBack();

            return redirect()->back()->with('error', 'Une erreur est survenue lors de la création du paiement du salaire.');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Versement  $versement)
    {
        $salaires = Salaire::all();
        return view('content.versement.edit', compact('salaires', 'versement'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Versement  $versement)
    {
        // $versements = $versement;
        $salaires = Salaire::all();
        return view('content.versement.edit', compact('salaires', 'versement'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Versement $versement)
    {
        $data = $request->validate([
            'salaire_id' => 'required',
            'montant_verse' => 'required',
            'date_verse' => 'required',
            "mois" => "required|integer",
            "status" => "required|boolean",
            "has_dette" => "nullable"
        ]);
        DB::beginTransaction();
        try {
            // $versements = Versement::findOrFail($id);
            $employee = Salaire::where('id', $request->input('salaire_id'))->first();

            $dettes = Dette::where('user_id', $employee->user->id)->first();
            if($dettes == null){
                $hasDette = 0;
                $montantVerse = $versement->montant_verse;
                // dd($montantVerse);
                $newDette = 0;
            }else{
                if ($dettes->montant_total <  $versement->montant_verse) {
                    $montantRembourse = $dettes->montant_total;
                    $montantVerse = $versement->montant_verse - $montantRembourse;
                    $newDette = 0;
                    $status = 0;
                    $hasDette = 0;
                } else {
                    $montantRembourse = $dettes->montant_total;
                    $newDette = $versement->montant_verse - $dettes->montant_total;
                    $montantVerse = $versement->montant_verse;
    
                    $status = 1;
                    $hasDette = 1;
                }
            }
            if ($dettes) {
                RemboursementDette::create(
                    [
                        'dette_id' => $dettes->id,
                        'montant_rembourse' => $montantRembourse,
                        'date_rembourse' => $request->date_verse,
                    ]
                );
                $dettes->update(
                    [
                        "montant_total" => $newDette,
                        "status" => $status
                    ]
                );
                // $remboursements = RemboursementDette::where('dette_id', $dettes->id)->where('montant_rembourse', $dettes->montant_total)->first();
                // $versement->salaire_id = $request->input('salaire_id');
                // $last_amount = $versement->montant_verse;
                // $versement->montant_verse = $request->input('montant_verse') - $dettes->montant_total;
                // $versement->date_verse = $request->input('date_verse');
                // $versement->save();

                // if ($dettes->montant_total < $request->input('montant_verse')) {
                //     $dettes->montant_total = 0;
                //     $dettes->status = 0;
                //     $dettes->save();
                // } else {
                //     $dettes->montant_total = $dettes->montant_total - $request->input('montant_verse');
                //     $dettes->status = 1;
                //     $dettes->save();
                // }

                // $remboursements->dette_id = $dettes->id;
                // $remboursements->montant_rembourse = $request->input('montant_verse') - $versement->montant_verse;
                // $remboursements->date_rembourse = $request->input('date_verse');
                // $remboursements->save();
            }
            $versement->update(
                [
                    'salaire_id' => $request->salaire_id,
                    'montant_verse' => $montantVerse,
                    'mois' => $request->mois,
                    'has_dette' => $hasDette,
                    'dette_montant' => $newDette,
                    'date_verse' => $request->date_verse,
                    'status' => $request->status
                ]
            );
            // $versement->salaire_id = $request->input('salaire_id');
            // $versement->montant_verse = $request->input('montant_verse');
            // $versement->date_verse = $request->input('date_verse');
            // $versement->save();

            DB::commit();

            return back()
                ->with('success', 'Salary payment updated successfully');
        } catch (\Exception $e) {
            dd($e);
            DB::rollBack();

            return redirect()->back()->with('error', 'An error occurred while updating the salary payment.');
        }
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        DB::beginTransaction();

        try {
            $versements = Versement::findOrFail($id);
            $employee = Salaire::where('id', $versements->salaire_id)->first();

            $dettes = Dette::where('user_id', $employee->user->id)->first();

            if ($dettes) {
                $dettes->montant_total = $employee->montant - $versements->montant_verse;
                $remboursements = RemboursementDette::where('dette_id', $dettes->id)->where('montant_rembourse', $dettes->montant_total)->first();

                $dettes->montant_total = $dettes->montant_total;
                $dettes->status = 1;
                $dettes->save();
                if ($remboursements) {
                    $remboursements->delete();
                }
            }

            $versements->delete();

            DB::commit();

            return redirect()->route('expenses.versement.index')
                ->with('success', 'Salary payment deleted successfully');
        } catch (\Exception $e) {
            dd($e);
            DB::rollBack();

            return redirect()->back()->with('error', 'An error occurred while deleting the salary payment.');
        }
    }
    public function search($data, $query)
    {
        if (count($data) > 0) {
            if ($client = $data["user"]) {
                $query
                    ->where('salaires.user_id', $client);
            }
            if ($date  = $data["date"]) {
                $query->where("date_verse", $date);
            }
            if ($title  = $data["title"]) {
                $query
                    ->where("title", $title);
            }
        }
        return $query;
    }
}
