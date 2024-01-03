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

Route::middleware(['auth'])->group(function () {
    //Home Controller
    Route::get('/home', [HomeController::class, 'index']);

    //Pallet Controller
    Route::get('/pallet', [PalletController::class, 'index'])->name('pallet.index');
    Route::post('/pallet/store', [PalletController::class, 'store'])->middleware(['checkRole:Super Admin']);
    Route::patch('/pallet/update/{id}', [PalletController::class, 'update'])->middleware(['checkRole:Super Admin']);
    Route::delete('/pallet/delete/{id}', [PalletController::class, 'delete'])->middleware(['checkRole:Super Admin']);
    Route::get('/pallet/download/format', [PalletController::class, 'excelFormat'])->middleware(['checkRole:Super Admin']);
    Route::post('/pallet/import', [PalletController::class, 'excelData'])->middleware(['checkRole:Super Admin']);
    Route::post('/pallet/search', [PalletController::class, 'palletSearch'])->middleware(['checkRole:Super Admin']);

     //Dropdown Controller
     Route::get('/dropdown', [DropdownController::class, 'index'])->middleware(['checkRole:Super Admin']);
     Route::post('/dropdown/store', [DropdownController::class, 'store'])->middleware(['checkRole:Super Admin']);
     Route::patch('/dropdown/update/{id}', [DropdownController::class, 'update'])->middleware(['checkRole:Super Admin']);
     Route::delete('/dropdown/delete/{id}', [DropdownController::class, 'delete'])->middleware(['checkRole:Super Admin']);
 
     //Rules Controller
     Route::get('/rule', [RulesController::class, 'index'])->middleware(['checkRole:Super Admin']);
     Route::post('/rule/store', [RulesController::class, 'store'])->middleware(['checkRole:Super Admin']);
     Route::patch('/rule/update/{id}', [RulesController::class, 'update'])->middleware(['checkRole:Super Admin']);
     Route::delete('/rule/delete/{id}', [RulesController::class, 'delete'])->middleware(['checkRole:Super Admin']);

    //User Controller
    Route::get('/user', [UserController::class, 'index'])->middleware(['checkRole:Super Admin']);
    Route::post('/user/store', [UserController::class, 'store'])->middleware(['checkRole:Super Admin']);
    Route::post('/user/store-partner', [UserController::class, 'storePartner'])->middleware(['checkRole:Super Admin']);
    Route::patch('/user/update/{user}', [UserController::class, 'update'])->middleware(['checkRole:Super Admin']);
    Route::get('/user/revoke/{user}', [UserController::class, 'revoke'])->middleware(['checkRole:Super Admin']);
    Route::get('/user/access/{user}', [UserController::class, 'access'])->middleware(['checkRole:Super Admin']);
});
