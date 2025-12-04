<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class LeaderboardController extends Controller
{
    /**
     * Affiche le classement global de tous les utilisateurs.
     */
    public function index()
    {
        // Récupération du Top 100 mondial trié par XP
        $users = User::orderBy('current_xp', 'desc')
            ->take(100)
            ->get();

        // Formatage des données pour la vue
        $leaderboard = $users->map(function ($user, $index) {
            // Palette de couleurs pour les avatars par défaut
            $colors = ['bg-red-400', 'bg-blue-400', 'bg-green-400', 'bg-yellow-400', 'bg-purple-400', 'bg-pink-400'];
            
            return [
                'id' => $user->id,
                'name' => $user->username,
                'points' => $user->current_xp,
                'rank' => $index + 1,
                'avatar_color' => $colors[$user->id % count($colors)],
                'is_me' => $user->id === Auth::id(), // Marqueur pour "Toi"
            ];
        });

        return view('leaderboard', compact('leaderboard'));
    }
}