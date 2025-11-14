<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\User;


use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;


/**
 * @OA\Get(
 *     path="/api/coaches/{id}/stagiaires",
 *     summary="Récupérer la liste des stagiaires d’un coach",
 *     description="Retourne la liste des stagiaires associés à un coach spécifique. Accessible uniquement aux utilisateurs ayant le rôle 'coache'.",
 *     tags={"Coach"},
 *     security={{"bearerAuth": {}}},
 *
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         description="Identifiant du coach connecté",
 *         @OA\Schema(type="integer", example=5)
 *     ),
 *
 *     @OA\Response(
 *         response=200,
 *         description="Liste des stagiaires récupérée avec succès",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(
 *                 property="stagiaires",
 *                 type="array",
 *                 @OA\Items(
 *                     type="object",
 *                     @OA\Property(property="id", type="integer", example=10),
 *                     @OA\Property(property="first_name", type="string", example="Jean"),
 *                     @OA\Property(property="last_name", type="string", example="Dupont"),
 *                     @OA\Property(property="email", type="string", example="jean.dupont@example.com")
 *                 )
 *             )
 *         )
 *     ),
 *
 *     @OA\Response(
 *         response=403,
 *         description="Accès non autorisé (l'utilisateur n'est pas le coach concerné ou n'a pas le bon rôle)",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="Accès non autorisé.")
 *         )
 *     ),
 *
 *     @OA\Response(
 *         response=401,
 *         description="Token invalide ou expiré",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="Token invalide ou expiré")
 *         )
 *     )
 * )
*/


    //     public function getStagiaires(Request $request, $id)
    // {
    //     $user = auth()->user();

    //     if ($user->id != $id || $user->role != 'coache') {
    //         return response()->json(['message' => 'Accès non autorisé2.'], 403);
    //     }

    //     $stagiaires = $user->stagiaires()->get(['id', 'first_name', 'last_name', 'email']);

    //     return response()->json(['stagiaires' => $stagiaires], 200);
    // }

    public function getStagiaires(Request $request, $id)
{
    $user = auth()->user();

    // Vérification d'accès
    if ($user->id != $id || $user->role !== 'coache') {
        return response()->json(['message' => 'Accès non autorisé.'], 403);
    }

    // 👉 Récupération des stagiaires via la table pivot coach_stagiaire
    $stagiaireIds = DB::table('coach_stagiaire')
        ->where('coach_id', $user->id)
        ->pluck('stagiaire_id');

    if ($stagiaireIds->isEmpty()) {
        return response()->json(['stagiaires' => []], 200);
    }

    // 👉 Récupération des informations des stagiaires
    $stagiaires = User::whereIn('id', $stagiaireIds)
        ->get(['id', 'first_name', 'last_name', 'email']);

    return response()->json(['stagiaires' => $stagiaires], 200);
}



/**
 * @OA\Get(
 *     path="/api/stagiaires/{id}",
 *     summary="Consulter le profil détaillé d’un stagiaire",
 *     description="Permet à un coach authentifié de consulter les informations détaillées d’un stagiaire spécifique.",
 *     tags={"Coach"},
 *     security={{"bearerAuth": {}}},
 *
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         description="Identifiant du stagiaire à consulter",
 *         @OA\Schema(type="integer", example=8)
 *     ),
 *
 *     @OA\Response(
 *         response=200,
 *         description="Profil détaillé du stagiaire récupéré avec succès",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="id", type="integer", example=8),
 *             @OA\Property(property="first_name", type="string", example="Aminata"),
 *             @OA\Property(property="last_name", type="string", example="Sow"),
 *             @OA\Property(property="email", type="string", example="aminata.sow@example.com"),
 *             @OA\Property(property="phone", type="string", example="+221772233445"),
 *             @OA\Property(property="formation", type="string", example="Développement web"),
 *             @OA\Property(property="coach_id", type="integer", example=3),
 *             @OA\Property(property="created_at", type="string", format="date-time", example="2025-10-28T09:00:00Z")
 *         )
 *     ),
 *
 *     @OA\Response(
 *         response=403,
 *         description="Accès non autorisé (l’utilisateur n’est pas un coach ou n’a pas accès à ce stagiaire)",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="Accès non autorisé.")
 *         )
 *     ),
 *
 *     @OA\Response(
 *         response=404,
 *         description="Stagiaire non trouvé",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="Stagiaire introuvable.")
 *         )
 *     ),
 *
 *     @OA\Response(
 *         response=401,
 *         description="Token invalide ou expiré",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="Token invalide ou expiré")
 *         )
 *     )
 * )
*/

//     public function showStagiaire(Request $request, $id)
// {
//     $user = auth()->user();

//     if (!$user || $user->role !== 'coache') {
//         return response()->json(['message' => 'Accès non autorisé.'], 403);
//     }

//     $stagiaire = \App\Models\User::where('id', $id)
//         ->where('coach_id', $user->id)
//         ->where('role', 'stagiaire')
//         ->first();

//     if (!$stagiaire) {
//         return response()->json(['message' => 'Stagiaire introuvable.'], 404);
//     }

//     return response()->json($stagiaire, 200);
// }
public function showStagiaire(Request $request, $id)
{
    $user = auth()->user();

    // Vérification d'accès
    if (!$user || $user->role !== 'coache') {
        return response()->json(['message' => 'Accès non autorisé.'], 403);
    }

    // 👉 Vérifier dans la table pivot que ce stagiaire appartient à ce coach
    $isLinked = DB::table('coach_stagiaire')
        ->where('coach_id', $user->id)
        ->where('stagiaire_id', $id)
        ->exists();

    if (!$isLinked) {
        return response()->json(['message' => 'Stagiaire introuvable ou non attribué à ce coach.'], 404);
    }

    // 👉 Récupérer les informations du stagiaire
    $stagiaire = User::where('id', $id)
        ->where('role', 'stagiaire')
        ->first();

    if (!$stagiaire) {
        return response()->json(['message' => 'Stagiaire introuvable.'], 404);
    }

    return response()->json($stagiaire, 200);
}


}
