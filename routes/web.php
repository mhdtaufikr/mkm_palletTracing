<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\DropdownController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RulesController;
use App\Http\Controllers\PalletController;

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

//Login Controller
Route::get('/', [AuthController::class, 'login'])->name('login');
Route::post('/auth/login', [AuthController::class, 'postLogin']);
Route::get('/logout', [AuthController::class, 'logout']);


// Add this route for the Ajax request
Route::get('/getNoPallets/{destination}',  [PalletController::class, 'getNoPallets']);
// Update the route for the Ajax request to fetch all no_pallet values
Route::get('/getAllNoPallets/{destination}',  [PalletController::class, 'getAllNoPallets']);




Route::middleware(['auth'])->group(function () {
    //Home Controller
    Route::get('/home', [HomeController::class, 'index']);

    //Pallet Controller
    Route::get('/pallet', [PalletController::class, 'index'])->name('pallet.index');
    Route::post('/pallet/store', [PalletController::class, 'store'])->middleware(['checkRole:Super Admin,IT']);
    Route::patch('/pallet/update/{id}', [PalletController::class, 'update'])->middleware(['checkRole:Super Admin,IT']);
    Route::delete('/pallet/delete/{id}', [PalletController::class, 'delete'])->middleware(['checkRole:Super Admin,IT']);
    Route::get('/pallet/download/format', [PalletController::class, 'excelFormat'])->middleware(['checkRole:Super Admin,IT']);
    Route::post('/pallet/import', [PalletController::class, 'excelData'])->middleware(['checkRole:Super Admin,IT']);
    Route::post('/pallet/search', [PalletController::class, 'palletSearch'])->middleware(['checkRole:Super Admin,IT']);

     //Dropdown Controller
     Route::get('/dropdown', [DropdownController::class, 'index'])->middleware(['checkRole:IT']);
     Route::post('/dropdown/store', [DropdownController::class, 'store'])->middleware(['checkRole:IT']);
     Route::patch('/dropdown/update/{id}', [DropdownController::class, 'update'])->middleware(['checkRole:IT']);
     Route::delete('/dropdown/delete/{id}', [DropdownController::class, 'delete'])->middleware(['checkRole:IT']);
 
     //Rules Controller
     Route::get('/rule', [RulesController::class, 'index'])->middleware(['checkRole:IT']);
     Route::post('/rule/store', [RulesController::class, 'store'])->middleware(['checkRole:IT']);
     Route::patch('/rule/update/{id}', [RulesController::class, 'update'])->middleware(['checkRole:IT']);
     Route::delete('/rule/delete/{id}', [RulesController::class, 'delete'])->middleware(['checkRole:IT']);

    //User Controller
    Route::get('/user', [UserController::class, 'index'])->middleware(['checkRole:IT']);
    Route::post('/user/store', [UserController::class, 'store'])->middleware(['checkRole:IT']);
    Route::post('/user/store-partner', [UserController::class, 'storePartner'])->middleware(['checkRole:IT']);
    Route::patch('/user/update/{user}', [UserController::class, 'update'])->middleware(['checkRole:IT']);
    Route::get('/user/revoke/{user}', [UserController::class, 'revoke'])->middleware(['checkRole:IT']);
    Route::get('/user/access/{user}', [UserController::class, 'access'])->middleware(['checkRole:IT']);
});
