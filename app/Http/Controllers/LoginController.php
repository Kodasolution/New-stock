<?php

namespace App\Http\Controllers;

use Exception;
use Carbon\Carbon;
use App\Models\User;
use App\Models\DetteDetail;
use App\Models\Caisse;
use App\Models\RemboursementDette;
use App\Models\Product;
use App\Models\Expenses;
use App\Models\Mouvement;
use App\Models\Versement;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Classes\Services\UserService;

class LoginController extends Controller
{
    private UserService $userService;

    public function __construct()
    {
        $this->userService = new UserService();
    }

    public function publiqPage()
    {
        return view('website.index');
    }

    public function login()
    {
        return view('admin.auth.login');
    }

    public function loginPost(Request $request)
    {
        $data = $request->validate([
            "email"     => "required|string",
            "password"  => "required|string"
        ]);
        if (!Auth::attempt($data)) {
            return back()->withInput()->with("error", "These credentials do not match our records.");
        } else {

            if (Auth::user()->roles->pluck('name')[0] == "admin") {
                return redirect()->route('admin.dashboard');
            } elseif (Auth::user()->roles->pluck('name')[0] == config('module.roles.caissier') || Auth::user()->roles->pluck('name')[0] == config('module.roles.cuissinier')) {
                return view('content.dashboard.welcome');
            } else {
                Auth::logout();
                $request->session()->invalidate();
                return back()->withInput()->with("error", "Unautorized.");
            }
        }
    }
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        return redirect()->route('admin.login');
    }
    public function home()
    {
        $dt = Carbon::now();
        $products = Mouvement::where('typeMouv', 'input')
            ->orderBy('id', 'desc')
            ->paginate(5);
        $productFinish = Product::where('quantity', 0)->paginate(5);
        $outputProduct = Mouvement::join('product_mouvements', 'product_mouvements.mouvement_id', 'mouvements.id')
            ->where('mouvements.typeMouv', 'output')
            ->whereMonth('mouvements.created_at', '=', $dt->month)
            ->whereYear('mouvements.created_at', '=', $dt->year)
            ->sum('product_mouvements.price_tot');
        $depense = Expenses::whereMonth('created_at', '=', $dt->month)
            ->whereYear('created_at', '=', $dt->year)
            ->sum('amount');
        $remboursement = RemboursementDette::whereMonth('created_at', '=', $dt->month)
            ->whereYear('created_at', '=', $dt->year)
            ->sum('montant_rembourse');
        $dettes = DetteDetail::whereMonth('created_at', '=', $dt->month)
            ->whereYear('created_at', '=', $dt->year)
            ->sum('montant') - $remboursement;
        $approvisionnement = Caisse::where('type', 'approvisionnement')
            ->whereMonth('created_at', '=', $dt->month)
            ->whereYear('created_at', '=', $dt->year)
            ->sum('montant');
        $caisses = Caisse::where('type', 'encaissement')
            ->whereMonth('created_at', '=', $dt->month)
            ->whereYear('created_at', '=', $dt->year)
            ->sum('montant') - $approvisionnement;
        $salaires = Versement::whereMonth('created_at', '=', $dt->month)
            ->whereYear('created_at', '=', $dt->year)
            ->sum('montant_verse');
        if (Auth::user() == null) {
            return redirect()->route('admin.login');
        }
        if (Auth::user()->roles->pluck('name')[0] == config('module.roles.caissier') || Auth::user()->roles->pluck('name')[0] == config('module.roles.cuissinier')) {
            return view('content.dashboard.welcome');
        }
        return view('content.dashboard.dashboards-ecommerce', compact('products', 'productFinish', 'outputProduct', 'depense', 'dettes', 'caisses', 'salaires'));
    }
    public function profil()
    {
        return view('back.pages.auth.profil');
    }
    public function profilUpdate(Request $request)
    {
        $user = User::findOrFail(Auth::user()->id);
        $data = $request->validate(
            [
                "pays"      => ["required", "string"],
                "email"     => ["required", Rule::unique("users", "email")->ignore($user->id)],
                "commune" => ["required", "string"],
                "zone"   => ["required", "string"],
                "first_name"      => ["required", "string"],
                "last_name"     => ["required", "string"],
                "province" => ["required", "string"],
                "gender"   => ["nullable", "boolean"],
                "phone_number"      => ["required"],
                "born_date"     => ["required"],
            ]
        );
        DB::beginTransaction();
        try {
            $adress = $user->adress->Update([
                "pays" => $request->pays,
                "province" => $request->province,
                "commune" => $request->commune,
                "zone" => $request->zone
            ]);
            $user->update(
                [
                    "first_name" => $request->first_name,
                    "last_name" => $request->last_name,
                    "email" => $request->email,
                    "gender" => $request->gender,
                    "phone_number" => $request->phone_number,
                    "born_date" => $request->born_date,
                ]
            );
            DB::commit();
            return back()->with("success", "User ($user->first_name) successfully updated.");
        } catch (Exception $e) {
            DB::rollBack();
            dd(
                [
                    $e->getMessage(),
                    $e->getLine(),
                    $e->getFile()
                ]
            );
        }
    }
    public function changePassword()
    {
        return view('back.pages.auth.change-password');
    }
    public function changePasswordUpdate(Request $request)
    {
        $data = $this->userService->DataValidation($request, "patch", Auth::user(), true);
        if ($data->fails()) {
            return back()->withInput()->withErrors($data);
        }
        $this->userService->ChangePassword($request, Auth::user());
        return back()->with("success", "Reset password for " .  Auth::user()->name . " successfully reset.");
    }
}
