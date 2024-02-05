<?php

use App\Http\Controllers\CaisseController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ClientsController;
use App\Http\Controllers\CommandeController;
use App\Http\Controllers\CreditClientsController;
use App\Http\Controllers\DetteController;
use App\Http\Controllers\ExpensesController;
use App\Http\Controllers\FournisseurController;
use App\Http\Controllers\IngredientController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\laravel_example\UserManagement;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\ModuleController;
use App\Http\Controllers\MouvementController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\RapportController;
use App\Http\Controllers\RemboursementDetteController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SalaireController;
use App\Http\Controllers\VersementController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

$controller_path = 'App\Http\Controllers';


Route::get('/', [LoginController::class, 'publiqPage'])->name('website.home');
//Login
Route::get('/login', [LoginController::class, 'login'])->name('admin.login');
Route::post('/login', [LoginController::class, 'loginPost'])->name('admin.login.store');
Route::group(["prefix" => "hejurucane", 'middleware' => 'auth'], function () {
    // Route::group(["prefix" => "hejurucane"], function () {
    //home
    Route::get('/', [LoginController::class, 'home'])->name('admin.dashboard');
    //Logout
    Route::post('/logout', [LoginController::class, 'logout'])->name('admin.logout');
    //Users
    Route::resource('/user', UserController::class);
    //Change password
    Route::post('/user/change-password/{user}', [UserController::class, 'change_password'])->name('user.change-password');

    Route::resource('/category', CategoryController::class);
    Route::resource('/product', ProductController::class);
    Route::resource('/provider', FournisseurController::class);
    Route::resource('/purchase', CommandeController::class);

    Route::patch('/inventory/return/{id}', [MouvementController::class, 'updateReturn'])->name('inventory.updateReturn');
    Route::patch('/inventory/output/{id}', [MouvementController::class, 'updateOutput'])->name('inventory.updateOutput');
    Route::get('/inventory/edit-return/{id}', [MouvementController::class, 'editReturn'])->name('inventory.editReturn');
    Route::get('/inventory/edit-output/{id}', [MouvementController::class, 'editOutput'])->name('inventory.editOutput');
    Route::get('/inventory/sorties', [MouvementController::class, 'indexOutput'])->name('inventory.sorties');
    Route::get('/inventory/return', [MouvementController::class, 'indexReturn'])->name('inventory.return');
    Route::get('/inventory/output', [MouvementController::class, 'output'])->name('inventory.output');
    Route::post('/inventory/storeOutput', [MouvementController::class, 'storeOutput'])->name('inventory.storeOutput');
    Route::get('/inventory/retour', [MouvementController::class, 'retour'])->name('inventory.retour');
    Route::post('/inventory/storeRetour', [MouvementController::class, 'storeRetour'])->name('inventory.storeRetour');
    Route::resource('/inventory', MouvementController::class);
    Route::resource('/ingredient', IngredientController::class, ['as' => 'product']);

    Route::delete('/expenses/{id}/destroy2', [ExpensesController::class, 'destroy2'])->name('expenses.destroy2');
    Route::patch('/expenses/{id}/update2', [ExpensesController::class, 'update2'])->name('expenses.update2');
    Route::get('/expenses/{id}/edit2', [ExpensesController::class, 'editDepenseIngredient'])->name('expenses.editDepenseIngredient');
    Route::get('/expenses/list', [ExpensesController::class, 'list'])->name('expenses.list');
    Route::get('/expenses/add', [ExpensesController::class, 'add'])->name('expenses.add');
    Route::post('/expenses/save', [ExpensesController::class, 'save'])->name('expenses.save');
    Route::resource('/expenses', ExpensesController::class);
    Route::resource('/client', ClientsController::class, [
        'as' => 'user'
    ]);
    Route::resource('/client-credit', CreditClientsController::class);
    Route::resource('/salaire', SalaireController::class, [
        'as' => 'user'
    ]);
    Route::resource('/caisse', CaisseController::class);
    Route::resource('/dette', DetteController::class, ['as' => 'expenses']);
    Route::resource('/versement', VersementController::class, ['as' => 'expenses']);
    Route::resource('/remboursement', RemboursementDetteController::class, ['as' => 'expenses']);
    Route::resource('/role', RoleController::class, ['as' => 'user']);
    Route::resource('/module', ModuleController::class, ['as' => 'user']);
    Route::resource('/permission', PermissionController::class, ['as' => 'user']);
    Route::resource('/rapport', RapportController::class);
});
// pages
