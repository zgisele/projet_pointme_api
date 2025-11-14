<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pointage;
use Illuminate\Support\Facades\Auth;
use App\Models\qr_tokens;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Models\User;

    

class PointageController extends Controller
{
    //
/**
     * Retourne l'historique des présences, retards et absences du stagiaire connecté.
     */

/**
     * @OA\Post(
     *     path="/api/stagiaire/historique",
     *     summary="Consulter l'historique des présences, retards et absences du stagiaire connecté",
     *     description="Permet au stagiaire authentifié de récupérer son historique de pointages. 
     *                  Possibilité de filtrer par une date précise ou par une période. 
     *                  Si aucun filtre n'est fourni, tout l'historique est renvoyé.",
     *     tags={"Stagiaire"},
     *     security={{"bearerAuth": {}}},
     *
     *     @OA\RequestBody(
     *         required=false,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 type="object",
     *                 @OA\Property(
     *                     property="date",
     *                     type="string",
     *                     format="date",
     *                     description="Filtrer par date précise (optionnel)"
     *                 ),
     *                 @OA\Property(
     *                     property="debut",
     *                     type="string",
     *                     format="date",
     *                     description="Date de début pour filtrer une période (optionnel)"
     *                 ),
     *                 @OA\Property(
     *                     property="fin",
     *                     type="string",
     *                     format="date",
     *                     description="Date de fin pour filtrer une période (optionnel)"
     *                 )
     *             )
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Historique récupéré avec succès",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Historique récupéré avec succès"),
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(
     *                     type="object",
     *                     @OA\Property(property="date_pointage", type="string", format="date", example="2025-11-12"),
     *                     @OA\Property(property="statut", type="string", example="Présent"),
     *                     @OA\Property(property="heure_arrivee", type="string", format="time", example="08:00:00"),
     *                     @OA\Property(property="heure_sortie", type="string", format="time", example="17:00:00"),
     *                     @OA\Property(property="note", type="string", example="Aucun retard")
     *                 )
     *             )
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=401,
     *         description="Utilisateur non authentifié",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="Unauthenticated.")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=500,
     *         description="Erreur interne du serveur",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Erreur interne du serveur.")
     *         )
     *     )
     * )
*/
    public function historique(Request $request)
    {
        try {
            $stagiaireId = Auth::id(); // ID du stagiaire connecté

            $query = Pointage::where('user_id', $stagiaireId);

            // Filtre par date précise
            if ($request->filled('date')) {
                $query->whereDate('date_pointage', $request->date);
            }

            // Filtre par période
            if ($request->filled('debut') && $request->filled('fin')) {
                $query->whereBetween('date_pointage', [$request->debut, $request->fin]);
            }

            // Récupération triée par date_pointage décroissante
            $historique = $query->orderBy('date_pointage', 'desc')
                ->get(['statut', 'heure_arrivee', 'heure_sortie', 'note', 'date_pointage']);

            // Si aucun pointage trouvé
            if ($historique->isEmpty()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Aucun pointage trouvé pour le filtre sélectionné.',
                    'data' => []
                ]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Historique récupéré avec succès',
                'data' => $historique
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur interne du serveur.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

/**
 * @OA\Get(
 *     path="/api/coach/presences",
 *     summary="Récupérer les pointages des stagiaires attribués à un coach",
 *     description="
 *         - Si une date est fournie : retourne uniquement les pointages enregistrés ce jour-là.
 *         - Si aucune date n'est fournie : retourne tous les pointages de tous les stagiaires associés au coach.
 *     ",
 *     tags={"Coach"},
 *
 *     @OA\Parameter(
 *         name="date",
 *         in="query",
 *         description="Date des pointages à récupérer (format Y-m-d). Si absente, retourne tous les pointages.",
 *         required=false,
 *         example="2025-02-01",
 *         @OA\Schema(type="string", format="date")
 *     ),
 *
 *     @OA\Response(
 *         response=200,
 *         description="Liste des pointages trouvés",
 *         @OA\JsonContent(
 *             @OA\Property(property="coach_id", type="integer", example=4),
 *             @OA\Property(property="date", type="string", example="2025-02-01"),
 *             @OA\Property(
 *                 property="pointages",
 *                 type="array",
 *                 @OA\Items(
 *                     @OA\Property(property="id", type="integer", example=12),
 *                     @OA\Property(property="user_id", type="integer", example=7),
 *                     @OA\Property(property="date_pointage", type="string", example="2025-02-01"),
 *                     @OA\Property(property="statut", type="string", example="present"),
 *                     @OA\Property(property="heure_arrivee", type="string", example="08:45:00"),
 *                     @OA\Property(property="heure_sortie", type="string", example="16:30:00"),
 *                     @OA\Property(property="note", type="string", example="RAS")
 *                 )
 *             )
 *         )
 *     ),
 *
 *     @OA\Response(
 *         response=403,
 *         description="Accès refusé (utilisateur non coach)",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="Accès réservé aux coachs uniquement")
 *         )
 *     ),
 *
 *     @OA\Response(
 *         response=404,
 *         description="Aucun stagiaire attribué à ce coach",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="Aucun stagiaire attribué")
 *         )
 *     ),
 *
 *     security={{"bearerAuth": {}}}
 * )
*/
public function getStagiairesPresences(Request $request)
{
    $coach = auth()->user();

    // Vérifie que l'utilisateur est un coach
    if ($coach->role !== 'coache') {
        return response()->json(['message' => 'Accès réservé aux coachs uniquement'], 403);
    }

    // Récupère les stagiaires associés
    $stagiaireIds = DB::table('coach_stagiaire')
        ->where('coach_id', $coach->id)
        ->pluck('stagiaire_id');

    if ($stagiaireIds->isEmpty()) {
        return response()->json(['message' => 'Aucun stagiaire attribué'], 404);
    }

    // 👉 Récupération du paramètre date (facultatif)
    $date = $request->input('date'); // peut être null

    // 👉 Requête dynamique :
    $query = Pointage::whereIn('user_id', $stagiaireIds);

    // Si une date est fournie, on filtre sur cette date
    if ($date) {
        $query->whereDate('date_pointage', $date);
    }

    // Execution
    $pointages = $query->orderBy('date_pointage', 'desc')->get();

    return response()->json([
        'coach_id'   => $coach->id,
        'date'       => $date ?? 'toutes les dates',
        'pointages'  => $pointages
        
    
    ]);


    // $pointages = $query->orderBy('date_pointage', 'desc')->get()->map(function ($p) {
    // return [
    //     'id'            => $p->id,
    //     'user_id'       => $p->user_id,
    //     'date_pointage' => $p->date_pointage,
    //     'statut'        => $p->statut,
    //     'heure_arrivee' => $p->heure_arrivee,
    //     'heure_sortie'  => $p->heure_sortie,
    //     'note'          => $p->note,
    //     // ❌ EXCLUSION volontaire
    //     // pas de 'qr_token_id'
    // ];
    // });
}



   

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

         // ✅ On garde Carbon ici (ne pas formatter tout de suite)
        $heureActuelle = Carbon::now();
        $heureLimite = Carbon::createFromTime(8, 30, 0);


        // $heureActuelle = Carbon::now()->format('H:i');
        // $heureLimite = '08:30'; // Heure limite pour être à l'heure

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
        // --- 2️⃣ Si l’entrée vient d’être faite → ignore le deuxième scan ---
        if (!is_null($pointage->heure_arrivee) && is_null($pointage->heure_sortie)) {
             $heureArrivee = Carbon::parse($pointage->heure_arrivee); 
            // $heureArrivee = Carbon::createFromFormat('H:i:s', $pointage->heure_arrivee);
            if ($heureActuelle->diffInMinutes($heureArrivee) < 3) {
                return response()->json([
                    'message' => 'Tu viens déjà de pointer ton arrivée. Attends quelques minutes avant de rescanner ⏳',
                ], 400);
            }
        }
        // 👉 Si l’entrée existe mais pas la sortie → on enregistre la sortie
        // if (is_null($pointage->heure_sortie)) {
        //     $pointage->update([
        //         'heure_sortie' => $heureActuelle,
        //     ]);

        //     return response()->json([
        //         'message' => 'Heure de sortie enregistrée ✅',
        //         'heure_sortie' => $heureActuelle,
        //     ]);
        // }
         // --- 3️⃣ Enregistrer la sortie après un délai minimal (ex: 4h) ---
    if (!is_null($pointage->heure_arrivee) && is_null($pointage->heure_sortie)) {
        $heureArrivee = Carbon::parse($pointage->heure_arrivee);

        if ($heureActuelle->diffInHours($heureArrivee) < 4) {
            return response()->json([
                'message' => 'Tu ne peux pas encore pointer ta sortie. Reviens plus tard ⏰',
            ], 400);
        }

        $pointage->update([
            'heure_sortie' => $heureActuelle->format('H:i:s'),
        ]);

        return response()->json([
            'message' => 'Heure de sortie enregistrée ✅',
            'heure_sortie' => $heureActuelle->format('H:i:s'),
        ]);
    }

        // 👉 Si les deux sont déjà enregistrés
        return response()->json([
            'message' => 'Tu as déjà effectué ton pointage complet pour aujourd’hui ✅',
        ]);
}

}
