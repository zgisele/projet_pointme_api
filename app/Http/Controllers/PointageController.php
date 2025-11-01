<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pointage;
use Illuminate\Support\Facades\Auth;
     
class PointageController extends Controller
{
    //
    // /**
    //  * Liste tous les pointages des stagiaires du coach
    //  *
    //  * @authenticated
    //  * @response 200 {
    //  *   "pointages": [
    //  *     {
    //  *       "id": 1,
    //  *       "user_id": 5,
    //  *       "statut": "present",
    //  *       "heure_arrivee": "08:30:00",
    //  *       "heure_sortie": "17:00:00",
    //  *       "note": null,
    //  *       "date": "2025-10-30",
    //  *       "stagiaire": {
    //  *           "id": 5,
    //  *           "first_name": "John",
    //  *           "last_name": "Doe",
    //  *           "email": "john@example.com"
    //  *       }
    //  *     }
    //  *   ]
    //  * }
    //  * @response 403 {"message": "Accès non autorisé"}
    // */


// /**
//  * @OA\Get(
//  *     path="/api/listePointages",
//  *     summary="Lister les pointages des stagiaires du coach connecté",
//  *     description="Cet endpoint permet au coach connecté de visualiser tous les pointages de ses stagiaires (présents, absents, en retard).",
//  *     tags={"Coach"},
//  *     security={{"bearerAuth":{}}},
//  *
//  *     @OA\Response(
//  *         response=200,
//  *         description="Liste des pointages récupérée avec succès",
//  *         @OA\JsonContent(
//  *             type="object",
//  *             @OA\Property(
//  *                 property="pointages",
//  *                 type="array",
//  *                 @OA\Items(
//  *                     type="object",
//  *                     @OA\Property(property="id", type="integer", example=12),
//  *                     @OA\Property(property="user_id", type="integer", example=7),
//  *                     @OA\Property(property="coach_id", type="integer", example=3),
//  *                     @OA\Property(property="statut", type="string", example="présent"),
//  *                     @OA\Property(property="heure_arrivee", type="string", format="time", example="08:45:00"),
//  *                     @OA\Property(property="heure_sortie", type="string", format="time", example="17:00:00"),
//  *                     @OA\Property(property="note", type="string", nullable=true, example="Arrivé légèrement en retard"),
//  *                     @OA\Property(property="date_pointage", type="string", format="date", example="2025-10-30"),
//  *                     @OA\Property(
//  *                         property="stagiaire",
//  *                         type="object",
//  *                         @OA\Property(property="id", type="integer", example=7),
//  *                         @OA\Property(property="first_name", type="string", example="Awa"),
//  *                         @OA\Property(property="last_name", type="string", example="Diop"),
//  *                         @OA\Property(property="email", type="string", example="awa.diop@example.com")
//  *                     )
//  *                 )
//  *             )
//  *         )
//  *     ),
//  *
//  *     @OA\Response(
//  *         response=403,
//  *         description="Accès non autorisé — l’utilisateur n’est pas un coach",
//  *         @OA\JsonContent(
//  *             type="object",
//  *             @OA\Property(property="message", type="string", example="Accès non autorisé")
//  *         )
//  *     ),
//  *
//  *     @OA\Response(
//  *         response=401,
//  *         description="Non authentifié — le token JWT est manquant ou invalide",
//  *         @OA\JsonContent(
//  *             type="object",
//  *             @OA\Property(property="message", type="string", example="Unauthenticated.")
//  *         )
//  *     )
//  * )
// */

     // GET /api/pointages
    public function listePointages()
    {
        $coach = auth()->user();

        if ($coach->role != 'coache') {
            return response()->json(['message' => 'Accès non autorisé'], 403);
        }

        $pointages = Pointage::where('coach_id', $coach->id)
            ->with('stagiaire:id,first_name,last_name,email')
            ->get();

        return response()->json(['pointages' => $pointages], 200);
    }

    // /**
    //  * Liste les pointages d'une journée spécifique
    //  *
    //  * @queryParam date string required Date au format YYYY-MM-DD. Example: 2025-10-30
    //  * @authenticated
    //  * @response 200 {
    //  *   "pointages": [
    //  *     {
    //  *       "id": 1,
    //  *       "user_id": 5,
    //  *       "statut": "present",
    //  *       "heure_arrivee": "08:30:00",
    //  *       "heure_sortie": "17:00:00",
    //  *       "note": null,
    //  *       "date": "2025-10-30",
    //  *       "stagiaire": {
    //  *           "id": 5,
    //  *           "first_name": "John",
    //  *           "last_name": "Doe",
    //  *           "email": "john@example.com"
    //  *       }
    //  *     }
    //  *   ]
    //  * }
    //  * @response 400 {"message": "Paramètre date requis"}
    // */


// /**
//  * @OA\Get(
//  *     path="/api/pointages/daily",
//  *     summary="Lister les pointages d'une journée spécifique",
//  *     description="Cet endpoint permet au coach connecté de consulter les présences, absences et retards de ses stagiaires pour une date donnée.",
//  *     tags={"Coach"},
//  *     security={{"bearerAuth":{}}},
//  *
//  *     @OA\Parameter(
//  *         name="date",
//  *         in="query",
//  *         required=true,
//  *         description="Date des pointages à consulter (format YYYY-MM-DD)",
//  *         @OA\Schema(type="string", format="date", example="2025-10-30")
//  *     ),
//  *
//  *     @OA\Response(
//  *         response=200,
//  *         description="Liste des pointages du jour récupérée avec succès",
//  *         @OA\JsonContent(
//  *             type="object",
//  *             @OA\Property(
//  *                 property="pointages",
//  *                 type="array",
//  *                 @OA\Items(
//  *                     type="object",
//  *                     @OA\Property(property="id", type="integer", example=8),
//  *                     @OA\Property(property="user_id", type="integer", example=15),
//  *                     @OA\Property(property="coach_id", type="integer", example=3),
//  *                     @OA\Property(property="statut", type="string", example="retard"),
//  *                     @OA\Property(property="heure_arrivee", type="string", format="time", example="09:10:00"),
//  *                     @OA\Property(property="heure_sortie", type="string", format="time", example="17:00:00"),
//  *                     @OA\Property(property="note", type="string", nullable=true, example="Arrivée tardive à cause du transport"),
//  *                     @OA\Property(property="date_pointage", type="string", format="date", example="2025-10-30"),
//  *                     @OA\Property(
//  *                         property="stagiaire",
//  *                         type="object",
//  *                         @OA\Property(property="id", type="integer", example=15),
//  *                         @OA\Property(property="first_name", type="string", example="Moussa"),
//  *                         @OA\Property(property="last_name", type="string", example="Ba"),
//  *                         @OA\Property(property="email", type="string", example="moussa.ba@example.com")
//  *                     )
//  *                 )
//  *             )
//  *         )
//  *     ),
//  *
//  *     @OA\Response(
//  *         response=400,
//  *         description="Paramètre date manquant",
//  *         @OA\JsonContent(
//  *             type="object",
//  *             @OA\Property(property="message", type="string", example="Paramètre date requis")
//  *         )
//  *     ),
//  *
//  *     @OA\Response(
//  *         response=403,
//  *         description="Accès non autorisé — l’utilisateur n’est pas un coach",
//  *         @OA\JsonContent(
//  *             type="object",
//  *             @OA\Property(property="message", type="string", example="Accès non autorisé")
//  *         )
//  *     ),
//  *
//  *     @OA\Response(
//  *         response=401,
//  *         description="Non authentifié — token JWT invalide ou manquant",
//  *         @OA\JsonContent(
//  *             type="object",
//  *             @OA\Property(property="message", type="string", example="Unauthenticated.")
//  *         )
//  *     )
//  * )
// */

    
    // GET /api/pointages/daily?date=YYYY-MM-DD
    public function daily(Request $request)
    {
        $coach = auth()->user();
        $date = $request->query('date');

        if (!$date) {
            return response()->json(['message' => 'Paramètre date requis'], 400);
        }

        $pointages = Pointage::where('coach_id', $coach->id)
            ->where('date', $date)
            ->with('stagiaire:id,first_name,last_name,email')
            ->get();

        return response()->json(['pointages' => $pointages], 200);
    }
    
    // /**
    //  * Met à jour un pointage spécifique
    //  *
    //  * @urlParam id int required ID du pointage. Example: 1
    //  * @bodyParam heure_arrivee string Heure d'arrivée. Example: "08:30:00"
    //  * @bodyParam statut string Statut du pointage. Example: "present"
    //  * @bodyParam note string Note du coach. Example: "Arrivé à l'heure"
    //  * @authenticated
    //  * @response 200 {
    //  *   "message": "Pointage mis à jour",
    //  *   "pointage": {
    //  *       "id": 1,
    //  *       "user_id": 5,
    //  *       "statut": "present",
    //  *       "heure_arrivee": "08:30:00",
    //  *       "note": "Arrivé à l'heure",
    //  *       "date": "2025-10-30"
    //  *   }
    //  * }
    //  * @response 403 {"message": "Accès non autorisé"}
    //  * @response 404 {"message": "Pointage non trouvé"}
    // */
// /**
//  * @OA\Put(
//  *     path="/api/pointages/{id}",
//  *     summary="Mettre à jour un pointage",
//  *     description="Permet au coach de corriger ou valider un pointage (heure d’arrivée, statut, note).",
//  *     tags={"Coach"},
//  *     security={{"bearerAuth":{}}},
//  *
//  *     @OA\Parameter(
//  *         name="id",
//  *         in="path",
//  *         required=true,
//  *         description="Identifiant du pointage à modifier",
//  *         @OA\Schema(type="integer", example=12)
//  *     ),
//  *
//  *     @OA\RequestBody(
//  *         required=true,
//  *         description="Données à mettre à jour dans le pointage",
//  *         @OA\JsonContent(
//  *             type="object",
//  *             @OA\Property(property="heure_arrivee", type="string", format="time", example="08:45:00", description="Heure d’arrivée du stagiaire"),
//  *             @OA\Property(property="statut", type="string", example="présent", description="Statut du stagiaire (présent, absent, retard)"),
//  *             @OA\Property(property="note", type="string", nullable=true, example="Arrivée en retard mais justifiée")
//  *         )
//  *     ),
//  *
//  *     @OA\Response(
//  *         response=200,
//  *         description="Pointage mis à jour avec succès",
//  *         @OA\JsonContent(
//  *             type="object",
//  *             @OA\Property(property="message", type="string", example="Pointage mis à jour"),
//  *             @OA\Property(
//  *                 property="pointage",
//  *                 type="object",
//  *                 @OA\Property(property="id", type="integer", example=12),
//  *                 @OA\Property(property="user_id", type="integer", example=7),
//  *                 @OA\Property(property="coach_id", type="integer", example=3),
//  *                 @OA\Property(property="statut", type="string", example="présent"),
//  *                 @OA\Property(property="heure_arrivee", type="string", example="08:45:00"),
//  *                 @OA\Property(property="note", type="string", example="Correction de l’heure d’arrivée"),
//  *                 @OA\Property(property="date_pointage", type="string", format="date", example="2025-10-30")
//  *             )
//  *         )
//  *     ),
//  *
//  *     @OA\Response(
//  *         response=404,
//  *         description="Pointage introuvable",
//  *         @OA\JsonContent(
//  *             type="object",
//  *             @OA\Property(property="message", type="string", example="Pointage non trouvé")
//  *         )
//  *     ),
//  *
//  *     @OA\Response(
//  *         response=403,
//  *         description="Accès non autorisé — le coach n’est pas propriétaire du pointage",
//  *         @OA\JsonContent(
//  *             type="object",
//  *             @OA\Property(property="message", type="string", example="Accès non autorisé")
//  *         )
//  *     ),
//  *
//  *     @OA\Response(
//  *         response=401,
//  *         description="Non authentifié — token JWT invalide ou manquant",
//  *         @OA\JsonContent(
//  *             type="object",
//  *             @OA\Property(property="message", type="string", example="Unauthenticated.")
//  *         )
//  *     )
//  * )
// */

    // PUT /api/pointages/{id}
    public function updatePointages(Request $request, $id)
    {
        $coach = auth()->user();

        $pointage = Pointage::find($id);

        if (!$pointage) {
            return response()->json(['message' => 'Pointage non trouvé'], 404);
        }

        if ($pointage->coach_id != $coach->id) {
            return response()->json(['message' => 'Accès non autorisé'], 403);
        }

        $pointage->update($request->only(['heure_arrivee', 'statut', 'note']));

        return response()->json(['message' => 'Pointage mis à jour', 'pointage' => $pointage], 200);
    }
}
