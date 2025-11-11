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
use App\Http\Controllers\QRTokenController;
use App\Http\Controllers\PointageController;

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
    // Liste tous les stagiaires d’un coach
    Route::get('coaches/{id}/stagiaires', [Controller::class, 'getStagiaires']);

    // Affiche un stagiaire spécifique
    Route::get('stagiaires/{id}', [Controller::class, 'showStagiaire']);

    // Affiche le profil du coach connecté
    Route::get('profileCoach', [AuthController::class, 'profileCoach']);
    
    // Mise  à jour du profil coach
    // Route::put('UpdateCoach/{id}', [AuthController::class, 'UpdateCoach']);
    Route::post('update-coach/{id}', [AuthController::class, 'updateCoach']);

    Route::get('/qr-code', [ QRTokenController::class, 'afficherQrCode']);

    // Route::post('/pointages/scan', [PointageController::class, 'validerScan']);

    Route::post('/qr-tokens', [QRTokenController::class, 'generate']);

    // Route::put('coachs/{id}', [Controller::class, 'updateCoach']);

    // Visualiser tous les pointages du coach
 // decommenter Route::get('listePointages', [PointageController::class, 'listePointages']);

    // Visualiser les pointages d'une journée donnée
    //Route::get('pointages/daily', [PointageController::class, 'daily']);

    // Corriger ou valider un pointage
   // Route::put('pointages/{id}', [PointageController::class, 'updatePointages']);
    // Route::post('logoutCoache', [AuthController::class, 'logoutCoache']);
    // Route::get('/coach/dashboard', [CoachController::class, 'index']);
    // Route::post('/coach/stagiaires', [CoachController::class, 'store']);
});

Route::middleware(['jwt.auth', 'stagiaire'])->group(function () {
     Route::get('profileStagiaire', [AuthController::class, 'profileStagiaire']);
     Route::post('update-stagiaire/{id}', [AuthController::class, 'updateStagiaire']);

     Route::get('/QrCodeStagiaire', [QRTokenController::class, 'afficherQrCodeStagiaire']);
     Route::post('/pointages/scan', [PointageController::class, 'validerScan']);
     Route::post('/pointages/scanQr', [PointageController::class, 'scanQr']);
    //  Route::post('logoutStagiaire', [AuthController::class, 'logoutStagiaire']);
   // Route::get('/stagiaire/cours', [StagiaireController::class, 'index']);
});