<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    /**
     * @OA\Post(
     *     path="api/register/admin",
     *     tags={"Authentification"},
     *     summary="Enregistrer un administrateur",
     *     description="Création d'un administrateur avec photo",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 required={"first_name","last_name","email","password","photo"},
     *                 @OA\Property(property="first_name", type="string", example="John"),
     *                 @OA\Property(property="last_name", type="string", example="Doe"),
     *                 @OA\Property(property="email", type="string", format="email", example="admin@example.com"),
     *                 @OA\Property(property="password", type="string", format="password", example="secret123"),
     *                 @OA\Property(property="photo", type="string", format="binary", description="Photo de profil au format jpg/png/jpeg")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Admin enregistré avec succès",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Admin enregistré avec succès"),
     *             @OA\Property(property="user", type="object",
     *                 @OA\Property(property="id", type="integer"),
     *                 @OA\Property(property="first_name", type="string"),
     *                 @OA\Property(property="last_name", type="string"),
     *                 @OA\Property(property="email", type="string"),
     *                 @OA\Property(property="photo", type="string")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation échouée",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Validation échouée"),
     *             @OA\Property(property="errors", type="object")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Erreur serveur",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Erreur serveur"),
     *             @OA\Property(property="error", type="string")
     *         )
     *     )
     * )
    */

}
