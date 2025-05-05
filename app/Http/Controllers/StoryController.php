<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Story;

class StoryController extends Controller
{

    // Récupère toutes les histoires de l'utilisateur connecté
    public function index()
    {
        return auth()->user()->stories()->latest()->get();
    }

    // Récupère les histoires publiques (accessibles sans authentification)
    public function publicStories()
    {
        return Story::where("is_public", true)->with('user:id,pseudo')->latest()->get();
    }

    // Récupère une histoire spécifique par son ID
    public function show($id)
    {
        $story = Story::findOrFail($id);

        // Vérifie si l'histoire est publique ou appartient à l'utilisateur connecté
        if (!$story->is_public && $story->user_id !== auth()->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        return $story;
    }

    // Crée une nouvelle histoire
    public function store(Request $request)
    {
        // Valide les données de la requête
        $validate = $request->validate([
            'title' => 'required|string|max:100',
            'content' => 'required|string',
            'is_public' => 'boolean',
            'slug' => 'string'
        ]);

        // Associe l'histoire à l'utilisateur connecté et la crée
        return auth()->user()->stories()->create($validate);
    }

    // Met à jour une histoire existante par son ID
    public function update(Request $request, $id)
    {
        $story = Story::findOrFail($id);

        // Vérifie si l'histoire appartient à l'utilisateur connecté
        if ($story->user_id !== auth()->id) {
            return response()->json(['message' => 'unauthorized'], 403);
        }

        // Met à jour les champs spécifiés
        $story->update($request->only(['title', 'content', 'is_public']));

        return $story;
    }

    // Supprime une histoire existante par son ID
    public function destroy($id)
    {
        $story = Story::findOrFail($id);

        // Vérifie si l'histoire appartient à l'utilisateur connecté
        if ($story->user_id !== auth()->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        // Supprime l'histoire
        $story->delete();
        return response()->json(['message' => 'Deleted'], 200);
    }
}
