<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Dette;
use App\Models\Client;
use App\Jobs\BackupJob;
use App\Models\DetteDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use SDamian\LaravelManPagination\Pagination;

class DetteController extends Controller
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
            "status"        => ["nullable", "string"],  
            "date"        => ["nullable", "string"],
            "type"        => ["nullable", "string"],

        ])->validated();

        $dettes = Dette::query();
        $dettes = $this->search($data, $dettes);
        // dd($purchases->get());
        $total = $dettes->count();
        $pagination = new Pagination(['options_select' => config('man-pagination.options_select')]);
        $pagination->paginate($total);
        $limit = $pagination->limit();
        $offset = $pagination->offset();
        $dettes = $dettes->skip($offset)->take($limit)->orderBy("dettes.id", "desc")->paginate($limit);

        // $dettes = Dette::orderBy('id', 'desc')->paginate(15);
        $users = User::all();
        return view('content.dette.list', compact('dettes', 'users', 'pagination'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $users = User::all();
        $clients = Client::all();
        return view('content.dette.create', compact('users', 'clients'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'type' => 'required',
            'user_id' => 'nullable',
            'client_id' => 'nullable',
            // 'status' => 'boolean',
            'montant' => 'required|integer',
            'date_creation' => 'required',
            'motif' => 'required',
        ]);

        DB::beginTransaction();

        try {

            $dettes = Dette::where('type', 'user')->where('user_id', $request->input('user_id'))->first();

            if ($request->input('user_id')) {
                if ($dettes) {

                    $montant_in = $request->input('montant');
                    $montant_tot = $montant_in + $dettes->montant_total;


                    $dettes->type = $request->input('type');
                    $dettes->user_id = $request->input('user_id');
                    $dettes->montant_total = $montant_tot;
                    $dettes->save();
                } else {
                    $dettes = new Dette([
                        'type' => $request->input('type'),
                        'user_id' => $request->input('user_id'),
                        'montant_total' => $request->input('montant'),
                    ]);
                    if ($data['type'] === 'user' && !empty($data['user_id'])) {
                        $dettes->user()->associate(User::find($data['user_id']));
                    }
                    $dettes->save();
                }

                $detailDettes = new DetteDetail([
                    'dette_id' => $dettes->id,
                    'montant' => $request->input('montant'),
                    'motif' => $request->input('motif'),
                    'date_creation' => $request->input('date_creation'),
                ]);
                $detailDettes->save();
            }


            $dette = Dette::where('type', 'client')->where('client_id', $request->input('client_id'))->first();

            if ($request->input('client_id')) {
                if ($dette) {

                    $montant_in = $request->input('montant');
                    $montant_tot = $montant_in + $dette->montant_total;
                    // dd($montant_tot);

                    $dette->type = $request->input('type');
                    $dette->client_id = $request->input('client_id');
                    $dette->montant_total = $montant_tot;
                    $dette->save();
                } else {
                    $dette = new Dette([
                        'type' => $request->input('type'),
                        'client_id' => $request->input('client_id'),
                        'montant_total' => $request->input('montant'),
                    ]);
                    if ($data['type'] === 'client' && !empty($data['client_id'])) {
                        $dette->client()->associate(Client::find($data['client_id']));
                    }
                    $dette->save();
                }

                $detailDettes = new DetteDetail([
                    'dette_id' => $dette->id,
                    'montant' => $request->input('montant'),
                    'motif' => $request->input('motif'),
                    'date_creation' => $request->input('date_creation'),
                ]);
                $detailDettes->save();
            }

            DB::commit();

            return redirect()->route('expenses.dette.index')
                ->with('success', 'Dette saved with success');
        } catch (\Exception $e) {
            dd($e);
            DB::rollBack();

            return redirect()->back()->with('error', 'Une erreur est survenue lors de la création de la dette.');
        }
    }




    /**
     * Display the specified resource.
     */
    public function show(Request $request,string $id)
    {
        $data = Validator::make($request->all(), [
            "date"        => ["nullable", "string"],

        ])->validated();
        $detailDettes = DetteDetail::query();
        $detailDettes = $this->searchView($data, $detailDettes);
        // dd($purchases->get());
        $total = $detailDettes->count();
        $pagination = new Pagination(['options_select' => config('man-pagination.options_select')]);
        $pagination->paginate($total);
        $limit = $pagination->limit();
        $offset = $pagination->offset();
        $detailDettes = $detailDettes->skip($offset)->take($limit)->orderBy("id", "desc")->paginate($limit);

        $dettes = Dette::findOrFail($id);
        // $detailDettes = DetteDetail::all();


        $users = User::all();
        $clients = Client::all();
        // $totalNotPaid=
        return view('content.dette.view', compact('dettes', 'users', 'clients', 'detailDettes','pagination'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $details = DetteDetail::findOrFail($id);
        $dettes = Dette::all();
        $users = User::all();
        $clients = Client::all();
        return view('content.dette.edit', compact('dettes', 'users', 'clients', 'details'));
    }

    /**
     * Update the specified resource in storage.
     */



    public function update(Request $request, string $id)
    {
        // dd($request->all());
        $data = $request->validate([
            'montant' => 'required',
            'date_creation' => 'required',
            'motif' => 'required',
        ]);
        // dd($data);
        DB::beginTransaction();

        try {
            $details = DetteDetail::findOrFail($id);

            $last_amount = $details->montant;
            // dd($last_amount);
            $detteId = $details->dette_id;
            // dd($detteId);

            $details->montant = $request->input('montant');
            $details->date_creation = $request->input('date_creation');
            $details->motif = $request->input('motif');

            $details->save();

            $dettes = Dette::where('id', $detteId)->first();
            $montant_restant = $dettes->montant_total - $last_amount + $details->montant;
            $dettes->montant_total = $montant_restant;

            if ($dettes->montant_total == 0) {
                $dettes->status = 0;
            }

            $dettes->save();
            // dd($dettes);

            DB::commit();

            return redirect()->route('expenses.dette.index')
                ->with('success', 'Dette mis à jour avec succès');
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()->with('error', 'Une erreur est survenue lors de la mise à jour de la dette.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        DB::beginTransaction();

        try {
            $details = DetteDetail::findOrFail($id);
            $detteId = $details->dette_id;
            $last_amount = $details->montant;

            $dettes = Dette::where('id', $detteId)->first();
            $montant_restant = $dettes->montant_total - $last_amount;

            $dettes->montant_total = $montant_restant;

            if ($dettes->montant_total == 0) {
                $dettes->status = 0;
            } else {
                $dettes->status = 1;
            }
        

            $dettes->save();

            $details->delete();

            DB::commit();

            return redirect()->route('expenses.dette.index')
                ->with('success', 'Dette supprimée avec succès');
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()->with('error', 'Une erreur est survenue lors de la suppression de la dette.');
        }
    }

    public function search($data, $query)
    {
        if (count($data) > 0) {

            if ($user = $data["user"]) {
                if ($data["type"] !== null && $data['type'] == "user") {
                    $query

                        ->join('users', 'dettes.user_id', 'users.id')
                        // user
                        ->orWhere(DB::raw("concat(users.firstName, ' ',users.lastName)"), 'LIKE', "%" . $data['user'] . "%")
                        ->orWhere(DB::raw("concat(users.lastName, ' ',users.firstName)"), 'LIKE', "%" . $data['user'] . "%")
                        ->orWhere("users.email", 'LIKE', "%" . $data['user'] . "%");
                }
                if ($data["type"] !== null && $data['type'] == "client") {
                    $query
                        ->join('clients', 'dettes.client_id', 'clients.id')
                        ->orWhere(DB::raw("concat(clients.firstName, ' ',clients.lastName)"), 'LIKE', "%" . $data['user'] . "%")
                        ->orWhere(DB::raw("concat(clients.lastName, ' ',clients.firstName)"), 'LIKE', "%" . $data['user'] . "%")
                        ->orWhere("clients.email", 'LIKE', "%" . $data['user'] . "%");
                }
            }
            if ($status = $data["status"]) {
                if ($status == "unpaid") {
                    $query->where("status", 1);
                } elseif ($status == "paid") {
                    $query->where("status", 0);
                }
            }
        
        }
        return $query;
    }

    public function searchView($data, $query)
    {
        if (count($data) > 0) {

            if ($date = $data["date"]) {
                $dateEx = explode("to", $data['date'], 2);
                $start = Carbon::parse(trim(htmlspecialchars($dateEx[0])));
                $end = Carbon::parse(trim(htmlspecialchars($dateEx[1])));
                if ($start != $end) {
                $query
                    ->where("date_creation", ">=", $start)
                    ->where("date_creation", "<=", $end);
                // ->where(DB::raw("(DATE_FORMAT(dettes.created_at,'%y-%m-%d'))"),'=', $date->format('y-m-d'));
                }
            }
        }
        return $query;
    }
}
