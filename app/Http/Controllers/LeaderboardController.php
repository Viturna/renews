<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class LeaderboardController extends Controller
{
    public function index()
    {
        $users = User::orderBy('current_xp', 'desc')
            ->take(100)
            ->get();

        $leaderboard = $users->map(function ($user, $index) {
            $colors = ['bg-red-400', 'bg-blue-400', 'bg-green-400', 'bg-yellow-400', 'bg-purple-400', 'bg-pink-400'];
            
            return [
                'id' => $user->id,
                'name' => $user->username,
                'points' => $user->current_xp,
                'rank' => $index + 1,
                'avatar_color' => $colors[$user->id % count($colors)],
                'is_me' => $user->id === Auth::id(),
            ];
        });

        return view('leaderboard', compact('leaderboard'));
    }
}