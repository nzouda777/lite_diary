<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\StoryController;
use Illuminate\Support\Facades\Route;


// Route pour l'inscription d'un utilisateur
Route::post('/register', [AuthController::class, 'signup'])->name('register');

// Route pour la connexion d'un utilisateur
Route::post('/login', [AuthController::class, 'login'])->name('login');

// Route pour récupérer les histoires publiques (accessible sans authentification)
Route::get('/stories/public', [StoryController::class, 'publicStories'])->name('public_stories');

// Groupe de routes nécessitant une authentification via Sanctum
Route::middleware('auth:sanctum')->group(function () {
    // Route pour déconnecter un utilisateur
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Route pour récupérer toutes les histoires de l'utilisateur connecté
    Route::get('/stories', [StoryController::class, 'index'])->name('index');

    // Route pour récupérer une histoire spécifique par son ID
    Route::get('/stories/{id}', [StoryController::class, 'show'])->name('show');

    // Route pour créer une nouvelle histoire
    Route::post('/stories', [StoryController::class, 'store'])->name('store');

    // Route pour mettre à jour une histoire existante par son ID
    Route::put('/stories/{id}', [StoryController::class, 'update'])->name('update');

    // Route pour supprimer une histoire existante par son ID
    Route::delete('/stories/{id}', [StoryController::class, 'destroy'])->name('destroy');
});
