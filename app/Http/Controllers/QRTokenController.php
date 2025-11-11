<?php

namespace App\Http\Controllers;


use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Carbon\Carbon;
use App\Models\qr_tokens;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Console\Command;






class QRTokenController extends Controller
{
    //
    /**
     * Génère un nouveau QR token.
     */


    // /**
    //  * @OA\Post(
    //  *     path="/api/qr-tokens",
    //  *     summary="Générer un nouveau QR Token",
    //  *     description="Permet à un coach ou un administrateur authentifié de générer un QR Token valide jusqu’à une date donnée.",
    //  *     tags={"QR Tokens"},
    //  *     security={{"bearerAuth":{}}},
    //  *
    //  *     @OA\RequestBody(
    //  *         required=true,
    //  *         @OA\JsonContent(
    //  *             required={"valid_until"},
    //  *             @OA\Property(property="valid_until", type="string", format="date-time", example="2025-11-10 23:59:59", description="Date d’expiration du QR token (format Y-m-d H:i:s)")
    //  *         )
    //  *     ),
    //  *
    //  *     @OA\Response(
    //  *         response=201,
    //  *         description="QR Token généré avec succès.",
    //  *         @OA\JsonContent(
    //  *             @OA\Property(property="message", type="string", example="QR Token généré avec succès."),
    //  *             @OA\Property(
    //  *                 property="data",
    //  *                 type="object",
    //  *                 @OA\Property(property="id", type="integer", example=1),
    //  *                 @OA\Property(property="token", type="string", example="b4c9f6d7-8e45-43d3-9f89-a45be5fd2a17"),
    //  *                 @OA\Property(property="created_by", type="integer", example=3),
    //  *                 @OA\Property(property="valid_until", type="string", format="date-time", example="2025-11-10 23:59:59"),
    //  *                 @OA\Property(property="is_active", type="boolean", example=true),
    //  *                 @OA\Property(property="created_at", type="string", example="2025-11-06T21:15:00.000000Z"),
    //  *                 @OA\Property(property="updated_at", type="string", example="2025-11-06T21:15:00.000000Z")
    //  *             )
    //  *         )
    //  *     ),
    //  *     @OA\Response(
    //  *         response=422,
    //  *         description="Erreur de validation.",
    //  *         @OA\JsonContent(
    //  *             @OA\Property(property="message", type="string", example="The valid_until field is required.")
    //  *         )
    //  *     ),
    //  *     @OA\Response(
    //  *         response=401,
    //  *         description="Non authentifié (token manquant ou invalide)."
    //  *     )
    //  * )
    // */
    public function generate(Request $request)
    {
            // ✅ Validation des données
            $request->validate([
                'valid_until' => 'required|date|after:now',
            ]);

            // ✅ Génération du token unique
            $token = Str::uuid(); // ou Str::random(32)

            // ✅ Création du QR token en base
            // $qrToken = qr_tokens::create([
            //     'token' => $token,
            //     'created_by' => $request->user()->id,
            //     'valid_until' => Carbon::parse($request->valid_until),
            //     'is_active' => true,
            // ]);

            // Créer l’enregistrement dans la base de données
        $qrToken = qr_tokens::create([
            'token' => $token,
            'created_by' => auth()->user()->id,
            'valid_until' => Carbon::now()->addDays(3),
            'is_active' => true,
        ]);

        // ✅ (Optionnel) Générer le QR code image si tu veux l’afficher
        // Générer le QR Code au format PNG (utilise GD, pas Imagick)
        // $qrCode = QrCode::format('png')
        //     ->size(300)
        //     ->errorCorrection('H')
        //     ->generate($token);

        // Encodage base64 pour afficher ou transférer l’image facilement
            //    $qrCodeBase64 = base64_encode($qrCode);
            //    $qrImage = base64_encode(QrCode::format('png')->size(200)->generate($token));

                // return response()->json([
                //     'message' => 'QR Token généré avec succès.',
                //     'token' => $token,
                //     'qr_image' => 'data:image/png;base64,' . $qrCode,
                //     'valid_until' => $qrToken->valid_until,

                        
                //         ], 201);
         return response()->json([
            'message' => 'QR Token généré avec succès.',
            'data' => [
                'id' => $qrToken->id,
                'token' => $qrToken->token,
                'created_by' => $qrToken->created_by,
                'valid_until' => $qrToken->valid_until,
                'is_active' => $qrToken->is_active,
                'created_at' => $qrToken->created_at,
                'updated_at' => $qrToken->updated_at,
                // 'qr_image' => 'data:image/png;base64,' . $qrCodeBase64
            ]
        ], 201);
    }


/**
 * @OA\Get(
 *     path="/api/qr-code",
 *     summary="Afficher le QR code actif",
 *     description="Retourne le QR code actif sous forme d’image SVG à partir du jeton actif enregistré dans la table qr_tokens.",
 *     operationId="afficherQrCode",
 *     tags={"Coach"},
 *
 *     @OA\Response(
 *         response=200,
 *         description="QR code généré avec succès (image SVG)",
 *         @OA\MediaType(
 *             mediaType="image/svg+xml"
 *         )
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Aucun QR code actif trouvé"
 *     )
 * )
*/
    // public function afficherQrCode()
    // {
    //     $qr = qr_tokens::where('is_active', true)->firstOrFail();

    //     return response( QrCode::size(250)->generate($qr->token))
    //         ->header('Content-Type', 'image/svg+xml');
    // }

    public function afficherQrCode()
    {
        // Récupère le token actif (non expiré)
        $qr = qr_tokens::where('is_active', true)
                     ->where('valid_until', '>=', now())
                     ->latest('created_at')
                     ->first();

        if (! $qr) {
            return response()->json(['message' => 'Aucun QR actif'], 404);
        }

        $svg = QrCode::size(250)->generate($qr->token);

        return response($svg, 200)->header('Content-Type', 'image/svg+xml');
    }

    // Redémarre Apache / PHP-FPM.

    //  public function validerScan(Request $request)
    // {
    //     // 1️⃣ Vérifier que le token est fourni
    //     $request->validate(['token' => 'required|string']);

    //     // 2️⃣ Rechercher le token en base
    //     $qr = QrToken::where('token', $request->token)
    //                 ->where('is_active', true)
    //                 ->first();

    //     // 3️⃣ Si le token n’existe pas ou est expiré
    //     if (!$qr || $qr->valid_until->isPast()) {
    //         return response()->json(['message' => 'Token invalide ou expiré'], 400);
    //     }

    //     // 4️⃣ Identifier le stagiaire connecté
    //     $stagiaireId = auth()->id(); // ou reçu depuis le frontend

    //     // 5️⃣ Vérifier s’il a déjà pointé aujourd’hui
    //     $dejaPointe = Pointage::where('user_id', $stagiaireId)
    //                         ->whereDate('created_at', today())
    //                         ->exists();

    //     if ($dejaPointe) {
    //         return response()->json(['message' => 'Vous avez déjà pointé aujourd’hui'], 400);
    //     }

    //     // 6️⃣ Enregistrer le pointage
    //     Pointage::create([
    //         'user_id' => $stagiaireId,
    //         'status' => 'present',
    //         'timestamp' => now(),
    //     ]);

    //     return response()->json(['message' => 'Pointage enregistré avec succès']);
    // }

   

// public function afficherQrCodeStagiaire()
// {
//     $stagiaire = auth()->user();

//     // Récupérer le coach du stagiaire
//     $coach = $stagiaire->coach; // Relation `coach()` à définir dans User

//     if (!$stagiaire) {
//         return response()->json(['message' => 'Aucun QR code disponible'], 404);
//     }

//     // Récupérer le token actif du coach
//     $qr = qr_tokens::where('coach_id', $coach->id)
//                  ->where('is_active', true)
//                  ->first();

//     if (!$qr) {
//         return response()->json(['message' => 'Aucun QR code actif trouvé'], 404);
//     }

//     // Générer le QR code SVG à partir du token
//     $qrCodeSvg = QrCode::size(250)->generate($qr->token);

//     return response()->json([
//         'token' => $qr->token,
//         'qr_code_svg' => $qrCodeSvg
//     ]);
// }

// public function afficherQrCodeStagiaire()
// {
//     $stagiaire = auth()->user();

//     if ($stagiaire->role !== 'stagiaire') {
//         return response()->json(['message' => 'Accès non autorisé'], 403);
//     }

//     // Récupérer le coach associé
//     $coach = $stagiaire->coachs()->first();

//     if (!$coach) {
//         return response()->json(['message' => 'Aucun coach associé'], 404);
//     }

//     // Récupérer le QR actif du coach
//     $qr = coach_stagiaire::where('coach_id', $coach->id)
//                  ->where('is_active', true)
//                  ->first();

//     if (!$qr) {
//         return response()->json(['message' => 'Aucun QR code actif'], 404);
//     }

//     return response(QrCode::size(250)->generate($qr->token))
//         ->header('Content-Type', 'image/svg+xml');
// }



}
