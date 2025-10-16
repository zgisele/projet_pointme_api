<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Models\User;

class AuthController extends Controller
{
    //
    //  $photoPath = $request->file('photo')->store('photos', 'public');

    /** =========================
     *   ADMIN REGISTRATION
     *  ========================= */
    // public function registerAdmin(Request $request)
    // {
    //     $validated = $request->validate([
    //         'first_name' => 'required|string|max:100',
    //         'last_name'  => 'required|string|max:100',
    //         'email'      => 'required|email|unique:users',
    //         'password'   => 'required|min:6',
    //     ]);

    //     $user = User::create([
    //         'first_name' => $validated['first_name'],
    //         'last_name'  => $validated['last_name'],
    //         'email'      => $validated['email'],
    //         'password'   => $validated['password'],
    //         'role'       => 'admin',
    //     ]);

    //         $token = JWTAuth::fromUser($user);

    //     return response()->json([
    //         'message' => 'Admin créé avec succès',
    //         'user' => $user,
    //         'token' => $token,
    //     ], 201);
    // }   
    // public function registerAdmin(Request $request)
    // {
    //     $validated = $request->validate([
    //         'first_name' => 'required|string',
    //         'last_name' => 'required|string',
    //         'email' => 'required|email|unique:users',
    //         'password' => 'required|string|min:6',
    //         'photo' => 'required|image|mimes:jpg,png,jpeg|max:2048',
    //     ]);

    //     if ($request->hasFile('photo')) {
    //         $photoPath = $request->file('photo')->store('photos', 'public');
    //         $validated['photo'] = $photoPath;
    //     }

    //     $validated['password'] = bcrypt($validated['password']);
    //     $validated['role'] = 'admin';

    //     $user = User::create($validated);

    //     return response()->json([
    //         'message' => 'Admin enregistré avec succès',
    //         'user' => $user
    //     ], 201);
    // }
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
    // public function registerCoache(Request $request)
    // {
    //     $validated = $request->validate([
    //         'first_name' => 'required|string|max:100',
    //         'last_name'  => 'required|string|max:100',
    //         'email'      => 'required|email|unique:users',
    //         'password'   => 'required|min:6',
    //         'photo'      => 'required|image|mimes:jpg,png,jpeg|max:2048',
    //         'phone'      => 'nullable|string|max:20',
    //     ]);

    //     $user = User::create([
    //         'first_name' => $validated['first_name'],
    //         'last_name'  => $validated['last_name'],
    //         'photo'      => $validated['photo'] ?? null,
    //         'email'      => $validated['email'],
    //         'phone'      => $validated['phone'] ?? null,
    //         'password'   => $validated['password'],
    //         'role'       => 'coache',
    //     ]);

    //     $token = JWTAuth::fromUser($user);

    //     return response()->json([
    //         'message' => 'Coach créé avec succès',
    //         'user' => $user,
    //         'token' => $token,
    //     ], 201); 
    // }
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
    // public function registerStagiaire(Request $request)
    // {
    //     $validated = $request->validate([
    //         'first_name' => 'required|string|max:100',
    //         'last_name'  => 'required|string|max:100',
    //         'email'      => 'required|email|unique:users',
    //         'password'   => 'required|min:6',
    //         'promotion'  => 'nullable|string|max:50',
    //         'start_date' => 'nullable|date',
    //         'end_date'   => 'nullable|date',
    //     ]);

    //     $user = User::create([
    //         'first_name' => $validated['first_name'],
    //         'last_name'  => $validated['last_name'],
    //         'email'      => $validated['email'],
    //         'promotion'  => $validated['promotion'] ?? null,
    //         'start_date' => $validated['start_date'] ?? null,
    //         'end_date'   => $validated['end_date'] ?? null,
    //         'password'   => $validated['password'],
    //         'role'       => 'stagiaire',
    //     ]);

    //     $token = JWTAuth::fromUser($user);

    //     return response()->json([
    //         'message' => 'Stagiaire créé avec succès',
    //         'user' => $user,
    //         'token' => $token,
    //     ], 201);
    // }
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
    // public function login(Request $request)
    // {
    //     $credentials = $request->only('email', 'password');

    //     if (!$token = Auth::attempt($credentials)) {
    //         return response()->json(['error' => 'Identifiants invalides'], 401);
    //     }

    //     return response()->json([
    //         'message' => 'Connexion réussie',
    //         'token'   => $token,
    //         'user'    => Auth::user(),
    //     ]);
    // }
    // public function login(Request $request)
    // {
    //     // 1️⃣ Validation
    //     $validator = Validator::make($request->all(), [
    //         'email' => 'required|email',
    //         'password' => 'required|string|min:6',
    //     ]);

    //     if ($validator->fails()) {
    //         return response()->json([
    //             'success' => false,
    //             'errors' => $validator->errors()
    //         ], 422);
    //     }

    //     $credentials = $request->only('email', 'password');

    //     // 2️⃣ Tentative d'authentification et génération du token
    //     try {
    //         if (!$token = JWTAuth::attempt($credentials)) {
    //             return response()->json([
    //                 'success' => false,
    //                 'message' => 'Identifiants invalides'
    //             ], 401);
    //         }
    //     } catch (JWTException $e) {
    //         return response()->json([
    //             'success' => false,
    //             'message' => 'Impossible de créer le token'
    //         ], 500);
    //     }

    //     // 3️⃣ Réponse avec token et infos utilisateur
    //     return response()->json([
    //         'success' => true,
    //         'message' => 'Connexion réussie',
    //         'user' => auth()->user(),
    //         'access_token' => $token,
    //         'token_type' => 'bearer',
    //         'expires_in' => auth()->factory()->getTTL() * 60
    //     ]);

    // }
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string|min:6',
        ]);

        if (!$token = JWTAuth::attempt($request->only('email', 'password'))) {
            return response()->json(['error' => 'Identifiants invalides'], 401);
        }

        return response()->json([
            'message' => 'Connexion réussie',
            'user' => auth()->user(),
            'access_token' => $token,
            'token_type' => 'bearer',
            // 'expires_in' => auth()->factory()->getTTL() * 60
        ]);
    }
    public function me()
    {
        return response()->json(Auth::user());
    }

    public function logout()
    {
        Auth::logout();
        return response()->json(['message' => 'Déconnexion réussie']);
    }
}
