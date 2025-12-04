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
    public function redirect($provider)
    {
        return Socialite::driver($provider)->redirect();
    }

    public function callback($provider)
    {
        $socialUser = Socialite::driver($provider)->user();

        $account = SocialAccount::where('provider', $provider)
            ->where('provider_user_id', $socialUser->getId())
            ->first();

        if ($account) {
            Auth::login($account->user);
            return redirect()->intended('/');
        }
        $user = User::where('email', $socialUser->getEmail())->first();

        if (!$user) {
            $defaultLevel = Level::where('level_number', 1)->first();
            
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

        $user->socialAccounts()->create([
            'provider' => $provider,
            'provider_user_id' => $socialUser->getId(),
            'token' => $socialUser->token,
        ]);

        Auth::login($user);
        return redirect()->intended('/dashboard');
    }
}