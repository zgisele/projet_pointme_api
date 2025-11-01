<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Models\User;
use App\Http\Requests\UpdateCoachRequest;
/**
 * @OA\Info(
 *     version="1.0.0",
 *     title="Pointme-Api",
 *     description="Documentation complète de l'API",
 * )
 */

class AuthController extends Controller
{
    //
    //  $photoPath = $request->file('photo')->store('photos', 'public');

    /** =========================
     *   ADMIN REGISTRATION
     *  ========================= */
    


/**
 * @OA\Post(
 *     path="/api/register/admin",
 *     summary="Enregistrer un nouvel administrateur",
 *     description="Crée un compte administrateur avec validation, upload de photo et hachage du mot de passe.",
 *     tags={"Authentification"},
 *     
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\MediaType(
 *             mediaType="multipart/form-data",
 *             @OA\Schema(
 *                 required={"first_name", "last_name", "email", "password", "photo"},
 *                 @OA\Property(property="first_name", type="string", example="Jean"),
 *                 @OA\Property(property="last_name", type="string", example="Dupont"),
 *                 @OA\Property(property="email", type="string", format="email", example="jean.dupont@gmail.com"),
 *                 @OA\Property(property="password", type="string", format="password", example="secret123"),
 *                 @OA\Property(property="photo", type="string", format="binary", description="Image de profil (jpg, jpeg, png)")
 *             )
 *         )
 *     ),
 *
 *     @OA\Response(
 *         response=201,
 *         description="Admin enregistré avec succès",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="Admin enregistré avec succès"),
 *             @OA\Property(property="user", type="object",
 *                 @OA\Property(property="id", type="integer", example=1),
 *                 @OA\Property(property="first_name", type="string", example="Jean"),
 *                 @OA\Property(property="last_name", type="string", example="Dupont"),
 *                 @OA\Property(property="email", type="string", example="jean.dupont@gmail.com"),
 *                 @OA\Property(property="photo", type="string", example="photos/jean_photo.jpg"),
 *                 @OA\Property(property="role", type="string", example="admin")
 *             )
 *         )
 *     ),
 *
 *     @OA\Response(
 *         response=422,
 *         description="Erreurs de validation",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="Validation échouée"),
 *             @OA\Property(property="errors", type="object",
 *                 @OA\Property(property="email", type="array", @OA\Items(type="string", example="L'email est déjà utilisé"))
 *             )
 *         )
 *     ),
 *
 *     @OA\Response(
 *         response=500,
 *         description="Erreur interne du serveur",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="Erreur serveur"),
 *             @OA\Property(property="error", type="string", example="SQLSTATE[HY000] ...")
 *         )
 *     )
 * )
*/

    public function registerAdmin(Request $request)
    {
        try {
            $validated = $request->validate([
                'first_name' => 'required|string',
                'last_name'  => 'required|string',
                // 'email'      => 'required|email|unique:users',
                'email'      => ['required', 'email', 'unique:users', 'regex:/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.(com|fr|org|sn)$/'],
                'password'   => 'required|string|min:6',
                'photo'      => 'required|image|mimes:jpg,png,jpeg|max:2048',
            ]);

            if ($request->hasFile('photo')) {
                $photoPath = $request->file('photo')->store('photos', 'public');
                $validated['photo'] = $photoPath;
            }

            $validated['password'] = bcrypt($validated['password']);
            $validated['role'] = 'admin';

            $user = User::create($validated);

            return response()->json([
                'message' => 'Admin enregistré avec succès',
                'user' => $user
            ], 201);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'message' => 'Validation échouée',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Erreur serveur',
                'error' => $e->getMessage()
            ], 500);
        }
    }


     /** =========================
     *   COACH REGISTRATION
     *  ========================= */
    
        public function registerCoache(Request $request)
        {
             $user = $request->user();

            try {
                $validated = $request->validate([
                    'first_name' => 'required|string',
                    'last_name'  => 'required|string',
                    // 'email'      => 'required|email|unique:users',
                    'email'      => ['required', 'email', 'unique:users', 'regex:/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.(com|fr|org|sn)$/'],                    'password'   => 'required|string|min:6',
                    'photo'      => 'required|image|mimes:jpg,png,jpeg|max:2048',
                    'phone'      => ['required', 'regex:/^(70|75|76|77|78)[0-9]{7}$/'],
                ]);

                if ($request->hasFile('photo')) {
                    $photoPath = $request->file('photo')->store('photos', 'public');
                    $validated['photo'] = $photoPath;
                }

                $validated['password'] = bcrypt($validated['password']);
                $validated['role'] = 'coache';

                $user = User::create($validated);

                return response()->json([
                    'message' => 'Coache enregistré avec succès',
                    'user' => $user
                ], 201);

            } catch (\Illuminate\Validation\ValidationException $e) {
                return response()->json([
                    'message' => 'Validation échouée',
                    'errors' => $e->errors()
                ], 422);
            } catch (\Exception $e) {
                return response()->json([
                    'message' => 'Erreur serveur',
                    'error' => $e->getMessage()
                ], 500);
            }
        }

    /** =========================
     *   STAGIAIRE REGISTRATION
     *  ========================= */
    
         public function registerStagiaire(Request $request)
        {
            try {
                $user = $request->user(); // utilisateur connecté
                $validated = $request->validate([
                    'first_name' => 'required|string',
                    'last_name' => 'required|string',
                    'email' => 'required|email|unique:users',
                    'phone' => 'required|string',
                    'password' => 'required|string|min:6',
                    'photo' => 'required|image|mimes:jpg,png,jpeg|max:2048',
                    'promotion'  => 'nullable|string|max:50',
                    'start_date' => 'nullable|date',
                    'end_date'   => 'nullable|date',
                ]);

                if ($request->hasFile('photo')) {
                    $photoPath = $request->file('photo')->store('photos', 'public');
                    $validated['photo'] = $photoPath;
                }

                $validated['password'] = bcrypt($validated['password']);
                $validated['role'] = 'stagiaire';

                $stagiaire = User::create($validated);

                return response()->json([
                    'message' => 'Stagiaire enregistré avec succès',
                    'user' => $stagiaire
                ], 201);

            } catch (\Illuminate\Validation\ValidationException $e) {
                return response()->json([
                    'message' => 'Erreur de validation',
                    'errors' => $e->errors()
                ], 422);

            } catch (\Exception $e) {
                return response()->json([
                    'message' => 'Une erreur est survenue',
                    'error' => $e->getMessage()
                ], 500);
            }
        }


    /** =========================
     *   AUTHENTICATION
     *  ========================= */
    /**
     * @OA\Post(
     *     path="/api/login",
     *     summary="Connexion utilisateur (coach et stagiaire)",
     *     tags={"Authentification"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *           mediaType="multipart/form-data",
     *           @OA\Schema(
     *             required={"email","password"},
     *             @OA\Property(property="email", type="string", format="email", example="user@example.com"),
     *             @OA\Property(property="password", type="string", format="password", example="secret123")
     *           )
     *         )
     *     ),
     *     @OA\Response(response=200, description="Connexion réussie"),
     *     @OA\Response(response=401, description="Identifiants invalides")
     * )
     */

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string|min:6',
        ]);

        if (!$token = JWTAuth::attempt($request->only('email', 'password'))) {
            return response()->json(['error' => 'Identifiants invalides'], 401);
        }

        $user = auth()->user();

        // Vérification du rôle
        if (!in_array($user->role, ['coache', 'stagiaire'])) {
            return response()->json(['error' => 'Accès non autorisé'], 403);
        }

        return response()->json([
            'message' => 'Connexion réussie',
            'user' => auth()->user(),
            'access_token' => $token,
            'token_type' => 'bearer',
            // 'expires_in' => auth()->factory()->getTTL() * 60
        ]);
    }


   
    /**
     * @OA\Get(
     *     path="/api/profileCoach",
     *     summary="Profil de l'utilisateur connecté (coach)",
     *     tags={"Coach"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(response=200, description="Utilisateur connecté"),
     *     @OA\Response(response=401, description="Non authentifié")
     * )
     */
    public function profileCoach()
    {
        return response()->json(Auth::user());
    }
    /**
     * @OA\Get(
     *     path="/api/profileStagiaire",
     *     summary="Profil de l'utilisateur connecté (stagiaire)",
     *     tags={"Stagiaire"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(response=200, description="Utilisateur connecté"),
     *     @OA\Response(response=401, description="Non authentifié")
     * )
     */
    public function profileStagiaire()
    {
        return response()->json(Auth::user());
    }

    /**
     * @OA\Post(
     *     path="/api/logout",
     *     summary="Déconnexion de l'utilisateur (coach et stagiaire)",
     *     tags={"Authentification"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(response=200, description="Déconnexion réussie"),
     *     @OA\Response(response=401, description="Non authentifié")
     * )
    */
    // public function logout()
    // {
        // Auth::logout();
        // return response()->json(['message' => 'Déconnexion réussie']);
    public function logout(Request $request)
    {
        try {
            // Invalider le token JWT de l'utilisateur connecté
            auth()->logout();

            return response()->json([
                'message' => 'Déconnexion réussie. Le token a été invalidé.'
            ], 200);

        } catch (\Tymon\JWTAuth\Exceptions\JWTException $e) {
            return response()->json([
                'error' => 'Impossible de se déconnecter, veuillez réessayer.'
            ], 500);
        }
    }


/**
 * @OA\Post(
 *     path="/api/update-coach/{id}",
 *     summary="Modifier le profil du coach",
 *     description="Permet au coach authentifié de mettre à jour son profil (nom, email, photo, mot de passe).",
 *     tags={"Coach"},
 *     security={{"bearerAuth":{}}},
 *
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         description="Identifiant du coach à modifier (doit correspondre à l’utilisateur connecté)",
 *         @OA\Schema(type="integer", example=3)
 *     ),
 *
 *     @OA\RequestBody(
 *         required=true,
 *         description="Données du profil à mettre à jour",
 *            @OA\MediaType(
 *             mediaType="multipart/form-data",
 *             @OA\Schema(
 *                 type="object",
 *                 @OA\Property(property="first_name", type="string", example="Jean"),
 *                 @OA\Property(property="last_name", type="string", example="Dupont"),
 *                 @OA\Property(property="email", type="string", format="email", example="jean.dupont@example.com"),
 *                 @OA\Property(property="photo", type="string", format="binary", description="Fichier image du profil (jpg, png...)"),
 *                 @OA\Property(property="password", type="string", format="password", example="nouveauMotDePasse123")
 *             )
 *         )
 *     ),
 *
 *     @OA\Response(
 *         response=200,
 *         description="Profil mis à jour avec succès",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="message", type="string", example="Profil mis à jour avec succès"),
 *             @OA\Property(
 *                 property="user",
 *                 type="object",
 *                 @OA\Property(property="id", type="integer", example=3),
 *                 @OA\Property(property="first_name", type="string", example="Fatou"),
 *                 @OA\Property(property="last_name", type="string", example="Diop"),
 *                 @OA\Property(property="email", type="string", example="fatou.diop@example.com"),
 *                 @OA\Property(property="photo", type="string", example="https://example.com/storage/photos/fatou.jpg")
 *             )
 *         )
 *     ),
 *
 *     @OA\Response(
 *         response=400,
 *         description="Erreur de validation des données",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="message", type="string", example="Les champs fournis ne sont pas valides.")
 *         )
 *     ),
 *
 *     @OA\Response(
 *         response=403,
 *         description="Accès non autorisé — l’utilisateur connecté ne correspond pas à l’ID fourni",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="message", type="string", example="Accès non autorisé")
 *         )
 *     ),
 *
 *     @OA\Response(
 *         response=404,
 *         description="Utilisateur non trouvé",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="message", type="string", example="Utilisateur non trouvé")
 *         )
 *     ),
 *
 *     @OA\Response(
 *         response=401,
 *         description="Non authentifié — token JWT invalide ou manquant",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="message", type="string", example="Unauthenticated.")
 *         )
 *     )
 * )
*/

public function updateCoach(UpdateCoachRequest $request, $id)
{
    $user = auth()->user();

    // Vérifie que l'utilisateur connecté correspond à l'ID envoyé
    if ($user->id != $id) {
        return response()->json(['message' => 'Accès non autorisé'], 403);
    }
    // $validated=$request->safe()->all();
     // ✅ Récupération des données validées depuis la FormRequest
    $validated = $request->validated();

    // Validation des champs
//    $validated = $request->validate([
//         'first_name' => 'nullable|string|max:255',
//         'last_name' => 'nullable|string|max:255',
//         'email' => 'nullable|email|unique:users,email,' . $user->id,
//         'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
//         'password' => 'nullable|min:6',
//     ]);
    // dd($validate);
    
    // Mise à jour du mot de passe si présent
    if (!empty($validated['password'])) {
        $validated['password'] = bcrypt($validated['password']);
    } else {
        unset($validated['password']);
    }

    // Gestion de la photo
    if ($request->hasFile('photo')) {
        // Supprime l’ancienne photo si elle existe
        if ($user->photo && \Storage::exists('public/' . $user->photo)) {
            \Storage::delete('public/' . $user->photo);
        }

        // Enregistre la nouvelle photo
        $path = $request->file('photo')->store('photos', 'public');
        $validated['photo'] = $path;
    }

    // Mise à jour de l'utilisateur
// dd($validated,$user);

    $user->update($validated);

    // Retourne la réponse
    return response()->json([
        'message' => 'Profil mis à jour avec succès',
        'user' => [
            'id' => $user->id,
            'first_name' => $user->first_name,
            'last_name' => $user->last_name,
            'email' => $user->email,
            'photo' => $user->photo ? asset('storage/' . $user->photo) : null,
        ]
    ], 200);
}
}
