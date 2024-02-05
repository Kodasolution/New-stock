<?php

namespace App\Classes\Services;

use Exception;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\Validator as ValidatorReturn;

class UserService
{
    public function Index($data, $query)
    {
        if (count($data) > 0) {
            if ($data["type"] !== null) {
                if ($client = $data["value"]  && $data['type'] == "name") {
                    $query->orWhere(DB::raw("concat(firstName, ' ',lastName)"), 'LIKE', "%" .$data["value"]  . "%")
                        ->orWhere(DB::raw("concat(lastName, ' ',firstName)"), 'LIKE', "%" . $data["value"]  . "%")
                        ->orWhere("email", 'LIKE', "%" . $data["value"]  . "%");
                }
                if ($role = $data["value"]  && $data['type'] == "role") {
                    $query= User::where('role','LIKE', "%" .$data["value"] . "%");
                }
            }
        }  
        // dd($query->get());

        return $query;
    }
    public function Create(Request $request)
    {
        $firstName = trim(htmlspecialchars($request->firstName));
        $lastName = trim(htmlspecialchars($request->lastName));
        $phone = trim(htmlspecialchars($request->phone));
        $email = trim(htmlspecialchars($request->email));
        $role = trim(htmlspecialchars($request->role));
        $password = trim($request->password);
        DB::beginTransaction();
        try {
            $user = User::create(
                [
                    "firstName" => $firstName,
                    "lastName" => $lastName,
                    "email" => $email,
                    "phone" => $phone,
                    "password"  => Hash::make($password),
                    "role" => $role
                ]
            );
            $role = Role::where('name', $role)->first();
            // dd($role); 
            $user->assignRole($role->name);
            DB::commit();
            return $user;
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
    public function Update(Request $request, User $user): User
    {
        $firstName = trim(htmlspecialchars($request->firstName));
        $lastName = trim(htmlspecialchars($request->lastName));
        $phone = trim(htmlspecialchars($request->phone));
        $email = trim(htmlspecialchars($request->email));
        $role = trim(htmlspecialchars($request->role));
        DB::beginTransaction();
        try {
            $user->update(
                [
                    "firstName" => $firstName,
                    "lastName" => $lastName,
                    "email" => $email,
                    "phone" => $phone,
                ]
            );
            DB::table('model_has_roles')->where('model_id', $user->id)->delete();
            $user->assignRole(Role::where("name", $role)->first());
            $role = Role::where('name', $role)->first();
            DB::commit();
            return $user;
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
    public function ChangePassword(Request $request, User $user): User
    {
        $user->update(["password" => Hash::make(trim($request->password)),]);
        return $user;
    }

    public function Delete(User $user): bool
    {
        return $user->delete();
    }

    /**
     * Validation
     *
     * @param  Request $request
     * @param  string $method
     * @param  User|bool $user
     * @return ValidatorReturn|null
     */
    public function DataValidation(Request $request, String $method, User|bool $user = null, $pswd = false): ValidatorReturn|null
    {
        if ($pswd) {
            return Validator::make($request->all(), [
                "password"  => ["required", Password::min(8)->numbers()->letters()->mixedCase()],
            ]);
        }
        switch (strtolower($method)) {
            case 'post':
                return Validator::make($request->all(), [
                    "email"     => ["required", "unique:users,email"],
                    "firstName"      => ["required", "string"],
                    "lastName"     => ["required", "string"],
                    "phone"      => ["required"],
                    "role" => ['required', "string"],
                    "password"  => ["required"],
                    "confirm_password"  => ['same:password'],

                ]);
            case 'patch':
                return Validator::make($request->all(), [
                    "email"     => ["required", Rule::unique("users", "email")->ignore($user->id)],
                    "firstName"      => ["required", "string"],
                    "lastName"     => ["required", "string"],
                    "phone"      => ["required"],
                    "role" => ['required', "string"],
                ]);
            default:
                return null;
        }
    }
}
