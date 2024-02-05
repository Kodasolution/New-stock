<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Dette;
use App\Models\Client;
use Illuminate\Http\Request;
use App\Models\RemboursementDette;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use SDamian\LaravelManPagination\Pagination;

class RemboursementDetteController extends Controller
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
            "date"        => ["nullable", "string"],
            "type"        => ["nullable", "string"],

        ])->validated();

        $remboursements = RemboursementDette::query();
        $remboursements = $this->search($data, $remboursements);
        // dd($purchases->get());
        $total = $remboursements->count();
        $pagination = new Pagination(['options_select' => config('man-pagination.options_select')]);
        $pagination->paginate($total);
        $limit = $pagination->limit();
        $offset = $pagination->offset();
        $remboursements = $remboursements->skip($offset)->take($limit)->orderBy("remboursements_dettes.id", "desc")->paginate($limit);

        // $remboursements = RemboursementDette::orderBy('id', 'desc')->paginate(10);
        return view('content.remboursement.list', compact('remboursements','pagination'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $users = User::all();
        $clients = Client::all();
        $dettes = Dette::all();
        return view('content.remboursement.create', compact('users', 'clients', 'dettes'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // dd($request->all());
        $data = $request->validate([
            'dette_id' => 'required',
            'montant_rembourse' => 'required',
            'date_rembourse' => 'required',
        ]);
        // dd($data);

        DB::beginTransaction();

        try {

            $remboursements = new RemboursementDette([
                'dette_id' => $request->input('dette_id'),
                'montant_rembourse' => $request->input('montant_rembourse'),
                'date_rembourse' => $request->input('date_rembourse'),
            ]);
            // dd($remboursements);

            $remboursements->save();

            $dettes = Dette::where('id', $request->input('dette_id'))->first();
            $montant_restant = $dettes->montant_total - $request->input('montant_rembourse');
            $dettes->montant_total = $montant_restant;

            if ($dettes->montant_total == 0) {
                $dettes->status = 0;
            }

            $dettes->save();
            // dd($dettes);

            DB::commit();

            return redirect()->route('expenses.remboursement.index')
                ->with('success', 'Remboursement saved with success');
        } catch (\Exception $e) {
            dd($e);
            DB::rollBack();

            return redirect()->back()->with('error', 'Une erreur est survenue lors de la création de la dette.');
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
        $remboursements = RemboursementDette::findOrFail($id);
        $dettes = Dette::all();
        $users = User::all();
        $clients = Client::all();
        return view('content.remboursement.edit', compact('remboursements', 'dettes', 'users', 'clients'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // dd($request->all());
        $data = $request->validate([
            'dette_id' => 'required',
            'montant_rembourse' => 'required',
            'date_rembourse' => 'required',
        ]);
        // dd($data);

        DB::beginTransaction();

        try {
            $remboursements = RemboursementDette::findOrFail($id);
            $last_amount = $remboursements->montant_rembourse;
            // $remboursements->dette_id = $request->input('dette_id');
            $remboursements->montant_rembourse = $request->input('montant_rembourse');
            $remboursements->date_rembourse = $request->input('date_rembourse');

            // dd($remboursements);

            $remboursements->save();
            $detteId = $remboursements->dette_id;
            // dd($detteId);

            $dettes = Dette::where('id', $detteId)->first();
            // dd($dettes);
            $montant_restant = $dettes->montant_total + $last_amount - $request->input('montant_rembourse');
            $dettes->montant_total = $montant_restant;

            if ($dettes->montant_total == 0) {
                $dettes->status = 0;
            } else {
                $dettes->status = 1;
            }

            $dettes->save();
            // dd($dettes);

            DB::commit();

            return redirect()->route('expenses.remboursement.index')
                ->with('success', 'Remboursement updated with success');
        } catch (\Exception $e) {
            dd($e);
            DB::rollBack();

            return redirect()->back()->with('error', 'Une erreur est survenue lors de la création de la dette.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        DB::beginTransaction();

        try {
            $remboursements = RemboursementDette::findOrFail($id);
            $last_amount = $remboursements->montant_rembourse;

            $detteId = $remboursements->dette_id;
            $dettes = Dette::where('id', $detteId)->first();

            $dettes->montant_total += $last_amount;

            if ($dettes->montant_total == 0) {
                $dettes->status = 0;
            }

            $dettes->save();
            $remboursements->delete();

            DB::commit();

            return redirect()->route('expenses.remboursement.index')
                ->with('success', 'Remboursement deleted successfully');
        } catch (\Exception $e) {
            dd($e);
            DB::rollBack();

            return redirect()->back()->with('error', 'An error occurred while deleting the remboursement.');
        }
    }

    public function search($data, $query)
    {
        if (count($data) > 0) {

            if ($user = $data["user"]) {
                if ($data["type"] !== null && $data['type'] == "user") {
                    $query->join('dettes', 'remboursements_dettes.dette_id', 'dettes.id')
                        ->join('users', 'dettes.user_id', 'users.id')
                        // user
                        ->orWhere(DB::raw("concat(users.firstName, ' ',users.lastName)"), 'LIKE', "%" . $data['user'] . "%")
                        ->orWhere(DB::raw("concat(users.lastName, ' ',users.firstName)"), 'LIKE', "%" . $data['user'] . "%")
                        ->orWhere("users.email", 'LIKE', "%" . $data['user'] . "%");
                }
                if ($data["type"] !== null && $data['type'] == "client") {
                    $query->join('dettes', 'remboursements_dettes.dette_id', 'dettes.id')
                        ->join('clients', 'dettes.client_id', 'clients.id')
                        ->orWhere(DB::raw("concat(clients.firstName, ' ',clients.lastName)"), 'LIKE', "%" . $data['user'] . "%")
                        ->orWhere(DB::raw("concat(clients.lastName, ' ',clients.firstName)"), 'LIKE', "%" . $data['user'] . "%")
                        ->orWhere("clients.email", 'LIKE', "%" . $data['user'] . "%");
                }
            }
            // if ($status = $data["status"]) {
            //     if ($status == "unpaid") {
            //         $query->where("status", 1);
            //     } elseif ($status == "paid") {
            //         $query->where("status", 0);
            //     }
            // }
            if ($date = $data["date"]) {
                // $date=Carbon::createFromTimeStamp(strtotime($date));
                $query
                    ->where('remboursements_dettes.date_rembourse', '=', $date);
            }
        }
        return $query;
    }
}
