<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use App\Classes\Services\UserService;
use Illuminate\Support\Facades\Validator;
use SDamian\LaravelManPagination\Pagination;

class UserController extends Controller
{
    private UserService $userService;

    public function __construct()
    {
        $this->userService = new UserService();
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {

        if (!auth()->user()->can(config('permission.name.user.view'))) {
            return view('admin.auth.forbidden');
        }
        $data = Validator::make($request->all(), [
            "type"        => ["nullable", "string"],
            "value"        => ["nullable", "string"],

        ])->validated();
        $users = User::query();  
        $users = $this->userService->Index($data, $users);
        $total = $users->count();
        $pagination = new Pagination(['options_select' => config('man-pagination.options_select')]);
        $pagination->paginate($total);
        $limit = $pagination->limit();
        $offset = $pagination->offset();
        $users = $users->skip($offset)->take($limit)->orderBy("id", "desc")->paginate($limit);
        $roles = Role::all();
        return view("admin.user.index", compact("users", "roles","pagination"));
    } 

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $this->userService->DataValidation($request, "post");
        if ($data->fails()) {
            return back()->withInput()->withErrors($data);
        }
        $user = $this->userService->Create($request);
        return redirect()->route("user.index")->with("success", "User successfully created.");
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        return view("admin.user.index", compact("user"));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        $data = $this->userService->DataValidation($request, "patch", $user);
        if ($data->fails()) {
            return back()->withInput()->withErrors($data, "err_" . $user->id)->with("err", $user->id);
        }
        $user = $this->userService->Update($request, $user);
        return redirect()->route("user.index")->with("success", "User ($user->firstName) successfully updated.");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        $name = $user->name;
        $this->userService->Delete($user);
        return redirect()->route("user.index")->with("success", "User ($user->firstName) successfully updated.");
    }

    /**
     * Update the specified resource in storage.
     */
    public function change_password(Request $request, User $user)
    {
        $data = $this->userService->DataValidation($request, "patch", $user, true);
        if ($data->fails()) {
            return back()->withInput()->withErrors($data, "err_pswd_" . $user->id)->with("err_pwsd", $user->id);
        }
        $user = $this->userService->ChangePassword($request, $user);
        return redirect()->route("user.index")->with("success", "Reset password for ($user->firstName), successfully reset.");
    }
}
