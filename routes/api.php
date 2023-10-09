<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\IncomesController;
use App\Http\Controllers\ExpensesController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

//rotte pubbliche
Route::post('/register', [UserController::class, 'register']);
Route::post('/login', [UserController::class, 'login']);
// Route::get('/csrf-token', function () {
//     return response()->json(['csrf_token' => csrf_token()]);
// });



//rotte protette
Route::group(['middleware' => ['auth:sanctum']], function () {

    Route::post('/logout', [UserController::class, 'logout']);

    //api per le spese
    Route::post('/expenses', [ExpensesController::class, 'store']);
    Route::delete('expenses/{id}', [ExpensesController::class, 'destroy']);
    Route::put('expenses/{id}', [ExpensesController::class, 'update']);
    Route::get('/expenses', [ExpensesController::class, 'index']);


    Route::get('/expenses/{id}', [ExpensesController::class, 'show']);
    Route::get('/user', [UserController::class, 'getUserByToken']);

    // //api per i guadagni 
    // Route::get('/incomes/total', [IncomesController::class, 'total']);
    // Route::post('/incomes', [IncomesController::class, 'store']);
    // Route::delete('/incomes/{id}', [IncomesController::class, 'destroy']);
    // Route::put('/incomes/{id}', [IncomesController::class, 'update']);
    // Route::get('/incomes', [IncomesController::class, 'index']);
    // Route::get('/incomes/{id}', [IncomesController::class, 'show']);
});


Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
