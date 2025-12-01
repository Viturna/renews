<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0">
        <title>Renews - Se connecter</title>

        <!-- Fonts & Scripts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
        @vite(['resources/css/app.scss', 'resources/js/app.js'])
    </head>
    
    <!-- Body : Fond Noir Impur + Motif étoiles -->
    <body class="bg-renews-noir-impure text-white font-sans antialiased min-h-screen flex flex-col relative overflow-x-hidden">

        <!-- Motif étoiles en fond (Faible opacité) -->
        <div class="absolute inset-0 z-0 pointer-events-none opacity-20" 
             style="background-image: url('/images/bg-stars.png'); background-size: cover; background-position: center;">
        </div>

        <!-- Conteneur Principal -->
        <div class="flex-1 flex flex-col items-center justify-center px-6 py-10 z-10 w-full max-w-md mx-auto">
            
            <!-- 1. LOGO (Avec la petite rotation stylée) -->
            <a href="/" class="mb-8 -rotate-3">
                <img src="/images/logo.svg" class="h-10 w-auto" alt="Renews">
            </a>

            <!-- 2. TITRE -->
            <h1 class="text-3xl font-medium mb-8 text-center tracking-tight mt-4">
                Se <span class="text-renews-vert font-accent italic text-4xl">connecter</span>
            </h1>

            <!-- 3. BOUTON GOOGLE -->
            <a href="{{ route('social.redirect', 'google') }}" 
               class="w-full flex items-center justify-center bg-white text-gray-800 font-medium text-base py-3 px-4 rounded-xl shadow-md hover:bg-gray-100 transition-transform active:scale-95 mb-8">
                <!-- SVG Google Officiel -->
                <svg class="w-5 h-5 mr-3" viewBox="0 0 24 24">
                    <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="#4285F4"/>
                    <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/>
                    <path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" fill="#FBBC05"/>
                    <path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"/>
                </svg>
                Se connecter avec Google
            </a>

            <!-- 4. SÉPARATEUR "OU" -->
            <div class="flex items-center justify-between w-full mb-8 px-2">
                <div class="h-[2px] bg-renews-vert flex-1 rounded-full opacity-80"></div>
                <span class="px-4 text-renews-vert font-medium text-lg lowercase mb-1">ou</span>
                <div class="h-[2px] bg-renews-vert flex-1 rounded-full opacity-80"></div>
            </div>

            <!-- 5. FORMULAIRE -->
            <form method="POST" action="{{ route('login') }}" class="w-full space-y-5">
                @csrf

                <!-- Session Status (Erreurs générales) -->
                <x-auth-session-status class="mb-4" :status="session('status')" />

                <!-- Email -->
                <div>
                    <label for="email" class="block text-white font-bold mb-2 text-sm pl-1">Adresse mail</label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus 
                           placeholder="Ton mail" 
                           class="w-full rounded-xl border-none bg-white py-3.5 px-4 text-gray-800 placeholder-gray-400 focus:ring-4 focus:ring-renews-vert/50 transition-shadow">
                    <x-input-error :messages="$errors->get('email')" class="mt-2 text-red-400 font-bold text-sm" />
                </div>

                <!-- Mot de passe -->
                <div>
                    <label for="password" class="block text-white font-bold mb-2 text-sm pl-1">Mot de passe</label>
                    <input id="password" type="password" name="password" required autocomplete="current-password" 
                           placeholder="Ton mot de passe" 
                           class="w-full rounded-xl border-none bg-white py-3.5 px-4 text-gray-800 placeholder-gray-400 focus:ring-4 focus:ring-renews-vert/50 transition-shadow">
                    <x-input-error :messages="$errors->get('password')" class="mt-2 text-red-400 font-bold text-sm" />
                </div>

                <!-- Mot de passe oublié (Lien discret) -->
                <div class="flex justify-end">
                    @if (Route::has('password.request'))
                        <a class="underline text-sm text-gray-400 hover:text-white rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-renews-vert" href="{{ route('password.request') }}">
                            {{ __('Mot de passe oublié ?') }}
                        </a>
                    @endif
                </div>

                <!-- Bouton Submit -->
                <div class="pt-2">
                    <button type="submit" 
                            class="w-full bg-renews-vert hover:bg-renews-electric text-white font-bold py-4 rounded-xl transition-all duration-300 shadow-[0_4px_14px_0_rgba(112,205,37,0.39)] hover:shadow-[0_6px_20px_rgba(112,205,37,0.23)] hover:-translate-y-1 text-lg">
                        Je me connecte !
                    </button>
                </div>
            </form>
        </div>
    </body>
</html>