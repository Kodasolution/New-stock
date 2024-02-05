<?php

namespace App\Classes\Services;

use Carbon\Carbon;
use App\Models\Dette;
use App\Models\DetteDetail;
use App\Models\Caisse;
use App\Models\Expenses;
use App\Models\Mouvement;
use App\Models\Versement;
use App\Models\RemboursementDette;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RapportService
{
    public function search(Request $request)
    {
        $resp = [];
        $data = Validator::make($request->all(), [
            "date"              => ["nullable", "string"],
            "type"              => ['required_if:date,null'],
        ])->validated();
        // dd($data);
        // $query = Payment::query()->join('student_payement_details', 'student_payement_details.payment_id', 'payments.id');
        if (count($data) > 0) {
            if ($date = $data['date'] && $data['type'] == null) {
                $dateEx = explode("to", $data['date'], 2);
                $start = Carbon::parse(trim(htmlspecialchars($dateEx[0])));
                $end = Carbon::parse(trim(htmlspecialchars($dateEx[1])));
                if ($start != $end) {
                    $outputProducts = Mouvement::join('product_mouvements', 'product_mouvements.mouvement_id', 'mouvements.id')
                        ->where('mouvements.typeMouv', 'output')
                        ->where("mouvements.created_at", ">=", $start)
                        ->where("mouvements.created_at", "<=", $end)
                        ->sum('product_mouvements.price_tot');
                    $depenses = Expenses::where("created_at", ">=", $start)
                        ->where("created_at", "<=", $end)->sum('amount');
                    
                    $dettes = Dette::join('details_dette','dettes.id','details_dette.dette_id')
                    ->where("details_dette.date_creation", ">=", $start)
                    ->where("details_dette.date_creation", "<=", $end)
                        ->sum('montant');
                    $approvisionnement = Caisse::where('type', 'approvisionnement')
                        ->where("created_at", ">=", $start)
                        ->where("created_at", "<=", $end)
                        ->sum('montant');
                    $caisses = Caisse::where('type', 'encaissement')
                        ->where("created_at", ">=", $start)
                        ->where("created_at", "<=", $end)
                        ->sum('montant') - $approvisionnement;
                    $salaires = Versement::where("created_at", ">=", $start)
                        ->where("created_at", "<=", $end)
                        ->sum('montant_verse');
                    $resp = [
                        "out" => $outputProducts,
                        "depense" => $depenses,
                        "dettes" => $dettes,
                        "caisses" => $caisses,
                        "salaires" => $salaires
                    ];
                }
                return $resp;
            }
            if ($data['date'] != null && $data['type'] !== null) {
                $date = $data['date'];
                $dateEx = explode("to", $date, 2);
                $start = Carbon::parse(trim(htmlspecialchars($dateEx[0])));
                $end = Carbon::parse(trim(htmlspecialchars($dateEx[1])));
                if ($data['type'] == "dette") {
                    // dd($date);
                    if ($start != $end) {
                        $dette = Dette::join('details_dette','dettes.id','details_dette.dette_id')
                        ->where("details_dette.date_creation", ">=", $start)
                        ->where("details_dette.date_creation", "<=", $end)->get();
      
                    }
                }
                if ($data['type'] == "depense") {
                    if ($start != $end) {
                        $depense = Expenses::where("created_at", ">=", $start)
                            ->where("created_at", "<=", $end)->get();
                    }
                }
                if ($data['type'] == "out") {
                    if ($start != $end) {
                        $outputProduct = Mouvement::join('product_mouvements', 'product_mouvements.mouvement_id', 'mouvements.id')
                            ->where('mouvements.typeMouv', 'output')
                            ->where("mouvements.created_at", ">=", $start)
                            ->where("mouvements.created_at", "<=", $end)
                            ->get();
                    }
                }
                if ($data['type'] == "caisse") {
                    if ($start != $end) {
                        $caisse = Caisse::where('type', 'encaissement')
                            ->where("created_at", ">=", $start)
                            ->where("created_at", "<=", $end)->get();
                    }
                }
                if ($data['type'] == "salaire") {
                    if ($start != $end) {
                        $salaires = Versement::where("created_at", ">=", $start)
                            ->where("created_at", "<=", $end)
                            ->get();
                    }
                }
                $response = [
                    "out" => $outputProduct ?? null,
                    "depense" => $depense ?? null,
                    "dettes" => $dette ?? null,
                    "caisses" => $caisse ?? null,
                    "salaires" => $salaires ?? null
                ];
                return $response;
            }
            if ($data['date'] == null && $data['type'] !== null) {
                if ($data['type'] == "dette") {
                    $dettess = Dette::orderBy('created_at', 'desc')->paginate(15);
                }
                if ($data['type'] == "depense") {
                    $depensess = Expenses::orderBy('created_at', 'desc')->paginate(15);
                }
                if ($data['type'] == "out") {
                    $outputProductss = Mouvement::join('product_mouvements', 'product_mouvements.mouvement_id', 'mouvements.id')
                        ->orderBy('mouvements.created_at', 'desc')->paginate(15);
                }
                if ($data['type'] == "caisse") {

                    $caissess = Caisse::where('type', 'encaissement')->orderBy('created_at', 'desc')->paginate(15);
                }
                if ($data['type'] == "salaire") {  

                    $salaires = Versement::orderBy('created_at', 'desc')->paginate(15);
                }
                $resp = [
                    "out" => $outputProductss ?? null,
                    "depense" => $depensess ?? null,
                    "dettes" => $dettess ?? null,
                    "caisses" => $caissess ?? null,
                    "salaires" => $salaires ?? null

                ];
                // dd($resp);  
                return $resp;
            }
        } else {
            return [
                "status" => false,
                "msg" => "no filter selected"
            ];
        }
        if ($data['date'] == null && $data['type'] == null) {
            return [
                "status" => false,
                "msg" => "no filter selected"
            ];
        }
    }
}
