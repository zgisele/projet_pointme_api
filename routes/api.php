<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });


use App\Http\Controllers\AuthController;

Route::post('register/admin', [AuthController::class, 'registerAdmin']);
// Route::post('register/coache', [AuthController::class, 'registerCoache']);
// Route::post('register/stagiaire', [AuthController::class, 'registerStagiaire']);

Route::post('login', [AuthController::class, 'login']);

Route::middleware('auth:api')->group(function () {
    Route::get('me', [AuthController::class, 'me']);
    Route::post('logout', [AuthController::class, 'logout']);
    Route::post('register/coache', [AuthController::class, 'registerCoache']);
    Route::post('register/stagiaire', [AuthController::class, 'registerStagiaire']);
});
