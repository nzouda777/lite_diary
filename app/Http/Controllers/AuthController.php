<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AuthController extends Controller
{

    // Inscription d'un nouvel utilisateur
    public function signup(Request $request)
    {
        // Valide les données de la requête
        $validated = $request->validate([
            'name' => 'required|string|max:255', // Nom requis, chaîne de caractères, max 255 caractères
            'email' => 'required|string|email|max:255|unique:users', // Email requis, unique dans la table users
            'password' => 'required|string|min:8', // Mot de passe requis, minimum 8 caractères
            'pseudo' => 'required|string|max:255|unique:users', // Pseudo requis, unique dans la table users
            "phone_number" => "required|string|max:255" // Numéro de téléphone requis, chaîne de caractères
        ]);

        // Crée un nouvel utilisateur avec les données validées
        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']), // Hash du mot de passe
            'pseudo' => $validated['pseudo'],
            "phone_number" => $validated["phone_number"]
        ]);

        // Génère un token d'authentification pour l'utilisateur
        $token = $user->createToken('auth_token')->plainTextToken;

        // Retourne une réponse JSON avec le token
        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
        ]);
    }

    // Connexion d'un utilisateur
    public function login(Request $request)
    {
        // Valide les données de la requête
        $request->validate([
            'email' => 'required|email', // Email requis
            'password' => 'required|string', // Mot de passe requis
        ]);

        // Recherche l'utilisateur par email
        $user = User::where('email', $request->email)->first();

        // Vérifie si l'utilisateur existe et si le mot de passe est correct
        if (!$user || !Hash::check($request->password, $user->password)) {
            dd($user); // Debug (à supprimer en production)
            return response()->json(['message' => 'Invalid credentials'], 401); // Erreur d'identification
        }

        // Génère un token d'authentification pour l'utilisateur
        $token = $user->createToken('auth_token')->plainTextToken;

        // Retourne une réponse JSON avec le token et les informations de l'utilisateur
        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
            'user' => $user,
        ]);
    }

    // Déconnexion de l'utilisateur
    public function logout(Request $request)
    {
        // Supprime le token d'accès actuel de l'utilisateur
        $request->user()->currentAccessToken()->delete();

        // Retourne une réponse JSON confirmant la déconnexion
        return response()->json([
            'message' => 'Logged out'
        ]);
    }
}
