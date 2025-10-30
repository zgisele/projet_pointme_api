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
use App\Http\Controllers\Controller;

Route::post('register/admin', [AuthController::class, 'registerAdmin']);
// Route::post('register/coache', [AuthController::class, 'registerCoache']);
// Route::post('register/stagiaire', [AuthController::class, 'registerStagiaire']);

Route::post('login', [AuthController::class, 'login']);
// Route::get('me', [AuthController::class, 'me']);
Route::post('logout', [AuthController::class, 'logout']);

Route::middleware('auth:api')->group(function () {
    Route::post('register/coache', [AuthController::class, 'registerCoache']);
    Route::post('register/stagiaire', [AuthController::class, 'registerStagiaire']);
    // Route::post('logout', [AuthController::class, 'logout']);

});


Route::middleware(['jwt.auth', 'coache'])->group(function () {
    Route::get('coaches/{id}/stagiaires', [Controller::class, 'getStagiaires']);
    Route::get('stagiaires/{id}', [Controller::class, 'showStagiaire']);
    Route::get('profileCoach', [AuthController::class, 'profileCoach']);
    // Route::post('logoutCoache', [AuthController::class, 'logoutCoache']);
    // Route::get('/coach/dashboard', [CoachController::class, 'index']);
    // Route::post('/coach/stagiaires', [CoachController::class, 'store']);
});

Route::middleware(['jwt.auth', 'stagiaire'])->group(function () {
     Route::get('profileStagiaire', [AuthController::class, 'profileStagiaire']);
    //  Route::post('logoutStagiaire', [AuthController::class, 'logoutStagiaire']);
   // Route::get('/stagiaire/cours', [StagiaireController::class, 'index']);
});