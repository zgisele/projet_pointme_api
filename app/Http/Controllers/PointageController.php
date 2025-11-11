<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pointage;
use Illuminate\Support\Facades\Auth;
use App\Models\qr_tokens;
use Carbon\Carbon;
    

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


/**
 * @OA\Get(
 *     path="/api/listePointages",
 *     summary="Lister les pointages des stagiaires du coach connecté",
 *     description="Cet endpoint permet au coach connecté de visualiser tous les pointages de ses stagiaires (présents, absents, en retard).",
 *     tags={"Coach"},
 *     security={{"bearerAuth":{}}},
 *
 *     @OA\Response(
 *         response=200,
 *         description="Liste des pointages récupérée avec succès",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(
 *                 property="pointages",
 *                 type="array",
 *                 @OA\Items(
 *                     type="object",
 *                     @OA\Property(property="id", type="integer", example=12),
 *                     @OA\Property(property="user_id", type="integer", example=7),
 *                     @OA\Property(property="coach_id", type="integer", example=3),
 *                     @OA\Property(property="statut", type="string", example="présent"),
 *                     @OA\Property(property="heure_arrivee", type="string", format="time", example="08:45:00"),
 *                     @OA\Property(property="heure_sortie", type="string", format="time", example="17:00:00"),
 *                     @OA\Property(property="note", type="string", nullable=true, example="Arrivé légèrement en retard"),
 *                     @OA\Property(property="date_pointage", type="string", format="date", example="2025-10-30"),
 *                     @OA\Property(
 *                         property="stagiaire",
 *                         type="object",
 *                         @OA\Property(property="id", type="integer", example=7),
 *                         @OA\Property(property="first_name", type="string", example="Awa"),
 *                         @OA\Property(property="last_name", type="string", example="Diop"),
 *                         @OA\Property(property="email", type="string", example="awa.diop@example.com")
 *                     )
 *                 )
 *             )
 *         )
 *     ),
 *
 *     @OA\Response(
 *         response=403,
 *         description="Accès non autorisé — l’utilisateur n’est pas un coach",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="message", type="string", example="Accès non autorisé")
 *         )
 *     ),
 *
 *     @OA\Response(
 *         response=401,
 *         description="Non authentifié — le token JWT est manquant ou invalide",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="message", type="string", example="Unauthenticated.")
 *         )
 *     )
 * )
*/

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


// /**
//  * @OA\Post(
//  *     path="/api/pointages/scan",
//  *     tags={"Stagiaire"},
//  *     summary="Valider le scan d'un QR code et notifier le stagiaire",
//  *     description="Cette méthode permet à un stagiaire de scanner un QR code actif pour enregistrer sa présence. 
//  *                  Elle vérifie que le token est valide et que l'utilisateur n'a pas déjà pointé aujourd'hui.
//  *                  Ensuite, elle enregistre le pointage avec la date et l'heure actuelles et renvoie une notification.",
//  *     security={{"bearerAuth":{}}},
//  *     @OA\RequestBody(
//  *         required=true,
//  *         @OA\MediaType(
//  *             mediaType="multipart/form-data",
//  *             @OA\Schema(
//  *                 type="object",
//  *                 required={"token"},
//  *                 @OA\Property(
//  *                     property="token",
//  *                     type="string",
//  *                     description="Le token du QR code fourni par le frontend",
//  *                     example="9j0TnY92tvB7SdUosNPhP5uD3piqfklR"
//  *                 )
//  *             )
//  *         )
//  *     ),
//  *     @OA\Response(
//  *         response=200,
//  *         description="Pointage enregistré et notification envoyée",
//  *         @OA\JsonContent(
//  *             type="object",
//  *             @OA\Property(property="message", type="string", example="Pointage enregistré avec succès"),
//  *             @OA\Property(
//  *                 property="notification",
//  *                 type="object",
//  *                 @OA\Property(property="type", type="string", example="info"),
//  *                 @OA\Property(property="content", type="string", example="Votre pointage du 10/11/2025 a été enregistré.")
//  *             )
//  *         )
//  *     ),
//  *     @OA\Response(
//  *         response=400,
//  *         description="Token invalide/expiré ou déjà pointé aujourd'hui",
//  *         @OA\JsonContent(
//  *             type="object",
//  *             @OA\Property(property="message", type="string", example="Vous avez déjà pointé aujourd'hui")
//  *         )
//  *     ),
//  *     @OA\Response(
//  *         response=404,
//  *         description="Aucun QR code actif trouvé",
//  *         @OA\JsonContent(
//  *             type="object",
//  *             @OA\Property(property="message", type="string", example="Aucun QR code actif trouvé")
//  *         )
//  *     )
//  * )
// */



    public function validerScan(Request $request)
{
    // 1️⃣ Vérifier que le token est fourni
    $request->validate(['token' => 'required|string']);

    // 2️⃣ Rechercher le token en base
    $qr = qr_tokens::where('token', $request->token)
                 ->where('is_active', true)
                 ->first();

    // 3️⃣ Si le token n’existe pas ou est expiré
    if (!$qr || $qr->valid_until->isPast()) {
        return response()->json(['message' => 'Token invalide ou expiré'], 400);
    }

    // 4️⃣ Identifier le stagiaire connecté
    $stagiaireId = auth()->id(); // ou reçu depuis le frontend

    // 5️⃣ Vérifier s’il a déjà pointé aujourd’hui
    $dejaPointe = Pointage::where('user_id', $stagiaireId)
                          ->whereDate('created_at', today())
                          ->exists();

    if ($dejaPointe) {
        return response()->json(['message' => 'Vous avez déjà pointé aujourd’hui'], 400);
    }

    // 6️⃣ Enregistrer le pointage
    Pointage::create([
        'user_id' => $stagiaireId,
        'status' => 'present',
        'timestamp' => now(),
        'date_pointage' => today(), 
    ]);

    return response()->json(['message' => 'Pointage enregistré avec succès']);
}




/**
 * @OA\Post(
 *     path="/api/pointages/scanQr",
 *     summary="Scanner un QR code pour enregistrer le pointage",
 *     description="Permet au stagiaire de scanner un QR code pour enregistrer automatiquement son pointage.
 *                  - Premier scan : enregistre l'heure d'arrivée et le statut (present/retard).
 *                  - Deuxième scan : enregistre l'heure de sortie.
 *                  - Si déjà complet : renvoie un message indiquant que le pointage est terminé.",
 *     operationId="scanQrCode",
 *     tags={"Stagiaire"},
 *     security={{"bearerAuth":{}}},
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\MediaType(
 *             mediaType="multipart/form-data",
 *             @OA\Schema(
 *                 required={"token"},
 *                 @OA\Property(
 *                     property="token",
 *                     type="string",
 *                     description="Le token contenu dans le QR code scanné",
 *                     example="A1B2C3D4E5F6"
 *                 )
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response=201,
 *         description="Pointage d’entrée ou sortie enregistré avec succès",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="Pointage d’entrée enregistré automatiquement ✅"),
 *             @OA\Property(property="statut", type="string", nullable=true, example="present"),
 *             @OA\Property(property="heure_arrivee", type="string", nullable=true, example="08:55"),
 *             @OA\Property(property="heure_sortie", type="string", nullable=true, example="17:00")
 *         )
 *     ),
 *     @OA\Response(
 *         response=400,
 *         description="QR code invalide ou expiré",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="QR code invalide ou expiré")
 *         )
 *     ),
 *     @OA\Response(
 *         response=409,
 *         description="Le stagiaire a déjà effectué son pointage complet",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="Tu as déjà effectué ton pointage complet pour aujourd’hui ✅")
 *         )
 *     )
 * )
*/

public function scanQr(Request $request)
{

    // Vérifie que le token du QR code est présent
    $request->validate([
            'token' => 'required|string',
        ]);

    // Recherche du QR code actif correspondant
        $qr = qr_tokens::where('token', $request->token)
            ->where('is_active', true)
            ->where('valid_until', '>=', now())
            ->first();

        if (!$qr) {
        return response()->json(['message' => 'QR code invalide ou expiré'], 400);
        }

    // 2️⃣ Trouver le stagiaire connecté
        $user = auth()->user();

    // Vérifie s’il a déjà un pointage aujourd’hui
        $pointage = Pointage::where('user_id', $user->id)
            ->whereDate('date_pointage', now()->toDateString())
            ->first();

        $heureActuelle = Carbon::now()->format('H:i');
        $heureLimite = '08:30'; // Heure limite pour être à l'heure

        // 👉 Si aucun pointage aujourd’hui → c’est l’entrée
        if (!$pointage) {
            $statut = $heureActuelle > $heureLimite ? 'retard' : 'present';

            $pointage = Pointage::create([
                'user_id' => $user->id,
                'qr_token_id' => $qr->id,
                'date_pointage' => now()->toDateString(),
                'heure_arrivee' => $heureActuelle,
                'statut' => $statut,
            ]);

            return response()->json([
                'message' => 'Pointage d’entrée enregistré ✅',
                'statut' => $statut,
                'heure_arrivee' => $heureActuelle,
            ]);
        }
        // 👉 Si l’entrée existe mais pas la sortie → on enregistre la sortie
        if (is_null($pointage->heure_sortie)) {
            $pointage->update([
                'heure_sortie' => $heureActuelle,
            ]);

            return response()->json([
                'message' => 'Heure de sortie enregistrée automatiquement ✅',
                'heure_sortie' => $heureActuelle,
            ]);
        }

        // 👉 Si les deux sont déjà enregistrés
        return response()->json([
            'message' => 'Tu as déjà effectué ton pointage complet pour aujourd’hui ✅',
        ]);
}

}
