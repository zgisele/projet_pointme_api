<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class QrTokensController extends Controller
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
//  *     path="/api/coach/stagiaires/presences",
//  *     summary="Consulter les présences des stagiaires d'un coach pour une journée donnée",
//  *     description="Permet au coach de consulter toutes les présences, retards et absences de ses stagiaires pour une journée spécifique.",
//  *     operationId="getStagiairesPresences",
//  *     tags={"Coach"},
//  *     security={{"bearerAuth":{}}},
//  *     @OA\Parameter(
//  *         name="date",
//  *         in="query",
//  *         required=true,
//  *         description="Date à consulter au format YYYY-MM-DD",
//  *         @OA\Schema(type="string", format="date", example="2025-11-11")
//  *     ),
//  *     @OA\Response(
//  *         response=200,
//  *         description="Liste des pointages pour les stagiaires du coach",
//  *         @OA\JsonContent(
//  *             type="array",
//  *             @OA\Items(
//  *                 @OA\Property(property="user_id", type="integer", example=1),
//  *                 @OA\Property(property="nom", type="string", example="Doe"),
//  *                 @OA\Property(property="prenom", type="string", example="John"),
//  *                 @OA\Property(property="statut", type="string", example="present"),
//  *                 @OA\Property(property="heure_arrivee", type="string", nullable=true, example="08:55"),
//  *                 @OA\Property(property="heure_sortie", type="string", nullable=true, example="17:00"),
//  *                 @OA\Property(property="note", type="string", nullable=true, example="Pointage automatique via QR code")
//  *             )
//  *         )
//  *     ),
//  *     @OA\Response(
//  *         response=400,
//  *         description="Date non fournie ou invalide",
//  *         @OA\JsonContent(
//  *             @OA\Property(property="message", type="string", example="La date est requise et doit être au format YYYY-MM-DD")
//  *         )
//  *     ),
//  *     @OA\Response(
//  *         response=401,
//  *         description="Non authentifié",
//  *         @OA\JsonContent(
//  *             @OA\Property(property="message", type="string", example="Non authentifié")
//  *         )
//  *     )
//  * )
// */

// public function getStagiairesPresences(Request $request)
// {
//     $request->validate([
//         'date' => 'required|date_format:Y-m-d',
//     ]);

//     $date = $request->date;
//     $coach = auth()->user();

//     // Récupère les stagiaires via la relation pivot
//     $stagiaires = $coach->stagiaires()->get();

//     $result = $stagiaires->map(function($stagiaire) use ($date) {
//         $pointage = Pointage::where('user_id', $stagiaire->id)
//             ->whereDate('date_pointage', $date)
//             ->first();

//         return [
//             'user_id' => $stagiaire->id,
//             'nom' => $stagiaire->last_name,
//             'prenom' => $stagiaire->first_name,
//             'statut' => $pointage ? $pointage->statut : 'absent',
//             'heure_arrivee' => $pointage ? $pointage->heure_arrivee : null,
//             'heure_sortie' => $pointage ? $pointage->heure_sortie : null,
//             'note' => $pointage ? $pointage->note : null,
//         ];
//     });

//     return response()->json($result);
// }



// /**
//      * @OA\Get(
//      *     path="/api/coach/stagiaires/presences",
//      *     summary="Consulter les présences des stagiaires d'un coach pour une journée donnée",
//      *     description="Permet au coach connecté de consulter les présences, retards et absences de ses stagiaires pour une journée spécifique.",
//      *     operationId="getStagiairesPresences",
//      *     tags={"Coach"},
//      *     security={{"bearerAuth":{}}},
//      *     @OA\Parameter(
//      *         name="date",
//      *         in="query",
//      *         required=true,
//      *         description="Date à consulter au format YYYY-MM-DD",
//      *         @OA\Schema(type="string", format="date", example="2025-11-11")
//      *     ),
//      *     @OA\Response(
//      *         response=200,
//      *         description="Liste des pointages pour les stagiaires du coach",
//      *         @OA\JsonContent(
//      *             type="array",
//      *             @OA\Items(
//      *                 @OA\Property(property="user_id", type="integer", example=5),
//      *                 @OA\Property(property="nom", type="string", example="Doe"),
//      *                 @OA\Property(property="prenom", type="string", example="John"),
//      *                 @OA\Property(property="statut", type="string", example="present"),
//      *                 @OA\Property(property="heure_arrivee", type="string", nullable=true, example="08:30"),
//      *                 @OA\Property(property="heure_sortie", type="string", nullable=true, example="17:00"),
//      *                 @OA\Property(property="note", type="string", nullable=true, example="Pointage automatique via QR code")
//      *             )
//      *         )
//      *     ),
//      *     @OA\Response(
//      *         response=400,
//      *         description="Date non fournie ou invalide",
//      *         @OA\JsonContent(
//      *             @OA\Property(property="message", type="string", example="La date est requise et doit être au format YYYY-MM-DD")
//      *         )
//      *     ),
//      *     @OA\Response(
//      *         response=401,
//      *         description="Non authentifié",
//      *         @OA\JsonContent(
//      *             @OA\Property(property="message", type="string", example="Non authentifié")
//      *         )
//      *     )
//      * )
// */
//     public function getStagiairesPresences(Request $request)
//     {
//         $request->validate([
//             'date' => 'required|date_format:Y-m-d',
//         ]);

//         $date = $request->date;
//         $coach = auth()->user();

//         // Vérifie que l'utilisateur connecté est bien un coach
//         if ($coach->role !== 'coache') {
//             return response()->json(['message' => 'Accès réservé aux coachs uniquement'], 403);
//         }

//         // ✅ Récupère les stagiaires attribués à ce coach via la table pivot
//         $stagiaires = $coach->stagiaires()->pluck('users.id')->toArray();

//         if (empty($stagiaires)) {
//             return response()->json(['message' => 'Aucun stagiaire attribué à ce coach'], 404);
//         }

//         // ✅ Récupère les pointages des stagiaires attribués pour la date donnée
//         $pointages = Pointage::whereIn('user_id', $stagiaires)
//             ->whereDate('date_pointage', $date)
//             ->get();

//         // ✅ Construit la réponse complète (présents, absents, retards)
//         $result = collect($stagiaires)->map(function ($stagiaireId) use ($pointages, $date) {
//             $stagiaire = User::find($stagiaireId);
//             $pointage = $pointages->where('user_id', $stagiaireId)->first();

//             return [
//                 'user_id' => $stagiaire->id,
//                 'nom' => $stagiaire->last_name,
//                 'prenom' => $stagiaire->first_name,
//                 'statut' => $pointage ? $pointage->statut : 'absent',
//                 'heure_arrivee' => $pointage ? $pointage->heure_arrivee : null,
//                 'heure_sortie' => $pointage ? $pointage->heure_sortie : null,
//                 'note' => $pointage ? $pointage->note : null,
//             ];
//         });

//         return response()->json($result);
//     }








// /**
//  * @OA\Get(
//  *     path="/api/coach/stagiaires/presences",
//  *     summary="Consulter les présences des stagiaires d’un coach (pour une date donnée ou toutes les dates)",
//  *     description="Permet au coach connecté de récupérer la liste de ses stagiaires avec leurs pointages pour une date spécifique ou pour toutes les dates si aucun paramètre 'date' n'est fourni.",
//  *     tags={"Coach - Présences"},
//  *     security={{"bearerAuth": {}}},
//  *
//  *     @OA\Parameter(
//  *         name="date",
//  *         in="query",
//  *         description="Date au format YYYY-MM-DD (facultative). Si absente, tous les pointages des stagiaires seront affichés.",
//  *         required=false,
//  *         @OA\Schema(type="string", example="2025-11-13")
//  *     ),
//  *
//  *     @OA\Response(
//  *         response=200,
//  *         description="Liste des présences ou absences des stagiaires du coach",
//  *         @OA\JsonContent(
//  *             type="object",
//  *             @OA\Property(property="coach_id", type="integer", example=3),
//  *             @OA\Property(property="date", type="string", nullable=true, example="2025-11-13"),
//  *             @OA\Property(
//  *                 property="presences",
//  *                 type="array",
//  *                 @OA\Items(
//  *                     type="object",
//  *                     @OA\Property(property="user_id", type="integer", example=8),
//  *                     @OA\Property(property="nom", type="string", example="Sow"),
//  *                     @OA\Property(property="prenom", type="string", example="Awa"),
//  *                     @OA\Property(property="date_pointage", type="string", example="2025-11-13"),
//  *                     @OA\Property(property="statut", type="string", example="présent"),
//  *                     @OA\Property(property="heure_arrivee", type="string", example="08:30:00"),
//  *                     @OA\Property(property="heure_sortie", type="string", example="17:00:00"),
//  *                     @OA\Property(property="note", type="string", example="Bon comportement.")
//  *                 )
//  *             )
//  *         )
//  *     ),
//  *
//  *     @OA\Response(
//  *         response=403,
//  *         description="L’utilisateur connecté n’est pas un coach",
//  *         @OA\JsonContent(@OA\Property(property="message", type="string", example="Accès réservé aux coachs uniquement"))
//  *     ),
//  *
//  *     @OA\Response(
//  *         response=404,
//  *         description="Aucun stagiaire attribué à ce coach",
//  *         @OA\JsonContent(@OA\Property(property="message", type="string", example="Aucun stagiaire attribué à ce coach"))
//  *     )
//  * )
//  */
// public function  getStagiairesPresences(Request $request)
// {
//     $coach = auth()->user();

//     // ✅ Vérifie que l’utilisateur est un coach
//     if ($coach->role !== 'coache') {
//         return response()->json(['message' => 'Accès réservé aux coachs uniquement'], 403);
//     }

//     // ✅ Récupère tous les stagiaires associés à ce coach via la table pivot
//     $stagiaireIds = DB::table('coach_stagiaire')
//         ->where('coach_id', $coach->id)
//         ->pluck('stagiaire_id')
//         ->toArray();

//     if (empty($stagiaireIds)) {
//         return response()->json(['message' => 'Aucun stagiaire attribué à ce coach'], 404);
//     }

//     // ✅ Si la date est fournie, on filtre, sinon on prend tous les pointages
//     $query = Pointage::whereIn('user_id', $stagiaireIds);

//     if ($request->filled('date')) {
//         $date = $request->date;

//         // Validation si le champ date est présent
//         $request->validate([
//             'date' => 'date_format:Y-m-d',
//         ]);

//         $query->whereDate('date_pointage', $date);
//     } else {
//         $date = null; // aucune date fournie
//     }

//     $pointages = $query->orderBy('date_pointage', 'desc')->get();

//     // ✅ On construit la liste complète des stagiaires et leurs pointages
//     $result = collect($stagiaireIds)->map(function ($stagiaireId) use ($pointages) {
//         $stagiaire = User::find($stagiaireId);

//         // Récupère tous les pointages de ce stagiaire
//         $pointagesStagiaire = $pointages->where('user_id', $stagiaireId);

//         if ($pointagesStagiaire->isEmpty()) {
//             return [[
//                 'user_id'       => $stagiaire->id,
//                 'nom'           => $stagiaire->last_name,
//                 'prenom'        => $stagiaire->first_name,
//                 'date_pointage' => null,
//                 'statut'        => 'absent',
//                 'heure_arrivee' => null,
//                 'heure_sortie'  => null,
//                 'note'          => null,
//             ]];
//         }

//         return $pointagesStagiaire->map(function ($p) use ($stagiaire) {
//             return [
//                 'user_id'       => $stagiaire->id,
//                 'nom'           => $stagiaire->last_name,
//                 'prenom'        => $stagiaire->first_name,
//                 'date_pointage' => $p->date_pointage,
//                 'statut'        => $p->statut,
//                 'heure_arrivee' => $p->heure_arrivee,
//                 'heure_sortie'  => $p->heure_sortie,
//                 'note'          => $p->note,
//             ];
//         });
//     })->flatten(1);

//     return response()->json([
//         'coach_id'  => $coach->id,
//         'date'      => $date,
//         'presences' => $result->values()
//     ]);
// }




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



//     public function validerScan(Request $request)
// {
//     // 1️⃣ Vérifier que le token est fourni
//     $request->validate(['token' => 'required|string']);

//     // 2️⃣ Rechercher le token en base
//     $qr = qr_tokens::where('token', $request->token)
//                  ->where('is_active', true)
//                  ->first();

//     // 3️⃣ Si le token n’existe pas ou est expiré
//     if (!$qr || $qr->valid_until->isPast()) {
//         return response()->json(['message' => 'Token invalide ou expiré'], 400);
//     }

//     // 4️⃣ Identifier le stagiaire connecté
//     $stagiaireId = auth()->id(); // ou reçu depuis le frontend

//     // 5️⃣ Vérifier s’il a déjà pointé aujourd’hui
//     $dejaPointe = Pointage::where('user_id', $stagiaireId)
//                           ->whereDate('created_at', today())
//                           ->exists();

//     if ($dejaPointe) {
//         return response()->json(['message' => 'Vous avez déjà pointé aujourd’hui'], 400);
//     }

//     // 6️⃣ Enregistrer le pointage
//     Pointage::create([
//         'user_id' => $stagiaireId,
//         'status' => 'present',
//         'timestamp' => now(),
//         'date_pointage' => today(), 
//     ]);

//     return response()->json(['message' => 'Pointage enregistré avec succès']);
// }



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
    // public function listePointages()
    // {
    //     $coach = auth()->user();

    //     if ($coach->role != 'coache') {
    //         return response()->json(['message' => 'Accès non autorisé'], 403);
    //     }

    //     $pointages = Pointage::where('coach_id', $coach->id)
    //         ->with('stagiaire:id,first_name,last_name,email')
    //         ->get();

    //     return response()->json(['pointages' => $pointages], 200);
    // }


}
