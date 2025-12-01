<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\SocialAccount;
use App\Models\User;
use App\Models\Level;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class SocialAuthController extends Controller
{
    // 1. Redirige l'utilisateur vers Google
    public function redirect($provider)
    {
        return Socialite::driver($provider)->redirect();
    }

    // 2. Gère le retour de Google (Mode DEBUG : sans try/catch)
    public function callback($provider)
    {
        // On récupère les infos de Google
        $socialUser = Socialite::driver($provider)->user();

        // Vérifie si ce compte social est déjà lié
        $account = SocialAccount::where('provider', $provider)
            ->where('provider_user_id', $socialUser->getId())
            ->first();

        // CAS A : Compte déjà lié -> On connecte direct
        if ($account) {
            Auth::login($account->user);
            return redirect()->intended('/dashboard');
        }

        // CAS B : Compte non lié, on vérifie l'email
        $user = User::where('email', $socialUser->getEmail())->first();

        // Si l'user n'existe pas du tout, on le CRÉE
        if (!$user) {
            // Récupère le niveau 1 (assure-toi d'avoir fait les seeders !)
            $defaultLevel = Level::where('level_number', 1)->first();
            
            // Sécurité : si le seeder n'a pas marché, on met 1 par défaut
            $levelId = $defaultLevel ? $defaultLevel->id : 1;

            $user = User::create([
                'username' => $socialUser->getNickname() ?? $socialUser->getName(),
                'email' => $socialUser->getEmail(),
                'password' => Hash::make(Str::random(16)),
                'current_level_id' => $levelId,
                'current_xp' => 0,
                'currency_balance' => 0,
                'email_verified_at' => now(),
            ]);
        }

        // On crée le lien SocialAccount
        $user->socialAccounts()->create([
            'provider' => $provider,
            'provider_user_id' => $socialUser->getId(),
            'token' => $socialUser->token,
        ]);

        Auth::login($user);
        return redirect()->intended('/dashboard');
    }
}