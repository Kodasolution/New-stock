<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use SDamian\LaravelManPagination\Pagination;

class ClientsController extends Controller
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
            "name"        => ["nullable", "string"]
        ])->validated();
        // dd($data);
        $clients = Client::query();
        if (array_key_exists('name',$data)) {
            $clients->orWhere(DB::raw("concat(firstName, ' ',lastName)"), 'LIKE', "%" . $data['name'] . "%")
                        ->orWhere(DB::raw("concat(lastName, ' ',firstName)"), 'LIKE', "%" . $data['name'] . "%")
                        ->orWhere("email", 'LIKE', "%" . $data['name'] . "%");
        }
        $total = $clients->count();
        $pagination = new Pagination(['options_select' => config('man-pagination.options_select')]);
        $pagination->paginate($total);
        $limit = $pagination->limit();
        $offset = $pagination->offset();
        $clients = $clients->skip($offset)->take($limit)->orderBy("id", "desc")->paginate($limit);
        // dd()
        // $clients = Client::orderBy('id', 'desc')->paginate(10);
        return view('content.clients.list', compact('clients','pagination'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('content.clients.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'firstName' => 'required',
            'lastName' => 'required',
            'phone' => 'nullable',
            'email' => 'nullable',
            'adress' => 'nullable',
        ]);
        // dd($data);
        $clients = new Client([
            'firstName' => $request->input('firstName'),
            'lastName' => $request->input('lastName'),
            'phone' => $request->input('phone'),
            'email' => $request->input('email'),
            'adress' => $request->input('adress'),
        ]);
        $clients->save();

        return redirect()->route('user.client.index')->with('success', 'Client créé avec succès.');
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
        $clients = Client::findOrFail($id);
        return view('content.clients.edit', compact('clients'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $data = $request->validate([
            "firstName" => "required",
            "lastName" => "required",
            "phone" => "nullable",
            "adress" => "nullable",
            "email" => "nullable",
        ]);

        DB::beginTransaction();
        try {
            $clients = Client::findOrFail($id);

            $clients->update([
                "firstName" => $request->firstName,
                "lastName" => $request->lastName,
                "phone" => $request->phone,
                "email" => $request->email,
                "adress" => $request->adress,
            ]);

            DB::commit();
            return redirect()->route('user.client.index')->with('success', 'Client updated successfully');
        } catch (\Throwable $e) {
            DB::rollBack();
            dd(
                $e->getMessage(),
                $e->getLine(),
                $e->getFile()
            );
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        DB::beginTransaction();

        try {
            $clients = Client::findOrFail($id);

            $clients->delete();

            DB::commit();

            return redirect()->route('user.client.index')->with('success', 'Client deleted successfully');
        } catch (\Throwable $e) {
            DB::rollBack();
            dd(
                $e->getMessage(),
                $e->getLine(),
                $e->getFile()
            );
        }
    }
}
