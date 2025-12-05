<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use App\Models\Level;
use App\Models\User;

class ProfileController extends Controller
{
    public function show(Request $request): View
    {
        $user = $request->user();
        
        $currentLevel = $user->level;
        $currentLevelNum = $currentLevel ? $currentLevel->level_number : 1;
        $currentXp = $user->current_xp;

        $nextLevel = Level::where('level_number', '>', $currentLevelNum)
            ->orderBy('level_number', 'asc')
            ->first();

        $currentThreshold = $currentLevel ? $currentLevel->xp_threshold : 0;
        $nextThreshold = $nextLevel ? $nextLevel->xp_threshold : ($currentXp * 1.5); 

        $range = $nextThreshold - $currentThreshold;
        if ($range <= 0) $range = 1;
        
        $progressPercent = (($currentXp - $currentThreshold) / $range) * 100;
        $progressPercent = min(100, max(0, $progressPercent));


        $rank = User::where('current_xp', '>', $currentXp)->count() + 1;


        return view('profile.show', [
            'user' => $user,
            'nextLevelThreshold' => $nextThreshold,
            'progressPercent' => $progressPercent,
            'rank' => $rank,
        ]);
    }

    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}